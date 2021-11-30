<?php

require_once './models/Mesa.php';
require_once './constants/estadoMesas.php';

class CerrarMesaController {

    public function CerrarMesa ($request, $response, $args)
    {
        try {

            //traigo el id de la mesa del body
            $parametros = $request->getParsedBody();
            $mesaID = $parametros['mesaID'];

            //traigo la mesa con el id
            $mesa = Mesa::obtenerMesa($mesaID);

            //updateo estado y usos
            $mesa->mesaEstado = MESA_CERRADA;
            $mesa->mesaUsos += 1;

            //lo pego contra la base
            $mesa->cerrarMesa($mesaID);

            $payload = json_encode(array("mensaje" => "Se ha cerrado la mesa correctamente."));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }


}