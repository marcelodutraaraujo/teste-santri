<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
$taxConfig = require __DIR__ . '/../config/taxes.php';

use App\Calculator\{PriceContext, ProductCalculator};
use App\Discount\{QuantityDiscountStrategy, CustomerDiscountStrategy};
use App\Surcharge\WeightSurchargeStrategy;
use App\Tax\StateTaxStrategy;
use App\Cache\FileCache;
use App\Database\Connection;
use App\Repository\CalculationRepository;
use App\Exception\DomainException;
use App\Tax\IcmsTaxStrategy;
use App\Tax\TaxCalculator;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'POST' && $uri === '/api/calculate') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido, verifique a documentacão para verificar o exemplo']);
        exit;
    }

    $pdo = Connection::make();
    $repository = new CalculationRepository($pdo);

    $taxCalculator = new TaxCalculator([
        new IcmsTaxStrategy($taxConfig['icms']),
    ]);

    try {
        $context = new PriceContext(
            (float)$data['basePrice'],
            (int)$data['quantity'],
            (string)$data['customerType'],
            (float)$data['weight'],
            (string)$data['state']
        );

        $calculator = new ProductCalculator(
            [
                new QuantityDiscountStrategy(),
                new CustomerDiscountStrategy()
            ],
            new WeightSurchargeStrategy(),
            $taxCalculator,
            new FileCache(),
            $repository
        );

        $result = $calculator->calculate($context);

        echo json_encode([
            'finalPrice' => round($result, 2)
        ]);
        exit;

    } catch (DomainException $e) {
        http_response_code(422);
        echo json_encode([ 'error' => $e->getMessage() ]);
        exit;
    } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode([ 'error' => $e->getMessage() ]);
        exit;
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode([ 'error' => 'Internal Server Error' ]);
        exit;
    }

    
}

http_response_code(404);
echo json_encode(['error' => 'Rota não encontrada']);
