<?php
//objetos de response e request
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//auto load do Slim.. obrigatorio
require '../vendor/autoload.php';

//esta flag é para desenvolvimento, em produção voce vmai querer deixar todas em false, para que
// nao fique aparecendo erros e avisos aos usuarios finals e para que o tamanho do header nao seja divulgado
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

//dados de conexao do banco de dados
$config['db']['host']   = 'localhost';
$config['db']['user']   = 'thiago';
$config['db']['pass']   = 'thiago123.';
$config['db']['dbname'] = 'girodb';


#$app = new \Slim\App; //use esta linha caso nao precise fas configuracoes
$app = new \Slim\App(['settings' => $config]);


//Container para instanciamento das classes. Todas as classes adicionadas e instanciadas no container
// sao acessiveis atravez de this->
$container = $app->getContainer();
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

//Abaixo são as rotas.. para mais info veja documentacao do SLIM..
//É aqui que vem a vantagem do SLIM!!

//GET - OLÁ MUNDO
$app->get('/{name}/', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("OLÁ, $name");

    return $response;
});

//GET retorna usuaros
$app->get('/tickets', function (Request $request, Response $response) {
//    $this->logger->addInfo("Ticket list");
    $sth = $this->db->prepare("SELECT * FROM usuario");
    $sth->execute();
    $todos = $sth->fetchAll();
//    $response->withJson($todos);
//    $response->getBody()->write($todos);

    return $response->withJson($todos);
});

//Os abaixo nao sao funcionais

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
