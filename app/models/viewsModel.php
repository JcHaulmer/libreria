<?php
    namespace app\models;

    class viewsModel{

        protected function obtenerVistasModelo($vista,$rol){

            $listaBlanca = ["dashboard","logOut","userNew","userList","userUpdate","autorNew","autorList","autorUpdate","editorialNew","editorialList","editorialUpdate","productNew","productList","productUpdate","cellarNew","cellarList","cellarUpdate","movementNew","movementList","cellarProducts","cellarReport","movementReport"];

            $listaBodeguero = ["dashboard","logOut","movementNew","movementList"];

            $listaJefeDeBodega = ["dashboard","logOut","autorNew","autorList","autorUpdate","editorialNew","editorialList","editorialUpdate","productNew","productList","productUpdate","cellarNew","cellarList","cellarUpdate","cellarProducts","cellarReport","movementReport"];

            $listaAdministrador = $listaBlanca;

            switch ($rol) {
                case 'Bodeguero':
                    $listaPermitida = $listaBodeguero;
                    break;
                case 'Jefe de Bodega':
                    $listaPermitida = $listaJefeDeBodega;
                    break;
                case 'Administrador':
                    $listaPermitida = $listaAdministrador;
                    break;
                default:
                    $listaPermitida = [];
            }

            if(in_array($vista,$listaPermitida)){
                if(is_file("./app/views/content/".$vista."-view.php")){
                    $contenido = "./app/views/content/".$vista."-view.php";
                }else{
                    $contenido = "404";
                }
            }elseif($vista=="login" || $vista=="index"){
                $contenido = "login";
            }else{
                $contenido = "404";
            }
            return $contenido;
        }
    }