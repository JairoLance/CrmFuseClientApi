<?php

use App\Lib\Auth,
    App\Lib\Response,
    App\Validation\ProjectsValidation,
    App\Middleware\AuthMiddleware;



$app->group('/projects', function () {

    $this->get('', function ($request, $response) {
        return $response->withStatus(200)->write("Route de proyectos");
    });

    $this->post('/list', function ($req, $res, $args) {
        $input = (object)$req->getParsedBody(); 
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->projects->getAll($input->empresa , 0, 0))
            );
    });

    $this->post('/list_tipo_proyecto', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->projects->getAllTipo())
            );
    });

    $this->post('/insert', function ($req, $res, $args) {
        $r = ProjectsValidation::validate($req->getParsedBody());
        if (!$r->response) {

            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r->errors));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(json_encode($this->model->projects->insert($req->getParsedBody())));
    });

    $this->post('/update', function ($req, $res, $args) {
        $r = ProjectsValidation::validate($req->getParsedBody());
        $input = (object)$req->getParsedBody();
        if (!$r->response) {

            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r->errors));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(json_encode($this->model->projects->update($req->getParsedBody(), $input->cp_idcp)));
    });


    /*
	
	$this->get('/create/{data}',function ($req,$response,$args) {	
		$body  = $req->getParsedBody();
        $input = json_decode($body);	
		
		$paramValue1 = $req->getQueryParam('p'); // equal to $_REQUEST
     
	echo $paramValue1."\n";
 
		  
		//echo $_REQUEST['p'];//$req->getAttribute('p');		
    });*/

    $this->post('/orden', function ($req, $res, $args) {
        $input  = (object)$req->getParsedBody();
        //$r = ProjectsValidation::validate($body, true);
        //$_REQUEST['posicion'] = ($_REQUEST['posicion'] == 0) ? 1 : $_REQUEST['posicion'];		
        //$r = $this->model->projects->updateOrden($_REQUEST['proyecto'],$_REQUEST['posicion'],$_REQUEST['sw']);
        $result = $this->model->projects->updateOrden($input->proyecto, $input->posicion, $input->sw);
        return $res->withHeader('Content-type', 'application/json')
            ->write(json_encode($result));
    });
})->add(new AuthMiddleware($app));
