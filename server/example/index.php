<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Helper\ControllerHelper as Controller;

header('Access-Control-Allow-Methods: GET, POST');
require('vendor/autoload.php');
$envconfig = @parse_ini_file('envconfig.ini');

// slim 3 configuration
$app = new \Slim\App([
  'settings' => ['displayErrorDetails' => $envconfig['debugging'] === '1']
]);

$app->get('/', function() use($envconfig) {
  Controller::view('index', $envconfig);
});

// graphql configuration
$app->options('/graphql', function(Request $request, Response $response) {
  if (php_sapi_name() === 'cli-server') {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: "POST, GET, PUT, DELETE, OPTIONS"');
    header('Access-Control-Allow-Headers: "Origin, X-Requested-With, Content-Type, Accept, Authorization"');
  }
});
$app->post('/graphql', function(Request $request, Response $response) use($envconfig) {
  if (php_sapi_name() === 'cli-server') header("Access-Control-Allow-Origin: *");
  date_default_timezone_set($envconfig['timezone']);
  
  $gql = new Helper\MyGraphQL;
  $graphql = $gql->graphql();
  return $response->withStatus(200)->withHeader('Content-Type', 'application/json')->write(json_encode($graphql, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT));
});

// run application
$app->run();
