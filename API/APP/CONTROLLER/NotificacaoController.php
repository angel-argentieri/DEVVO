<?php
require_once '../APP/MODEL/NotificacaoModel.php';
require_once '../APP/VIEW/BaseView.php';

class NotificacaoController {
    private $model;
    private $view;

    public function __construct($db) {
        $this->model = new NotificacaoModel($db);
        $this->view  = new BaseView();
    }

    public function getNotificacoes() {
        $id_usuario = $_GET['id_usuario'] ?? null;
        if (!$id_usuario) {
            $this->view->sendResponse(['error' => 'ID do usuario obrigatorio.'], 400);
            return;
        }

        $notificacoes = $this->model->buscarPorUsuario($id_usuario);
        $this->view->sendResponse($notificacoes);
    }

    public function marcarLida() {
        $data = json_decode(file_get_contents('php://input'), true);
        $id   = $data['id'] ?? null;

        if (!$id) {
            $this->view->sendResponse(['error' => 'ID da notificacao obrigatorio.'], 400);
            return;
        }

        $this->model->marcarComoLida($id);
        $this->view->sendResponse(['message' => 'Notificacao marcada como lida.']);
    }

    public function marcarTodasLidas() {
        $data       = json_decode(file_get_contents('php://input'), true);
        $id_usuario = $data['id_usuario'] ?? null;

        if (!$id_usuario) {
            $this->view->sendResponse(['error' => 'ID do usuario obrigatorio.'], 400);
            return;
        }

        $this->model->marcarTodasComoLidas($id_usuario);
        $this->view->sendResponse(['message' => 'Todas notificacoes marcadas como lidas.']);
    }
}
?>
