<?php

require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './constants/estadoPedidos.php';
require_once './constants/estadoMesas.php';

class CrearPedidoController {


    public function CrearPedido ($request, $response, $args)
    {
        try {
            //obtengo el body.
            $parametros = $request->getParsedBody();
            
            //me fijo que no exista ese codigo en la base.
            Pedido::buscarCodigoExistente($parametros['pedidoCodigo'], $parametros['pedidoMesaID']);

            //creo el objeto
            $pedido = new Pedido();
            $pedido->pedidoCodigo = $parametros['pedidoCodigo'];
            $pedido->pedidoProductoID = $parametros['pedidoProductoID'];
            $pedido->pedidoMesaID = $parametros['pedidoMesaID'];
            $pedido->pedidoCliente = $parametros['pedidoCliente'];
            $pedido->pedidoEstado = PEDIDO_EN_ESPERA;
            $pedido->pedidoFechaCreacion = date("Y-m-d H:i:s");
            $pedido->guardarImagenVenta($_FILES['pedidoImagen']);
            //inserto el pedido en la base
            $pedido->pedidoID = $pedido->crearPedido();

            //creo la mesa
            $mesa = new Mesa();
            $mesa->mesaEstado = MESA_ESPERANDO;
            //updateo el state de la mesa
            $mesa->modificarMesaPorId($pedido->pedidoMesaID);

            //seteo el payload del response;
            $payload = json_encode(array("mensaje" => "Se creo el pedido", "data" => $pedido));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

}


?>