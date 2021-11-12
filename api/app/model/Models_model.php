<?php

namespace App\Model;

use App\Lib\Response;

class ModelsModel {

    private $db;
    private $response;

    public function __CONSTRUCT($db) {
        $this->db = $db;
        $this->response = new Response();
    }

    public function Prepare($sql) {
        $q = $this->db->getPdo()->prepare($sql);
        return $q;
    }

    public function Dbs() {
        return $this->db->getPdo();
    }

    public function PrepareSqlList($sql) {
        $q = $this->db->getPdo()->prepare($sql);
        $q->execute();
        $data = $q->fetchAll();
        return $data;
    }

    function pdoMultiUpdate($tableName, $data, $key) {
        $setStr = "";
        $sql = "";
        $toBind = array();
        foreach ($data as $arrayIndex => $row) {
            $params = array();
            foreach ($row as $columnName => $columnValue) {
                $setStr .= "`" . str_replace("`", "``", $columnName) . "` = :" . $columnName . $arrayIndex . ",";
                $param = ":" . $columnName . $arrayIndex;
                $toBind[$param] = $columnValue;
                $_key  = $key." = ".$row[$key];
            }
            $setStr = rtrim($setStr, ",");
            $sql .= "UPDATE $tableName SET $setStr WHERE $_key;";
            $setStr = "";
            $_key = "";
        }

        $pdoStatement = $this->db->getPdo()->prepare($sql);
        //Bind our values.
        foreach ($toBind as $param => $val) {
            $pdoStatement->bindValue($param, $val);
        }
        //Execute our statement (i.e. insert the data).
        return $pdoStatement->execute();
    }
	 

    function pdoMultiInsert($tableName, $data) {
        //Will contain SQL snippets.
        $rowsSQL = array();
        //Will contain the values that we need to bind.
        $toBind = array();
        //Get a list of column names to use in the SQL statement.
        $validate = isset($data[0]) ? 1 : 0;


        //Loop through our $data array.

        if ($validate) {
            $columnNames = array_keys($data[0]);
            foreach ($data as $arrayIndex => $row) {
                $params = array();
                foreach ($row as $columnName => $columnValue) {
                    $param = ":" . $columnName . $arrayIndex;
                    $params[] = $param;
                    $toBind[$param] = $columnValue;
                }
                $rowsSQL[] = "(" . implode(", ", $params) . ")";
            }
            //Construct our SQL statement
            $sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);
            //Prepare our PDO statement.
            $pdoStatement = $this->db->getPdo()->prepare($sql);
            //Bind our values.
            foreach ($toBind as $param => $val) {
                $pdoStatement->bindValue($param, $val);
            }
            //Execute our statement (i.e. insert the data).
            return $pdoStatement->execute();
        }
    }

    public function Execute($pdoStatement) {
        $pdoStatement->execute();
        return $this->db->getPdo()->lastInsertId();
    }

}
