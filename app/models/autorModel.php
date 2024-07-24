<?php
    namespace app\models;

    class autorModel extends mainModel{
        private $tabla = "Autores";
        private $ID;
        private $Nombre;

        protected function __construct($Nombre,$ID=null){
            $this->ID = $ID;
            $this->Nombre = $Nombre;
        }

        private function construirConsulta() {
            $datos = [];
            $propiedades = get_object_vars($this);
        
            foreach ($propiedades as $propiedad => $valor) {
                if ($propiedad != 'tabla' && $valor !== null) {
                    $campo = $propiedad;
                    $marcador = ":$campo";
                    $datos[] = [
                        "campo_nombre" => $propiedad,
                        "campo_marcador" => $marcador,
                        "campo_valor" => $valor
                    ];
                }
            }
            
            return $datos;
        }

        protected function registrarAutor() {
            $datosAutor = $this->construirConsulta();
            return $this->guardarDatos($this->tabla, $datosAutor);
        }

        protected function buscarAutores($tipo,$inicio="", $registros="",$busqueda="") {
            if($tipo=="Contar"){
                if($busqueda != ""){
                    $consulta = "SELECT COUNT(ID) FROM $this->tabla WHERE Nombre LIKE '%$busqueda%'";
                }else{
                    $consulta = "SELECT COUNT(ID) FROM $this->tabla";
                }
            }elseif($tipo=="Datos"){
                if($busqueda != ""){
                    $consulta = "SELECT * FROM $this->tabla WHERE Nombre LIKE '%$busqueda%' ORDER BY Nombre ASC LIMIT $inicio, $registros";
                }else{
                    $consulta = "SELECT * FROM $this->tabla ORDER BY Nombre ASC LIMIT $inicio, $registros";
                }
            }
            return $this->ejecutarConsulta($consulta);
        }

        protected function eliminarAutor($id){
            return $this->eliminarRegistro($this->tabla,"ID",$id);
        }

        protected function actualizarAutor(){
            $datosAutor = $this->construirConsulta();
            return $this->actualizarDatos($this->tabla, $datosAutor,$this->ID);
        }
    }