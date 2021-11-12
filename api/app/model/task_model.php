<?php

namespace App\Model;

use App\Lib\Response;

class TaskModel
{
	private $db;
	private $table = 'crm_tareas';
	private $response;

	public function __CONSTRUCT($db)
	{
		$this->db = $db;
		$this->response = new Response();
	}

	public function getAllTipo()
	{
		$data = $this->db->from("crm_proyectos_tipos")
			->where('pt_estado', 1)
			->orderBy('pt_idpt ASC')
			->fetchAll();
		return [
			'total' => "",
			'data'  => $data
		];
	}

	public function getAll($id)
	{
		/*$data = $this->db->from($this->table)
						 ->where('ta_proyecto_id', $id)
                         ->orderBy('ta_idta DESC')
                         ->fetchAll();*/

		$sql  = " SELECT ta_idta id ,  te_name status, ta_titulo title , ta_descripcion, ta_estado , tt_nombre, tt_icon FROM crm_tareas ";
		$sql .= " INNER JOIN crm_tareas_estado on ta_estado = te_idte ";
		$sql .= " INNER JOIN crm_tareas_tipos on tt_idtt = ta_tipo_tarea ";
		$sql .= " WHERE ta_proyecto_id = " . $id;
		$q = $this->db->getPdo()->prepare($sql);
		$q->execute();
		$row = $q->fetchAll();
		$array = array();
		$t = array();

		$t[1] = array(
			"id"   => 1,
			"text" => "EN ESPERA",
			"name" =>  "on-hold"
		);
		$t[2] = array(
			"id"   => 2,
			"text" => "EN PROGRESO",
			"name" =>  "in-progress"
		);
		$t[3] = array(
			"id"   => 3,
			"text" => "REVISION",
			"name" =>  "needs-review"
		);
		$t[4] = array(
			"id"   => 4,
			"text" => "APROBADO",
			"name" =>  "approved"
		);


		foreach ($row as $rows) {
			$array[] = array(
				'id'             => $rows->id,
				'status'         => $t[$rows->ta_estado],
				'title'          => $rows->title,
				'ta_descripcion' => $rows->ta_descripcion,
				'tt_nombre'      => $rows->tt_nombre,
				'tt_icon'        => $rows->tt_icon
			);
		}


		return [
			'total' => 0,
			'data'  => $array
		];
	}

	public function get($id)
	{
		return $this->db->from($this->table, $id)
			->fetch();
	}

	/*
    function insert
    params: $data
    result: true or false
    */
	public function insert($data)
	{

		$query = $this->db->insertInto($this->table, $data);
		$insert = $query->execute();

		print_r($insert);
		die;

		return $this->response->SetResponse(true);
	}

	public function updateOrden($proyecto, $posicion, $sw)
	{
		$sql = "Call PilasProyectos($proyecto,$posicion,'$sw'); ";
		$q = $this->db->getPdo()->prepare($sql);
		$r = $q->execute();
		return $this->response->SetResponse($r);
	}

	public function move($proyecto_id, $tarea_id, $estado)
	{

		$t = array();

		$t[] = array(
			"id"   => 1,
			"text" => "EN ESPERA",
			"name" =>  "on-hold"
		);
		$t[] = array(
			"id"   => 2,
			"text" => "EN PROGRESO",
			"name" =>  "in-progress"
		);
		$t[] = array(
			"id"   => 3,
			"text" => "REVISION",
			"name" =>  "needs-review"
		);
		$t[] = array(
			"id"   => 4,
			"text" => "APROBADO",
			"name" =>  "approved"
		);

		$key_est = array_search($estado, array_column($t, 'name'));
		$estado_id  = $t[$key_est]["id"];

		$data = array(
			"ta_estado" => $estado_id,
			"ta_fecha_actualizacion" => date("Y-m-d H:i:s")
		);

		$this->db->update($this->table, $data)
			->where('ta_proyecto_id', $proyecto_id)
			->where('ta_tipo_tarea', $tarea_id)
			->execute();

		return $this->response->SetResponse(true);
	}

	public function update($data, $id)
	{
		$this->db->update($this->table, $data)
			->where('cp_idcp', $id)
			->execute();
		return $this->response->SetResponse(true);
	}

	public function delete($id)
	{
		$this->db->deleteFrom($this->table, $id)
			->execute();

		return $this->response->SetResponse(true);
	}
}
