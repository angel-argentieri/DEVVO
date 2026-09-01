<?php
require_once '../APP/MODEL/UsuarioModel.php';
require_once '../APP/VIEW/BaseView.php';

class UsuarioController {
    private $model;
    private $view;

    public function __construct($db) {
        $this->model = new UsuarioModel($db);
        $this->view  = new BaseView();
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data['matricula'] || !$data['senha']) {
            $this->view->sendResponse(['error' => 'Preencha matricula e senha.'], 400);
            return;
        }

        $usuario = $this->model->buscarPorMatricula($data['matricula']);

        if (!$usuario) {
            $this->view->sendResponse(['error' => 'Matricula nao encontrada.'], 401);
            return;
        }

        if ($data['senha'] !== $usuario['senha']) {
            $this->view->sendResponse(['error' => 'Senha incorreta.'], 401);
            return;
        }

        $this->view->sendResponse([
            'id_usuario'  => $usuario['id_usuario'],
            'nome'        => $usuario['nome'],
            'tipo_acesso' => $usuario['tipo_acesso']
        ]);
    }

    public function cadastrar() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data['nome'] || !$data['matricula'] || !$data['senha']) {
            $this->view->sendResponse(['error' => 'Nome, matricula e senha sao obrigatorios.'], 400);
            return;
        }

        $jaExiste = $this->model->buscarPorMatricula($data['matricula']);
        if ($jaExiste) {
            $this->view->sendResponse(['error' => 'Essa matricula ja esta cadastrada.'], 409);
            return;
        }

        $id = $this->model->criar($data['nome'], $data['matricula'], $data['email'] ?? '', $data['senha']);

        if (!$id) {
            $this->view->sendResponse(['error' => 'Erro ao criar conta.'], 500);
            return;
        }

        $this->view->sendResponse(['message' => 'Conta criada com sucesso.', 'id_usuario' => $id], 201);
    }
}
?>
