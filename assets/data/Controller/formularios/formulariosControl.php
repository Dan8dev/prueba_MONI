<?php
session_start();
if (isset($_POST["action"])) {
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/formularios/formulariosModel.php';
    $forms = new formularios();

    $accion = @$_POST["action"];

    switch ($accion) {
        case 'formularioHistoriaClinica':
            unset($_POST["action"]);
            $id = 1;
            $Informacion = $forms->getFormulario($id);
            $Secciones = $forms->seccionFormulario($id);
            $informacionGeneral = $forms->itemsSeccion(0)["data"];
            foreach ($Secciones["data"] as $info){
                if($info["idseccion"]!=0){
                    $componentes[$info["idseccion"]] = $forms->itemsSeccion($info["idseccion"])["data"];
                }
            }
            $forms->pdfHistoriaClinica($Secciones["data"], $componentes, $informacionGeneral);
            break;
        case 'loadFormsTable':
            unset($_POST["action"]);
            $formularios = $forms->loadFormsTable();
            $data = Array();
            while($dato=$formularios->fetchObject()){
                $data[]=array(
                    0=> $dato->Nombre,
                    1=> $dato->Descripcion,
                    2=> "<button class = 'btn btn-primary' onClick = 'formularioHistoriaClinica($dato->idform)''> Ver Formulario </button>"
                    );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;
        case 'informacionHistoriaClinica':
            //Crear una estructura que se pueda usar com json para formar los formularios dentro del html
            unset($_POST["action"]);
            $RespuestaForm=[];
            $structNombre = $forms->getFormulario($_POST["idformulario"]);
            //echo json_encode($RespuestaForm);
            $structSeccion = $forms->seccionFormulario($_POST["idformulario"])["data"];
            
            foreach($structSeccion as $Secciones){
                $StructItems[]= ["nombre"=>$Secciones["nombre"],$Secciones["idseccion"] => $forms->itemsSeccion($Secciones["idseccion"])["data"],"idseccion"=>$Secciones["idseccion"]];
            }
            $RespuestaForm = ["nombre"=>$structNombre["data"]["Nombre"],"secciones"=>$StructItems];
            echo json_encode($RespuestaForm);
            break;
        default:
            # code...
            break;
    }
}else {
    header('Location: ../../../../index.php');
}
