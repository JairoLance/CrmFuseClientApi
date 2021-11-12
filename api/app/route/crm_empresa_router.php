<?php

use App\Lib\Auth,
	App\Lib\Response,
	App\Middleware\AuthMiddleware;



$app->group('/Crm_empresa/', function () {


	$this->get('', function ($request, $response) {

		return $response->withStatus(200)->write("crm_empresa");
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
		$institucion = isset($input->institucion) ? $input->institucion : '';

		$sql = " SELECT * FROM  crm_empresa order by emp_idemp asc ";
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
			$filter = " WHERE   emp_idemp like '%$search%'  OR   emp_nombre like '%$search%'  OR   emp_nit like '%$search%'  OR   emp_telefono like '%$search%'  OR   emp_direccion like '%$search%'  OR   emp_email like '%$search%' ";
		}
		$countQuery = $this->model->models->prepare("SELECT COUNT(*) total FROM crm_empresa $filter ");
		$sql  = " SELECT * FROM crm_empresa $filter ";
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

	$this->post('create', function ($req, $res, $args) {
		$msn = array();
		$input = (object) $req->getParsedBody();
		$op = isset($input->op) ? $input->op : '';
		$emp_nombre = $input->emp_nombre;
		$emp_nit = $input->emp_nit;
		$emp_telefono = $input->emp_telefono;
		$emp_direccion = $input->emp_direccion;
		$emp_email = $input->emp_email;
		$emp_idemp = isset($input->emp_idemp) ? $input->emp_idemp : '';
		$trecords = 0;

		$id = 0;



		if ($emp_idemp) {



			$statement_val = $this->model->models->Prepare("SELECT * FROM crm_empresa WHERE  emp_idemp = :emp_idemp limit 1");

			$statement_val->bindValue(':emp_idemp', $emp_idemp);



			$trecords = $statement_val->rowCount();
			$id = $this->model->models->Execute($statement_val);
		}



		try {



			if ($trecords <= 0) {

				$sql = "INSERT INTO crm_empresa (emp_nombre,emp_nit,emp_telefono,emp_direccion,emp_email) 
                    VALUES (:emp_nombre,:emp_nit,:emp_telefono,:emp_direccion,:emp_email)";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("emp_nombre", $emp_nombre);
				$stmt->bindParam("emp_nit", $emp_nit);
				$stmt->bindParam("emp_telefono", $emp_telefono);
				$stmt->bindParam("emp_direccion", $emp_direccion);
				$stmt->bindParam("emp_email", $emp_email);
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
		$emp_nombre = $input->emp_nombre;
		$emp_nit = $input->emp_nit;
		$emp_telefono = $input->emp_telefono;
		$emp_direccion = $input->emp_direccion;
		$emp_email = $input->emp_email;
		$emp_idemp = isset($input->emp_idemp) ? $input->emp_idemp : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_empresa WHERE  emp_idemp = :emp_idemp limit 1");

		$statement_val->bindValue(':emp_idemp', $emp_idemp);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "UPDATE crm_empresa SET emp_nombre=:emp_nombre,emp_nit=:emp_nit,emp_telefono=:emp_telefono,emp_direccion=:emp_direccion,emp_email=:emp_email WHERE emp_idemp = :emp_idemp";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("emp_idemp", $emp_idemp);
				$stmt->bindParam("emp_nombre", $emp_nombre);
				$stmt->bindParam("emp_nit", $emp_nit);
				$stmt->bindParam("emp_telefono", $emp_telefono);
				$stmt->bindParam("emp_direccion", $emp_direccion);
				$stmt->bindParam("emp_email", $emp_email);
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
		$emp_nombre = $input->emp_nombre;
		$emp_nit = $input->emp_nit;
		$emp_telefono = $input->emp_telefono;
		$emp_direccion = $input->emp_direccion;
		$emp_email = $input->emp_email;
		$emp_idemp = isset($input->emp_idemp) ? $input->emp_idemp : '';


		$statement_val = $this->model->models->Prepare("SELECT * FROM crm_empresa WHERE  emp_idemp = :emp_idemp limit 1");

		$statement_val->bindValue(':emp_idemp', $emp_idemp);

		$id = $this->model->models->Execute($statement_val);

		try {



			if ($statement_val->rowCount() > 0) {

				$sql = "DELETE FROM crm_empresa WHERE emp_idemp = :emp_idemp";
				$stmt = $this->model->models->Prepare($sql);

				$stmt->bindParam("emp_idemp", $emp_idemp);
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
