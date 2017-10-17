<?php

//use Slim;

require 'vendor/autoload.php';
require 'src/classLoader.php';

$app = new \Slim\Slim();
$autoLoader = new ClassLoader();
$app->add(new \Slim2BasicAuth());

/**
 * API Calls
 */
function hello() {
   
   $service = new PurchaseOrderService();
   echo "Hello, API";
};
function getPurchaseOrders () {
  
  $instance = \Slim\Slim::getInstance();
  $req = $instance->request()->getBody();
  $request = json_decode($req,TRUE);
  $service = new PurchaseOrderService();
  $response = $service->getPurchaseOrders($request);
  echo json_encode($response);
};

// set up routers

// register hello
$app->get('/test','hello' ); 

// register getPurchaseOrders
$app->post('/test','getPurchaseOrders');


$app->run();


