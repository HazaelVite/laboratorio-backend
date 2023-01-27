<?php

require_once dirname(__FILE__) . '/Response.php';
require_once dirname(__FILE__) . '/Request.php';
require_once dirname(__FILE__) . '/Connection.php';
require_once dirname(__FILE__) . '/controllers/Movimiento.php';

RequestHTTP::setHeaders();
RequestHTTP::json_request();

$movimientoController = new MovimientoControlller();

$response = ResponseHTTP::getInstance();
$request = RequestHTTP::getInstance();

// $movimientoController->getMovimiento($request->getRequest(), $response);
// $movimientoController->getMovimientos($request->getRequest(), $response);
$movimientoController->createMovimiento($request->getRequest(), $response);
