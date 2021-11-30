<?php

use Slim\Psr7\Response as Response;

class Logger
{

    public static function VerificadorSocio($request, $handler){

        try {
            
            $header = $request->getHeaderLine('Authorization');
            $token = trim(explode("Bearer", $header)[1]);
        
            if($token)
            {
                try {
                    $payload = AutentificadorJWT::ObtenerData($token);
                } catch (Exception $e) {
                    $payload = json_encode(array('error' => $e->getMessage()));
                }

                if($payload->perfil == 'socio'){
                    $response = $handler->handle($request);
                } 
                else
                {
                    $response = new Response();
                    $response->getBody()->write(json_encode(array('error' => 'Error de Autenticacion')));
                }
            } 
            else 
            {
                $response = new Response();
                $response->getBody()->write(json_encode(array('error' => 'Token vacio')));
            }
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
        }
      
        return $response->withHeader('Content-Type', 'application/json'); 
    }

    public static function VerificadorMozo($request, $handler){

        try {
            
            $header = $request->getHeaderLine('Authorization');
            $token = trim(explode("Bearer", $header)[1]);
        
            if($token)
            {

                try {
                    $payload = AutentificadorJWT::ObtenerData($token);
                } catch (Exception $e) {
                    $payload = json_encode(array('error' => $e->getMessage()));
                }
                
                if($payload->perfil == 'mozo'){
                    $response = $handler->handle($request);
                } 
                else
                {
                    $response = new Response();
                    $response->getBody()->write(json_encode(array('error' => 'Error de Autenticacion')));
                }
            } 
            else 
            {
                $response = new Response();
                $response->getBody()->write(json_encode(array('error' => 'Token vacio')));
            }
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
        }
      
        return $response->withHeader('Content-Type', 'application/json'); 
    }

    public static function VerificadorEmpleado($request, $handler){

        try {
            
            $header = $request->getHeaderLine('Authorization');
            $token = trim(explode("Bearer", $header)[1]);
        
            if($token)
            {
                try {
                    $payload = AutentificadorJWT::ObtenerData($token);
                } catch (Exception $e) {
                    $payload = json_encode(array('error' => $e->getMessage()));
                }

                if($payload->perfil == 'bartender' || $payload->perfil == 'cervecero' || $payload->perfil == 'cocinero'){

                    $response = $handler->handle($request);
                } 
                else
                {
                    $response = new Response();
                    $response->getBody()->write(json_encode(array('error' => 'Error de Autenticacion')));
                }
            } 
            else 
            {
                $response = new Response();
                $response->getBody()->write(json_encode(array('error' => 'Token vacio')));
            }
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
        }
      
        return $response->withHeader('Content-Type', 'application/json'); 
    }

    public static function ObtenerPerfil($request){
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        return $data->perfil;
    }

    public static function ObtenerID($request){
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        return $data->id;
    }


}