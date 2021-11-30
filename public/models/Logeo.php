<?php

class Logeo {
    public $logeoId;
    public $perfilEmpleado;
    public $idEmpleado;
    public $correoEmpleado;
    public $fechaLogeo;

    public function registrarLogeo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logeo (perfilEmpleado, idEmpleado, correoEmpleado, fechaLogeo) VALUES (:perfilEmpleado, :idEmpleado, :correoEmpleado, :fechaLogeo)");

        $consulta->bindValue(':perfilEmpleado', $this->perfilEmpleado, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':correoEmpleado', $this->correoEmpleado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaLogeo', $this->fechaLogeo, PDO::PARAM_STR);
        
        if(!$consulta->execute()){
            throw new Exception("Error al realizar la consulta.");
        }

        return $objAccesoDatos->obtenerUltimoId();
    }

}


?>