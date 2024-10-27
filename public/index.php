<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment as Twig; // Make sure to import Twig correctly
use PDO;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here, so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

// Require the app
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Add Database connection to Container
$app->singleton(PDO::class, function () {
    $dburl = parse_url(getenv('DATABASE_URL') ?: throw new Exception('no DATABASE_URL'));

    return new PDO(sprintf(
        "pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s",
        $dburl['host'],
        $dburl['port'],
        ltrim($dburl['path'], '/'), // URL path is the DB name, must remove leading slash
        $dburl['user'],
        $dburl['pass']
    ));
});

// Define the route
$app->get('/db', function (Request $request, LoggerInterface $logger, Twig $twig, PDO $pdo) {
    $st = $pdo->prepare('SELECT name FROM test_table');
    $st->execute();
    $names = [];

    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $logger->debug('Row ' . $row['name']);
        $names[] = $row;
    }

    return $twig->render('database.twig', [
        'names' => $names,
    ]);
});

// Handle the incoming request
$kernel = $app->make(Kernel::class);
$response = $kernel->handle($request = Request::capture())->send();
$kernel->terminate($request, $response);
