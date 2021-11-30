<?php

include_once '.\models\Mesa.php';

class ConsultarMesasController {

    public function ConsultarMesas($request, $response, $args)
    {
        try {
            //traigo las mesas
            $mesas = Mesa::obtenerTodos();

            //seteo el payload del response
            $payload = json_encode(array("data" => $mesas));
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