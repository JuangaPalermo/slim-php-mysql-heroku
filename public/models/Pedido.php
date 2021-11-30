<?php


require_once './constants/estadoPedidos.php';

class Pedido{
    public $pedidoID;
    public $pedidoCodigo;
    public $pedidoEmpleadoID; //este se le asigna cuando lo toma un empleado.
    public $pedidoProductoID;
    public $pedidoMesaID;
    public $pedidoCliente;
    public $pedidoEstado; //esto lo puedo poner desde el codigo, no hace falta que lo mande por PostMan
    public $pedidoFechaCreacion; //lo obtengo desde el codigo
    public $pedidoFechaTomado; //lo seteo cuando lo toma el empleado que lo cocina/sirve
    public $pedidoFechaEstimadaEntrega; //lo setea el empleado cuando lo toma tambien
    public $pedidoFechaEntregado; //se setea cuando se cambia de estado
    public $pedidoFechaFinalizacion; //se setea cuando se concluye el pedido.
    public $pedidoImagen; // se setea desde el codigo, se envia desde postman.
    public $pedidoCalificacion; //lo pone el cliente al terminar el pedido
    public $pedidoComentario; //lo pone el cliente al terminar el pedido

    //Alta
    public function crearPedido(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (pedidoCodigo, pedidoProductoID, pedidoMesaID, pedidoCliente, pedidoEstado, pedidoFechaCreacion, pedidoImagen) VALUES (:pedidoCodigo, :pedidoProductoID, :pedidoMesaID, :pedidoCliente, :pedidoEstado, :pedidoFechaCreacion, :pedidoImagen)");

        $consulta->bindValue(':pedidoCodigo', $this->pedidoCodigo, PDO::PARAM_INT);
        $consulta->bindValue(':pedidoProductoID', $this->pedidoProductoID, PDO::PARAM_INT);
        $consulta->bindValue(':pedidoMesaID', $this->pedidoMesaID, PDO::PARAM_INT);
        $consulta->bindValue(':pedidoCliente', $this->pedidoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':pedidoEstado', $this->pedidoEstado, PDO::PARAM_STR);
        $consulta->bindValue(':pedidoFechaCreacion', $this->pedidoFechaCreacion, PDO::PARAM_STR);
        $consulta->bindValue(':pedidoImagen', $this->pedidoImagen, PDO::PARAM_STR);
        
        if(!$consulta->execute()){
            throw new Exception("Error al realizar la consulta.");
        }

        return $objAccesoDatos->obtenerUltimoId();
    }

    //Baja (no tengo que eliminarlos, tengo que cambiarles el estado).
        public static function borrarPedido($pedidoCodigo, $pedidoMesaID)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET 
                                                          pedidoFechaFinalizacion = :pedidoFechaFinalizacion,
                                                          pedidoEstado = :pedidoEstado
                                                          WHERE pedidoCodigo = :pedidoCodigo 
                                                          AND pedidoMesaID = :pedidoMesaID
                                                          AND pedidoEstado NOT LIKE '%concluido%'");
            $consulta->bindValue(':pedidoCodigo', $pedidoCodigo, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoMesaID', $pedidoMesaID, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoFechaFinalizacion', date("Y-m-d H:i:s"));
            $consulta->bindValue(':pedidoEstado', PEDIDO_CONCLUIDO, PDO::PARAM_STR);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay pedidos con ese ID para dar de baja.");
            }
        }

    //Modificacion
        public function modificarPedidoPorId($idPedido)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido 
                                                        SET pedidoCodigo = :pedidoCodigo, 
                                                        pedidoProductoID = :pedidoProductoID,
                                                        pedidoMesaID = :pedidoMesaID,
                                                        pedidoCliente = :pedidoCliente,
                                                        pedidoEstado = :pedidoEstado,
                                                        pedidoCalificacion = :pedidoCalificacion,
                                                        pedidoComentario = :pedidoComentario
                                                        WHERE pedidoID = :idPedido");
            $consulta->bindValue(':pedidoCodigo', $this->pedidoCodigo, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoProductoID', $this->pedidoProductoID, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoMesaID', $this->pedidoMesaID, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoCliente', $this->pedidoCliente, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoEstado', $this->pedidoEstado, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoCalificacion', $this->pedidoCalificacion, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoComentario', $this->pedidoComentario, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay pedidos con ese ID.");
            }
        }

        public function updateEstado(){
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido 
                                                        SET pedidoEstado = :pedidoEstado
                                                        WHERE pedidoCodigo = :pedidoCodigo");
            $consulta->bindValue(':pedidoCodigo', $this->pedidoCodigo, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoEstado', $this->pedidoEstado, PDO::PARAM_STR);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay pedidos con ese codigo.");
            }
        }

        public function updateFinalizacion(){
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido 
                                                        SET pedidoFechaFinalizacion = :pedidoFechaFinalizacion
                                                        WHERE pedidoCodigo = :pedidoCodigo");
            $consulta->bindValue(':pedidoCodigo', $this->pedidoCodigo, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoFechaFinalizacion', $this->pedidoFechaFinalizacion, PDO::PARAM_STR);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay pedidos con ese codigo.");
            }
        }
    
    //Tomar por ID
        public function tomarPedidoPorId($idPedido)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido 
                                                        SET pedidoEmpleadoID = :pedidoEmpleadoID, 
                                                        pedidoFechaTomado = :pedidoFechaTomado,
                                                        pedidoFechaEstimadaEntrega = :pedidoFechaEstimadaEntrega,
                                                        pedidoEstado = :pedidoEstado
                                                        WHERE pedidoID = :idPedido");
            $consulta->bindValue(':pedidoEmpleadoID', $this->pedidoEmpleadoID, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoFechaTomado', $this->pedidoFechaTomado, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoFechaEstimadaEntrega', $this->pedidoFechaEstimadaEntrega, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoEstado', $this->pedidoEstado, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay pedidos con ese ID.");
            }
        }
    
    //Servir por ID
        public function servirPedidoPorId($idPedido)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido 
                                                        SET pedidoFechaEntregado = :pedidoFechaEntregado,
                                                        pedidoEstado = :pedidoEstado
                                                        WHERE pedidoID = :idPedido");
            $consulta->bindValue(':pedidoFechaEntregado', $this->pedidoFechaEntregado, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoEstado', $this->pedidoEstado, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay pedidos con ese ID.");
            }
        }

    //Concluir pedido
        public function concluirPedido($pedidoCodigo, $idMesa)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido 
                                                          SET pedidoFechaFinalizacion = :pedidoFechaFinalizacion,
                                                          pedidoEstado = :pedidoEstado
                                                          WHERE pedidoCodigo = :pedidoCodigo
                                                          AND pedidoMesaID = :idMesa
                                                          AND pedidoEstado NOT LIKE '%concluido%'");
            $consulta->bindValue(':pedidoFechaFinalizacion', $this->pedidoFechaFinalizacion, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoEstado', $this->pedidoEstado, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoCodigo', $pedidoCodigo, PDO::PARAM_INT);
            $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay pedidos con ese ID.");
            }
        }

    //Cargar encuesta
        public function dejarEncuesta($pedidoCodigo, $idMesa)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido 
                                                          SET pedidoCalificacion = :pedidoCalificacion,
                                                          pedidoComentario = :pedidoComentario
                                                          WHERE pedidoCodigo = :pedidoCodigo
                                                          AND pedidoMesaID = :idMesa");
            $consulta->bindValue(':pedidoCalificacion', $this->pedidoCalificacion, PDO::PARAM_INT);
            $consulta->bindValue(':pedidoComentario', $this->pedidoComentario, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoCodigo', $pedidoCodigo, PDO::PARAM_INT);
            $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            if(!$consulta->execute()){
                throw new Exception("Error al realizar la consulta.");
            } else if ($consulta->rowCount() == 0){
                throw new Exception("No hay pedidos con ese ID.");
            }
        }

    //Listar
        //Uno
            public static function obtenerPedido($pedidoCodigo)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido 
                                                               WHERE pedidoCodigo = :pedidoCodigo
                                                               AND pedidoEstado NOT LIKE '%concluido%'");
                $consulta->bindValue(':pedidoCodigo', $pedidoCodigo, PDO::PARAM_INT);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay pedidos con ese ID.");
                }
                
                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
            }

            public static function buscarCodigoExistente($pedidoCodigo, $mesaID)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido 
                                                               WHERE pedidoCodigo = :pedidoCodigo
                                                               AND pedidoMesaID = :mesaID
                                                               AND pedidoEstado NOT LIKE '%pendiente%'");
                $consulta->bindValue(':pedidoCodigo', $pedidoCodigo, PDO::PARAM_INT);
                $consulta->bindValue(':mesaID', $mesaID, PDO::PARAM_INT);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() != 0){
                    throw new Exception("Ya hay un pedido con ese codigo.");
                }
                
                return true;
            }

            public static function obtenerPedidoPorID($pedidoID)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido 
                                                               WHERE pedidoID = :pedidoID");
                $consulta->bindValue(':pedidoID', $pedidoID, PDO::PARAM_INT);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay pedidos con ese ID.");
                }
                
                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
            }

        //Todos
            public function obtenerTodos()
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido");
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                }

                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
            }
        
        //Todos los no concluidos
            public static function obtenerTodosLosActivos()
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido
                                                               WHERE pedidoEstado NOT LIKE '%concluido%'");
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if($consulta->rowCount() == 0){
                    throw new Exception("No hay pedidos activos en este momento.");
                }

                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
            }

        //Todos los listos para servir
            public function obtenerPedidosListos()
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido
                                                               WHERE pedidoEstado LIKE '%listo para servir%'");
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                }

                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
            }

            public static function verificarPedidoListo($pedidoCodigo)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido
                                                               WHERE pedidoCodigo = :pedidoCodigo
                                                               GROUP BY pedidoCodigo, pedidoEstado, pedidoMesaID");
                $consulta->bindValue(':pedidoCodigo', $pedidoCodigo, PDO::PARAM_INT);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay ningun pedido con ese numero.");
                } else if ($consulta->rowCount() > 1){
                    throw new Exception("No todos los productos del pedido estan listos para servir.");
                }
                
                return true;
            }

    

        //Pendientes por perfil
            public static function obtenerPendientesPorPerfil($perfilEmpleado)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM `pedido`
                                                               LEFT JOIN producto ON productoID = pedidoProductoID
                                                               WHERE producto.productoPerfilEmpleado = :perfilEmpleado
                                                               AND pedidoEstado = :pedidoEstado");
                $consulta->bindValue(':perfilEmpleado', $perfilEmpleado, PDO::PARAM_STR);
                $consulta->bindValue(':pedidoEstado', PEDIDO_EN_ESPERA, PDO::PARAM_STR);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                }

                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
            }

        //En preparacion
            public static function obtenerEnPreparacion($empleadoID)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM `pedido`
                                                               WHERE pedidoEmpleadoID = :empleadoID
                                                               AND pedidoEstado = :pedidoEstado");
                $consulta->bindValue(':empleadoID', $empleadoID, PDO::PARAM_INT);
                $consulta->bindValue(':pedidoEstado', PEDIDO_EN_PREPARACION, PDO::PARAM_STR);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                }

                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
            }
        //Consultar demora
            public static function consultarDemoraCliente($pedidoMesaID, $pedidoCodigo)
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidoFechaEstimadaEntrega AS demora
                                                               FROM pedido
                                                               WHERE pedidoMesaID = :pedidoMesaID
                                                               AND pedidoCodigo = :pedidoCodigo
                                                               AND pedidoEstado NOT LIKE '%concluido%'
                                                               ORDER BY pedidoFechaEstimadaEntrega DESC
                                                               LIMIT 1");
                $consulta->bindValue(':pedidoCodigo', $pedidoCodigo, PDO::PARAM_INT);
                $consulta->bindValue(':pedidoMesaID', $pedidoMesaID, PDO::PARAM_INT);
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay pedidos pendientes con ese ID de mesa y codigo.");
                }
                
                return $consulta->fetch(PDO::FETCH_ASSOC);
            }
        
        //Mejores comentarios
            public static function mejoresComentarios()
            {
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido
                                                               WHERE pedidoCalificacion IS NOT NULL
                                                               GROUP BY pedidoComentario
                                                               ORDER BY pedidoCalificacion DESC
                                                               LIMIT 3");
                if(!$consulta->execute()){
                    throw new Exception("Error al realizar la consulta.");
                } else if ($consulta->rowCount() == 0){
                    throw new Exception("No hay pedidos para esta consulta");
                }
                
                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
            }

    //Utils
        public function guardarImagenVenta($files)
        {
            //obtengo el cuerpo del mail
            $cuerpoMail = explode("@",$this->pedidoCliente);
            //obtengo la extension del archivo
            $path = $files['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            //seteo el nombre de la imagen
            $nombreImagen = $this->pedidoCodigo . "-" . $this->pedidoMesaID . "-" . $cuerpoMail[0] . "." . $ext;
            //dictamino el destino
            $destino = "./ImagenesDeLosPedidos/".$nombreImagen;
            //la envio
            move_uploaded_file($files["tmp_name"], $destino);
            $this->pedidoImagen = $destino;
        }

    //Validations
        public function Validate()
        {
            switch($this->pedidoEstado)
            {
                case PEDIDO_EN_ESPERA:
                case PEDIDO_EN_PREPARACION:
                case PEDIDO_LISTO_PARA_SERVIR:
                case PEDIDO_CONCLUIDO:
                    break;
                default:
                    throw new Exception ("El estado para el pedido no es valido.");
            }

            switch($this->pedidoCalificacion)
            {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    break;
                default:
                    throw new Exception ("La calificacion no es valida, debe ser entre 1 y 5.");
            }
        }
}


?>