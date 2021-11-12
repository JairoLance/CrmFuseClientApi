<?php

namespace App\Model;

use App\Lib\Response,
    App\Lib\Auth;

class AuthModel
{

    private $db;
    private $table = 'app_usuarios';
    private $response;

    public function __CONSTRUCT($db)
    {

        $this->db = $db;
        $this->response = new Response();
    }

    public function CalculateDateExpired($fecha_expired)
    {

        $expire = strtotime($fecha_expired);
        /* Get dias */
        $diff = ($expire - strtotime(date("Y-m-d H:i:s"))) / 86400;
        $matriz = array();
        $matriz["day"] = round($diff, 0);

        if ($matriz["day"] < 0) {
            $matriz["msn_day"] = "Lo sentimos , su cuenta de usuario expiro hace " . ($matriz["day"] * -1) . " dias";
        } else {
            $matriz["msn_day"] = "Su cuenta de usuario vence en " . $matriz["day"]; //"Su cuenta de usuario vence en ".$matriz["day"]. " dias";
        }

        return (object) $matriz;
    }

    /*
     * function: authenticate
     * params: $email, $password
     * result: false, true + token generated
     */

    public function authenticate($email, $password)
    {

        $sql = "  SELECT * FROM crm_usuarios "; 
        $sql .= " INNER JOIN crm_empresa ON emp_idemp = us_empresa";
        $sql .= " WHERE us_usuario = :usuario ";
        $q = $this->db->getPdo()->prepare($sql);
        $q->bindParam(':usuario', $email);
        $q->execute();
        $user = $q->fetch();


        if (is_object($user)) {


            if (md5($password) == $user->us_clave) {

                $expire = strtotime($user->us_fecha_expira);
                $expireDate = $this->CalculateDateExpired($user->us_fecha_expira);

                if ((time() >= $expire) && ($user->us_fecha_expira <> '0000-00-00 00:00:00') && ($user->us_nunca_expira)) {
                    return $this->response->SetResponse(false, $expireDate->msn_day);
                } else {

                    if ($user->us_activo == 0) {
                        return $this->response->SetResponse(false, " Usuario inactivo ! ");
                    } else {

                        $token = Auth::SignIn([
                            'id' => $user->us_idusuario,
                            'email' => $user->us_email,
                            'usuario' => $user->us_usuario,
                            'nombres' => $user->us_nombres, 
                            'us_empresa' =>  $user->us_empresa,
                            'nombre_empresa' =>  $user->emp_nombre,
                        ]);

                        $result = array(
                            "data" => $user,
                            "numberError" => 1,
                            "access_token" => $token
                        );

                        $this->response->result = $result;
                        $this->response->type = 'info';
                        return $this->response->SetResponse(true);
                    }
                }
            } else {
                return $this->response->SetResponse(false, " Contraseña invalida ! ");
            }
        } else {
            return $this->response->SetResponse(false, " Ops Credenciales invalidas  ");
        }
    }
}
