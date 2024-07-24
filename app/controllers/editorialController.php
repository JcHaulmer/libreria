<?php
    namespace app\controllers;
    use app\models\editorialModel;

    class EditorialController extends editorialModel{

        public function __construct(){}

        # Controlador registrar editorial #
        public function registrarEditorialControlador(){
            
            # Almacenando datos #
            $nombre = $this->limpiarCadena($_POST['editorial_nombre']);

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

            # Verificando editorial #
            $checkEditorial = $this->seleccionarDatos("Unico","Editoriales","Nombre",$nombre);
            if($checkEditorial->rowCount()!=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El autor '$nombre' que acaba de ingresar, ya se encuentra registrado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $nuevaEditorial = new editorialModel($nombre);
            
            $registrarEditorial = $nuevaEditorial->registrarEditorial();

            if($registrarEditorial->rowCount()==1){
                $alerta = [
                    "tipo" => "limpiar",
                    "titulo" => "Editorial registrada",
                    "texto" => "La editorial ".$nombre." se registró con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar la editorial, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador listar editorial #
        public function listarEditorialControlador($pagina, $registros, $url, $busqueda){

            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
        
            $url = $this->limpiarCadena($url);
            $url = APP_URL.$url."/";
        
            $busqueda = $this->limpiarCadena($busqueda);
            $tabla = "";
        
            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
        
            if(isset($busqueda) && $busqueda != ""){
                $consulta_datos = $this->buscarEditoriales("Datos",$inicio,$registros,$busqueda);
                $consulta_total = $this->buscarEditoriales("Contar",$inicio,$registros,$busqueda);
            } else {
                $consulta_datos = $this->buscarEditoriales("Datos",$inicio, $registros);
                $consulta_total = $this->buscarEditoriales("Contar");
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
                                <a href="' . APP_URL . 'editorialUpdate/' . $rows['ID'] . '/" class="btn btn-success btn-sm rounded">Actualizar</a>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/editorialAjax.php" method="POST" autocomplete="off">
                                    <input type="hidden" name="modulo_editorial" value="eliminar">
                                    <input type="hidden" name="editorial_id" value="' . $rows['ID'] . '">
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
                $tabla .= '<p class="text-end">Mostrando editoriales <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
        
            return $tabla;
        }

        # Controlador eliminar editorial #
        public function eliminarEditorialControlador(){
            $id = $this->limpiarCadena($_POST['editorial_id']);

            # Verificando editorial #
            $checkEditorial= $this->seleccionarDatos("Unico","Editoriales","ID",$id);
            if($checkEditorial->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado la editorial en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $editorial = $checkEditorial->fetch();
            }

            # Verificando editorial con producto #
            $checkProductosEditorial = $this->seleccionarDatos("Unico","Productos","ID_Editorial",$id);
            if($checkProductosEditorial->rowCount()>=1){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Editorial no puede ser eliminada",
                    "texto" => "La editorial ".$editorial['Nombre']." tiene productos asignados",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $eliminarEditorial = $this->eliminarEditorial($id);
            if($eliminarEditorial->rowCount()==1){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Editorial eliminada",
                    "texto" => "La editorial ".$editorial['Nombre']." se eliminó con éxito",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo eliminar la editorial ".$editorial['Nombre'].", por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador actualizar editorial #
        public function actualizarEditorialControlador(){
            $id = $this->limpiarCadena($_POST['editorial_id']);

            # Verificando editorial #
            $datosEditorial = $this->seleccionarDatos("Unico","Editoriales","ID",$id);
            if($datosEditorial->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado la editorial en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $datosEditorial = $datosEditorial->fetch();
            }
            
            # Almacenando datos#
		    $nombre = $this->limpiarCadena($_POST['editorial_nombre']);

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

            # Verificando editorial existe #
            if($datosEditorial['Nombre']!=$nombre){
                $checkEditorial = $this->seleccionarDatos("Unico","Editoriales","Nombre",$nombre);
                if($checkEditorial->rowCount()!=0){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "La editorial '$nombre' que acaba de ingresar, ya se encuentra registrado",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            $nuevaEditorial= new editorialModel($nombre,$id);
            $actualizarEditorial = $nuevaEditorial->actualizarEditorial();

            if($actualizarEditorial){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Editorial actualizada",
                    "texto" => "La editorial ".$nombre." se actualizó con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo actualizar la editorial, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }
    }