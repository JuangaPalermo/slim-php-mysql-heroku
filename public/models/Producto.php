<?php

require_once './constants/perfilEmpleados.php';

class Producto {
    public $productoID;
    public $productoNombre;
    public $productoPrecio;
    public $productoPerfilEmpleado;

    //Alta producto
        public function crearProducto(){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto (productoNombre, productoPrecio, productoPerfilEmpleado) VALUES (:productoNombre, :productoPrecio, :productoPerfilEmpleado)");

            $consulta->bindValue(':productoNombre', $this->productoNombre, PDO::PARAM_STR);
            $consulta->bindValue(':productoPrecio', $this->productoPrecio, PDO::PARAM_STR);
            $consulta->bindValue(':productoPerfilEmpleado', $this->productoPerfilEmpleado, PDO::PARAM_STR);
            
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            }

            return $objAccesoDatos->obtenerUltimoId();
        }
    
    //Baja
        public static function borrarProducto($idProducto)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("DELETE FROM producto WHERE productoID = :id");

            $consulta->bindValue(':id', $idProducto, PDO::PARAM_INT);

            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay productos con ese ID para dar de baja.");
            }
        }

    //Modificacion
        public function modificarProductoPorId($idProducto)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE producto 
                                                        SET productoNombre = :productoNombre,
                                                            productoPrecio = :productoPrecio,
                                                            productoPerfilEmpleado = :productoPerfilEmpleado
                                                        WHERE productoID = :id");

            $consulta->bindValue(':productoNombre', $this->productoNombre, PDO::PARAM_STR);
            $consulta->bindValue(':productoPrecio', $this->productoPrecio, PDO::PARAM_STR);
            $consulta->bindValue(':productoPerfilEmpleado', $this->productoPerfilEmpleado, PDO::PARAM_STR);
            $consulta->bindValue(':id', $idProducto, PDO::PARAM_INT);

            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay mesas con ese ID.");
            }
        }
    //Listado
        //Uno
            public static function obtenerProducto($idProducto)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT productoID as productoID, 
                                                                      productoNombre as productoNombre,
                                                                      productoPrecio as productoPrecio,
                                                                      productoPerfilEmpleado as productoPerfilEmpleado
                                                                      FROM producto
                                                                      WHERE productoID = :idProducto");
                $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_STR);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay productos con ese ID.");
                }
                
                return $consulta->fetchObject('Producto');
            }
        //Todos
            public static function obtenerTodos()
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT productoID as productoID, 
                                                                    productoNombre as productoNombre,
                                                                    productoPrecio as productoPrecio,
                                                                    productoPerfilEmpleado as productoPerfilEmpleado
                                                                    FROM producto");
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                }

                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
            }
        //Servido por

    //Validation
        public function Validate()
        {
            switch($this->productoPerfilEmpleado)
            {
                case EMPLEADO_COCINERO:
                case EMPLEADO_BARTENDER:
                case EMPLEADO_CERVECERO:
                    break;
                default:
                    throw new Exception ("El perfil del producto no es valido.");
            }
        }
}


?>