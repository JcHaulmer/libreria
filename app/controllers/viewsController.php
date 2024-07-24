<?php
    namespace app\controllers;
    use app\models\viewsModel;

    class viewsController extends viewsModel{

        public function obtenerVistasControlador($vista,$rol){
            if($vista!=""){
                $respuesta = $this->obtenerVistasModelo($vista,$rol);
            }else{
                $respuesta = "login";
            }
            return $respuesta;
        }
    }