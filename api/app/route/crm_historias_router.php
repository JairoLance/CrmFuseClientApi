<?php

use App\Lib\Auth,
	App\Lib\Response,
	App\Middleware\AuthMiddleware;



$app->group('/Crm_historias/', function () {


	$this->get('', function ($request, $response) {

		return $response->withStatus(200)->write("crm_historias");
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
		$hi_proyecto_id = isset($input->hi_proyecto_id) ? $input->hi_proyecto_id : '';

		// $sql = " SELECT * FROM crm_historias ";
		// $sql .= " INNER JOIN crm_usuarios ON hi_usuario_asignado_id = us_idusuario ";
		// $sql .= " where hi_proyecto_id = $hi_proyecto_id  order by hi_idhi asc ";

		$sql = " SELECT  ";
		$sql .= " SUM(IF(ta_estado = 1 , 1 , 0)) activo, ";
		$sql .= " SUM(IF(ta_estado = 2 , 1 , 0)) desarrollo, ";
		$sql .= " SUM(IF(ta_estado = 3 , 1 , 0)) finalizado,  ";
		$sql .= " crm_historias.* , crm_usuarios.*  ";
		$sql .= " FROM crm_historias  ";
		$sql .= " INNER JOIN crm_usuarios ON hi_usuario_asignado_id = us_idusuario ";
		$sql .= " LEFT JOIN crm_tareas ON ta_historia_id = hi_idhi ";
		$sql .= " WHERE hi_proyecto_id = $hi_proyecto_id  GROUP BY hi_idhi ORDER BY hi_idhi ASC ";

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
		$sql  = " SELECT * FROM crm_historias ";
		$sql .= " INNER JOIN crm_usuarios ON hi_usuario_asignado_id = us_idusuario" ;
		$sql .= " WHERE hi_idhi = $id order by hi_idhi asc limit 1 ";
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
			$filter = " WHERE   hi_idhi like '%$search%'  OR   hi_nombre like '%$search%'  OR   hi_descripcion like '%$search%'  OR   hi_proyecto_id like '%$search%'  OR   hi_usuario_crea_id like '%$search%'  OR   hi_usuario_asignado_id like '%$search%'  OR   hi_fecha_creacion like '%$search%' ";
		}
		$countQuery = $this->model->models->prepare("SELECT COUNT(*) total FROM crm_historias $filter ");
		$sql  = " SELECT * FROM crm_historias $filter ";
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
		$hi_nombre = $input->hi_nombre;
		$hi_descripcion = $input->hi_descripcion;
		$hi_proyecto_id = $input->hi_proyecto_id;
		$hi_usuario_crea_id = $input->usuario;
		$hi_usuario_asignado_id = $input->hi_usuario_asignado_id;
		$hi_fecha_creacion =  date("Y-m-d H:i:s");
		$hi_idhi = isset($input->hi_idhi) ? $input->hi_idhi : '';
		$trecords = 0;

		$id = 0;



		if ($hi_idhi) {
			$statement_val = $this->model->models->Prepare("SELECT * FROM crm_historias WHERE  hi_idhi = :hi_idhi limit 1");
			$statement_val->bindValue(':hi_idhi', $hi_idhi);
			$trecords = $statement_val->rowCount();
			$id = $this->model->models->Execute($statement_val);
		}

		try {

			if ($trecords <= 0) {

				$sql = "INSERT INTO crm_historias (hi_nombre,hi_descripcion,hi_proyecto_id,hi_usuario_crea_id,hi_usuario_asignado_id,hi_fecha_creacion) 
                    VALUES (:hi_nombre,:hi_descripcion,:hi_proyecto_id,:hi_usuario_crea_id,:hi_usuario_asignado_id,:hi_fecha_creacion)";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("hi_nombre", $hi_nombre);
				$stmt->bindParam("hi_descripcion", $hi_descripcion);
				$stmt->bindParam("hi_proyecto_id", $hi_proyecto_id);
				$stmt->bindParam("hi_usuario_crea_id", $hi_usuario_crea_id);
				$stmt->bindParam("hi_usuario_asignado_id", $hi_usuario_asignado_id);
				$stmt->bindParam("hi_fecha_creacion", $hi_fecha_creacion);
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
		$hi_nombre = $input->hi_nombre;
		$hi_descripcion = $input->hi_descripcion;
		$hi_proyecto_id = $input->hi_proyecto_id;
		$hi_usuario_crea_id = $input->hi_usuario_crea_id;
		$hi_usuario_asignado_id = $input->hi_usuario_asignado_id;
		$hi_fecha_creacion = $input->hi_fecha_creacion;
		$hi_idhi = isset($input->hi_idhi) ? $input->hi_idhi : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_historias WHERE  hi_idhi = :hi_idhi limit 1");

		$statement_val->bindValue(':hi_idhi', $hi_idhi);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "UPDATE crm_historias SET hi_nombre=:hi_nombre,hi_descripcion=:hi_descripcion,hi_proyecto_id=:hi_proyecto_id,hi_usuario_crea_id=:hi_usuario_crea_id,hi_usuario_asignado_id=:hi_usuario_asignado_id,hi_fecha_creacion=:hi_fecha_creacion WHERE hi_idhi = :hi_idhi";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("hi_idhi", $hi_idhi);
				$stmt->bindParam("hi_nombre", $hi_nombre);
				$stmt->bindParam("hi_descripcion", $hi_descripcion);
				$stmt->bindParam("hi_proyecto_id", $hi_proyecto_id);
				$stmt->bindParam("hi_usuario_crea_id", $hi_usuario_crea_id);
				$stmt->bindParam("hi_usuario_asignado_id", $hi_usuario_asignado_id);
				$stmt->bindParam("hi_fecha_creacion", $hi_fecha_creacion);
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
		$hi_nombre = $input->hi_nombre;
		$hi_descripcion = $input->hi_descripcion;
		$hi_proyecto_id = $input->hi_proyecto_id;
		$hi_usuario_crea_id = $input->hi_usuario_crea_id;
		$hi_usuario_asignado_id = $input->hi_usuario_asignado_id;
		$hi_fecha_creacion = $input->hi_fecha_creacion;
		$hi_idhi = isset($input->hi_idhi) ? $input->hi_idhi : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_historias WHERE  hi_idhi = :hi_idhi limit 1");

		$statement_val->bindValue(':hi_idhi', $hi_idhi);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "DELETE FROM crm_historias WHERE hi_idhi = :hi_idhi";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("hi_idhi", $hi_idhi);
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
