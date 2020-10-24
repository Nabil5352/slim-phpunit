<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

function todoIdValid($id) {
	return (int)$id && $id > 0 && $id <= 10;
}

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->group('/todo', function(){
	
	$this->map(['GET'], '', function (Request $request, Response $response) { 
		return $response->withJson(['message' => 'Hello, Todo']);
	});

	
	$this->get('/{id}', function (Request $request, Response $response, $args) { 
		if (todoIdValid($args['id'])){
			return $response->withJson(['message' => 'Todo '. $args['id']]);
		}

		return $response->withJson(['message' => 'Todo Not Found'], 404);
	});
	

	$this->map(['POST', 'PUT', 'PATCH'], '/{id}',  function (Request $request, Response $response, $args) { 
		if (todoIdValid($args['id'])){
			return $response->withJson(['message' => 'Todo '. $args['id'] . " updated successfully"]);
		}

		return $response->withJson(['message' => 'Todo Not Found'], 404);
	});


	$this->delete('/{id}', function (Request $request, Response $response, $args) { 
		if (todoIdValid($args['id'])){
			return $response->withJson(['message' => 'Todo '. $args['id'] . " deleted succesfully"]);
		}

		return $response->withJson(['message' => 'Todo Not Found'], 404);
	});
});

$app->run();

