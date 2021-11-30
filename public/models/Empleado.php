<?php

require_once './constants/estadoEmpleados.php';
require_once './constants/perfilEmpleados.php';

class Empleado {

    public $empleadoID;
    public $empleadoPerfil;
    public $empleadoNombre;
    public $empleadoApellido;
    public $empleadoCorreo;
    public $empleadoClave;
    public $empleadoEstado;
    public $empleadoDisponible;
    public $empleadoFechaAlta;
    public $empleadoFechaBaja;

    //Alta
        public function crearempleado()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia(); 
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO empleado (empleadoPerfil, empleadoNombre, empleadoApellido, empleadoCorreo, empleadoClave, empleadoEstado, empleadoDisponible, empleadoFechaAlta, empleadoFechaBaja) VALUES (:empleadoPerfil, :empleadoNombre, :empleadoApellido, :empleadoCorreo, :empleadoClave, :empleadoEstado, :empleadoDisponible, :empleadoFechaAlta, :empleadoFechaBaja)");

            $consulta->bindValue(':empleadoPerfil', $this->empleadoPerfil, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoNombre', $this->empleadoNombre, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoApellido', $this->empleadoApellido, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoCorreo', $this->empleadoCorreo, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoClave', password_hash($this->empleadoClave, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $consulta->bindValue(':empleadoEstado', $this->empleadoEstado, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoDisponible', $this->empleadoDisponible, PDO::PARAM_BOOL);
            $consulta->bindValue(':empleadoFechaAlta', $this->empleadoFechaAlta, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoFechaBaja', $this->empleadoFechaBaja, PDO::PARAM_NULL);
            
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            }

            return $objAccesoDatos->obtenerUltimoId();
        }

    //Baja
        public static function borrarEmpleado($idEmpleado)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE empleado SET empleadoFechaBaja = :fechaBaja,
                                                                              empleadoEstado = :empleadoEstado,
                                                                              empleadoDisponible = :empleadoDisponible
                                                                              WHERE empleadoID = :id 
                                                                              AND empleadoFechaBaja IS NULL");
                                                                             
            $consulta->bindValue(':id', $idEmpleado, PDO::PARAM_INT);
            $consulta->bindValue(':fechaBaja', date("Y-m-d"));
            $consulta->bindValue(':empleadoEstado', EMPLEADO_BAJA, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoDisponible', FALSE, PDO::PARAM_BOOL);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay empleados con ese ID para dar de baja.");
            }
        }

    //Modificacion
        public function modificarEmpleadoPorId($idEmpleado)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE empleado 
                                                          SET empleadoPerfil = :empleadoPerfil, 
                                                              empleadoNombre = :empleadoNombre,
                                                              empleadoApellido = :empleadoApellido,
                                                              empleadoEstado = :empleadoEstado,
                                                              empleadoDisponible = :empleadoDisponible
                                                              WHERE empleadoID = :id");
            $consulta->bindValue(':empleadoPerfil', $this->empleadoPerfil, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoNombre', $this->empleadoNombre, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoApellido', $this->empleadoApellido, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoEstado', $this->empleadoEstado, PDO::PARAM_STR);
            $consulta->bindValue(':empleadoDisponible', $this->empleadoDisponible, PDO::PARAM_BOOL);
            $consulta->bindValue(':id', $idEmpleado, PDO::PARAM_INT);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay empleados con ese ID.");
            }
        }

    //Listado
        //Uno
            public static function obtenerEmpleado($idEmpleado)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                               FROM empleado 
                                                               WHERE empleadoID = :idEmpleado");
                $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_STR);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay empleados con ese ID.");
                }
                
                return $consulta->fetchObject('Empleado');
            }

            public static function obtenerEmpleadoPorCorreo($empleadoCorreo)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                               FROM empleado 
                                                               WHERE empleadoCorreo = :empleadoCorreo");
                $consulta->bindValue(':empleadoCorreo', $empleadoCorreo, PDO::PARAM_STR);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay empleados con ese ID.");
                }
                
                return $consulta->fetchObject('Empleado');
            }

        //Todos
            public function obtenerTodos()
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                               FROM empleado");
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                }

                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
            }
        //PorPerfil
        //PorEstado
        //PorDisponibilidad
    
    //utils
        public function logearUsuario()
        {
            //traigo el usuario
            $empleadoBBDD = self::obtenerEmpleadoPorCorreo($this->empleadoCorreo);

            //valido los datos con lo que traje.
            if(!$empleadoBBDD)
            {
                throw new Exception('No existe ningun empleado con ese correo');
            } else if (!password_verify($this->empleadoClave, $empleadoBBDD->empleadoClave)) {
                throw new Exception('No coincide la clave');
            } else {
                return $empleadoBBDD;
            }
        }

    //validations

        public function Validate()
        {
            switch($this->empleadoEstado)
            {
                case EMPLEADO_DISPONIBLE:
                case EMPLEADO_SUSPENDIDO:
                case EMPLEADO_BAJA:
                    break;
                default:
                    throw new Exception ("El estado para el empleado no es valido.");
            }

            switch($this->empleadoPerfil)
            {
                case EMPLEADO_COCINERO:
                case EMPLEADO_BARTENDER:
                case EMPLEADO_CERVECERO:
                case EMPLEADO_MOZO:
                case EMPLEADO_SOCIO:
                    break;
                default:
                    throw new Exception ("El perfil para el empleado no es valido.");
            }
        }
}


?>