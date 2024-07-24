<?php
    namespace app\controllers;
    use app\models\mainModel;

    class searchController extends mainModel{
        /* Controlador modulos de busquedas */
        public function modulosBusquedaControlador($modulo){
            $listaModulos = ['userList','autorList','editorialList','productList','cellarList','movementList','cellarReport','movementReport'];

            if(in_array($modulo,$listaModulos)){
                return false;
            }else{
                return true;
            }
        }

        /* Controlador iniciar busqueda */
        public function iniciarBuscadorControlador(){

            # Almacenando datos #
            $url = $this->limpiarCadena($_POST['modulo_url']);
            $texto = $this->limpiarCadena($_POST['txt_buscador']);

            if($this->modulosBusquedaControlador($url)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No podemos procesar la petición en este momento",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            if(empty($texto)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "Introduce un termino de busqueda",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $_SESSION[$url] = $texto;
			$alerta = [
				"tipo" => "redireccionar",
				"url" => APP_URL.$url."/"
			];
			return json_encode($alerta);
        }

        /* Controlador eliminar busqueda */
        public function eliminarBuscadorControlador(){

            # Almacenando datos #
            $url = $this->limpiarCadena($_POST['modulo_url']);

            if($this->modulosBusquedaControlador($url)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No podemos procesar la petición en este momento",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }
            
            unset($_SESSION[$url]);

            $alerta = [
				"tipo" => "redireccionar",
				"url" => APP_URL.$url."/"
			];
            
			return json_encode($alerta);
        }
    }