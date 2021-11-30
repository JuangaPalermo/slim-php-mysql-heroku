<?php

require_once './models/Pedido.php';

class TomarPendienteController {

    public function TomarPendiente($request, $response, $args)
    {
        
        try {
            
            //obtengo del jwt el id del empleado
            $empleadoID = Logger::ObtenerID($request);
            $idPedido = $args['idPedido'];
            
            //obtengo el body
            $parametros = $request->getParsedBody();
            $tiempoEstimado = $parametros['tiempoEstimado'];            

            //creo las fechas
            $fechaObj = new DateTime();
            $fechaActual = $fechaObj->format("Y-m-d H:i:s");
            $fechaEstimada = $fechaObj->modify('+' . $tiempoEstimado . ' minute')->format("Y-m-d H:i:s");
            
            //creo el objeto
            $pedido = new Pedido();
            $pedido->pedidoID = $idPedido;
            $pedido->pedidoEmpleadoID = $empleadoID;
            $pedido->pedidoFechaTomado = $fechaActual;
            $pedido->pedidoFechaEstimadaEntrega = $fechaEstimada;
            $pedido->pedidoEstado = PEDIDO_EN_PREPARACION;

            //modifico el pedido
            $pedido->tomarPedidoPorId($pedido->pedidoID);

            //seteo el payload
            $payload = json_encode(array("mensaje" => "Ha tomado el pedido."));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

}


?>