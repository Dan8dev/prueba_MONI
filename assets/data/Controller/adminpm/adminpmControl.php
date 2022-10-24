<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/adminpm/adminpmModel.php';
    require_once '../../../../adminpm/utilidades.php';

    $adminpm = new AdminPM();
    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }

    switch($_POST['action']){
        
        case 'consultarMedicos':
            unset($_POST['action']);
            $loadMedicos = $adminpm->consultarMedicos();
            $data = Array();
            while($dato=$loadMedicos->fetchObject()){
                $tipo = $dato->tipo == 'M' ? 'M. CALIDAD' : 'M. TUTOR';
                $boton = $dato->estado == 1 ? '<button class="btn btn-primary" onclick="validarDesactivarMedico('.$dato->id.', 0)">Desactivar</button>': '<button class="btn btn-secondary" onclick="validarDesactivarMedico('.$dato->id.', 1)">Activar</button>';
                if( $dato->estado == 1 )
                    $boton = '<button class="btn btn-secondary" onclick="mostrarMedico('.$dato->id.')">Editar</button> '.$boton;
                $data[]=array(
                0=> $dato->apellidop.' '.$dato->apellidom.', '.$dato->nombre,
                1=> $dato->correo,
                2=> $tipo,
                3=> $boton
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

        case "agregarMedico":
            unset($_POST['action']);
            $agregar = $adminpm->agregarMedico( $_POST );
            echo json_encode($agregar);
            break;
        
        case "editarMedico":
            unset($_POST['action']);
            $editar = $adminpm->editarMedico( $_POST );
            echo json_encode($editar);
            break;

        case 'buscarMedico':
            unset($_POST['action']);
            $busMedico = $adminpm->buscarMedico($_POST['idBuscar']);
            echo json_encode($busMedico);
            break;

        case 'desactivarMedico':
            unset($_POST['action']);
            $del = $adminpm->desactivarMedico($_POST);
            echo json_encode($del);
            break;

        case 'consultarHospitales':
            unset($_POST['action']);
            $loadHospitales = $adminpm->consultarHospitales();
            $data = Array();
            while($dato=$loadHospitales->fetchObject()){
                $boton = $dato->estado == 1 ? '<button class="btn btn-primary" onclick="validarDesactivarHospital('.$dato->idsitio.', 0)">Desactivar</button>': '<button class="btn btn-secondary" onclick="validarDesactivarHospital('.$dato->idsitio.', 1)">Activar</button>';
                if( $dato->estado == 1 )
                    $boton = '<button class="btn btn-secondary" onclick="mostrarHospital('.$dato->idsitio.')">Editar</button> '.$boton;
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->direccion,
                2=> $boton
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

        case "agregarHospital":
            unset($_POST['action']);
            $agregar = $adminpm->agregarHospital( $_POST );
            echo json_encode($agregar);
            break;

        case 'buscarHospital':
            unset($_POST['action']);
            $busHospital = $adminpm->buscarHospital($_POST['idBuscar']);
            echo json_encode($busHospital);
            break;

        case "editarHospital":
            unset($_POST['action']);
            $editar = $adminpm->editarHospital( $_POST );
            echo json_encode($editar);
            break;

        case 'desactivarHospital':
            unset($_POST['action']);
            $del = $adminpm->desactivarHospital($_POST);
            echo json_encode($del);
            break;

        case 'consultarProcedimientos':
            unset($_POST['action']);
            $loadProcedimientos = $adminpm->consultarProcedimientos();
            $data = Array();
            while($dato=$loadProcedimientos->fetchObject()){
                $boton = $dato->estado == 1 ? '<button class="btn btn-primary" onclick="validarDesactivarProcedimiento('.$dato->idpm.', 0)">Desactivar</button>': '<button class="btn btn-secondary" onclick="validarDesactivarProcedimiento('.$dato->idpm.', 1)">Activar</button>';
                if( $dato->estado == 1 )
                    $boton = '<button class="btn btn-secondary" onclick="mostrarProcedimiento('.$dato->idpm.'); mostrarCarreras(\'listacarreras_e\');">Editar</button> '.$boton;
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->carrera_nombre,
                2=> '$'.number_format($dato->costo, 2, '.', ','), 
                3=> $dato->descripcion,
                4=> $boton
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

        case 'buscarCarreras':
            unset($_POST['action']);
            $loadCarreras = $adminpm->buscarCarreras();
            $data = Array();
            while($dato=$loadCarreras->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->idCarrera
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

        case "agregarProcedimiento":
            unset($_POST['action']);
            $agregar = $adminpm->agregarProcedimiento( $_POST );
            echo json_encode($agregar);
            break;

        case 'desactivarProcedimiento':
            unset($_POST['action']);
            $del = $adminpm->desactivarProcedimiento($_POST);
            echo json_encode($del);
            break;

        case 'buscarProcedimiento':
            unset($_POST['action']);
            $busProcedimiento = $adminpm->buscarProcedimiento($_POST['idBuscar']);
            echo json_encode($busProcedimiento);
            break;

        case "editarProcedimiento":
            unset($_POST['action']);
            $editar = $adminpm->editarProcedimiento( $_POST );
            echo json_encode($editar);
            break;

        case 'consultarExpedientes':
            unset($_POST['action']);
            $loadMedicos = $adminpm->consultarExpedientes();
            $data = Array();

            while($dato=$loadMedicos->fetchObject()){

                $tiempo = '<li class="far fa-clock" style="color:green; font-size:x-large; cursor: pointer;" title="A tiempo"></li><br><span style="font-size:small;"><b>A tiempo</b></span><br>';

                if( $dato->estado == 1 && $dato->atendido >= 72 )
                    $tiempo = '<li class="far fa-clock" style="color:red; font-size:x-large; cursor: pointer;" title="Atrasado"></li><br><span style="font-size:small;"><b>Atrasado</b></span><br>';
                
                if( $dato->estado == 8 )
                    $tiempo = '<li class="far fa-clock" style="color:#cccccc; font-size:x-large; cursor: pointer;" title="Cancelado"></li><br><span style="font-size:small;"><b>Cancelado</b></span><br>';

                if( $dato->estado == 7 )
                    $tiempo = '<li class="far fa-clock" style="color:#cccccc; font-size:x-large; cursor: pointer;" title="Cerrado"></li><br><span style="font-size:small;"><b>Cerrado</b></span><br>';

                if( $dato->estado == 3 && ($dato->atendido > 48 && $dato->atendido < 168) )
                    $tiempo = '<li class="far fa-clock" style="color:orange; font-size:x-large; cursor: pointer;" title="Por cancelarse"></li><br><span style="font-size:small;"><b>Por cancelarse</b></span><br>';

                if( $dato->estado == 4 && $dato->atendido > 48 )
                    $tiempo = '<li class="far fa-clock" style="color:red; font-size:x-large; cursor: pointer;" title="Atrasado"></li><br><span style="font-size:small;"><b>Atrasado</b></span><br>';

                $tiempo_m = $dato->atendido." hrs."; 
                if( $dato->atendido/24 >= 1 && $dato->atendido/24 < 30 ) $tiempo_m = round($dato->atendido/24)." días.";
                if( $dato->atendido/24 >= 30 && $dato->atendido/24 < 365 ) $tiempo_m = round(($dato->atendido/24)/30)." meses.";

                $data[]=array(
                0=> $dato->apellidoPaterno.' '.$dato->apellidoMaterno.', '.$dato->nombres,
                1=> $dato->paciente,
                2=> $dato->nompre_proc, 
                3=> $tiempo.' '.$dato->factualizacion.'<br> Hace '.$tiempo_m,
                4=> '<span style="padding: 5px; background-color:'.colorEstados($dato->estado).'">'.nombreEstados($dato->estado).'</span>',
                5=> '<a class="btn btn-secondary" href="mostrarExpediente.php?idexp='.$dato->idexp.'" target="_blank">Ver</a>'
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

            case 'dirAlumnos':
                unset($_POST['action']);
                $loadMedicos = $adminpm->dirAlumnos();
                $data = Array();
                while($dato=$loadMedicos->fetchObject()){
                    $data[]=array(
                    0=> $dato->apellidoPaterno.' '.$dato->apellidoMaterno.', '.$dato->nombres,
                    1=> $dato->correo,
                    2=> $dato->telefono,
                    3=> $dato->nombreCarrera,
                    4=> $dato->ngeneracion
                    );
                }
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count($data),
                    'iTotalDisplayRecords'=>count($data),
                    'aaData'=>$data
                );
                echo json_encode($result);
                break;

        case 'no_session':
            echo 'no_session';
            break;

        default:
        echo "noaction";
            break;
    }

}else{
	header('Location: ../../../../index.php');
}

?>