<?php
    namespace app\models;

    class movementProductsModel extends mainModel{
        private $tabla = "ProductoEnMovimiento";
        private $ID;
        private $ID_Movimiento;
        private $ID_Producto;
        private $Cantidad;


        protected function __construct($ID_Movimiento,$ID_Producto,$Cantidad,$ID=null){
            $this->ID = $ID;
            $this->ID_Movimiento = $ID_Movimiento;
            $this->ID_Producto = $ID_Producto;
            $this->Cantidad = $Cantidad;
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

        protected function registrarProductoEnMovimiento() {
            $datosProductoEnMovimiento = $this->construirConsulta();
            return $this->guardarDatos($this->tabla, $datosProductoEnMovimiento);
        }

        protected function buscarProductoEnMovimiento($tipo,$inicio="", $registros="",$busqueda="") {
            if($tipo=="Contar"){
                if($busqueda != ""){
                    $consulta = "SELECT COUNT(ID) FROM $this->tabla
                                 INNER JOIN Movimientos ON $this->tabla.ID_Movimiento = Movimientos.ID
                                 INNER JOIN Productos ON $this->tabla.ID_Producto = Productos.ID
                                 WHERE Productos.Nombre LIKE '%$busqueda%'";
                }else{
                    $consulta = "SELECT COUNT(ID) FROM $this->tabla";
                }
            }elseif($tipo=="Datos"){
                if($busqueda != ""){
                    $consulta = "SELECT $this->tabla.*, Productos.Nombre AS NombreProducto 
                                 FROM $this->tabla
                                 INNER JOIN Movimientos ON $this->tabla.ID_Movimiento = Movimientos.ID
                                 INNER JOIN Productos ON $this->tabla.ID_Producto = Productos.ID
                                 WHERE Productos.Nombre LIKE '%$busqueda%'
                                 LIMIT $inicio, $registros";
                }else{
                    $consulta = "SELECT $this->tabla.*, Productos.Nombre AS NombreProducto 
                                 FROM $this->tabla
                                 INNER JOIN Movimientos ON $this->tabla.ID_Movimiento = Movimientos.ID
                                 INNER JOIN Productos ON $this->tabla.ID_Producto = Productos.ID
                                 LIMIT $inicio, $registros";
                }
            }
            return $this->ejecutarConsulta($consulta);
        }

        protected function eliminarProductoEnMovimiento($id){
            return $this->eliminarRegistro($this->tabla,"ID",$id);
        }

        protected function actualizarProductoEnMovimiento(){
            $datosProductoEnMovimiento = $this->construirConsulta();
            return $this->actualizarDatos($this->tabla, $datosProductoEnMovimiento,$this->ID);
        }
    }