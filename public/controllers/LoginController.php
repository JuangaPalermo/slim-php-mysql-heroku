<?php

require_once './models/Empleado.php';
require_once './models/Logeo.php';

class LoginController {

    public function LogearUno ($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody();

            //creo el usuario con los datos
            $empleado = new Empleado();
            $empleado->empleadoCorreo = $parametros['empleadoCorreo'];
            $empleado->empleadoClave = $parametros['empleadoClave'];

            //obtengo el usuario, de lo contrario lanza error
            $empleadoObtenido = $empleado->logearUsuario();

            //gestiono el payload
            $datos = array('empleado' => $empleado->empleadoCorreo, 
                            'clave' => $empleadoObtenido->empleadoClave, 
                            'perfil' => $empleadoObtenido->empleadoPerfil,
                            'id' => $empleadoObtenido->empleadoID);
            $token = AutentificadorJWT::CrearToken($datos);

            $logeo = new Logeo();
            $logeo->perfilEmpleado = $empleadoObtenido->empleadoPerfil;
            $logeo->idEmpleado = $empleadoObtenido->empleadoID;
            $logeo->correoEmpleado = $empleadoObtenido->empleadoCorreo;
            $logeo->fechaLogeo = date("Y-m-d H:i:s");

            $logeo->registrarLogeo();

            $payload = json_encode(array("mensaje" => "Se ha logeado el usuario", "jwt" => $token, "perfil" => $empleadoObtenido->empleadoPerfil, "id" => $empleadoObtenido->empleadoID));

        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}


?>