<?php
require_once '../APP/MODEL/ItemPerdidoModel.php';
require_once '../APP/VIEW/BaseView.php';

class ItemPerdidoController {
    private $model;
    private $view;

    public function __construct($db) {
        $this->model = new ItemPerdidoModel($db);
        $this->view  = new BaseView();
    }

    public function criar() {
        $data          = json_decode(file_get_contents('php://input'), true);
        $id_categoria  = $data['id_categoria']  ?? null;
        $id_aluno      = $data['id_aluno']      ?? null;
        $titulo        = $data['titulo']        ?? null;
        $descricao     = $data['descricao']     ?? '';
        $local_perdido = $data['local_perdido'] ?? null;

        if (!$id_categoria || !$id_aluno || !$titulo || !$local_perdido) {
            $this->view->sendResponse(['error' => 'Campos obrigatorios nao preenchidos.'], 400);
            return;
        }

        $id = $this->model->criar($id_categoria, $id_aluno, $titulo, $descricao, $local_perdido);

        if (!$id) {
            $this->view->sendResponse(['error' => 'Erro ao registrar item perdido.'], 500);
            return;
        }

        $this->view->sendResponse(['message' => 'Registrado com sucesso.', 'id_perdido' => $id], 201);
    }

    public function getPerdidos() {
        $perdidos = $this->model->buscarTodas();
        $this->view->sendResponse($perdidos);
    }
}
?>