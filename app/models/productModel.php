<?php
    namespace app\models;

    class productModel extends mainModel{
        private $tabla = "Productos";
        private $ID;
        private $Nombre;
        private $Descripcion;
        private $Tipo;
        private $ID_Autor;
        private $ID_Editorial;


        protected function __construct($Nombre,$Descripcion,$Tipo,$ID_Autor,$ID_Editorial,$ID=null){
            $this->ID = $ID;
            $this->Nombre = $Nombre;
            $this->Descripcion = $Descripcion;
            $this->Tipo = $Tipo;
            $this->ID_Autor = $ID_Autor;
            $this->ID_Editorial = $ID_Editorial;
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

        protected function registrarProducto() {
            $datosProducto = $this->construirConsulta();
            return $this->guardarDatos($this->tabla, $datosProducto);
        }

        protected function buscarProductos($tipo,$inicio="", $registros="",$busqueda="") {
            if($tipo=="Contar"){
                if($busqueda != ""){
                    $consulta = "SELECT COUNT(Productos.ID) FROM $this->tabla Productos
                                 INNER JOIN Autores ON Productos.ID_Autor = Autores.ID
                                 INNER JOIN Editoriales ON Productos.ID_Editorial = Editoriales.ID
                                 WHERE Productos.Nombre LIKE '%$busqueda%'";
                }else{
                    $consulta = "SELECT COUNT(Productos.ID) FROM $this->tabla Productos";
                }
            }elseif($tipo=="Datos"){
                if($busqueda != ""){
                    $consulta = "SELECT Productos.*, Autores.Nombre AS NombreAutor, Editoriales.Nombre AS NombreEditorial 
                                 FROM $this->tabla productos
                                 INNER JOIN Autores ON Productos.ID_Autor = Autores.ID
                                 INNER JOIN Editoriales ON Productos.ID_Editorial = Editoriales.ID
                                 WHERE Productos.Nombre LIKE '%$busqueda%'
                                 ORDER BY Productos.Nombre ASC LIMIT $inicio, $registros";
                }else{
                    $consulta = "SELECT Productos.*, Autores.Nombre AS NombreAutor, Editoriales.Nombre AS NombreEditorial 
                                 FROM $this->tabla Productos
                                 INNER JOIN Autores ON Productos.ID_Autor = Autores.ID
                                 INNER JOIN Editoriales ON Productos.ID_Editorial = Editoriales.ID
                                 ORDER BY Productos.Nombre ASC LIMIT $inicio, $registros";
                }
            }
            return $this->ejecutarConsulta($consulta);
        }

        protected function eliminarProducto($id){
            return $this->eliminarRegistro($this->tabla,"ID",$id);
        }

        protected function actualizarProducto(){
            $datosProducto = $this->construirConsulta();
            return $this->actualizarDatos($this->tabla, $datosProducto,$this->ID);
        }
    }