<?php

require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './constants/estadoPedidos.php';
require_once './constants/estadoMesas.php';

class ServirPedidoController {

    public function ServirPedido($request, $response, $args)
    {
        try {
            //traigo el codigo del pedido del body
            $parametros = $request->getParsedBody();
            $pedidoCodigo = $parametros['pedidoCodigo'];
            $mesaID = $parametros['mesaID'];

            //hago un group by de pedidocodigo y pedidoestado. (throwea exception si trae mas de uno).
            Pedido::verificarPedidoListo($pedidoCodigo);

            //updateo el estado del pedido a concluido
            $pedido = new Pedido();
            $pedido->pedidoCodigo = $pedidoCodigo;
            $pedido->pedidoEstado = PEDIDO_CONCLUIDO;
            $pedido->updateEstado();

            //updateo el estado de la mesa a con cliente comiendo
            $mesa = new Mesa();
            $mesa->mesaEstado = MESA_COMIENDO;
            $mesa->modificarMesaPorId($mesaID);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "se ha servido la mesa."));
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