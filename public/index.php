<?php declare(strict_types=1);

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../src/bootstrap.php';

$kernel = new Kernel;
$kernel->boot();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
