<?php
    namespace app\controllers;
    use app\models\userModel;

    class userController extends userModel{

        public function __construct(){}

        # Controlador registrar usuario #
        public function registrarUsuarioControlador(){
            
            # Almacenando datos #
            $nombre = $this->limpiarCadena($_POST['usuario_nombre']);
            $apellido = $this->limpiarCadena($_POST['usuario_apellido']);
            $usuario = $this->limpiarCadena($_POST['usuario_usuario']);
            $rol = $this->limpiarCadena($_POST['usuario_rol']);
            $clave1 = $this->limpiarCadena($_POST['usuario_clave_1']);
            $clave2 = $this->limpiarCadena($_POST['usuario_clave_2']);

            # Verificando campos obligatorios #
            if(empty($nombre) || empty($apellido) || empty($usuario) || empty($rol) || empty($clave1) || empty($clave2)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando usuario #
            $checkUsuario = $this->seleccionarDatos("Unico","Usuarios","Usuario",$usuario);
            if($checkUsuario->rowCount()!=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El usuario '$usuario' que acaba de ingresar, ya se encuentra registrado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando claves y encriptando #
            if($clave1!=$clave2){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "Las claves que acaba de ingresar no coinciden",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $clave = password_hash($clave1,PASSWORD_BCRYPT,["cost"=>10]);
            }

            $nuevoUsuario = new userModel($nombre,$apellido,$usuario,$rol,$clave);
            
            $registrarUsuario = $nuevoUsuario->registrarUsuario();

            if($registrarUsuario->rowCount()==1){
                $alerta = [
                    "tipo" => "limpiar",
                    "titulo" => "Usuario registrado",
                    "texto" => "El usuario ".$nombre." ".$apellido." se registró con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar el usuario, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador listar usuario #
        public function listarUsuarioControlador($pagina, $registros, $url, $busqueda){

            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
        
            $url = $this->limpiarCadena($url);
            $url = APP_URL.$url."/";
        
            $busqueda = $this->limpiarCadena($busqueda);
            $tabla = "";
        
            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
        
            if(isset($busqueda) && $busqueda != ""){
                $consulta_datos = $this->buscarUsuarios("Datos",$inicio,$registros,$busqueda);
                $consulta_total = $this->buscarUsuarios("Contar",$inicio,$registros,$busqueda);
            } else {
                $consulta_datos = $this->buscarUsuarios("Datos",$inicio, $registros);
                $consulta_total = $this->buscarUsuarios("Contar");
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
                            <th class="text-center">Apellido</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Rol</th>
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
                            <td>' . $rows['Apellido'] . '</td>
                            <td>' . $rows['Usuario'] . '</td>
                            <td>' . $rows['Rol'] . '</td>
                            <td>
                                <a href="' . APP_URL . 'userUpdate/' . $rows['ID'] . '/" class="btn btn-success btn-sm rounded">Actualizar</a>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/usuarioAjax.php" method="POST" autocomplete="off">
                                    <input type="hidden" name="modulo_usuario" value="eliminar">
                                    <input type="hidden" name="usuario_id" value="' . $rows['ID'] . '">
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
                $tabla .= '<p class="text-end">Mostrando usuarios <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
        
            return $tabla;
        }

        # Controlador eliminar usuario #
        public function eliminarUsuarioControlador(){
            $id = $this->limpiarCadena($_POST['usuario_id']);

            if($id==1){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No podemos eliminar el usuario principal del sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando usuario #
            $checkUsuario = $this->seleccionarDatos("Unico","Usuarios","ID",$id);
            if($checkUsuario->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado el usuario en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $usuario = $checkUsuario->fetch();
            }

            $eliminarUsuario = $this->eliminarUsuario($id);
            if($eliminarUsuario->rowCount()==1){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Usuario eliminado",
                    "texto" => "El usuario ".$usuario['Nombre']." ".$usuario['Apellido']." se eliminó con éxito",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo eliminar el usuario ".$usuario['Nombre']." ".$usuario['Apellido'].", por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        # Controlador actualizar usuario #
        public function actualizarUsuarioControlador(){
            $id = $this->limpiarCadena($_POST['usuario_id']);

            # Verificando usuario #
            $datosUsuario = $this->seleccionarDatos("Unico","Usuarios","ID",$id);
            if($datosUsuario->rowCount()<=0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No hemos encontrado el usuario en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $datosUsuario = $datosUsuario->fetch();
            }
            
            # Almacenando datos#
		    $nombre = $this->limpiarCadena($_POST['usuario_nombre']);
		    $apellido = $this->limpiarCadena($_POST['usuario_apellido']);
		    $usuario = $this->limpiarCadena($_POST['usuario_usuario']);
		    $rol = $this->limpiarCadena($_POST['usuario_rol']);
		    $clave1 = $this->limpiarCadena($_POST['usuario_clave_1']);
		    $clave2 = $this->limpiarCadena($_POST['usuario_clave_2']);

		    # Verificando campos obligatorios #
		    if($nombre=="" || $apellido=="" || $usuario=="" || $rol==""){
		        $alerta = [
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

            # Verificando claves y encriptando #
            if($clave1!="" || $clave2!=""){
			    	if($clave1!=$clave2){
						$alerta = [
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"Las nuevas claves que acaba de ingresar no coinciden",
							"icono"=>"error"
						];
						return json_encode($alerta);
						exit();
			    	}else{
			    		$clave = password_hash($clave1,PASSWORD_BCRYPT,["cost"=>10]);
			    	}
			    
			}else{
				$clave = $datosUsuario['Clave'];
            }

            # Verificando usuario #
            if($datosUsuario['Usuario']!=$usuario){
                $checkUsuario = $this->seleccionarDatos("Unico","Usuarios","Usuario",$usuario);
                if($checkUsuario->rowCount()!=0){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "El usuario '$usuario' que acaba de ingresar, ya se encuentra registrado",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            $nuevoUsuario = new userModel($nombre,$apellido,$usuario,$rol,$clave,$id);
            
            $actualizarUsuario = $nuevoUsuario->actualizarUsuario();

            if($actualizarUsuario){
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Usuario actualizado",
                    "texto" => "El usuario ".$nombre." ".$apellido." se actualizó con éxito",
                    "icono" => "success"
                ];
                
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo actualizar el usuario, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }
    }