<?php
    namespace app\controllers;
    use app\models\reportModel;

    class reportController extends reportModel {

        public function listarProductosPorBodegaControlador($pagina, $registros, $url, $busqueda) {
            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
            $url = $this->limpiarCadena($url);
            $url = APP_URL.$url."/";
            $busqueda = $this->limpiarCadena($busqueda);
    
            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
    
            if (isset($busqueda) && $busqueda != "") {
                $consulta_datos = $this->buscarProductosPorBodega("Datos", $inicio, $registros, $busqueda);
                $consulta_total = $this->buscarProductosPorBodega("Contar", $inicio, $registros, $busqueda);
            } else {
                $consulta_datos = $this->buscarProductosPorBodega("Datos", $inicio, $registros);
                $consulta_total = $this->buscarProductosPorBodega("Contar");
            }
    
            $datos = $consulta_datos->fetchAll();
            $total = (int) $consulta_total->fetchColumn();
            $numeroPaginas = ceil($total / $registros);
    
            $tabla = '
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Bodega</th>
                            <th class="text-center">Tipo de Producto</th>
                            <th class="text-center">Cantidad de Productos</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
    
            if ($total >= 1 && $pagina <= $numeroPaginas) {
                foreach ($datos as $rows) {
                    $tabla .= '
                        <tr class="text-center">
                            <td>' . $rows['Bodega'] . '</td>
                            <td>' . $rows['TipoProducto'] . '</td>
                            <td>' . $rows['CantidadProductos'] . '</td>
                        </tr>
                    ';
                }
            } else {
                if ($total >= 1) {
                    $tabla .= '
                        <tr class="text-center">
                            <td colspan="3">
                                <a href="' . $url . '1/" class="btn btn-link btn-sm mt-4 mb-4">
                                    Haga clic acá para recargar el listado
                                </a>
                            </td>
                        </tr>
                    ';
                } else {
                    $tabla .= '
                        <tr class="text-center">
                            <td colspan="3">
                                No hay registros en el sistema
                            </td>
                        </tr>
                    ';
                }
            }
    
            $tabla .= '</tbody></table></div>';
    
            if ($total > 0 && $pagina <= $numeroPaginas) {
                $tabla .= '<p class="text-end">Mostrando registros <strong>' . (($inicio + 1)) . '</strong> al <strong>' . ($inicio + count($datos)) . '</strong> de un <strong>total de ' . $total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
    
            return $tabla;
        }
    
        public function listarMovimientosControlador($pagina, $registros, $url, $busqueda) {
            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
            $url = $this->limpiarCadena($url);
            $url = APP_URL.$url."/";
            $busqueda = $this->limpiarCadena($busqueda);
    
            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
    
            if (isset($busqueda) && $busqueda != "") {
                $consulta_datos = $this->buscarMovimientos("Datos", $inicio, $registros, $busqueda);
                $consulta_total = $this->buscarMovimientos("Contar", $inicio, $registros, $busqueda);
            } else {
                $consulta_datos = $this->buscarMovimientos("Datos", $inicio, $registros);
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
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Bodega Origen</th>
                            <th class="text-center">Bodega Destino</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Cantidad de Productos</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
    
            if ($total >= 1 && $pagina <= $numeroPaginas) {
                foreach ($datos as $rows) {
                    $tabla .= '
                        <tr class="text-center">
                            <td>' . $rows['Fecha'] . '</td>
                            <td>' . $rows['BodegaOrigen'] . '</td>
                            <td>' . $rows['BodegaDestino'] . '</td>
                            <td>' . $rows['Usuario'] . '</td>
                            <td>' . $rows['CantidadProductos'] . '</td>
                        </tr>
                    ';
                }
            } else {
                if ($total >= 1) {
                    $tabla .= '
                        <tr class="text-center">
                            <td colspan="5">
                                <a href="' . $url . '1/" class="btn btn-link btn-sm mt-4 mb-4">
                                    Haga clic acá para recargar el listado
                                </a>
                            </td>
                        </tr>
                    ';
                } else {
                    $tabla .= '
                        <tr class="text-center">
                            <td colspan="5">
                                No hay registros en el sistema
                            </td>
                        </tr>
                    ';
                }
            }
    
            $tabla .= '</tbody></table></div>';
    
            if ($total > 0 && $pagina <= $numeroPaginas) {
                $tabla .= '<p class="text-end">Mostrando registros <strong>' . (($inicio + 1)) . '</strong> al <strong>' . ($inicio + count($datos)) . '</strong> de un <strong>total de ' . $total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
    
            return $tabla;
        }
    
    }