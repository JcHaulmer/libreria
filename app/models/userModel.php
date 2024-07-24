<?php
    namespace app\models;

    class userModel extends mainModel{
        private $tabla = "Usuarios";
        private $ID;
        private $Nombre;
        private $Apellido;
        private $Usuario;
        private $Clave;
        private $Rol;

        protected function __construct($Nombre,$Apellido,$Usuario,$Rol,$Clave,$ID=null){
            $this->ID = $ID;
            $this->Nombre = $Nombre;
            $this->Apellido = $Apellido;
            $this->Usuario = $Usuario;
            $this->Rol = $Rol;
            $this->Clave = $Clave;
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

        protected function registrarUsuario() {
            $datosUsuario = $this->construirConsulta();
            return $this->guardarDatos($this->tabla, $datosUsuario);
        }

        protected function buscarUsuarios($tipo,$inicio="", $registros="",$busqueda="") {
            if($tipo=="Contar"){
                if($busqueda != ""){
                    $consulta = "SELECT COUNT(ID) FROM $this->tabla WHERE (ID != '1' AND (Nombre LIKE '%$busqueda%' OR Apellido LIKE '%$busqueda%' OR Usuario LIKE '%$busqueda%'))";
                }else{
                    $consulta = "SELECT COUNT(ID) FROM $this->tabla WHERE ID != '1'";
                }
            }elseif($tipo=="Datos"){
                if($busqueda != ""){
                    $consulta = "SELECT * FROM $this->tabla WHERE (ID != '1' AND (Nombre LIKE '%$busqueda%' OR Apellido LIKE '%$busqueda%' OR Usuario LIKE '%$busqueda%')) ORDER BY Nombre ASC LIMIT $inicio, $registros";
                }else{
                    $consulta = "SELECT * FROM $this->tabla WHERE ID != '1' ORDER BY Nombre ASC LIMIT $inicio, $registros";
                }
            }
            return $this->ejecutarConsulta($consulta);
        }

        protected function eliminarUsuario($id){
            return $this->eliminarRegistro($this->tabla,"ID",$id);
        }

        protected function actualizarUsuario(){
            $datosUsuario = $this->construirConsulta();
            return $this->actualizarDatos($this->tabla, $datosUsuario,$this->ID);
        }
    }