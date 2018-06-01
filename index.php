<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'thiago';
$config['db']['pass']   = 'thiago123.';
$config['db']['dbname'] = 'girodb';

#$app = new \Slim\App;
$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/{name}/', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("OLÁ, $name");

    return $response;
});

//GET
$app->get('/tickets', function (Request $request, Response $response) {
//    $this->logger->addInfo("Ticket list");
    $sth = $this->db->prepare("SELECT * FROM usuario");
    $sth->execute();
    $todos = $sth->fetchAll();
//    $response->withJson($todos);
//    $response->getBody()->write($todos);

    return $response->withJson($todos);
});

//POST
$app->post('/ticket/new', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $ticket_data = [];
    $ticket_data['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $ticket_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
    // ...
});

// DELETE
$app->delete('/todo/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("DELETE FROM tasks WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $todos = $sth->fetchAll();
    return $this->response->withJson($todos);
});

// Update
$app->put('/todo/[{id}]', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    $sql = "UPDATE tasks SET task=:task WHERE id=:id";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->bindParam("task", $input['task']);
    $sth->execute();
    $input['id'] = $args['id'];
    return $this->response->withJson($input);
});

$app->run(); 
