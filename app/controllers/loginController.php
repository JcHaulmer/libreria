<?php
    namespace app\controllers;
    use app\models\mainModel;

    class loginController extends mainModel{

        # Controlador iniciar sesion #
        public function iniciarSesionControlador(){

            # Almacenando datos #
            $usuario = $this->limpiarCadena($_POST['login_usuario']);
            $clave = $this->limpiarCadena($_POST['login_clave']);

            # Verificando campos obligatorios #
            if(empty($usuario) || empty($clave)){
                echo "
                    <script>
                        Swal.fire({
                            icon: 'error', 
                            title: 'Ocurrio un error inesperado',
                            text: 'No has llenado todos los campos que son obligatorios',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>
                ";
            }else{
                # Verificando usuario #
                $checkUsuario = $this->seleccionarDatos("Unico","Usuarios","Usuario",$usuario);
                if($checkUsuario->rowCount()==1){
                    
                    $checkUsuario = $checkUsuario->fetch(); 

                    if($checkUsuario['Usuario']==$usuario && password_verify($clave,$checkUsuario['Clave'])){
                        $_SESSION['id'] = $checkUsuario['ID'];
                        $_SESSION['nombre'] = $checkUsuario['Nombre'];
                        $_SESSION['apellido'] = $checkUsuario['Apellido'];
                        $_SESSION['usuario'] = $checkUsuario['Usuario'];
                        $_SESSION['rol'] = $checkUsuario['Rol'];

                        if(headers_sent()){
                            echo "<script> window.location.href = '".APP_URL."dashboard/'; </script>";
                        }else{
                            header("Location: ".APP_URL."dashboard/");
                        }

                    }else{
                        echo "
                        <script>
                            Swal.fire({
                                icon: 'error', 
                                title: 'Ocurrio un error inesperado',
                                text: 'Usuario o clave incorrectos',
                                confirmButtonText: 'Aceptar'
                            });
                        </script>
                        ";
                    }
                }else{
                    echo "
                    <script>
                        Swal.fire({
                            icon: 'error', 
                            title: 'Ocurrio un error inesperado',
                            text: 'Usuario o clave incorrectos',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>
                    ";
                }
            }
        }

        # Controlador cerrar sesion #
        public function cerrarSesionControlador(){
            session_destroy();

            if(headers_sent()){
                echo "<script> window.location.href = '".APP_URL."login/'; </script>";
            }else{
                header("Location: ".APP_URL."login/");
            }
        }
    }