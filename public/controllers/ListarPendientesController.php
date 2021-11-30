<?php

require_once './models/Pedido.php';

class ListarPendientesController {

    public function ListarPendientes($request, $response, $args)
    {
        try {
            //Traigo el perfil desde el JWT
            $perfilEmpleado = Logger::ObtenerPerfil($request);
            
            //traigo los pedidos pendientes
            $pedidos = Pedido::obtenerPendientesPorPerfil($perfilEmpleado);

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