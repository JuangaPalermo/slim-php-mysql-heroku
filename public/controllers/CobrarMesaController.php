<?php

require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './constants/estadoPedidos.php';
require_once './constants/estadoMesas.php';

class CobrarMesaController {


    public function CobrarMesa ($request, $response, $args)
    {
        try {
            //obtengo mesaid y codigopedido
            $idMesa = $args['idMesa'];
            $pedidoCodigo = $args['pedidoCodigo'];

            $fecha = new DateTime();

            //updateo el pedido concluido con fecha de fin.
            $pedido = new Pedido();
            $pedido->pedidoFechaFinalizacion = $fecha->format("Y-m-d H:i:s");
            $pedido->pedidoEstado = PEDIDO_CONCLUIDO;

            $pedido->concluirPedido($pedidoCodigo, $idMesa);

            //updateo la mesa pagando
            $mesa = new Mesa();
            $mesa->mesaEstado = MESA_PAGANDO;

            $mesa->modificarMesaPorId($idMesa);

            //seteo el payload
            $payload = json_encode(array("mensaje" => "Se ha cobrado la mesa correctamente."));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

}


?>