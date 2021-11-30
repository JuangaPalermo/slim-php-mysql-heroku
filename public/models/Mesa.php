<?php

class Mesa {

    public $mesaID;
    public $mesaEstado;
    public $mesaUsos;

    //Alta
        public function crearMesa()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (mesaEstado) VALUES (:mesaEstado)");

            $consulta->bindValue(':mesaEstado', $this->mesaEstado, PDO::PARAM_STR);
            
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            }

            return $objAccesoDatos->obtenerUltimoId();
        }

    //Baja
        public static function borrarMesa($idMesa)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("DELETE FROM mesa WHERE mesaID = :id");

            $consulta->bindValue(':id', $idMesa, PDO::PARAM_INT);

            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay mesas con ese ID para dar de baja.");
            }
        }
    //Modificacion
        public function modificarMesaPorId($idMesa)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa 
                                                          SET mesaEstado = :mesaEstado
                                                          WHERE mesaID = :id");

            $consulta->bindValue(':mesaEstado', $this->mesaEstado, PDO::PARAM_STR);
            $consulta->bindValue(':id', $idMesa, PDO::PARAM_INT);

            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            }
        }

        public function mesaComiendo($idMesa)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa 
                                                          SET mesaEstado = :mesaEstado
                                                          WHERE mesaID = :id");

            $consulta->bindValue(':mesaEstado', $this->mesaEstado, PDO::PARAM_STR);
            $consulta->bindValue(':id', $idMesa, PDO::PARAM_INT);

            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay mesas con ese ID.");
            }
        }

        public function cerrarMesa($idMesa)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa 
                                                          SET mesaEstado = :mesaEstado,
                                                          mesaUsos = :mesaUsos
                                                          WHERE mesaID = :id");

            $consulta->bindValue(':mesaEstado', $this->mesaEstado, PDO::PARAM_STR);
            $consulta->bindValue(':mesaUsos', $this->mesaUsos, PDO::PARAM_INT);
            $consulta->bindValue(':id', $idMesa, PDO::PARAM_INT);

            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay mesas con ese ID.");
            }
        }
        //Listado
        //uno
            public static function obtenerMesa($idMesa)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                               FROM mesa 
                                                               WHERE mesaID = :idMesa");
                $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay mesas con ese ID.");
                }
                
                return $consulta->fetchObject('Mesa');
            }
        //mas usada
            public static function obtenerMesaMasUsada()
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa
                                                               ORDER BY mesaUsos DESC
                                                               LIMIT 1");
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay mesas para esta consulta");
                }
                
                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
            }
        //todos
            public static function obtenerTodos()
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                               FROM mesa");
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                }

                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
            }
    
    //validations
            public function Validate()
            {
                switch($this->mesaEstado)
                {
                    case MESA_CERRADA:
                    case MESA_ESPERANDO:
                    case MESA_COMIENDO:
                    case MESA_PAGANDO:
                        break;
                    default:
                        throw new Exception("El estado ingresado para la mesa no es valido.");
                }
            }
}


?>