<?php
    namespace app\controllers;
    use app\models\autorModel;

    class autorController extends autorModel{

        public function __construct(){}

        # Controlador registrar autor #
        public function registrarAutorControlador(){
            
            # Almacenando datos #
            $nombre = $this->limpiarCadena($_POST['autor_nombre']);

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

            # Verificando autor #
            $checkAutor = $this->seleccionarDatos("Unico","Autores","Nombre",$nombre);
            if($checkAutor->rowCount()!=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El autor '$nombre' que acaba de ingresar, ya se encuentra registrado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $nuevoAutor = new autorModel($nombre);
            
            $registrarAutor = $nuevoAutor->registrarAutor();

            if($registrarAutor->rowCount()==1){
                $alerta = [
                    "tipo" => "limpiar",
                    "titulo" => "Autor registrado",
                    "texto" => "El autor ".$nombre." se registró con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar el autor, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador listar autor #
        public function listarAutorControlador($pagina, $registros, $url, $busqueda){

            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
        
            $url = $this->limpiarCadena($url);
            $url = APP_URL.$url."/";
        
            $busqueda = $this->limpiarCadena($busqueda);
            $tabla = "";
        
            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
        
            if(isset($busqueda) && $busqueda != ""){
                $consulta_datos = $this->buscarAutores("Datos",$inicio,$registros,$busqueda);
                $consulta_total = $this->buscarAutores("Contar",$inicio,$registros,$busqueda);
            } else {
                $consulta_datos = $this->buscarAutores("Datos",$inicio, $registros);
                $consulta_total = $this->buscarAutores("Contar");
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
                                <a href="' . APP_URL . 'autorUpdate/' . $rows['ID'] . '/" class="btn btn-success btn-sm rounded">Actualizar</a>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/autorAjax.php" method="POST" autocomplete="off">
                                    <input type="hidden" name="modulo_autor" value="eliminar">
                                    <input type="hidden" name="autor_id" value="' . $rows['ID'] . '">
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
                $tabla .= '<p class="text-end">Mostrando autores <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
        
            return $tabla;
        }

        # Controlador eliminar autor #
        public function eliminarAutorControlador(){
            $id = $this->limpiarCadena($_POST['autor_id']);

            # Verificando autor existe #
            $checkAutor= $this->seleccionarDatos("Unico","Autores","ID",$id);
            if($checkAutor->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado el autor en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $autor = $checkAutor->fetch();
            }

            # Verificando autor con producto #
            $checkProductosAutor = $this->seleccionarDatos("Unico","Productos","ID_Autor",$id);
            if($checkProductosAutor->rowCount()>=1){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Autor no puede ser eliminado",
                    "texto" => "El autor ".$autor['Nombre']." tiene productos asignados",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            $eliminarAutor = $this->eliminarAutor($id);
            if($eliminarAutor->rowCount()==1){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Autor eliminado",
                    "texto" => "El autor ".$autor['Nombre']." se eliminó con exito",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo eliminar el autor ".$autor['Nombre'].", por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador actualizar autor #
        public function actualizarAutorControlador(){
            $id = $this->limpiarCadena($_POST['autor_id']);

            # Verificando autor #
            $datosAutor = $this->seleccionarDatos("Unico","Autores","ID",$id);
            if($datosAutor->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado el autor en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $datosAutor = $datosAutor->fetch();
            }
            
            # Almacenando datos#
		    $nombre = $this->limpiarCadena($_POST['autor_nombre']);

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

            # Verificando autor #
            if($datosAutor['Nombre']!=$nombre){
                $checkAutor = $this->seleccionarDatos("Unico","Autores","Nombre",$nombre);
                if($checkAutor->rowCount()!=0){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "El autor '$nombre' que acaba de ingresar, ya se encuentra registrado",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            $nuevoAutor = new autorModel($nombre,$id);
            $actualizarAutor = $nuevoAutor->actualizarAutor();

            if($actualizarAutor){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Autor actualizado",
                    "texto" => "El autor ".$nombre." se actualizo con exito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo actualizar el autor, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }
    }