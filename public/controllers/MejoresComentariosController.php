<?php

require_once './models/Pedido.php';

class MejoresComentariosController {

    public function TraerMejoresComentarios ($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody();
    
            $mejoresPedidos = Pedido::mejoresComentarios();

            $payload = json_encode(array("mensaje" => "Se han traido los mejores comentarios.", "data" => $mejoresPedidos));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}


?>