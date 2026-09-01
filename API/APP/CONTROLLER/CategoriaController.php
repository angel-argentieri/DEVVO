<?php
require_once '../APP/MODEL/CategoriaModel.php';
require_once '../APP/VIEW/BaseView.php';

class CategoriaController {
    private $model;
    private $view;

    public function __construct($db) {
        $this->model = new CategoriaModel($db);
        $this->view  = new BaseView();
    }

    public function getCategorias() {
        $this->view->sendResponse($this->model->buscarTodas());
    }
}
?>
