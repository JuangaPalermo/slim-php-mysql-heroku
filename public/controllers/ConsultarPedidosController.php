<?php


class ConsultarPedidosController {

    public function ConsultarPedidos($request, $response, $args)
    {
        try {
            //traigo los pedidos
            $pedidos = Pedido::obtenerTodosLosActivos();

            //seteo el payload del response
            $payload = json_encode(array("data" => $pedidos));
        } catch (Exception $e) {
            //seteo el payload del response
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }


}