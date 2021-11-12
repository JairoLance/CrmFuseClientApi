<?php

use App\Lib\Auth,
	App\Lib\Response,
	App\Middleware\AuthMiddleware;



$app->group('/Crm_proyectos/', function () {


	$this->get('', function ($request, $response) {

		return $response->withStatus(200)->write("crm_proyectos");
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
		$institucion = isset($input->institucion) ? $input->institucion : '';

		$sql = " SELECT * FROM  crm_proyectos order by cp_idcp asc ";
		$sth = $this->model->models->Prepare($sql);
		$sth->execute();
		$arr = array();
		$arr["data"] = $sth->fetchAll();
		return $this->response->withJson($arr);
	});

	$this->post('getById', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$id = isset($input->id) ? $input->id : '';
		$sql = " SELECT * FROM crm_proyectos WHERE cp_idcp = $id order by cp_idcp asc limit 1";
		$sth = $this->model->models->Prepare($sql);
		$sth->execute();
		$arr = array();
		$arr["data"] = $sth->fetch(PDO::FETCH_ASSOC);
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
			$filter = " WHERE   cp_idcp like '%$search%'  OR   cp_nombre like '%$search%'  OR   cp_descripcion like '%$search%'  OR   cp_orden like '%$search%'  OR   cp_usuario_id like '%$search%'  OR   cp_fecha_creacion like '%$search%'  OR   cp_id_tipo_proyecto like '%$search%'  OR   cp_estado like '%$search%'  OR   cp_empresa like '%$search%' ";
		}
		$countQuery = $this->model->models->prepare("SELECT COUNT(*) total FROM crm_proyectos $filter ");
		$sql  = " SELECT * FROM crm_proyectos $filter ";
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
		$cp_nombre = $input->cp_nombre;
		$cp_descripcion = $input->cp_descripcion;
		$cp_orden = $input->cp_orden;
		$cp_usuario_id = $input->cp_usuario_id;
		$cp_fecha_creacion =  date("Y-m-d H:i:s");
		$cp_id_tipo_proyecto = $input->cp_id_tipo_proyecto;
		$cp_estado = $input->cp_estado;
		$cp_empresa = $input->cp_empresa;
		$cp_idcp = isset($input->cp_idcp) ? $input->cp_idcp : '';
		$trecords = 0;

		$id = 0;

		if ($cp_idcp) {
			$statement_val = $this->model->models->Prepare("SELECT * FROM crm_proyectos WHERE  cp_idcp = :cp_idcp limit 1");
			$statement_val->bindValue(':cp_idcp', $cp_idcp);
			$trecords = $statement_val->rowCount();
			$id = $this->model->models->Execute($statement_val);
		}



		try {



			if ($trecords <= 0) {

				$sql = "INSERT INTO crm_proyectos (cp_nombre,cp_descripcion,cp_orden,cp_usuario_id,cp_fecha_creacion,cp_id_tipo_proyecto,cp_estado,cp_empresa) 
                    VALUES (:cp_nombre,:cp_descripcion,:cp_orden,:cp_usuario_id,:cp_fecha_creacion,:cp_id_tipo_proyecto,:cp_estado,:cp_empresa)";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("cp_nombre", $cp_nombre);
				$stmt->bindParam("cp_descripcion", $cp_descripcion);
				$stmt->bindParam("cp_orden", $cp_orden);
				$stmt->bindParam("cp_usuario_id", $cp_usuario_id);
				$stmt->bindParam("cp_fecha_creacion", $cp_fecha_creacion);
				$stmt->bindParam("cp_id_tipo_proyecto", $cp_id_tipo_proyecto);
				$stmt->bindParam("cp_estado", $cp_estado);
				$stmt->bindParam("cp_empresa", $cp_empresa);
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
		$cp_nombre = $input->cp_nombre;
		$cp_descripcion = $input->cp_descripcion;
		$cp_orden = $input->cp_orden;
		$cp_usuario_id = $input->cp_usuario_id;
		$cp_fecha_creacion = $input->cp_fecha_creacion;
		$cp_id_tipo_proyecto = $input->cp_id_tipo_proyecto;
		$cp_estado = $input->cp_estado;
		$cp_empresa = $input->cp_empresa;
		$cp_idcp = isset($input->cp_idcp) ? $input->cp_idcp : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_proyectos WHERE  cp_idcp = :cp_idcp limit 1");

		$statement_val->bindValue(':cp_idcp', $cp_idcp);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "UPDATE crm_proyectos SET cp_nombre=:cp_nombre,cp_descripcion=:cp_descripcion,cp_orden=:cp_orden,cp_usuario_id=:cp_usuario_id,cp_fecha_creacion=:cp_fecha_creacion,cp_id_tipo_proyecto=:cp_id_tipo_proyecto,cp_estado=:cp_estado,cp_empresa=:cp_empresa WHERE cp_idcp = :cp_idcp";
				$stmt = $this->model->models->Prepare($sql);
				$stmt->bindParam("cp_idcp", $cp_idcp);
				$stmt->bindParam("cp_nombre", $cp_nombre);
				$stmt->bindParam("cp_descripcion", $cp_descripcion);
				$stmt->bindParam("cp_orden", $cp_orden);
				$stmt->bindParam("cp_usuario_id", $cp_usuario_id);
				$stmt->bindParam("cp_fecha_creacion", $cp_fecha_creacion);
				$stmt->bindParam("cp_id_tipo_proyecto", $cp_id_tipo_proyecto);
				$stmt->bindParam("cp_estado", $cp_estado);
				$stmt->bindParam("cp_empresa", $cp_empresa);
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
		$cp_idcp = isset($input->cp_idcp) ? $input->cp_idcp : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_proyectos WHERE  cp_idcp = :cp_idcp limit 1");

		$statement_val->bindValue(':cp_idcp', $cp_idcp);

		$id = $this->model->models->Execute($statement_val);

		try {


			if ($statement_val->rowCount() > 0) {

				$sql = "DELETE FROM crm_proyectos WHERE cp_idcp = :cp_idcp";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("cp_idcp", $cp_idcp);
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
