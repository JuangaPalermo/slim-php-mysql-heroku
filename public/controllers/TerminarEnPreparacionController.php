<?php

require_once './models/Pedido.php';

class TerminarEnPreparacionController {

    public function TerminarEnPreparacion($request, $response, $args)
    {
        try {
            
            //obtengo de los args el perfil y el id
            $idPedido = $args['idPedido'];

            //obtengo el body
            $parametros = $request->getParsedBody();
            $tiempoEstimado = $parametros['tiempoEstimado'];            

            //creo la fechas
            $fechaObj = new DateTime();
 
            //creo el objeto
            $pedido = new Pedido();

            $pedido->pedidoID = $idPedido;
            $pedido->pedidoFechaEntregado = $fechaObj->format("Y-m-d H:i:s");
            $pedido->pedidoEstado = PEDIDO_LISTO_PARA_SERVIR;

            //modifico el pedido
            $pedido->servirPedidoPorId($pedido->pedidoID);

            //seteo el payload
            $payload = json_encode(array("mensaje" => "Ha puesto el pedido como listo para servir."));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }


}


?>