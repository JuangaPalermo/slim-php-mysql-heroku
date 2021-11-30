<?php

require_once './models/Empleado.php';
require_once './interfaces/IApiUsable.php';
require_once './constants/estadoEmpleados.php';

class EmpleadoController extends Empleado implements IApiUsable {

    //declared in IApiUsable
    public function CargarUno($request, $response, $args)
    {
        try {
            //obtengo el body
            $parametros = $request->getParsedBody();
            
            //creo el objeto
            $empleado = new Empleado();
            $empleado->empleadoPerfil = strtolower($parametros['empleadoPerfil']);
            $empleado->empleadoNombre = $parametros['empleadoNombre'];
            $empleado->empleadoApellido = $parametros['empleadoApellido'];
            $empleado->empleadoCorreo = $parametros['empleadoCorreo'];
            $empleado->empleadoClave = $parametros['empleadoClave'];
            $empleado->empleadoEstado = EMPLEADO_DISPONIBLE;
            $empleado->empleadoDisponible = TRUE;
            $empleado->empleadoFechaAlta = date('Y-m-d');
            $empleado->empleadoFechaBaja = NULL;

            //valido los datos
            $empleado->Validate();
            
            //lo pego contra la base
            $empleado->empleadoID = $empleado->crearEmpleado();

            //seteo el payload del response;
            $payload = json_encode(array("mensaje" => "Se creo el empleado", "data" => $empleado));
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
            //traigo los empleados
            $empleados = Empleado::obtenerTodos();

            //seteo el payload del response
            $payload = json_encode(array("data" => $empleados));
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
            //obtengo el ID del empleado de los args
            $idEmpleado = $args['idEmpleado'];

            //traigo el empleado
            $empleado = Empleado::obtenerEmpleado($idEmpleado);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se obtuvo el empleado", "data" => $empleado));
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

            //obtengo el ID del empleado de los parametros
            $idEmpleado = $parametros['idEmpleado'];

            //borro al empleado al que le corresponde ese ID
            Empleado::borrarEmpleado($idEmpleado);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se borro al empleado"));
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
            $empleado = new Empleado();
            $empleado->empleadoID = $parametros['idEmpleado']; 
            $empleado->empleadoPerfil = strtolower($parametros['empleadoPerfil']);
            $empleado->empleadoNombre = $parametros['empleadoNombre'];
            $empleado->empleadoApellido = $parametros['empleadoApellido'];
            $empleado->empleadoEstado = $parametros['empleadoEstado'];
            $empleado->empleadoDisponible = $parametros['empleadoDisponible'];

            //valido datos
            $empleado->Validate();

            //modifico el empleado
            $empleado->modificarEmpleadoPorId($empleado->empleadoID);

            //seteo el payload
            $payload = json_encode(array("mensaje" => "Empleado modificado con exito"));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}


?>