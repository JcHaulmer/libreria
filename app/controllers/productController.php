<?php
    namespace app\controllers;
    use app\models\productModel;

    class productController extends productModel{

        public function __construct(){}

        # Controlador registrar producto #
        public function registrarProductoControlador(){
            
            # Almacenando datos #
            $nombre = $this->limpiarCadena($_POST['producto_nombre']);
            $descripcion = $this->limpiarCadena($_POST['producto_descripcion']);
            $tipo = $this->limpiarCadena($_POST['producto_tipo']);
            $autor = intval($this->limpiarCadena($_POST['producto_autor']));
            $editorial = intval($this->limpiarCadena($_POST['producto_editorial']));

            # Verificando campos obligatorios #
            if(empty($nombre) || empty($descripcion) || empty($tipo) || empty($autor) || empty($editorial)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando producto #
            $checkProducto = $this->seleccionarDatos("Unico","Productos","Nombre",$nombre);
            if($checkProducto->rowCount()!=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El producto '$nombre' que acaba de ingresar, ya se encuentra registrado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $nuevoProducto= new productModel($nombre,$descripcion,$tipo,$autor,$editorial);
            
            $registrarProducto = $nuevoProducto->registrarProducto();

            if($registrarProducto->rowCount()==1){
                $alerta = [
                    "tipo" => "limpiar",
                    "titulo" => "Producto registrado",
                    "texto" => "El producto ".$nombre." se registró con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar el producto, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador listar producto #
        public function listarProductoControlador($pagina, $registros, $url, $busqueda){

            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
        
            $url = $this->limpiarCadena($url);
            $url = APP_URL.$url."/";
        
            $busqueda = $this->limpiarCadena($busqueda);
            $tabla = "";
        
            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
        
            if(isset($busqueda) && $busqueda != ""){
                $consulta_datos = $this->buscarProductos("Datos",$inicio,$registros,$busqueda);
                $consulta_total = $this->buscarProductos("Contar",$inicio,$registros,$busqueda);
            } else {
                $consulta_datos = $this->buscarProductos("Datos",$inicio, $registros);
                $consulta_total = $this->buscarProductos("Contar");
            }
        
            $datos = $consulta_datos->fetchAll();
            $total = (int) $consulta_total->fetchColumn();
        
            $numeroPaginas = ceil($total / $registros);
        
            $tabla = '
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Descripcion</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Autor</th>
                            <th class="text-center">Editorial</th>
                            <th class="text-center" colspan="2">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        
            if ($total >= 1 && $pagina <= $numeroPaginas) {
                $contador = $inicio + 1;
                $pag_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                        <tr class="text-center">
                            <td>' . $contador . '</td>
                            <td>' . $rows['Nombre'] . '</td>
                            <td>' . $rows['Descripcion'] . '</td>
                            <td>' . $rows['Tipo'] . '</td>
                            <td>' . $rows['NombreAutor'] . '</td>
                            <td>' . $rows['NombreEditorial'] . '</td>
                            <td>
                                <a href="' . APP_URL . 'productUpdate/' . $rows['ID'] . '/" class="btn btn-success btn-sm rounded">Actualizar</a>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/productAjax.php" method="POST" autocomplete="off">
                                    <input type="hidden" name="modulo_producto" value="eliminar">
                                    <input type="hidden" name="producto_id" value="' . $rows['ID'] . '">
                                    <button type="submit" class="btn btn-danger btn-sm rounded">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    ';
                    $contador++;
                }
                $pag_final = $contador - 1;
            } else {
                if ($total >= 1) {
                    $tabla .= '
                        <tr class="text-center">
                            <td colspan="7">
                                <a href="' . $url . '1/" class="btn btn-link btn-sm mt-4 mb-4">
                                    Haga clic acá para recargar el listado
                                </a>
                            </td>
                        </tr>
                    ';
                } else {
                    $tabla .= '
                        <tr class="text-center">
                            <td colspan="7">
                                No hay registros en el sistema
                            </td>
                        </tr>
                    ';
                }
            }
        
            $tabla .= '</tbody></table></div>';
        
            # Paginacion #
            if ($total > 0 && $pagina <= $numeroPaginas) {
                $tabla .= '<p class="text-end">Mostrando productos <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
        
            return $tabla;
        }

        # Controlador eliminar producto #
        public function eliminarProductoControlador(){
            $id = $this->limpiarCadena($_POST['producto_id']);

            # Verificando producto existe #
            $checkProducto= $this->seleccionarDatos("Unico","Productos","ID",$id);
            if($checkProducto->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado el producto en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $producto = $checkProducto->fetch();
            }

            # Verificando producto en bodega #
            $checkProductoEnBodega = $this->seleccionarDatos("Unico","ProductoEnBodega","ID_Producto",$id);
            if($checkProductoEnBodega->rowCount()>=1){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Producto no puede ser eliminado",
                    "texto" => "El producto ".$producto['Nombre']." se encuentra registrado en una bodega",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $eliminarProducto = $this->eliminarProducto($id);
            if($eliminarProducto->rowCount()==1){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Producto eliminado",
                    "texto" => "El producto ".$producto['Nombre']." se eliminó con éxito",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo eliminar el producto ".$producto['Nombre'].", por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador actualizar producto #
        public function actualizarProductoControlador(){
            $id = $this->limpiarCadena($_POST['producto_id']);

            # Verificando producto #
            $datosProducto = $this->seleccionarDatos("Unico","Productos","ID",$id);
            if($datosProducto->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado el producto en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $datosProducto = $datosProducto->fetch();
            }
            
            # Almacenando datos #
		    $nombre = $this->limpiarCadena($_POST['producto_nombre']);
            $descripcion = $this->limpiarCadena($_POST['producto_descripcion']);
            $tipo = $this->limpiarCadena($_POST['producto_tipo']);
            $autor = intval($this->limpiarCadena($_POST['producto_autor']));
            $editorial = intval($this->limpiarCadena($_POST['producto_editorial']));

		    # Verificando campos obligatorios #
            if(empty($nombre) || empty($descripcion) || empty($tipo) || empty($autor) || empty($editorial)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando producto #
            if($datosProducto['Nombre']!=$nombre){
                $checkProducto = $this->seleccionarDatos("Unico","Productos","Nombre",$nombre);
                if($checkProducto->rowCount()!=0){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "El producto '$nombre' que acaba de ingresar, ya se encuentra registrado",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            $nuevoProducto = new productModel($nombre,$descripcion,$tipo,$autor,$editorial,$id);
            $actualizarProducto= $nuevoProducto->actualizarProducto();

            if($actualizarProducto){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Producto actualizado",
                    "texto" => "El producto ".$nombre." se actualizó con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo actualizar el producto, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }
    }