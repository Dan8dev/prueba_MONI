 <?php

require_once( "cx.php" );

function dataMedico( $idmedico, $cx ){
    $resultado = $cx->query("SELECT * 
    FROM pm_medicos 
    WHERE id = $idmedico");
    return $resultado->fetch_assoc();
}//fin dataAlumno

function listarExpedientes( ){
	$con = conect();

	$sql = "SELECT pm_expedientes.idexp, pm_expedientes.idpm, pm_expedientes.paciente, pm_expedientes.factualizacion, pm_expedientes.estado, pm_expedientes.idsitio, 
	pm_procedimientos.nombre AS pnombre, pm_sitios.nombre AS psitio, idmedico, idtutor, idalumno    
	FROM pm_expedientes, pm_procedimientos, pm_sitios 
	WHERE (idtutor = ".$_SESSION["usuario"]["idPersona"]." OR idmedico = ".$_SESSION["usuario"]["idPersona"].") AND pm_procedimientos.idpm = pm_expedientes.idpm AND pm_sitios.idsitio = pm_expedientes.idsitio ORDER BY factualizacion DESC";
	
	$resultado = $con->query($sql);
	return $resultado;
}//listarDocumentosAlumnos()

function info_nota( $alumno, $expediente ){
    $archivo = '../alumnos/apm/pm_files/'.$alumno.'/nota_'.$expediente.'.txt';
    $gestor = fopen($archivo, "r");
    $contenido = fread($gestor, filesize($archivo));
    $contenido = json_decode( $contenido );
    fclose($gestor);
    return $contenido;
}//historiaClinica

function colorEstados( $estado ){
    switch ($estado){
        case 1: return "antiquewhite"; //Enviado: Beige
        case 2: return "lightblue"; //En revisión: Azul
        case 3: return "lightpink"; //Con observaciones: Rosa
        case 4: return "#FFDF9E"; //Aceptado: Naranja
        case 5: return "lightsalmon"; //Pospuesto: Rojo claro
        case 6: return "#D7BDE2"; //En proceso: verde claro
        case 7: return "#C8E6C9"; //Finalizado: verde
        case 8: return "#F1B2A5"; //Cancelado: Rojo
        default: return "transparent";
    }//fin switch
}//Fin colorEstados

function nombreEstados( $estado ){
    switch ($estado){
        //Calidad
        case 1: return "En espera de revisión,<br>Médico de Calidad";//Enviado";
        case 2: return "En revisión";
        case 3: return "Con observaciones,<br>En espera de Revisión Alumno";
        case 4: return "En espera,<br>aceptación de tutor";
        //Tutoría
        case 5: return "Pospuesto";
        case 6: return "En proceso";
        case 7: return "Cerrado";
        case 8: return "Cancelado/Rechazado";
        default: return "-";
    }//fin switch
}//Fin nombreEstados

function agregarClaseForm( $idMaestro ){
	$con = conect();
	$sql = "SELECT maestros_cursos.*, a_carreras.idCarrera AS idCurso, a_carreras.nombre AS nombreCurso FROM maestros_cursos, a_carreras WHERE id_maestro =".$idMaestro." AND a_carreras.idCarrera = maestros_cursos.id_curso";
	$resultado = $con->query($sql);
	return $resultado;
}//Fin agregarTareaForm

function agregarTareaForm( $idMaestro ){
	$con = conect();
	$sql = "SELECT clases.idClase, clases.idMateria, clases.titulo AS nombre, a_carreras.idCarrera, a_carreras.nombre AS nombreCurso  
	FROM clases, a_carreras 
	WHERE clases.idMateria = a_carreras.idCarrera AND idMaestro = $idMaestro;";
	$resultado = $con->query($sql);
	return $resultado;
}//Fin agregarTareaForm

function listarTareasMaestros( $idMaestro ){
	$con = conect();
	$sql = "SELECT clases.idClase AS idClase, clases.idMateria AS idCurso, clases.titulo AS nombreClase, clases_tareas.fecha_limite, clases_tareas.idTareas AS idTareas, clases_tareas.titulo AS tituloTarea, a_carreras.nombre AS nombreCurso, clases_tareas.descripcion 
	FROM clases, clases_tareas, a_carreras 
	WHERE clases.idClase = clases_tareas.idClase AND clases_tareas.idMaestro = $idMaestro AND clases.idMaestro = $idMaestro AND a_carreras.idCarrera = clases.idMateria ORDER BY idTareas DESC;";
	$resultado = $con->query($sql);
	return $resultado;
}//listarTareasMaestros()

function listarClasesMaestros( $idMaestro ){
	$con = conect();
	$sql = "SELECT clases.idClase, clases.idMateria, clases.titulo AS nombre, a_carreras.idCarrera, a_carreras.nombre AS nombreCurso  
	FROM clases, a_carreras  
	WHERE clases.idMateria = a_carreras.idCarrera AND clases.idMaestro = $idMaestro ORDER BY clases.idClase DESC;";
	$resultado = $con->query($sql);
	return $resultado;
}//listarTareasMaestros()

function historiaClinica( $alumno, $expediente ){
    //$archivo = 'pm_files/'.$_SESSION["usuario"]["idPersona"].'/historiaclinica_'.$idexp.'.txt';
    $archivo = '../alumnos/apm/pm_files/'.$alumno.'/historiaclinica_'.$expediente.'.txt';
    $gestor = fopen($archivo, "r");
    $contenido = fread($gestor, filesize($archivo));
    $contenido = json_decode( $contenido );
    //print_r( $contenido );
    fclose($gestor);
    return $contenido;
}//historiaClinica

function listarArchivos( $idexpediente, $cx, $folder ){
    $resultado = $cx->query("SELECT * FROM pm_archivos WHERE idexpediente = ".$idexpediente." ORDER BY nombre DESC" );
    
    if( $resultado->num_rows > 0 ){

        echo '<div class="list-group">';
        while( $fila = $resultado->fetch_assoc() ){
            echo '<a href="../alumnos/apm/pm_files/'.$folder.'/'.$fila['archivo'].'" title="Descargar"  target="_blank" class="list-group-item list-group-item-action"> <li class="fas fa-download"></li> <b>'.$fila['nombre'].'</b> ('.$fila['fecha'].')</a>';
        }
        echo '</div>';

        /*echo '
        <table id="datatable2" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="border-collapse: collapse; width: 100%;" role="grid" aria-describedby="datatable_info">
            <thead><tr>
                  <th><b>NOMBRE</b></th>
                  <th><b>ARCHIVO</b></th>
                  <th><b>FECHA</b></th>
                  <th><b>OPCIONES</b></th>
                </tr>
            </thead>

            <tbody>
            
            ';
                while( $fila = $resultado->fetch_assoc() ){
                    echo'
                    <tr>
                    <td>'.$fila['nombre'].'</td>
                    <td>'.$fila['archivo'].'</td>
                    <td>'.$fila['fecha'].'</td>
                    <td ><a href="../alumnos/apm/pm_files/'.$folder.'/'.$fila['archivo'].'" target="_blank" style="color:deepskyblue">VER</a></td>
                    </tr>';
                }//while

          echo '
            </tbody>
            </table>';*/

        
/*echo '<table id="datatable2" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="border-collapse: collapse; width: 100%;" role="grid" aria-describedby="datatable_info">
<thead>
<tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 97px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name</th><th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 156px;" aria-label="Position: activate to sort column ascending">Position</th><th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 64px;" aria-label="Office: activate to sort column ascending">Office</th><th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 29px;" aria-label="Age: activate to sort column ascending">Age</th><th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 71px;" aria-label="Start date: activate to sort column ascending">Start date</th><th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 52px; display: none;" aria-label="Salary: activate to sort column ascending">Salary</th></tr>
</thead>


<tbody>

<tr role="row" class="odd">
    <td tabindex="0" class="sorting_1">Airi Satou</td>
    <td style="">Accountant</td>
    <td style="">Tokyo</td>
    <td style="">33</td>
    <td style="">2008/11/28</td>
    <td style="display: none;">$162,700</td>
</tr><tr role="row" class="even">
    <td class="sorting_1" tabindex="0">Angelica Ramos</td>
    <td style="">Chief Executive Officer (CEO)</td>
    <td style="">London</td>
    <td style="">47</td>
    <td style="">2009/10/09</td>
    <td style="display: none;">$1,200,000</td>
</tr><tr role="row" class="odd">
    <td tabindex="0" class="sorting_1">Ashton Cox</td>
    <td style="">Junior Technical Author</td>
    <td style="">San Francisco</td>
    <td style="">66</td>
    <td style="">2009/01/12</td>
    <td style="display: none;">$86,000</td>
</tr><tr role="row" class="even">
    <td class="sorting_1" tabindex="0">Bradley Greer</td>
    <td style="">Software Engineer</td>
    <td style="">London</td>
    <td style="">41</td>
    <td style="">2012/10/13</td>
    <td style="display: none;">$132,000</td>
</tr><tr role="row" class="odd">
    <td class="sorting_1" tabindex="0">Brenden Wagner</td>
    <td style="">Software Engineer</td>
    <td style="">San Francisco</td>
    <td style="">28</td>
    <td style="">2011/06/07</td>
    <td style="display: none;">$206,850</td>
</tr><tr role="row" class="even">
    <td tabindex="0" class="sorting_1">Brielle Williamson</td>
    <td style="">Integration Specialist</td>
    <td style="">New York</td>
    <td style="">61</td>
    <td style="">2012/12/02</td>
    <td style="display: none;">$372,000</td>
</tr><tr role="row" class="odd">
    <td class="sorting_1" tabindex="0">Caesar Vance</td>
    <td style="">Pre-Sales Support</td>
    <td style="">New York</td>
    <td style="">21</td>
    <td style="">2011/12/12</td>
    <td style="display: none;">$106,450</td>
</tr><tr role="row" class="even">
    <td tabindex="0" class="sorting_1">Cedric Kelly</td>
    <td style="">Senior Javascript Developer</td>
    <td style="">Edinburgh</td>
    <td style="">22</td>
    <td style="">2012/03/29</td>
    <td style="display: none;">$433,060</td>
</tr><tr role="row" class="odd">
    <td class="sorting_1" tabindex="0">Charde Marshall</td>
    <td style="">Regional Director</td>
    <td style="">San Francisco</td>
    <td style="">36</td>
    <td style="">2008/10/16</td>
    <td style="display: none;">$470,600</td>
</tr><tr role="row" class="even">
    <td tabindex="0" class="sorting_1">Colleen Hurst</td>
    <td style="">Javascript Developer</td>
    <td style="">San Francisco</td>
    <td style="">39</td>
    <td style="">2009/09/15</td>
    <td style="display: none;">$205,500</td>
</tr></tbody>
</table>';*/


        }//IF

}//listarArchivos

function info_trans( $alumno, $expediente ){
    //$archivo = 'pm_files/'.$_SESSION["usuario"]["idPersona"].'/historiaclinica_'.$idexp.'.txt';
    $archivo = '../alumnos/apm/pm_files/'.$alumno.'/trans_'.$expediente.'.txt';
    $gestor = fopen($archivo, "r");
    $contenido = fread($gestor, filesize($archivo));
    $contenido = json_decode( $contenido );
    //print_r( $contenido );
    fclose($gestor);
    return $contenido;
}//historiaClinica

?>