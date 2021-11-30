<?php

require_once './models/Empleado.php';

class RegistroController {

    public function RegistrarUno ($request, $response, $args)
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
}


?>