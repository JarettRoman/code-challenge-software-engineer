<?php

namespace SoftwareChallenge\Mid;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use SoftwareChallenge\Mid\Controller;
use SoftwareChallenge\Mid\Provider;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/v1/users/{userId}', function (Request $request, Response $response, array $args) {
    $userId = $args['userId'] ?? null;

    $controller = new Controller(new Provider());
    $user = $controller->getUser($userId);

    return getUserViewResponse($user, $response);
});

$app->post('/v1/users', function (Request $request, Response $response) {
    $controller = new Controller(new Provider());
    $user = $controller->createUser((string) $request->getBody());

    return getUserViewResponse($user, $response);
});

$app->post('/v1/users/{userId}/record-donation-attempt', function (Request $request, Response $response, array $args) {
    $userId = $args['userId'];
    $controller = new Controller(new Provider());
    $result = $controller->recordDonationAttempt($userId);

    return getUserViewResponse($result, $response);
});

function getUserViewResponse($data, Response $response): Response
{
    $payload = json_encode($data, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
}

$app->run();