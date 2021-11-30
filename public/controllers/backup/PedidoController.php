<?php

require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';
require_once './constants/estadoPedidos.php';

class PedidoController extends Pedido implements IApiUsable {

    //declared in IApiUsable
    public function CargarUno($request, $response, $args)
    {
        try {
            //obtengo el body
            $parametros = $request->getParsedBody();
            
            //creo el objeto
            $pedido = new Pedido();
            $pedido->pedidoCodigo = $parametros['pedidoCodigo'];
            $pedido->pedidoProductoID = $parametros['pedidoProductoID'];
            $pedido->pedidoMesaID = $parametros['pedidoMesaID'];
            $pedido->pedidoCliente = $parametros['pedidoCliente'];
            $pedido->pedidoEstado = PEDIDO_EN_ESPERA;
            $pedido->pedidoFechaCreacion = date("Y-m-d H:i:s");
            $pedido->guardarImagenVenta($_FILES['pedidoImagen']);

            //lo pego contra la base
            $pedido->pedidoID = $pedido->crearPedido();

            //seteo el payload del response;
            $payload = json_encode(array("mensaje" => "Se creo el pedido", "data" => $pedido));
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
            //traigo los pedidos
            $pedidos = Pedido::obtenerTodos();

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

    //declared in IApiUsable
    public function TraerUno($request, $response, $args)
    {
        try {
            //obtengo el ID del pedido de los args
            $pedidoCodigo = $args['pedidoCodigo'];

            //traigo el pedido
            $pedido = Pedido::obtenerPedido($pedidoCodigo);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se obtuvo el pedido", "data" => $pedido));
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

            //obtengo el ID del pedido y de la mesa de los parametros
            $pedidoCodigo = $parametros['pedidoCodigo'];
            $pedidoMesaID = $parametros['pedidoMesaID'];

            //borro al pedido al que le corresponde ese ID (baja logica)
            Pedido::borrarPedido($pedidoCodigo, $pedidoMesaID);

            //seteo el payload del response
            $payload = json_encode(array("mensaje" => "Se borro al pedido"));
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
            $pedido = new Pedido();

            $pedido->pedidoID = $parametros['pedidoID'];
            $pedido->pedidoCodigo = $parametros['pedidoCodigo'];
            $pedido->pedidoProductoID = $parametros['pedidoProductoID'];
            $pedido->pedidoMesaID = $parametros['pedidoMesaID'];
            $pedido->pedidoCliente = $parametros['pedidoCliente'];
            $pedido->pedidoEstado = strtolower($parametros['pedidoEstado']);
            $pedido->pedidoCalificacion = $parametros['pedidoCalificacion'];;
            $pedido->pedidoComentario = $parametros['pedidoComentario'];

            //valido datos
            $pedido->Validate();

            //modifico el pedido
            $pedido->modificarPedidoPorId($pedido->pedidoID);

            //seteo el payload
            $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
        } catch (Exception $e) {
            $payload = json_encode(array("ERROR" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

}


?>