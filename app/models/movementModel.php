<?php
    namespace app\models;
    use PDO;

    class movementModel extends mainModel{
        private $tabla = "Movimientos";
        private $ID;
        private $Fecha;
        private $ID_bodegaOrigen;
        private $ID_bodegaDestino;
        private $ID_Usuario;


        protected function __construct($Fecha,$ID_bodegaOrigen,$ID_bodegaDestino,$ID_Usuario,$ID=null){
            $this->ID = $ID;
            $this->Fecha = $Fecha;
            $this->ID_bodegaOrigen = $ID_bodegaOrigen;
            $this->ID_bodegaDestino = $ID_bodegaDestino;
            $this->ID_Usuario = $ID_Usuario;
        }

        private function construirConsulta($objeto=null){
            $datos = [];
            if($objeto){
                $propiedades = $objeto;
            }else{
                $propiedades = get_object_vars($this);
            }
            
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

        protected function registrarMovimiento() {
            $datosMovimiento = $this->construirConsulta();
            return $this->guardarDatos($this->tabla, $datosMovimiento);
        }

        protected function obtenerUltimoMovimiento() {
            $ultimoMovimiento = $this->ejecutarConsulta("SELECT ID FROM Movimientos ORDER BY Fecha DESC LIMIT 1");
            $ultimoMovimiento = $ultimoMovimiento->fetch(PDO::FETCH_ASSOC);
            return $ultimoMovimiento['ID'];
        }

        protected function registrarProductoEnMovimiento($productoEnMovimiento) {
            $datosProductoEnMovimiento = $this->construirConsulta($productoEnMovimiento);
            return $this->guardarDatos("ProductoEnMovimiento", $datosProductoEnMovimiento);
        }

        protected function actualizarProductoEnBodega($bodegaID, $productoID, $cantidad){
            $existeProductoEnBodega = $this->ejecutarConsulta("SELECT * FROM ProductoEnBodega WHERE ID_Bodega = $bodegaID AND ID_Producto = $productoID");
        
            if($existeProductoEnBodega->rowCount() > 0){
                $consulta = $this->ejecutarConsulta("UPDATE ProductoEnBodega SET Cantidad = Cantidad + $cantidad WHERE ID_Bodega = $bodegaID AND ID_Producto = $productoID");
            } else {
                $consulta = $this->ejecutarConsulta("INSERT INTO ProductoEnBodega (ID_Bodega, ID_Producto, Cantidad) VALUES ($bodegaID, $productoID, $cantidad)");
            }
            return $consulta;
        }

        protected function verificarStock($bodegaID, $productoID, $cantidad){
            $consulta = $this->ejecutarConsulta("SELECT Cantidad FROM ProductoEnBodega WHERE ID_Bodega = $bodegaID AND ID_Producto = $productoID");
        
            if($consulta->rowCount() > 0){
                
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                if($resultado['Cantidad'] >= $cantidad){
                    return true;
                }else{
                    return false;
                }
                
            }else{
                return false;
            }
        }

        protected function eliminarMovimiento($id){
            return $this->eliminarRegistro($this->tabla,"ID",$id);
        }

        protected function buscarMovimientos($tipo,$inicio="", $registros="",$busqueda="") {
            if($tipo=="Contar"){
                if($busqueda != ""){
                    $consulta = "SELECT COUNT(ID) FROM $this->tabla
                                 INNER JOIN Bodegas AS BodegaOrigen ON $this->tabla.ID_bodegaOrigen = BodegaOrigen.ID
                                 INNER JOIN Bodegas AS BodegaDestino ON $this->tabla.ID_bodegaDestino = BodegaDestino.ID
                                 INNER JOIN Usuarios ON $this->tabla.ID_Usuario = Usuarios.ID
                                 WHERE BodegaOrigen.Nombre LIKE '%$busqueda%' OR BodegaDestino.Nombre LIKE '%$busqueda%'";
                }else{
                    $consulta = "SELECT COUNT(ID) FROM $this->tabla";
                }
            }elseif($tipo=="Datos"){
                if($busqueda != ""){
                    $consulta = "SELECT $this->tabla.*, BodegaOrigen.Nombre AS NombreBodegaOrigen, BodegaDestino.Nombre AS NombreBodegaDestino, Usuarios.Nombre AS NombreUsuario
                                 FROM $this->tabla
                                 INNER JOIN Bodegas AS BodegaOrigen ON $this->tabla.ID_bodegaOrigen = BodegaOrigen.ID
                                 INNER JOIN Bodegas AS BodegaDestino ON $this->tabla.ID_bodegaDestino = BodegaDestino.ID
                                 INNER JOIN Usuarios ON $this->tabla.ID_Usuario = Usuarios.ID
                                 WHERE BodegaOrigen.Nombre LIKE '%$busqueda%' OR BodegaDestino.Nombre LIKE '%$busqueda%'
                                 ORDER BY $this->tabla.Fecha DESC LIMIT $inicio, $registros";
                }else{
                    $consulta = "SELECT $this->tabla.*, BodegaOrigen.Nombre AS NombreBodegaOrigen, BodegaDestino.Nombre AS  NombreBodegaDestino, Usuarios.Nombre AS NombreUsuario
                                 FROM $this->tabla
                                 INNER JOIN Bodegas AS BodegaOrigen ON $this->tabla.ID_bodegaOrigen = BodegaOrigen.ID
                                 INNER JOIN Bodegas AS BodegaDestino ON $this->tabla.ID_bodegaDestino = BodegaDestino.ID
                                 INNER JOIN Usuarios ON $this->tabla.ID_Usuario = Usuarios.ID
                                 ORDER BY $this->tabla.Fecha DESC LIMIT $inicio, $registros";
                }
            }
            return $this->ejecutarConsulta($consulta);
        }

        protected function buscarProductosPorMovimiento($ID_Movimiento) {
            $sql = $this->conectar()->prepare("SELECT *, Productos.Nombre AS NombreProducto FROM ProductoEnMovimiento INNER JOIN Productos ON ProductoEnMovimiento.ID_Producto = Productos.ID WHERE ID_Movimiento = :id");
            $sql->bindParam(':id', $ID_Movimiento);
            $sql->execute();
            return $sql;
        }
        

    }