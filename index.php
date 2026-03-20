<?php

require_once __DIR__ . '/vendor/autoload.php';

// use Dotenv\Dotenv;

// $dotenv = Dotenv::createImmutable(__DIR__);
// $dotenv->load();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// TEMPORARY DEBUG - remove after fixing
if (isset($_GET['debug'])) {
    $host     = getenv('MYSQLHOST');
    $dbname   = getenv('MYSQLDATABASE');
    $username = getenv('MYSQLUSER');
    $password = getenv('MYSQLPASSWORD');
    $port     = getenv('MYSQLPORT');

    echo json_encode([
        'host'     => $host,
        'dbname'   => $dbname,
        'username' => $username,
        'port'     => $port,
        'password' => $password ? '***set***' : 'NOT SET',
    ]);

    try {
        $dsn = "mysql:host={$host};dbname={$dbname};port={$port};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        echo json_encode(['db' => 'CONNECTION SUCCESS']);
    } catch (Exception $e) {
        echo json_encode(['db' => 'FAILED: ' . $e->getMessage()]);
    }
    exit();
}

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->post('/graphql', [App\Controller\GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $handler($vars);
        break;
}
