<?php
    namespace app\models;
    use \PDO;

    if(file_exists(__DIR__."/../../config/server.php")){
        require_once __DIR__."/../../config/server.php";
    }

    class mainModel{
        private $server = DB_SERVER;
        private $db = DB_NAME;
        private $user = DB_USER;
        private $pass = DB_PASS;

        protected function conectar(){
            $conexion = new PDO("mysql:host=".$this->server.";dbname=".$this->db, $this->user, $this->pass);
            $conexion->exec("SET NAMES 'utf8'");
            return $conexion;
        }

        protected function ejecutarConsulta($consulta){
            $sql = $this->conectar()->prepare($consulta);
            $sql->execute();
            return $sql;
        }

        public function limpiarCadena($cadena){
            $palabras = ["<script>","</script>","<script src","<script type=","SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>","--","^","<",">","==","=",";","::"];

            $cadena = trim($cadena);
			$cadena = stripslashes($cadena);

			foreach($palabras as $palabra){
				$cadena = str_ireplace($palabra, "", $cadena);
			}

			$cadena = trim($cadena);
			$cadena = stripslashes($cadena);

			return $cadena;
        }

        protected function guardarDatos($tabla,$datos){
            $query = "INSERT INTO $tabla (";

            $C = 0;
            foreach($datos as $dato){
                if($C>=1){ $query .= ","; }
                $query .= $dato["campo_nombre"];
                $C++;
            }
            $query .= ") VALUES(";

            $C = 0;
            foreach($datos as $dato){
                if($C>=1){ $query .= ","; }
                $query .= $dato["campo_marcador"];
                $C++;
            }
            $query .= ")";

            $sql = $this->conectar()->prepare($query);
            foreach($datos as $dato){
                $sql->bindParam($dato["campo_marcador"],$dato["campo_valor"]);
            }
            
            $sql->execute();
            return $sql;
        }

        public function seleccionarDatos($tipo,$tabla,$campo="",$valor=""){
            $tipo = $this->limpiarCadena($tipo);
            $tabla = $this->limpiarCadena($tabla);
            $campo = $this->limpiarCadena($campo);
            $valor = $this->limpiarCadena($valor);

            if($tipo=="Unico"){
                $sql = $this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo = :VALOR");
                $sql->bindParam(":VALOR",$valor);
            }elseif($tipo=="Normal"){
                $sql = $this->conectar()->prepare("SELECT * FROM $tabla");
            }

            $sql->execute();
            return $sql;       
        }

        protected function actualizarDatos($tabla,$datos,$id){
            $query = "UPDATE $tabla SET ";

            $C = 0;
            foreach($datos as $dato){
                if($C>=1){ $query .= ","; }
                $query .= $dato["campo_nombre"]."=".$dato["campo_marcador"];
                $C++;
            }
            $query .= " WHERE ID = :ID";

            $sql = $this->conectar()->prepare($query);
            foreach($datos as $dato){
                $sql->bindParam($dato["campo_marcador"],$dato["campo_valor"]);
            }
            $sql->bindParam(":ID",$id);

            $sql->execute();
            return $sql;
        }

        protected function eliminarRegistro($tabla,$campo,$valor){
            $sql = $this->conectar()->prepare("DELETE FROM $tabla WHERE $campo = :Valor");
            $sql->bindParam(":Valor",$valor);
            $sql->execute();
            return $sql;
        }

        protected function paginadorTablas($pagina,$numeroPaginas,$url,$botones){
            $paginador = '
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
            ';

            if($pagina<=1){
                $paginador .= '
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                ';
            }else{
                $paginador .= '
                    <li class="page-item"><a class="page-link" href="'.$url.($pagina-1).'/">Anterior</a></li>
                    <li class="page-item"><a class="page-link" href="'.$url.'1/">1</a></li>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                ';
            }

            $C = 0;
            for($i=$pagina; $i<=$numeroPaginas; $i++){
                if($C>=$botones){
                    break;
                }
                if($pagina==$i){
                    $paginador .= '
                        <li class="page-item active" aria-current="page"><span class="page-link">'.$i.'</span></li>
                    ';
                }else{
                    $paginador .= '
                        <li class="page-item"><a class="page-link" href="'.$url.$i.'/">'.$i.'</a></li>
                    ';
                }
                $C++;
            }

            if($pagina==$numeroPaginas){
                $paginador .= '
                                <li class="page-item disabled"><a class="page-link">Siguiente</a></li>
                            </ul>
                        </nav>
                    ';
            }else{
                $paginador .= '
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                <li class="page-item"><a class="page-link" href="'.$url.$numeroPaginas.'/">'.$numeroPaginas.'</a></li>
                                <li class="page-item"><a class="page-link" href="'.$url.($pagina+1).'/">Siguiente</a></li>
                            </ul>
                        </nav>
                    ';
            }

            return $paginador;
        }
    }