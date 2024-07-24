<?php
    namespace app\controllers;
    use app\models\movementModel;

    class movementController extends movementModel{

        public function __construct(){}

        # Controlador registrar movimiento #
        public function registrarMovimientoControlador(){
            # Almacenando datos #
            $fecha = date('Y-m-d H:i:s');
            $bodegaOrigen = intval($this->limpiarCadena($_POST['movimiento_bodegaOrigen']));
            $bodegaDestino = intval($this->limpiarCadena($_POST['movimiento_bodegaDestino']));
            $usuario = intval($_SESSION['id']);
            $productos = ($_POST['movimiento_producto']);
            $cantidades = ($_POST['movimiento_cantidadProducto']);
        
            # Verificando campos obligatorios
            if (empty($bodegaOrigen) || empty($bodegaDestino) || empty($productos) || empty($cantidades)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            # Verificar stock producto en bodegaOrigen #
            foreach ($productos as $key => $producto) {
                $ID_Producto = $this->limpiarCadena($producto);
                $Cantidad = intval($this->limpiarCadena($cantidades[$key]));
                $stockProductoBodegaOrigen = $this->verificarStock($bodegaOrigen, $ID_Producto, $Cantidad);
                if(!$stockProductoBodegaOrigen){
                $buscaProducto = $this->seleccionarDatos("Unico","Productos","ID",$ID_Producto);
                $producto = $buscaProducto->fetch();
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No hay suficiente stock del producto '".$producto['Nombre']."' en la bodega de origen",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
                }
            }

            # Registrar movimiento #
            $nuevoMovimiento = new movementModel($fecha, $bodegaOrigen, $bodegaDestino, $usuario);
            $registrarMovimiento = $nuevoMovimiento->registrarMovimiento();
        
            # Verificando registro movimiento #
            if($registrarMovimiento->rowCount() == 1){
                # Obtener id movimiento #
                $ID_Movimiento = $this->obtenerUltimoMovimiento();
        
                # Agregar registros a ProductoEnMovimiento y actualizar ProductoEnBodega #
                foreach ($productos as $key => $producto) {
                    $ID_Producto = $this->limpiarCadena($producto);
                    $Cantidad = intval($this->limpiarCadena($cantidades[$key]));
                    
                    # Registrar ProductoEnMovimiento #
                    $nuevoProductoEnMovimiento = [
                        'ID_Movimiento' => $ID_Movimiento,
                        'ID_Producto' => $ID_Producto,
                        'Cantidad' => $Cantidad
                    ];
                    $this->registrarProductoEnMovimiento($nuevoProductoEnMovimiento);

                    # Actualizar ProductoEnBodega para origen y destino #
                    $this->actualizarProductoEnBodega($bodegaOrigen, $ID_Producto, -$Cantidad);
                    $this->actualizarProductoEnBodega($bodegaDestino, $ID_Producto, $Cantidad);
                }
        
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Movimiento registrado",
                    "texto" => "El movimiento se registró con éxito",
                    "icono" => "success"
                ];
            } else {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No se pudo registrar el movimiento, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador listar movimiento #
        public function listarMovimientoControlador($pagina, $registros, $url, $busqueda){

            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
        
            $url = $this->limpiarCadena($url);
            $url = APP_URL.$url."/";
        
            $busqueda = $this->limpiarCadena($busqueda);
            $tabla = "";
        
            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
        
            if(isset($busqueda) && $busqueda != ""){
                $consulta_datos = $this->buscarMovimientos("Datos",$inicio,$registros,$busqueda);
                $consulta_total = $this->buscarMovimientos("Contar",$inicio,$registros,$busqueda);
            } else {
                $consulta_datos = $this->buscarMovimientos("Datos",$inicio, $registros);
                $consulta_total = $this->buscarMovimientos("Contar");
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
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Bodega Origen</th>
                            <th class="text-center">Bodega Destino</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Opciones</th>
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
                            <td>' . $rows['Fecha'] . '</td>
                            <td>' . $rows['NombreBodegaOrigen'] . '</td>
                            <td>' . $rows['NombreBodegaDestino'] . '</td>
                            <td>' . $rows['NombreUsuario'] . '</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm rounded" data-bs-toggle="modal" data-bs-target="#productModal" onclick="loadProducts('.$rows['ID'].')">Ver Productos</button>
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
                $tabla .= '<p class="text-end">Mostrando movimientos <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
        
            return $tabla;
        }

        # Controlador listar productos por movimiento #
        public function listarProductosPorMovimiento($ID_Movimiento) {
            $ID_Movimiento = $this->limpiarCadena($ID_Movimiento);
        
            $productosPorMovimiento = $this->buscarProductosPorMovimiento($ID_Movimiento);
        
            $productos = $productosPorMovimiento->fetchAll();
        
            $tabla = '
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Producto</th>
                            <th class="text-center">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        
            foreach ($productos as $producto) {
                $tabla .= '
                    <tr class="text-center">
                        <td>' . $producto['NombreProducto'] . '</td>
                        <td>' . $producto['Cantidad'] . '</td>
                    </tr>
                ';
            }
        
            $tabla .= '</tbody></table></div>';
        
            return $tabla;
        }

    }