<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Calculator\{PriceContext, ProductCalculator};
use App\Discount\{QuantityDiscountStrategy, CustomerDiscountStrategy};
use App\Surcharge\WeightSurchargeStrategy;
use App\Cache\FileCache;
use App\Database\Connection;
use App\Repository\CalculationRepository;
use App\Exception\DomainException;
use App\Factory\MarginFactory;
use App\Tax\IcmsTaxStrategy;
use App\Tax\TaxCalculator;

header('Content-Type: application/json');

$taxConfig = require __DIR__ . '/../config/taxes.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'POST' && $uri === '/api/calculate') {

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido']);
        exit;
    }

    try {

        $context = new PriceContext(
            basePrice: (float)$data['basePrice'],
            marginType: (string)$data['marginType'],
            marginValue: (float)$data['marginValue'],
            quantity: (int)$data['quantity'],
            customerType: (string)$data['customerType'],
            weight: (float)$data['weight'],
            state: (string)$data['state']
        );
        
        $margin = MarginFactory::create(
            $context->marginType,
            $context->marginValue
        );

        $pdo = Connection::make();
        $repository = new CalculationRepository($pdo);

        $taxCalculator = new TaxCalculator([
            new IcmsTaxStrategy($taxConfig['icms'])
        ]);

        $calculator = new ProductCalculator(
            margin: $margin,
            discounts: [
                new QuantityDiscountStrategy(),
                new CustomerDiscountStrategy()
            ],
            surcharge: new WeightSurchargeStrategy(),
            taxCalculator: $taxCalculator,
            cache: new FileCache(),
            repository: $repository
        );

        $result = $calculator->calculate($context);

        echo json_encode([
            'finalPrice' => round($result, 2)
        ]);

        exit;

    } catch (DomainException $e) {

        http_response_code(422);
        echo json_encode(['error' => $e->getMessage()]);
        exit;

    } catch (\InvalidArgumentException $e) {

        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
        exit;

    } catch (\Throwable $e) {

        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        exit;
    }
}

http_response_code(404);
echo json_encode(['error' => 'Rota não encontrada']);
