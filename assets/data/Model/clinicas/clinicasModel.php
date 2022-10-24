<?php 
date_default_timezone_set("America/Mexico_City");
	class Clinica {
		public function listar_validaciones($estatus = 'pendiente'){
			$conexion = new Conexion();
			$con = $conexion->conectar()["conexion"];
            if($estatus == 'verificado'){
                $sql = "SELECT afc.id_prospecto, afc.id_afiliado, afc.email, afc.celular, afc.pais, afc.estado, afc.foto, vrf.* FROM afiliados_conacon afc 
                        JOIN afiliados_verificados vrf ON vrf.id_prospecto = afc.id_prospecto";
            }else{
                $sql = "SELECT prs.nombre, prs.aPaterno, prs.aMaterno, afc.id_prospecto, afc.id_afiliado, afc.email, afc.celular, afc.pais, afc.estado, afc.foto FROM afiliados_conacon afc 
                        JOIN a_prospectos prs ON prs.idAsistente = afc.id_prospecto
                        WHERE afc.verificacion = 1 AND afc.id_prospecto NOT IN (SELECT id_prospecto FROM `afiliados_verificados`)";
            }
            
            $statement = $con->prepare($sql);
            $statement->execute();

			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

	}
?>