<?php

require_once './models/Pedido.php';

class ListarEnPreparacionController {

    public function ListarEnPreparacion($request, $response, $args)
    {
        try {
            //Traigo el perfil desde el JWT
            $idEmpleado = Logger::ObtenerID($request);
            
            //traigo los pedidos en preparacion del empleado
            $pedidos = Pedido::obtenerEnPreparacion($idEmpleado);

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


?>