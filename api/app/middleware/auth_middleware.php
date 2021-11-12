<?php

namespace App\Middleware;

use Exception,
    App\Lib\Response,
    App\Lib\Auth;

class AuthMiddleware
{

    private $app = null;
    private $response;

    public function __construct($app)
    {
        $this->app = $app;
        $this->response = new Response();
    }

    public function __invoke($request, $response, $next)
    {
        //Sdw1s9x8@Pa$$w0rdRomanos18
        $c = $this->app->getContainer();
        $app_token_name = $c->settings['app_token_name'];

        //$token = $request->getHeader($app_token_name);
        $gets = $request->getHeader('Authorization');
        $token = str_replace('Bearer ', "", isset($gets[0]) ? $gets[0] : '');
        if (isset($token))
            $token = $token;

        try {
            Auth::Check($token);
        } catch (Exception $e) {
            $data = array(
                'type' => 'error',
                'result' => 0,
                'status' => 401,
                'message' => " No estas autorizado ! "
            );
            return $response->withStatus(401)
                ->withJson($data); 
        }

        return $next($request, $response);
    }
}
