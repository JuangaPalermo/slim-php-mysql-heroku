<?php

require_once './models/Pedido.php';

class ConsultarDemoraController {

    public function ConsultarDemora($request, $response, $args)
    {
        try {
            //obtengo el ID del pedido y de la mesa
            $pedidoMesaID = $args['mesaID'];
            $pedidoCodigo = $args['pedidoCodigo'];

            //traigo la ultima fecha de entrega del pedido
            $entrega = Pedido::consultarDemoraCliente($pedidoMesaID, $pedidoCodigo);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se obtuvo la hora de entrega del pedido.", "data" => $entrega));
        } catch (Exception $e) {
            //seteo el payload del response
            $payload = json_encode(array("ERROR" => $e->getMessage()));   
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }


}