<?php
require_once '../APP/MODEL/ItemModel.php';
require_once '../APP/VIEW/BaseView.php';

class ItemController {
    private $model;
    private $view;

    public function __construct($db) {
        $this->model = new ItemModel($db);
        $this->view  = new BaseView();
    }

    public function getItens() {
        $categoria = $_GET['categoria'] ?? null;
        $busca     = $_GET['busca'] ?? null;
        $itens     = $this->model->buscarTodos($categoria, $busca);
        $this->view->sendResponse($itens);
    }

    public function getItemPorId() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->view->sendResponse(['error' => 'ID obrigatorio.'], 400);
            return;
        }
        $item = $this->model->buscarPorId($id);
        if (!$item) {
            $this->view->sendResponse(['error' => 'Item nao encontrado.'], 404);
            return;
        }
        $this->view->sendResponse($item);
    }

    public function criarItem() {
        $titulo           = $_POST['titulo']           ?? null;
        $id_categoria     = $_POST['id_categoria']     ?? null;
        $descricao        = $_POST['descricao']        ?? '';
        $local_encontrado = $_POST['local_encontrado'] ?? null;
        $local_armazenado = $_POST['local_armazenado'] ?? '';
        $id_usuario       = $_POST['id_usuario']       ?? null;

        if (!$titulo || !$id_categoria || !$local_encontrado || !$id_usuario) {
            $this->view->sendResponse(['error' => 'Campos obrigatorios nao preenchidos.'], 400);
            return;
        }

        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $extensao   = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = uniqid('item_') . '.' . $extensao;
            $destino     = '../../FRONT/uploads/' . $nomeArquivo;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $foto = $nomeArquivo;
            }
        }

        $id = $this->model->criar($id_categoria, $id_usuario, $titulo, $descricao, $foto, $local_encontrado, $local_armazenado);

        if (!$id) {
            $this->view->sendResponse(['error' => 'Erro ao cadastrar item.'], 500);
            return;
        }

        $this->view->sendResponse(['message' => 'Item cadastrado com sucesso.', 'id_item' => $id], 201);
    }

    public function atualizarStatus() {
        $data   = json_decode(file_get_contents('php://input'), true);
        $id     = $data['id']     ?? null;
        $status = $data['status'] ?? null;

        if (!$id || !$status) {
            $this->view->sendResponse(['error' => 'ID e status obrigatorios.'], 400);
            return;
        }

        $statusPermitidos = ['disponivel', 'em_analise', 'entregue', 'doado'];
        if (!in_array($status, $statusPermitidos)) {
            $this->view->sendResponse(['error' => 'Status invalido.'], 400);
            return;
        }

        $this->model->atualizarStatus($id, $status);
        $this->view->sendResponse(['message' => 'Status atualizado.']);
    }

    public function getItensAdmin() {
        $itens = $this->model->buscarTodosAdmin();
        $this->view->sendResponse($itens);
    }

    public function getDashboard() {
        $this->view->sendResponse([
            'disponiveis' => $this->model->contarPorStatus('disponivel'),
            'em_analise'  => $this->model->contarPorStatus('em_analise'),
            'entregues'   => $this->model->contarPorStatus('entregue'),
        ]);
    }
}
?>
