<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json; charset=UTF-8");
$origin = $_SERVER["HTTP_ORIGIN"] ?? "*";
header("Access-Control-Allow-Origin: " . $origin);
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

require_once '../CONFIG/db.php';
require_once '../APP/CONTROLLER/UsuarioController.php';
require_once '../APP/CONTROLLER/ItemController.php';
require_once '../APP/CONTROLLER/ItemPerdidoController.php';
require_once '../APP/CONTROLLER/SolicitacaoController.php';
require_once '../APP/CONTROLLER/CategoriaController.php';

$database = new Database();
$db       = $database->getConnection();

$itemController       = new ItemController($db);
$solicitacaoController = new SolicitacaoController($db);
$categoriaController  = new CategoriaController($db);
$itemPerdidoController = new ItemPerdidoController($db);
$usuarioController    = new UsuarioController($db);

$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route  = basename($path);
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($route) {

        case 'health':
            echo json_encode(['status' => 'Devvo API online!']);
            exit;

        case 'login':
            if ($method === 'POST') { $usuarioController->login(); exit; }
            break;

        case 'cadastrar':
            if ($method === 'POST') { $usuarioController->cadastrar(); exit; }
            break;

        case 'itens':
            if ($method === 'GET')  { $itemController->getItens(); exit; }
            if ($method === 'POST') { $itemController->criarItem(); exit; }
            break;

        case 'item':
            if ($method === 'GET') { $itemController->getItemPorId(); exit; }
            break;

        case 'itens-admin':
            if ($method === 'GET') { $itemController->getItensAdmin(); exit; }
            break;

        case 'item-perdido':
            if ($method === 'POST') { $itemPerdidoController->criar(); exit; }
            break;

        case 'itens-perdidos':
            if ($method === 'GET') { $itemPerdidoController->getPerdidos(); exit; }
            break;

        case 'item-status':
            if ($method === 'PUT') { $itemController->atualizarStatus(); exit; }
            break;

        case 'dashboard':
            if ($method === 'GET') { $itemController->getDashboard(); exit; }
            break;

        case 'solicitacao':
            if ($method === 'POST') { $solicitacaoController->criar(); exit; }
            break;

        case 'solicitacoes':
            if ($method === 'GET') { $solicitacaoController->getSolicitacoes(); exit; }
            break;

        case 'solicitacao-status':
            if ($method === 'PUT') { $solicitacaoController->atualizarStatus(); exit; }
            break;

        case 'categorias':
            if ($method === 'GET') { $categoriaController->getCategorias(); exit; }
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Rota nao encontrada.']);
            exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Metodo nao permitido.']);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error'  => 'Erro interno do servidor.',
        'detail' => $e->getMessage()
    ]);
}
?>
