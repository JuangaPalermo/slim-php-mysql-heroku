<?php

require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './constants/estadoPedidos.php';
require_once './constants/estadoMesas.php';

class CobrarPedidoController {

    public function CobrarPedido($request, $response, $args)
    {
        try {
            //traigo el codigo del pedido del body
            $parametros = $request->getParsedBody();
            $pedidoCodigo = $parametros['pedidoCodigo'];
            $mesaID = $parametros['mesaID'];


            //updateo el estado del pedido a concluido
            $pedido = new Pedido();
            $pedido->pedidoCodigo = $pedidoCodigo;
            $pedido->pedidoFechaFinalizacion = date("Y-m-d H:i:s");
            $pedido->updateFinalizacion();

            //updateo el estado de la mesa a con cliente comiendo
            //creo la mesa
            $mesa = new Mesa();
            $mesa->mesaEstado = MESA_PAGANDO;
            $mesa->modificarMesaPorId($mesaID);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "se ha cobrado la mesa."));
        } catch (Exception $e) {
            //seteo el payload del response
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

}



?>