<?php
    namespace app\controllers;
    use app\models\cellarModel;

    class cellarController extends cellarModel{

        public function __construct(){}

        # Controlador registrar bodega #
        public function registrarBodegaControlador(){
            
            # Almacenando datos #
            $nombre = $this->limpiarCadena($_POST['bodega_nombre']);

            # Verificando campos obligatorios #
            if(empty($nombre)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando bodega #
            $checkBodega = $this->seleccionarDatos("Unico","Bodegas","Nombre",$nombre);
            if($checkBodega->rowCount()!=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "La bodega '$nombre' que acaba de ingresar, ya se encuentra registrada",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $nuevaBodega = new cellarModel($nombre);
            
            $registrarBodega = $nuevaBodega->registrarBodega();

            if($registrarBodega->rowCount()==1){
                $alerta = [
                    "tipo" => "limpiar",
                    "titulo" => "Bodega registrada",
                    "texto" => "La bodega ".$nombre." se registró con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar la bodega, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador listar bodega #
        public function listarBodegaControlador($pagina, $registros, $url, $busqueda){

            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
        
            $url = $this->limpiarCadena($url);
            $url = APP_URL.$url."/";
        
            $busqueda = $this->limpiarCadena($busqueda);
            $tabla = "";
        
            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
        
            if(isset($busqueda) && $busqueda != ""){
                $consulta_datos = $this->buscarBodegas("Datos",$inicio,$registros,$busqueda);
                $consulta_total = $this->buscarBodegas("Contar",$inicio,$registros,$busqueda);
            } else {
                $consulta_datos = $this->buscarBodegas("Datos",$inicio, $registros);
                $consulta_total = $this->buscarBodegas("Contar");
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
                            <td>
                                <a href="' . APP_URL . 'cellarUpdate/' . $rows['ID'] . '/" class="btn btn-success btn-sm rounded">Actualizar</a>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/cellarAjax.php" method="POST" autocomplete="off">
                                    <input type="hidden" name="modulo_bodega" value="eliminar">
                                    <input type="hidden" name="bodega_id" value="' . $rows['ID'] . '">
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
                $tabla .= '<p class="text-end">Mostrando bodegas <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
        
            return $tabla;
        }

        # Controlador eliminar bodega #
        public function eliminarBodegaControlador(){
            $id = $this->limpiarCadena($_POST['bodega_id']);

            # Verificando bodega existe #
            $checkBodega = $this->seleccionarDatos("Unico","Bodegas","ID",$id);
            if($checkBodega->rowCount()<1){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado la bodega en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $bodega = $checkBodega->fetch();
            }

            # Verificando productos en bodega #
            $checkProductosBodega = $this->seleccionarDatos("Unico","ProductoEnBodega","ID_Bodega",$id);
            if($checkProductosBodega->rowCount()>=1){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Bodega no puede ser eliminada",
                    "texto" => "La bodega ".$bodega['Nombre']." tiene productos",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $eliminarBodega = $this->eliminarBodega($id);
            if($eliminarBodega->rowCount()==1){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Bodega eliminada",
                    "texto" => "La bodega ".$bodega['Nombre']." se eliminó con éxito",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo eliminar la bodega ".$bodega['Nombre'].", por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador actualizar bodega #
        public function actualizarBodegaControlador(){
            $id = $this->limpiarCadena($_POST['bodega_id']);

            # Verificando bodega #
            $datosBodega = $this->seleccionarDatos("Unico","Bodegas","ID",$id);
            if($datosBodega->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado la bodega en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $datosBodega = $datosBodega->fetch();
            }
            
            # Almacenando datos #
		    $nombre = $this->limpiarCadena($_POST['bodega_nombre']);

		    # Verificando campos obligatorios #
		    if($nombre==""){
		        $alerta = [
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

            # Verificando bodega #
            if($datosBodega['Nombre']!=$nombre){
                $checkBodega = $this->seleccionarDatos("Unico","Bodegas","Nombre",$nombre);
                if($checkBodega->rowCount()!=0){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "La bodega '$nombre' que acaba de ingresar, ya se encuentra registrada",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            $nuevaBodega = new cellarModel($nombre,$id);
            $actualizarBodega = $nuevaBodega->actualizarBodega();

            if($actualizarBodega){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Bodega actualizada",
                    "texto" => "La bodega ".$nombre." se actualizó con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo actualizar la bodega, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador registrar producto en bodega #
        public function registrarProductoEnBodegaControlador(){
            # Almacenando datos #
            $ID_Bodega = intval($this->limpiarCadena($_POST['bodega_bodega']));
            $productos = ($_POST['bodega_producto']);
            $cantidades = ($_POST['bodega_productoCantidad']);
        
            # Verificando campos obligatorios
            if (empty($ID_Bodega) || empty($productos) || empty($cantidades)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Agregar registros a ProductoEnBodega #
            foreach ($productos as $key => $producto) {
                $ID_Producto = intval($this->limpiarCadena($producto));
                $Cantidad = intval($this->limpiarCadena($cantidades[$key]));
                $this->registrarProductoEnBodega($ID_Producto,$ID_Bodega,$Cantidad);
            }

            $Buscarbodega = $this->seleccionarDatos("Unico","Bodegas","ID",$ID_Bodega);
            $bodega = $Buscarbodega->fetch();
            
            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Ingreso a bodega registrado",
                "texto" => "Los productos fueron ingresados a la bodega ".$bodega['Nombre']."",
                "icono" => "success"
            ];

            return json_encode($alerta);
        }
    }