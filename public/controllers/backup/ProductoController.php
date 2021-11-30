<?php

require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable {
    
    //declared in IApiUsable
    public function CargarUno($request, $response, $args)
    {
        try {
            //obtengo el body
            $parametros = $request->getParsedBody();

            //creo el objeto
            $producto = new Producto();
            $producto->productoNombre = $parametros['productoNombre'];
            $producto->productoPrecio = $parametros['productoPrecio'];
            $producto->productoPerfilEmpleado = strtolower($parametros['productoPerfilEmpleado']);

            //valido datos
            $producto->Validate();
            
            //lo pego contra la base
            $producto->productoID = $producto->crearProducto();

            //seteo el payload del response;
            $payload = json_encode(array("mensaje" => "Se creo el producto", "data" => $producto));
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
            //traigo los productos
            $productos = Producto::obtenerTodos();

            //seteo el payload del response
            $payload = json_encode(array("data" => $productos));
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
            //obtengo el ID del producto de los args
            $idProducto = $args['idProducto'];

            //traigo el producto
            $producto = Producto::obtenerProducto($idProducto);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se obtuvo el producto", "data" => $producto));
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

            //obtengo el ID del producto de los parametros
            $idProducto = $parametros['idProducto'];

            //borro el producto al que le corresponde ese ID
            Producto::borrarProducto($idProducto);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se borro el producto"));
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
            $producto = new Producto();
            $producto->productoID = $parametros['idProducto'];
            $producto->productoNombre = $parametros['productoNombre'];
            $producto->productoPrecio = $parametros['productoPrecio'];
            $producto->productoPerfilEmpleado = strtolower($parametros['productoPerfilEmpleado']);

            //valido el estado
            $producto->Validate();

            //modifico el producto
            $producto->modificarProductoPorId($producto->productoID);

            //seteo el payload
            $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}


?>