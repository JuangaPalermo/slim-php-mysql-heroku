<?php

require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';
require_once './constants/estadoMesas.php';

class MesaController extends Mesa implements IApiUsable {

    //declared in IApiUsable
    public function CargarUno($request, $response, $args)
    {
        try {
            //creo el objeto
            $mesa = new Mesa();
            $mesa->mesaEstado = MESA_CERRADA;
            
            //lo pego contra la base
            $mesa->mesaID = $mesa->crearMesa();

            //seteo el payload del response;
            $payload = json_encode(array("mensaje" => "Se creo la mesa", "data" => $mesa));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    //declared in IApiUsable
	public function TraerTodos($request, $response, $args)
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

    //declared in IApiUsable
    public function TraerUno($request, $response, $args)
    {
        try {
            //obtengo el ID de la mesa de los args
            $idMesa = $args['idMesa'];

            //traigo la mesa
            $mesa = Mesa::obtenerMesa($idMesa);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se obtuvo la mesa", "data" => $mesa));
        } catch (Exception $e) {
            //seteo el payload del response
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
          return $response
          ->withHeader('Content-Type', 'application/json');
    }

    //declared in IApiUsable
	public function BorrarUno($request, $response, $args)
    {
        try {
            //obtengo los parametros
            $parametros = $request->getParsedBody();

            //obtengo el ID de la mesa de los parametros
            $idMesa = $parametros['idMesa'];

            //borro la mesa a la que le corresponde ese ID
            Mesa::borrarMesa($idMesa);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se borro la mesa"));
        } catch (Exception $e) {
            //seteo el payload del response
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }
            
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    //declared in IApiUsable
	public function ModificarUno($request, $response, $args)
    {
        
        try {
            //obtengo el body
            $parametros = $request->getParsedBody();

            //creo el objeto
            $mesa = new Mesa();
            $mesa->mesaID = $parametros['idMesa']; 
            $mesa->mesaEstado = strtolower($parametros['mesaEstado']);

            //valido el estado
            $mesa->Validate();

            //modifico la mesa
            $mesa->modificarMesaPorId($mesa->mesaID);

            //seteo el payload
            $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
}


?>