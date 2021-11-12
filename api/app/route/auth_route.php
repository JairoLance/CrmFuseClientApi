<?php

use App\Lib\Auth,
    App\Lib\Response,
    App\Middleware\AuthMiddleware;

//http://localhost/CRM/taski/api/public/auth/authenticate?email=ingtorres1986@gmail.com&password=123
$app->group('/auth/', function () {

    $this->get('', function ($request, $response) {
        return $response->withStatus(200)->write("Autenticacion");
    });

    $this->post('authenticate', function ($req, $res, $args) {

        $input = (object) $req->getParsedBody();
        $email = $input->username;
        $password = $input->password;
        $result = $this->model->auth->authenticate($email, $password);

        return $res->withHeader('Content-type', 'application/json')->write(json_encode($result));
    });

    $this->post('getAllEmpresas', function ($req, $res, $args) {
        $msn = array();
        $input = (object) $req->getParsedBody();
        $op = isset($input->op) ? $input->op : '';
        $response = array();
        $institucion = isset($input->institucion) ? $input->institucion : '';

        $sql = " SELECT * FROM  crm_empresa order by emp_idemp asc ";
        $sth = $this->model->models->Prepare($sql);
        $sth->execute();
        $arr = array();
        $arr["data"] = $sth->fetchAll();
        return $this->response->withJson($arr);
    });

    $this->post('register', function ($req, $res, $args) {
        $msn = array();
        $input = (object) $req->getParsedBody();
        $op = isset($input->op) ? $input->op : '';
        $us_usuario = $input->rusername;
        $us_clave = md5($input->rpassword);
        $us_nombres = $input->rnombres;
        $us_email = $input->rcorreo;
        $us_rol_id = 1;
        $us_imagen = "";
        $us_estado = 1;
        $us_empresa = $input->rempresa;
        $us_activo = 1;
        $us_fecha_expira = date("Y-m-d H:i:s");
        $us_nunca_expira = 0;
        $us_idusuario = 0;
        $trecords = 0;
        $trecords1 = 0;
        $id = 0;

        //us_empresa =:us_empresa and
        $statement_val = $this->model->models->Prepare("SELECT * FROM crm_usuarios WHERE  us_usuario = :us_usuario limit 1");
        $statement_val->bindValue(':us_usuario', $us_usuario);
        // $statement_val->bindParam(":us_empresa", $us_empresa);
        $statement_val->execute();
        $trecords = $statement_val->rowCount();

        $statement_val1 = $this->model->models->Prepare("SELECT * FROM crm_usuarios WHERE  us_email = :us_email limit 1");
        $statement_val1->bindValue(':us_email', $us_email);
        // $statement_val1->bindParam(":us_empresa", $us_empresa);
        $statement_val1->execute();
        $trecords1 = $statement_val1->rowCount();


        try {
            if ($trecords <= 0) {
                if ($trecords1 <= 0) {
                    $sql = "INSERT INTO crm_usuarios (us_usuario,us_clave,us_nombres,us_email,us_rol_id,us_imagen,us_estado,us_empresa,us_activo,us_fecha_expira,us_nunca_expira) 
                    VALUES (:us_usuario,:us_clave,:us_nombres,:us_email,:us_rol_id,:us_imagen,:us_estado,:us_empresa,:us_activo,:us_fecha_expira,:us_nunca_expira)";
                    $stmt = $this->model->models->Prepare($sql);
                    $stmt->bindParam("us_usuario", $us_usuario);
                    $stmt->bindParam("us_clave", $us_clave);
                    $stmt->bindParam("us_nombres", $us_nombres);
                    $stmt->bindParam("us_email", $us_email);
                    $stmt->bindParam("us_rol_id", $us_rol_id);
                    $stmt->bindParam("us_imagen", $us_imagen);
                    $stmt->bindParam("us_estado", $us_estado);
                    $stmt->bindParam("us_empresa", $us_empresa);
                    $stmt->bindParam("us_activo", $us_activo);
                    $stmt->bindParam("us_fecha_expira", $us_fecha_expira);
                    $stmt->bindParam("us_nunca_expira", $us_nunca_expira);
                    $id = $this->model->models->Execute($stmt);
                    $msn['type'] = "success";
                    $msn['result'] = $id;
                    $msn['title'] = "Proceso";
                    $msn['content'] = "registro exitosamente";
                } else {
                    $msn['type'] = "error";
                    $msn['result'] = $id;
                    $msn['title'] = "Proceso";
                    $msn['content'] = "El correo electronico esta en uso!";
                }
            } else {
                $msn['type'] = "error";
                $msn['result'] = $id;
                $msn['title'] = "Proceso";
                $msn['content'] = "El nombre de usuario esta en uso ! ";
            }
            return $this->response->withJson($msn);
        } catch (PDOException $e) {
            $msn['type'] = "error";
            $msn['result'] = 0;
            $msn['title'] = "Proceso";
            $msn['content'] = $e->getMessage();
            return $this->response->withJson($msn);
        }
    });
});
