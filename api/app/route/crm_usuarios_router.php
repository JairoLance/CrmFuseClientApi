<?php

use App\Lib\Auth,
	App\Lib\Response,
	App\Middleware\AuthMiddleware;



$app->group('/Crm_usuarios/', function () {


	$this->get('', function ($request, $response) {

		return $response->withStatus(200)->write("crm_usuarios");
	});




	/**
	 * Function index		 
	 * Acceso a la vista						 
	 * Metodologia ajax post.
	 * 
	 * @autor Jairo torres <ingtorres1986@gmail.com>
	 * @creado   : 2021-11-09 
	 * 
	 * 
	 * @param type 
	 * @return type
	 * exceptions
	 *
	 * Proyecto para codenigter 
	 * 
	 */

	$this->post('getAll', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$response = array();

		$empresa = isset($input->empresa) ? $input->empresa : '';

		$sql = " SELECT * FROM  crm_usuarios where us_empresa = $empresa order by us_idusuario asc ";
		$sth = $this->model->models->Prepare($sql);
		$sth->execute();
		$arr = array();
		$arr["data"] = $sth->fetchAll();
		return $this->response->withJson($arr);
	});



	/**
	 * Function get_all_paginate()		 
	 * El objetivo es paginar los items						 
	 * Metodologia ajax post.
	 * 
	 * @autor Jairo torres <ingtorres1986@gmail.com>
	 * @creado   : 2021-11-09 
	 * 
	 * 
	 * @param type 
	 * @return type
	 * exceptions
	 *
	 * Proyecto para codenigter 
	 * 
	 */

	$this->post('showPagination', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$response = array();
		$institucion = isset($input->institucion) ? $input->institucion : '';
		$page = isset($input->page) ? $input->page : '';
		$limit = isset($input->itemsPerPage) ? $input->itemsPerPage : '';
		$search = isset($input->search) ? $input->search : '';
		$offset = (--$page) * $limit;
		$filter = "";
		if ($search) {
			$filter = " WHERE   us_idusuario like '%$search%'  OR   us_usuario like '%$search%'  OR   us_clave like '%$search%'  OR   us_nombres like '%$search%'  OR   us_email like '%$search%'  OR   us_rol_id like '%$search%'  OR   us_imagen like '%$search%'  OR   us_estado like '%$search%'  OR   us_empresa like '%$search%'  OR   us_activo like '%$search%'  OR   us_fecha_expira like '%$search%'  OR   us_nunca_expira like '%$search%' ";
		}
		$countQuery = $this->model->models->prepare("SELECT COUNT(*) total FROM crm_usuarios $filter ");
		$sql  = " SELECT * FROM crm_usuarios $filter ";
		$sql .= " LIMIT :limit OFFSET :offset ";
		$sth = $this->model->models->Prepare($sql);
		$sth->bindValue(':limit', $limit, \PDO::PARAM_INT);
		$sth->bindValue(':offset', $offset, \PDO::PARAM_INT);
		$sth->execute();
		$countQuery->execute();
		$arr = array();
		$arr["data"] = $sth->fetchAll();
		$arr["total"] = $countQuery->fetchAll()[0]->total;
		$arr["page"] = $page;
		$arr["itemsPerPage"] = $limit;
		return $this->response->withJson($arr);
	});



	/**
	 * Functon create
	 * 
	 * Metodo de creacion 
	 * Contiene auditoria
	 * Metodologia ajax post.
	 * 
	 * @autor Jairo torres <ingtorres1986@gmail.com>
	 * @creado   : 2021-11-09 
	 * 
	 * 
	 * @param type 
	 * @return type
	 * exceptions
	 *
	 * Proyecto para codenigter 
	 * 
	 */

	$this->post('create', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$us_usuario = $input->us_usuario;
		$us_clave = $input->us_clave;
		$us_nombres = $input->us_nombres;
		$us_email = $input->us_email;
		$us_rol_id = $input->us_rol_id;
		$us_imagen = $input->us_imagen;
		$us_estado = $input->us_estado;
		$us_empresa = $input->us_empresa;
		$us_activo = $input->us_activo;
		$us_fecha_expira = $input->us_fecha_expira;
		$us_nunca_expira = $input->us_nunca_expira;
		$us_idusuario = isset($input->us_idusuario) ? $input->us_idusuario : '';
		$trecords = 0;

		$id = 0;



		if ($us_idusuario) {
			$statement_val = $this->model->models->Prepare("SELECT * FROM crm_usuarios WHERE  us_idusuario = :us_idusuario limit 1");
			$statement_val->bindValue(':us_idusuario', $us_idusuario);
			$trecords = $statement_val->rowCount();
			$id = $this->model->models->Execute($statement_val);
		}

		try {
			if ($trecords <= 0) {

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
				$msn['content'] = "Enviado exitosamente";
			} else {
				$msn['type'] = "error";
				$msn['result'] = $id;
				$msn['title'] = "Proceso";
				$msn['content'] = "El item esta en uso!";
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



	/**
	 * Functon get_by_id
	 * 
	 * Metodo de obtencion de registro
	 * mediante el id de la tabla						 
	 * Metodologia ajax post.
	 * 
	 * @autor Jairo torres <ingtorres1986@gmail.com>
	 * @creado   : 2021-11-09 
	 * 
	 * 
	 * @param type 
	 * @return type
	 * exceptions
	 *
	 * Proyecto para codenigter 
	 * 
	 */ /**
	 * Functon update
	 * 
	 * Metodo de actualizacion y edicion 
	 * Contiene auditoria
	 * Metodologia ajax post.
	 * 
	 * @autor Jairo torres <ingtorres1986@gmail.com>
	 * @creado   : 2021-11-09 
	 * 
	 * 
	 * @param type 
	 * @return type
	 * exceptions
	 *
	 * Proyecto para codenigter 
	 * 
	 */

	$this->post('update', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$us_usuario = $input->us_usuario;
		$us_clave = $input->us_clave;
		$us_nombres = $input->us_nombres;
		$us_email = $input->us_email;
		$us_rol_id = $input->us_rol_id;
		$us_imagen = $input->us_imagen;
		$us_estado = $input->us_estado;
		$us_empresa = $input->us_empresa;
		$us_activo = $input->us_activo;
		$us_fecha_expira = $input->us_fecha_expira;
		$us_nunca_expira = $input->us_nunca_expira;
		$us_idusuario = isset($input->us_idusuario) ? $input->us_idusuario : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_usuarios WHERE  us_idusuario = :us_idusuario limit 1");

		$statement_val->bindValue(':us_idusuario', $us_idusuario);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "UPDATE crm_usuarios SET us_usuario=:us_usuario,us_clave=:us_clave,us_nombres=:us_nombres,us_email=:us_email,us_rol_id=:us_rol_id,us_imagen=:us_imagen,us_estado=:us_estado,us_empresa=:us_empresa,us_activo=:us_activo,us_fecha_expira=:us_fecha_expira,us_nunca_expira=:us_nunca_expira WHERE us_idusuario = :us_idusuario";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("us_idusuario", $us_idusuario);
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
				$msn['content'] = "Actualizado exitosamente";
			} else {
				$msn['type'] = "error";
				$msn['result'] = $id;
				$msn['title'] = "Proceso";
				$msn['content'] = "Problemas al actualizar!";
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



	/**
	 * Functon delete
	 * 
	 * Metodo de eliminacion
	 * Contiene auditoria						 
	 * Metodologia ajax post.
	 * 
	 * @autor Jairo torres <ingtorres1986@gmail.com>
	 * @creado   : 2021-11-09 
	 * 
	 * 
	 * @param type 
	 * @return type
	 * exceptions
	 *
	 * Proyecto para codenigter 
	 * 
	 */

	$this->post('delete', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$us_usuario = $input->us_usuario;
		$us_clave = $input->us_clave;
		$us_nombres = $input->us_nombres;
		$us_email = $input->us_email;
		$us_rol_id = $input->us_rol_id;
		$us_imagen = $input->us_imagen;
		$us_estado = $input->us_estado;
		$us_empresa = $input->us_empresa;
		$us_activo = $input->us_activo;
		$us_fecha_expira = $input->us_fecha_expira;
		$us_nunca_expira = $input->us_nunca_expira;
		$us_idusuario = isset($input->us_idusuario) ? $input->us_idusuario : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_usuarios WHERE  us_idusuario = :us_idusuario limit 1");

		$statement_val->bindValue(':us_idusuario', $us_idusuario);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "DELETE FROM crm_usuarios WHERE us_idusuario = :us_idusuario";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("us_idusuario", $us_idusuario);
				$id = $this->model->models->Execute($stmt);

				$msn['type'] = "success";
				$msn['result'] = $id;
				$msn['title'] = "Proceso";
				$msn['content'] = "Eliminado exitosamente";
			} else {
				$msn['type'] = "error";
				$msn['result'] = $id;
				$msn['title'] = "Proceso";
				$msn['content'] = "Problemas al eliminar!";
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
})->add(new AuthMiddleware($app));
