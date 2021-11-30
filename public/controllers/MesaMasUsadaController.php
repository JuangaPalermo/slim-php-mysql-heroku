<?php

require_once './models/Mesa.php';

class MesaMasUsadaController {

    public function MesaMasUsada ($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody();
    
            $mesaMasUsada = Mesa::obtenerMesaMasUsada();

            $payload = json_encode(array("mensaje" => "Se obtuvo la mesa mas usada", "data" => $mesaMasUsada));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}


?>