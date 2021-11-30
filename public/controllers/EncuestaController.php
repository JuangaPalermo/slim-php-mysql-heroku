<?php

require_once './models/Pedido.php';

class EncuestaController {

    public function CargarEncuesta ($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody();

            $pedido = new Pedido();
            $pedido->pedidoMesaID = $parametros['mesaID'];
            $pedido->pedidoCodigo = $parametros['pedidoCodigo'];
            $pedido->pedidoCalificacion = $parametros['pedidoCalificacion'];
            $pedido->pedidoComentario = $parametros['pedidoComentario'];
    
            $pedido->dejarEncuesta($pedido->pedidoCodigo, $pedido->pedidoMesaID);

            $payload = json_encode(array("mensaje" => "Se ha cargado la encuesta correctamente."));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}


?>