<?php

use App\Lib\Auth,
	App\Lib\Response,
	App\Middleware\AuthMiddleware;



$app->group('/Crm_tareas/', function () {


	$this->get('', function ($request, $response) {

		return $response->withStatus(200)->write("crm_tareas");
	});




	/**
	 * Function index		 
	 * Acceso a la vista						 
	 * Metodologia ajax post.
	 * 
	 * @autor Jairo torres <ingtorres1986@gmail.com>
	 * @creado   : 2021-11-10 
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
		$id = isset($input->ta_historia_id) ? $input->ta_historia_id : '';
		$sql = " SELECT * FROM crm_tareas WHERE ta_historia_id = $id order by ta_orden asc ";
		$sth = $this->model->models->Prepare($sql);
		$sth->execute();
		$arr = array();
		$arr["data"] = $sth->fetchAll();
		return $this->response->withJson($arr);
	});

	$this->post('TienesTicketsAbiertos', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$response = array();
		$usuario_id = isset($input->usuario) ? $input->usuario : '';
		$sql = " SELECT count(*) total FROM crm_historias
		INNER JOIN crm_tareas ON ta_historia_id = hi_idhi
		WHERE hi_usuario_asignado_id = $usuario_id AND ta_estado = 1 ORDER BY ta_orden ASC limit 1";
		$sth = $this->model->models->Prepare($sql);
		$sth->execute();
		$arr = array();
		$arr["data"] = $sth->fetchAll();
		return $this->response->withJson($arr);
	});

	$this->post('TienesTicketsAbiertosList', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$response = array();
		$usuario_id = isset($input->usuario) ? $input->usuario : '';
		$estado = isset($input->estado) ? $input->estado : '';
		$sql = " SELECT * FROM crm_historias
		INNER JOIN crm_tareas ON ta_historia_id = hi_idhi
		INNER JOIN crm_tareas_estado ON ta_estado = te_idte
		WHERE hi_usuario_asignado_id = $usuario_id AND ta_estado = $estado ORDER BY ta_orden ASC ";
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
	 * @creado   : 2021-11-10 
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
			$filter = " WHERE   ta_idta like '%$search%'  OR   ta_historia_id like '%$search%'  OR   ta_usuario_id like '%$search%'  OR   ta_tipo_tarea like '%$search%'  OR   ta_titulo like '%$search%'  OR   ta_comentarios like '%$search%'  OR   ta_fecha_vencimiento like '%$search%'  OR   ta_hora like '%$search%'  OR   ta_descripcion like '%$search%'  OR   ta_asignar_a like '%$search%'  OR   ta_vincular_a like '%$search%'  OR   ta_visible_usuarios like '%$search%'  OR   ta_prioridad like '%$search%'  OR   ta_estado like '%$search%'  OR   ta_recordatorio like '%$search%'  OR   ta_recordatorio_fecha like '%$search%'  OR   ta_archivado like '%$search%'  OR   ta_fecha_actualizacion like '%$search%'  OR   ta_orden like '%$search%' ";
		}
		$countQuery = $this->model->models->prepare("SELECT COUNT(*) total FROM crm_tareas $filter ");
		$sql  = " SELECT * FROM crm_tareas $filter ";
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
	 * @creado   : 2021-11-10 
	 * 
	 * 
	 * @param type 
	 * @return type
	 * exceptions
	 *
	 * Proyecto para codenigter 
	 * 
	 */

	$this->post('move', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();

		$ta_historia_id = $input->ta_historia_id;
		$ta_idta = $input->ta_idta;
		$ta_orden = $input->ta_historia_id;
		$ta_estado = $input->ta_estado;

		try {

			$sql = "UPDATE crm_tareas SET ta_historia_id=:ta_historia_id,ta_estado=:ta_estado,ta_orden=:ta_orden WHERE ta_idta = :ta_idta";
			$stmt = $this->model->models->Prepare($sql);

			$stmt->bindParam("ta_idta", $ta_idta);
			$stmt->bindParam("ta_historia_id", $ta_historia_id);
			$stmt->bindParam("ta_orden", $ta_orden);
			$stmt->bindParam("ta_estado", $ta_estado);
			$this->model->models->Execute($stmt);

			$msn['type'] = "success";
			$msn['result'] = 0;
			$msn['title'] = "Proceso";
			$msn['content'] = "Movido exitosamente";
			return $this->response->withJson($msn);
		} catch (PDOException $e) {
			$msn['type'] = "error";
			$msn['result'] = 0;
			$msn['title'] = "Proceso fallido !";
			$msn['content'] = $e->getMessage();
			return $this->response->withJson($msn);
		}
	});

	$this->post('create', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$ta_historia_id = $input->ta_historia_id;
		$ta_usuario_id = $input->ta_usuario_id;
		$ta_tipo_tarea = $input->ta_tipo_tarea;
		$ta_titulo = $input->ta_titulo;
		$ta_comentarios = $input->ta_comentarios;
		$ta_fecha_vencimiento = $input->ta_fecha_vencimiento;
		$ta_hora = $input->ta_hora;
		$ta_descripcion = $input->ta_descripcion;
		$ta_asignar_a = $input->ta_asignar_a;
		$ta_vincular_a = $input->ta_vincular_a;
		$ta_visible_usuarios = $input->ta_visible_usuarios;
		$ta_prioridad = $input->ta_prioridad;
		$ta_estado = $input->ta_estado;
		$ta_recordatorio = $input->ta_recordatorio;
		$ta_recordatorio_fecha = $input->ta_recordatorio_fecha;
		$ta_archivado = $input->ta_archivado;
		$ta_fecha_actualizacion = date("Y-m-d H:i:s");
		$ta_orden = $input->ta_orden;
		$ta_idta = isset($input->ta_idta) ? $input->ta_idta : '';
		$trecords = 0;

		$id = 0;



		if ($ta_idta) {


			$statement_val = $this->model->models->Prepare("SELECT * FROM crm_tareas WHERE  ta_idta = :ta_idta limit 1");
			$statement_val->bindValue(':ta_idta', $ta_idta);
			$trecords = $statement_val->rowCount();
			$id = $this->model->models->Execute($statement_val);
		}



		try {



			if ($trecords <= 0) {

				$sql = "INSERT INTO crm_tareas (ta_historia_id,ta_usuario_id,ta_tipo_tarea,ta_titulo,ta_comentarios,ta_fecha_vencimiento,ta_hora,ta_descripcion,ta_asignar_a,ta_vincular_a,ta_visible_usuarios,ta_prioridad,ta_estado,ta_recordatorio,ta_recordatorio_fecha,ta_archivado,ta_fecha_actualizacion,ta_orden,ta_fecha_creacion) 
                    VALUES (:ta_historia_id,:ta_usuario_id,:ta_tipo_tarea,:ta_titulo,:ta_comentarios,:ta_fecha_vencimiento,:ta_hora,:ta_descripcion,:ta_asignar_a,:ta_vincular_a,:ta_visible_usuarios,:ta_prioridad,:ta_estado,:ta_recordatorio,:ta_recordatorio_fecha,:ta_archivado,:ta_fecha_actualizacion,:ta_orden,NOW())";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("ta_historia_id", $ta_historia_id);
				$stmt->bindParam("ta_usuario_id", $ta_usuario_id);
				$stmt->bindParam("ta_tipo_tarea", $ta_tipo_tarea);
				$stmt->bindParam("ta_titulo", $ta_titulo);
				$stmt->bindParam("ta_comentarios", $ta_comentarios);
				$stmt->bindParam("ta_fecha_vencimiento", $ta_fecha_vencimiento);
				$stmt->bindParam("ta_hora", $ta_hora);
				$stmt->bindParam("ta_descripcion", $ta_descripcion);
				$stmt->bindParam("ta_asignar_a", $ta_asignar_a);
				$stmt->bindParam("ta_vincular_a", $ta_vincular_a);
				$stmt->bindParam("ta_visible_usuarios", $ta_visible_usuarios);
				$stmt->bindParam("ta_prioridad", $ta_prioridad);
				$stmt->bindParam("ta_estado", $ta_estado);
				$stmt->bindParam("ta_recordatorio", $ta_recordatorio);
				$stmt->bindParam("ta_recordatorio_fecha", $ta_recordatorio_fecha);
				$stmt->bindParam("ta_archivado", $ta_archivado);
				$stmt->bindParam("ta_fecha_actualizacion", $ta_fecha_actualizacion);
				$stmt->bindParam("ta_orden", $ta_orden);
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
	 * @creado   : 2021-11-10 
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
	 * @creado   : 2021-11-10 
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
		$ta_historia_id = $input->ta_historia_id;
		$ta_usuario_id = $input->ta_usuario_id;
		$ta_tipo_tarea = $input->ta_tipo_tarea;
		$ta_titulo = $input->ta_titulo;
		$ta_comentarios = $input->ta_comentarios;
		$ta_fecha_vencimiento = $input->ta_fecha_vencimiento;
		$ta_hora = $input->ta_hora;
		$ta_descripcion = $input->ta_descripcion;
		$ta_asignar_a = $input->ta_asignar_a;
		$ta_vincular_a = $input->ta_vincular_a;
		$ta_visible_usuarios = $input->ta_visible_usuarios;
		$ta_prioridad = $input->ta_prioridad;
		$ta_estado = $input->ta_estado;
		$ta_recordatorio = $input->ta_recordatorio;
		$ta_recordatorio_fecha = $input->ta_recordatorio_fecha;
		$ta_archivado = $input->ta_archivado;
		$ta_fecha_actualizacion =  date("Y-m-d H:i:s");
		$ta_orden = $input->ta_orden;
		$ta_idta = isset($input->ta_idta) ? $input->ta_idta : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_tareas WHERE  ta_idta = :ta_idta limit 1");

		$statement_val->bindValue(':ta_idta', $ta_idta);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "UPDATE crm_tareas SET ta_historia_id=:ta_historia_id,ta_usuario_id=:ta_usuario_id,ta_tipo_tarea=:ta_tipo_tarea,ta_titulo=:ta_titulo,ta_comentarios=:ta_comentarios,ta_fecha_vencimiento=:ta_fecha_vencimiento,ta_hora=:ta_hora,ta_descripcion=:ta_descripcion,ta_asignar_a=:ta_asignar_a,ta_vincular_a=:ta_vincular_a,ta_visible_usuarios=:ta_visible_usuarios,ta_prioridad=:ta_prioridad,ta_estado=:ta_estado,ta_recordatorio=:ta_recordatorio,ta_recordatorio_fecha=:ta_recordatorio_fecha,ta_archivado=:ta_archivado,ta_fecha_actualizacion=:ta_fecha_actualizacion,ta_orden=:ta_orden WHERE ta_idta = :ta_idta";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("ta_idta", $ta_idta);
				$stmt->bindParam("ta_historia_id", $ta_historia_id);
				$stmt->bindParam("ta_usuario_id", $ta_usuario_id);
				$stmt->bindParam("ta_tipo_tarea", $ta_tipo_tarea);
				$stmt->bindParam("ta_titulo", $ta_titulo);
				$stmt->bindParam("ta_comentarios", $ta_comentarios);
				$stmt->bindParam("ta_fecha_vencimiento", $ta_fecha_vencimiento);
				$stmt->bindParam("ta_hora", $ta_hora);
				$stmt->bindParam("ta_descripcion", $ta_descripcion);
				$stmt->bindParam("ta_asignar_a", $ta_asignar_a);
				$stmt->bindParam("ta_vincular_a", $ta_vincular_a);
				$stmt->bindParam("ta_visible_usuarios", $ta_visible_usuarios);
				$stmt->bindParam("ta_prioridad", $ta_prioridad);
				$stmt->bindParam("ta_estado", $ta_estado);
				$stmt->bindParam("ta_recordatorio", $ta_recordatorio);
				$stmt->bindParam("ta_recordatorio_fecha", $ta_recordatorio_fecha);
				$stmt->bindParam("ta_archivado", $ta_archivado);
				$stmt->bindParam("ta_fecha_actualizacion", $ta_fecha_actualizacion);
				$stmt->bindParam("ta_orden", $ta_orden);
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
	 * @creado   : 2021-11-10 
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
		$ta_historia_id = $input->ta_historia_id;
		$ta_usuario_id = $input->ta_usuario_id;
		$ta_tipo_tarea = $input->ta_tipo_tarea;
		$ta_titulo = $input->ta_titulo;
		$ta_comentarios = $input->ta_comentarios;
		$ta_fecha_vencimiento = $input->ta_fecha_vencimiento;
		$ta_hora = $input->ta_hora;
		$ta_descripcion = $input->ta_descripcion;
		$ta_asignar_a = $input->ta_asignar_a;
		$ta_vincular_a = $input->ta_vincular_a;
		$ta_visible_usuarios = $input->ta_visible_usuarios;
		$ta_prioridad = $input->ta_prioridad;
		$ta_estado = $input->ta_estado;
		$ta_recordatorio = $input->ta_recordatorio;
		$ta_recordatorio_fecha = $input->ta_recordatorio_fecha;
		$ta_archivado = $input->ta_archivado;
		$ta_fecha_actualizacion = $input->ta_fecha_actualizacion;
		$ta_orden = $input->ta_orden;
		$ta_idta = isset($input->ta_idta) ? $input->ta_idta : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_tareas WHERE  ta_idta = :ta_idta limit 1");

		$statement_val->bindValue(':ta_idta', $ta_idta);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "DELETE FROM crm_tareas WHERE ta_idta = :ta_idta";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("ta_idta", $ta_idta);
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
