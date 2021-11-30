<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/Logger.php';

require_once './controllers/backup/EmpleadoController.php';
require_once './controllers/backup/MesaController.php';
require_once './controllers/backup/ProductoController.php';
require_once './controllers/backup/PedidoController.php';

require_once './controllers/RegistroController.php';
require_once './controllers/LoginController.php';
require_once './controllers/CrearPedidoController.php';
require_once './controllers/ListarPendientesController.php';
require_once './controllers/TomarPendienteController.php';
require_once './controllers/ListarEnPreparacionController.php';
require_once './controllers/TerminarEnPreparacionController.php';
require_once './controllers/ServirPedidoController.php';
require_once './controllers/CobrarPedidoController.php';
require_once './controllers/CerrarMesaController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/ConsultarPedidosController.php';
require_once './controllers/ConsultarDemoraController.php';
require_once './controllers/ConsultarMesasController.php';
require_once './controllers/MejoresComentariosController.php';
require_once './controllers/MesaMasUsadaController.php';


require_once './db/AccesoDatos.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
// se lo puedo sacar para pegarle directamente a localhost/ en vez de tener que poner el public despues
// $app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//setting timezone a Buenos Aires
date_default_timezone_set("America/Argentina/Buenos_Aires");

//generales (backup) 
  $app->group('/empleado', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
    $group->get('/{idEmpleado}', \EmpleadoController::class . ':TraerUno');
    $group->post('[/]', \EmpleadoController::class . ':CargarUno');
    $group->delete('[/]', \EmpleadoController::class . ':BorrarUno');
    $group->put('[/]', \EmpleadoController::class . ':ModificarUno');
  });

  $app->group('/mesa', function (RouteCollectorProxy $group){
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->get('/{idMesa}', \MesaController::class . ':TraerUno');
    $group->post('[/]', \MesaController::class . ':CargarUno');
    $group->delete('[/]', \MesaController::class . ':BorrarUno');
    $group->put('[/]', \MesaController::class . ':ModificarUno');
  });

  $app->group('/producto', function (RouteCollectorProxy $group){
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{idProducto}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->delete('[/]', \ProductoController::class . ':BorrarUno');
    $group->put('[/]', \ProductoController::class . ':ModificarUno');
  });

  $app->group('/pedido', function (RouteCollectorProxy $group){
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/{pedidoCodigo}', \PedidoController::class . ':TraerUno');
    $group->post('[/]', \PedidoController::class . ':CargarUno');
    $group->delete('[/]', \PedidoController::class . ':BorrarUno');
    $group->put('[/]', \PedidoController::class . ':ModificarUno');
  });
//


$app->group('/public', function (RouteCollectorProxy $group) {
  $group->post('/registro', \RegistroController::class . ':RegistrarUno');
  $group->post('/login', \LoginController::class . ':LogearUno');
});


$app->group('/socios', function (RouteCollectorProxy $group) {
  //CERRAR MESA
  $group->put('/mesas/cerrar', \CerrarMesaController::class . ':CerrarMesa');
  //CONSULTAR PEDIDOS
  $group->get('/pedidos/pendientes', \ConsultarPedidosController::class . ':ConsultarPedidos');
  //CONSULTAR DEMORA
  $group->get('/pedidos/consulta/{mesaID}/{pedidoCodigo}', \ConsultarDemoraController::class . ':ConsultarDemora');
  //CONSULTAR MESAS
  $group->get('/mesas', \ConsultarMesasController::class . ':ConsultarMesas');
  //MEJORES COMENTARIOS
  $group->get('/mejoresComentarios', \MejoresComentariosController::class . ':TraerMejoresComentarios');
  //MESA MAS USADA
  $group->get('/mesaMasUsada', \MesaMasUsadaController::class . ':MesaMasUsada');
})->add(\Logger::class . ':VerificadorSocio');


$app->group('/clientes', function (RouteCollectorProxy $group) {
  //COMPLETAR ENCUESTA
  $group->put('/encuesta', \EncuestaController::class . ':CargarEncuesta');
  //CONSULTAR DEMORA
  $group->get('/pedidos/consulta/{mesaID}/{pedidoCodigo}', \ConsultarDemoraController::class . ':ConsultarDemora');
});


$app->group('/empleados', function (RouteCollectorProxy $group) {
  //TRAE LOS PENDIENTES SEGUN EL PERFIL DEL EMPLEADO
  $group->get('/pendientes', \ListarPendientesController::class . ':ListarPendientes');
  //TOMA EL PEDIDO PENDIENTE POR ID
  $group->put('/pendientes/{idPedido}', \TomarPendienteController::class . ':TomarPendiente');
  //TRAE LOS PENDIENTES DEL EMPLEADO
  $group->get('/preparacion', \ListarEnPreparacionController::class . ':ListarEnPreparacion');
  //TERMINA LA PREPARACION
  $group->put('/preparacion/{idPedido}', \TerminarEnPreparacionController::class . ':TerminarEnPreparacion');
})->add(\Logger::class . ':VerificadorEmpleado');


$app->group('/mozos', function (RouteCollectorProxy $group) {
  //CREAR PEDIDO
  $group->post('/pedidos/crear', \CrearPedidoController::class . ':CrearPedido');
  //SERVIR PEDIDO
  $group->put('/pedidos/servir', \ServirPedidoController::class . ':ServirPedido');
  //COBRAR PEDIDO
  $group->put('/pedidos/cobrar', \CobrarPedidoController::class . ':CobrarPedido');
})->add(\Logger::class . ':VerificadorMozo');


// para no tener problemas con el put y delete
$app->addBodyParsingMiddleware();

// Run app
$app->run();

