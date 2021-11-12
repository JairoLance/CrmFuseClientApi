<?php

namespace App\Model;

use App\Lib\Response;

class ProjectsModel
{
    private $db;
    private $table = 'crm_proyectos';
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

    public function getAll($empresa , $l, $p)
    {
        /* if ($l > 0 && $p > 0) {
             $data = $this->db->from($this->table)                         
                         ->limit($l)
                         ->offset($p)
                         ->orderBy('cp_orden ASC')
                         ->fetchAll();

        } else {
            $data = $this->db->from($this->table)
                         ->orderBy('cp_orden ASC')
                         ->fetchAll();

        }
		*/
        $total = $this->db->from($this->table)
            ->select('COUNT(*) Total')
            ->fetch()
            ->Total;

        $sql = "select * , count(hist.hi_idhi) task_count from crm_proyectos 
					left join(   
						SELECT *
						FROM crm_historias
						GROUP BY hi_idhi
					  ) hist on hist.hi_proyecto_id = cp_idcp
                      where cp_empresa = $empresa
					  group by cp_idcp";

        $q = $this->db->getPdo()->prepare($sql);
        $q->execute();
        $data = $q->fetchAll();


        return [
            'total' => $total,
            'data'  => $data

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

        $array = array(
            "cp_nombre" => $data['cp_nombre'],
            "cp_descripcion" => $data['cp_descripcion'],
            "cp_id_tipo_proyecto" => $data['cp_id_tipo_proyecto'],
            "cp_orden" => $data['cp_orden'],
            "cp_usuario_id" => $data['usuario'],
            "cp_empresa" => $data['empresa'],
        );

        $existe = $this->db->from($this->table)
            ->where('cp_nombre', $data['cp_nombre'])
            ->fetch();

        if (is_object($existe)) {
            return $this->response->SetResponse(false, " El nombre del proyecto esta en eso ! ");
        } else {
            $query = $this->db->insertInto($this->table, $array);
            $insert = $query->execute();
            return $this->response->SetResponse(true);
        }
    }

    public function updateOrden($proyecto, $posicion, $sw)
    {
        $sql = "Call PilasProyectos($proyecto,$posicion,'$sw'); ";
        $q = $this->db->getPdo()->prepare($sql);
        $r = $q->execute();
        return $this->response->SetResponse($r);
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
