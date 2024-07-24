<?php
    namespace app\models;
    use PDO;
    class cellarModel extends mainModel{
        private $tabla = "Bodegas";
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

        protected function registrarBodega() {
            $datosBodega = $this->construirConsulta();
            return $this->guardarDatos($this->tabla, $datosBodega);
        }

        protected function buscarBodegas($tipo,$inicio="", $registros="",$busqueda="") {
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

        protected function eliminarBodega($id){
            return $this->eliminarRegistro($this->tabla,"ID",$id);
        }

        protected function actualizarBodega(){
            $datosBodega = $this->construirConsulta();
            return $this->actualizarDatos($this->tabla, $datosBodega,$this->ID);
        }

        protected function verificarProductoEnBodega($bodegaID, $productoID){
            $consulta = $this->ejecutarConsulta("SELECT Cantidad FROM ProductoEnBodega WHERE ID_Bodega = $bodegaID AND ID_Producto = $productoID");
        
            if($consulta->rowCount() > 0){
                return true;                
            }else{
                return false;
            }
        }

        protected function registrarProductoEnBodega($ID_Producto,$ID_Bodega,$Cantidad){
            $existeProducto = $this->verificarProductoEnBodega($ID_Bodega,$ID_Producto);
            if($existeProducto){
                $registrarProductoEnBodega = $this->ejecutarConsulta("UPDATE ProductoEnBodega SET Cantidad = Cantidad + $Cantidad WHERE ID_Bodega = $ID_Bodega AND ID_Producto = $ID_Producto");
            }else{
                $registrarProductoEnBodega = $this->ejecutarConsulta("INSERT INTO ProductoEnBodega(ID_Producto,ID_Bodega,Cantidad) VALUES ($ID_Producto,$ID_Bodega,$Cantidad)");
            }
            return $registrarProductoEnBodega;
        }
    }