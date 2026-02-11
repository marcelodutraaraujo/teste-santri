<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Calculator\{PriceContext, ProductCalculator};
use App\Discount\{QuantityDiscountStrategy, CustomerDiscountStrategy};
use App\Surcharge\WeightSurchargeStrategy;
use App\Tax\StateTaxStrategy;
use App\Cache\FileCache;
use App\Database\Connection;
use App\Repository\CalculationRepository;

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

    $context = new PriceContext(
        (float)$data['basePrice'],
        (int)$data['quantity'],
        (string)$data['customerType'],
        (float)$data['weight'],
        (string)$data['state']
    );

    $pdo = Connection::make();
    $repository = new CalculationRepository($pdo);

    $calculator = new ProductCalculator(
        [
            new QuantityDiscountStrategy(),
            new CustomerDiscountStrategy()
        ],
        new WeightSurchargeStrategy(),
        new StateTaxStrategy(),
        new FileCache(),
        $repository
    );

    echo json_encode([
        'finalPrice' => round($calculator->calculate($context), 2)
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Rota não encontrada']);
