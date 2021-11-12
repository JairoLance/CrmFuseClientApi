<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
// Adding dependencies
//define('JWT_ISS', 'http://192.168.1.51:8182');
define('JWT_ISS', 'http://192.168.179.2/');
define('JWT_SECRET', '9a4P5K0o/UMajZLuSf9iK1lehlmEwqL5X89//9+ISYgMpqashAzaXXvluEih2rqhBDQsmP0fpYFeCU3uoJXzOA==');

$container = $app->getContainer();

$container["jwt"] = function ($container) {
    return new StdClass;
};

$app->add(new \Slim\Middleware\JwtAuthentication([
	"path" => "/",
    "logger" => $container['logger'],
    "secure" => false,
    "relaxed" => ["localhost", "192.168.179.2"],
    "secret" => JWT_SECRET,
    "rules" => [
        new \Slim\Middleware\JwtAuthentication\RequestPathRule([
            "path" => "/",
            "passthrough" => [
                "/projects/*",
				"/task/*",
                "/auth/*"                
            ]
        ]),
        new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
            "passthrough" => ["OPTIONS"]
        ]),
    ],
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];

        $secret = JWT_SECRET;
        $token = Firebase\JWT\JWT::encode($arguments["decoded"], $secret, "HS256");

        // Comprobamos que el token fue creado desde nuestro dominio.
        // if ($arguments["decoded"]->iss !== JWT_ISS) {
        //     return false;
        // }

        // Comprobamos que el token no se encuentre en la lista negra.
        // if (TokenBlackListController::isExistsToken($token)) {
        //     return false;
        // }
    },
    "error" => function ($request, $response, $arguments) {
        $resp = array(
                "error" => 'Error token',
                "message" => $arguments["message"],
                "data" => null,
                "numberError" => 401);

        return $response->withJson($resp, $resp['numberError']);
    }
]));



$app->add(new \Tuupola\Middleware\Cors([
    "logger" => $container["logger"],
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
    "headers.allow" => ["Origin", "Content-Type", "Authorization", "Accept", "ignoreLoadingBar", "X-Requested-With", "Access-Control-Allow-Origin"],
    "headers.expose" => [],
    "credentials" => true,
    "cache" => 0,
    "error" => function ($request, $response, $arguments) {
        $resp = array(
            "error" => 101,
            "message" => $arguments["message"],
            "data" => null,
            "numberError" => 401
        );

        return $response->withJson($resp, $resp['numberError']);
    }
]));