<?php
require_once '../APP/MODEL/SolicitacaoModel.php';
require_once '../APP/MODEL/ItemModel.php';
require_once '../APP/VIEW/BaseView.php';

class SolicitacaoController {
    private $model;
    private $modelItem;
    private $view;

    public function __construct($db) {
        $this->model     = new SolicitacaoModel($db);
        $this->modelItem = new ItemModel($db);
        $this->view      = new BaseView();
    }

    public function criar() {
        $data     = json_decode(file_get_contents('php://input'), true);
        $id_item  = $data['id_item']  ?? null;
        $id_aluno = $data['id_aluno'] ?? null;
        $mensagem = $data['mensagem'] ?? null;

        if (!$id_item || !$id_aluno || !$mensagem) {
            $this->view->sendResponse(['error' => 'Campos obrigatorios nao preenchidos.'], 400);
            return;
        }

        try {
            $id = $this->model->criar($id_item, $id_aluno, $mensagem);
            if (!$id) throw new Exception('Erro ao criar solicitacao.');

            $this->modelItem->atualizarStatus($id_item, 'em_analise');

            $this->view->sendResponse(['message' => 'Solicitacao enviada com sucesso.', 'id_solicitacao' => $id], 201);
        } catch (Exception $e) {
            $this->view->sendResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getSolicitacoes() {
        $solicitacoes = $this->model->buscarTodas();
        $this->view->sendResponse($solicitacoes);
    }

    public function atualizarStatus() {
        $data   = json_decode(file_get_contents('php://input'), true);
        $id     = $data['id']     ?? null;
        $status = $data['status'] ?? null;

        if (!$id || !$status) {
            $this->view->sendResponse(['error' => 'ID e status obrigatorios.'], 400);
            return;
        }

        $this->model->atualizarStatus($id, $status);

        if ($status === 'aprovada') {
            $solicitacoes = $this->model->buscarTodas();
            foreach ($solicitacoes as $s) {
                if ($s['id_solicitacao'] == $id) {
                    $this->modelItem->atualizarStatus($s['id_item'], 'entregue');
                    break;
                }
            }
        }

        $this->view->sendResponse(['message' => 'Status atualizado.']);
    }
}
?>
