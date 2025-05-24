<?php
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
	$session_cookie = FigRequestCookies::get($request, "session");

	if ($session_cookie->getValue() !== "test") {
		$response->getBody()->write(json_encode(["status" => "Cookie not set."]));

		return $response->withHeader('Content-Type', 'application/json');
	}

	$response->getBody()->write(json_encode(["status" => "Cookie set."]));
	return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/setcookie', function (Request $request, Response $response, $args) {
	/* Set cookie */
	$session_cookie = SetCookie::create("session", "test")
		->withHttpOnly(true)
		->withSecure(true)
		->withPath("/")
		->withSameSite(sameSite: SameSite::strict());

	$response = FigResponseCookies::set($response, $session_cookie);

	return $response;
});

/* Cors Middleware */
$app->add(function ($request, $handler) {
	$response = $handler->handle($request);
	return $response
		->withHeader('Access-Control-Allow-Origin', '*')
		->withHeader('Access-Control-Allow-Headers', '*');
});


$app->run();