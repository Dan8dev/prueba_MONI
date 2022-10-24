<?php
$cx = conect();
/*$resultado = $cx->query("SELECT pm_expedientes.*, a_alumnos.id_alumno, a_alumnos.nombres AS nalumno, a_alumnos.apellidoPaterno 
AS palumno, a_alumnos.apellidoMaterno AS malumno FROM pm_expedientes, a_alumnos WHERE idexp = ".$_GET['idexp']." 
AND pm_expedientes.idalumno = a_alumnos.id_alumno");*/
$resultado = $cx->query("SELECT pm_expedientes.*, pm_expedientes.idalumno AS id_alumno, afiliados_conacon.id_afiliado, afiliados_conacon.nombre AS nalumno, afiliados_conacon.apaterno
AS palumno, afiliados_conacon.amaterno AS malumno FROM pm_expedientes, afiliados_conacon WHERE idexp = ".$_GET['idexp']."
AND pm_expedientes.idalumno = afiliados_conacon.id_afiliado");
$detalle = $resultado->fetch_assoc();

switch( $_GET['tk'] ){
    case 'c': $titulom = "MÉDICO DE CALIDAD"; break;
    case 't': $titulom = "TUTOR"; break;
    default: $titulom = "MÉDICO/TUTOR"; break;
}
?>

<script>

var numeroDeInput = 0;

function GuardarCambios(){
    document.getElementById( 'guardar' ).disabled = false;
}

function AdjuntarOtroArchivo() {
    numeroDeInput++;
    document.getElementById( 'fd'+numeroDeInput ).style.display = "block";
    document.getElementById( 'f'+numeroDeInput ).required = true;
    document.getElementById( 'fn'+numeroDeInput ).required = true;
    document.getElementById( 'layer_archivos' ).style.display = "block";
    document.getElementById( 'quitar' ).style.display = "";
}//AdjuntarOtroArchivo

function QuitarTodosArchivos( total ) {
    for( i = 1; i <= total; i++ ){
        document.getElementById( 'f'+i ).value = "";
        document.getElementById( 'fn'+i ).value = "";
        document.getElementById( 'f'+i ).required = false;
        document.getElementById( 'fn'+i ).required = false;
        document.getElementById( 'fd'+i ).style.display = "none";
    }

    document.getElementById( 'quitar' ).style.display = "none";
    numeroDeInput = 0;
}//QuitarTodosArchivos

function QuitarArchivo( num ) {
        document.getElementById('f'+num ).value = "";
        document.getElementById( 'f'+num ).required = false;
        document.getElementById( 'fn'+num ).required = false;
        document.getElementById('fn'+num ).value = "";
        document.getElementById( 'fd'+num ).style.display = "none";
}//QuitarArchivo

function fileValidation( $nombre ){
    var fileInput = document.getElementById( $nombre );
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.pdf)$/i;
    var fileInput = document.getElementById($nombre);
    var files = fileInput.files;
    
    if(!allowedExtensions.exec(filePath) || files[0].size > 2097152 ){
        alert('El archivo debe pesar máximo 2Mb y ser PDF, JPG o PNG.');
        fileInput.value = '';
        filePath.value='';
        return false;
    }
    GuardarCambios();
}//FileValidation

function agregar_comentario(){
    cn = document.getElementById( 'cnuevo' );
    if( cn.value == "" ) return;
    c = document.getElementById( 'comentarios' );  
    time = new Date();
    time_text = '['+time.getDate()+"-"+(time.getMonth()+1)+"-"+time.getFullYear()+" "+time.getHours()+":"+time.getMinutes()+":"+time.getSeconds()+"] ";
    c.value = time_text + "-<?=$titulom?> DICE: " + cn.value +'\n'+ c.value;
    cn.value = "";
    GuardarCambios();
}//agregar_comentario

function requerimientos( $valor ){
    document.getElementById( 'tcancelacion' ).required = $valor;
}
</script>

<div class="tab-content br-profile-body">
          <div class="tab-pane fade active show" id="posts">
            <div class="row">

                <div class="col-lg-12 mg-t-20 mg-lg-t-0">
                <div class="card pd-10 pd-xs-30 shadow-base bd-0">
                  <h1>Detalles de Práctica Médica #<?=$_GET['idexp']?></h1>

                  <h4>Alumno: <?=$detalle['palumno'].' '.$detalle['malumno'].', '.$detalle['nalumno']?> (<?=$detalle['id_alumno']?>)</h4>                            
                  <h4>Creación: <?=$detalle['fcreacion']?> | Actualización: <?=$detalle['factualizacion']?> </h4>                            
                  
                                <form id="form1" method="post" action="pm_detallestm_p.php?idexp=<?=$_GET['idexp']?>&tk=<?=$_GET['tk']?>" enctype="multipart/form-data">

                                                                <?php
                                                                $resultado = $cx->query("SELECT pm_procedimientos.nombre AS nombrep, pm_procedimientos.costo AS costop 
                                                                FROM pm_procedimientos, pm_expedientes WHERE pm_procedimientos.idpm = ".$detalle['idpm']." AND pm_expedientes.idexp = ".$_GET['idexp']);
                                                                $fila = $resultado->fetch_assoc();
                                                                ?>

                                                                <div class="card bd-0">
                                                                <div class="card-header bg-light">
                                                                    <h5><b>Procedimiento a realizar:</b> <?=$fila['nombrep'];?><br></h5>
                                                                    <?php $proc_nombre = $fila['nombrep']; ?>
                                                                
                                                                <!--REVISIÓN MÉDICO DE CALIDAD ASIGNADO-->
                                                                <?php
                                                                $medico = $cx->query("SELECT * FROM pm_medicos WHERE id = ".$detalle['idmedico']);
                                                                $fmedico = $medico->fetch_assoc();
                                                                ?>
                                                                <b>Médico de calidad asignado:</b> 
                                                                <?php
                                                                    if( $medico->num_rows > 0 ){
                                                                ?>
                                                                <?=$fmedico['nombre']?> <?=$fmedico['apellidop']?> <?=$fmedico['apellidom']?><br>

                                                                <?php }else{ echo "Sin Asignar"; } ?>
                                                                
                                                                <!--REVISIÓN TUTOR ASIGNADO-->
                                                                <?php
                                                                $medico = $cx->query("SELECT * FROM pm_medicos WHERE id = ".$detalle['idtutor']);
                                                                $fmedico = $medico->fetch_assoc();
                                                                ?>
                                                                
                                                                <b>Médico tutor asignado:</b> 
                                                                <?php
                                                                    if( $medico->num_rows > 0 ){
                                                                ?>
                                                                <?=$fmedico['nombre']?> <?=$fmedico['apellidop']?> <?=$fmedico['apellidom']?><br>

                                                                <?php }else{ 
                                                                    echo "Sin Asignar";                                                                     
                                                                    } ?>

                                                                </div><!-- card-header -->

                                                                <!--VERIFICACIÓN Y CAMBIO DE ESTADO-->
                                                                <div style="border-radius:5px; padding: 10px; background-color:<?=colorEstados( $detalle['estado'] )?>">
                                                                    <h5>Estado actual: <?=nombreEstados( $detalle['estado'] )?></h5>
                                                                    
                                                                    <?php if( $detalle['estado'] < 7 ){
                                                                        switch( $_GET['tk']  ){ 
                                                                            case 'c': $li = $detalle['estado']; $ls = 4; break; //Sólo soy médico de calidad
                                                                            case 't': $li = $detalle['estado']; $ls = 7; break; //Sólo soy tutor
                                                                            default:  $li = $detalle['estado']; $ls = 7;
                                                                        }
                                                                    ?>
                                                                    <?php if( $li < $ls ){ ?>
                                                                    <b>Cambiar estado a:</b>
                                                                        <select id="estado" name="estado" class="form-control select" data-placeholder="Seleccione una opción" onChange="javascript:GuardarCambios();" 
                                                                        <?php if( $_GET['tk'] == 't' && $detalle['estado'] < 4 ) echo "disabled"; ?> >
                                                                            <?php
                                                                                for( $i = $li; $i <= $ls; $i++ ){
                                                                                    echo '<option value="'.$i.'"';
                                                                                    if( $detalle['estado'] == $i ) echo " selected";
                                                                                    echo '>'.nombreEstados( $i ).'</option>';
                                                                                }//fin for
                                                                                
                                                                            ?>
                                                                        </select><br>
                                                                    <?php }?>
                                                                    <?php }?>

                                                                </div> <!--FIN VERIFICACIÓN DE ESTADO-->

                                                                <?php if( $detalle['estado'] >= 6 && $detalle['estado'] != 8 && !file_exists('../alumnos/apm/pm_files/'.$detalle['id_alumno'].'/trans_'.$_GET['idexp'].'.txt') ){?>
                                                                    <div class="alert alert-danger" role="alert">
                                                                        <strong class="d-block d-sm-inline-block-force">¡ADVERTENCIA!</strong> No se ha ingresado información sobre <b>Trans-Operatorio/Post-operatorio</b>.
                                                                    </div><!-- alert -->
                                                                <?php }//fin if alert trans-operatorio?>

                                                                <?php if( $detalle['estado'] >= 6 && $detalle['estado'] != 8 && !file_exists('../alumnos/apm/pm_files/'.$detalle['id_alumno'].'/nota_'.$_GET['idexp'].'.txt') ){?>
                                                                    <div class="alert alert-danger" role="alert">
                                                                        <strong class="d-block d-sm-inline-block-force">¡ADVERTENCIA!</strong> No se ha ingresado la <b>NOTA Post-operatoria</b>.
                                                                    </div><!-- alert -->
                                                                <?php }//fin if alert trans-operatorio?>

                                                                <div class="card-body bg-white bd bd-t-54 rounded-bottom">
                                                                    <b>Nombre completo del paciente:</b> <?=$detalle['paciente']?><br>

                                                                <b>Fecha en que se realizará el procedimiento: </b>
                                                                    <?php $fecha = substr($detalle['frealizacion'], 8, 2).'/'.substr($detalle['frealizacion'], 5, 2).'/'.substr($detalle['frealizacion'], 0, 4);?>
                                                                    <?=$fecha?><br>

                                                                <b>Lugar en el que se realizará el procedimiento:</b> 
                                                                <?php
                                                                    $resultado = $cx->query("SELECT * FROM pm_sitios WHERE idsitio = ".$detalle['idsitio']);
                                                                    $fila = $resultado->fetch_assoc();
                                                                    echo $fila['nombre'];
                                                                ?>
                                                                </div>
                                                                </div><!-- card-body -->
                                                                </div><!-- card -->

                                                                <!--HISTORIA CLÍNICA DATOS GENERALES-->

                                                                <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="headingOne">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapseOne-2" aria-expanded="false" aria-controls="collapseOne-2">HISTORIA CLÍNICA DEL PACIENTE:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapseOne-2" class="collapse" aria-labelledby="headingOne" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                            <?php
                                                                                    $hc = historiaClinica( $detalle['id_alumno'], $_GET['idexp'] );                                                                                    
                                                                                ?>
                                                                                
                                                                                Fecha de nacimiento del paciente:   
                                                                                    <div class="input-group col-lg-14 mg-t-10 mg-lg-t-0">                                                    
                                                                                        <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                                                                                        <input name="hc_fechanacimiento" type="date" class="form-control" value="<?=$hc->{'Fecha nacimiento paciente'};?>" onChange="javascript:GuardarCambios();" required disabled> &nbsp;
                                                                                    </div>
                                                                                    <hr>
                                                                                    <p>
                                                                                    <span class="shape_option">
                                                                                        &nbsp;&nbsp;<b>Interrogatorio:</b>&nbsp;
                                                                                        <label><input type="radio" name = "hc_interrogatorio" value = "D" <?php if( $hc->{'Interrogatorio'} == "D" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Directo</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_interrogatorio" value = "I" <?php if( $hc->{'Interrogatorio'} == "I" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Indirecto</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_interrogatorio" value = "M" <?php if( $hc->{'Interrogatorio'} == "M" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Mixto</label>&nbsp;
                                                                                        </span>

                                                                                        <span class="shape_option">
                                                                                        &nbsp;&nbsp;<b>Sexo:</b>&nbsp;                                                                                        
                                                                                        <label><input type="radio" name = "hc_sexo" value = "M" <?php if( $hc->{'Sexo'} == "M" ) echo "checked"; ?> onClick="javascript: mostrar_gineco();" onChange="javascript:GuardarCambios();" disabled></input>&nbsp;M</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_sexo" value = "H" <?php if( $hc->{'Sexo'} == "H" ) echo "checked"; ?> onClick="javascript: colapsar_gineco();" onChange="javascript:GuardarCambios();" disabled></input>&nbsp;H</label>&nbsp;
                                                                                        </span>

                                                                                        <span class="shape_option">
                                                                                        &nbsp;&nbsp;<b>Estado civil:</b>&nbsp;
                                                                                        <label><input type="radio" name = "hc_edocivil" value = "S" <?php if( $hc->{'Estado civil'} == "S" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Soltero(a)</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_edocivil" value = "C" <?php if( $hc->{'Estado civil'} == "C" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Casado(a)</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_edocivil" value = "D" <?php if( $hc->{'Estado civil'} == "D" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Divorciado(a)</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_edocivil" value = "V" <?php if( $hc->{'Estado civil'} == "V" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Viudez</label>&nbsp;
                                                                                        </span>
                                                                                    </p>
                                                                                    
                                                                                    <p>Religión:<br><input  name="hc_religion" class="form-control" value = "<?=$hc->{'Religión'}?>" type="text" required oninput="javascript:GuardarCambios();" disabled></p>
                                                                                    <p>Ocupación:<br><input  name="hc_ocupacion" class="form-control" value = "<?=$hc->{'Ocupación'}?>" type="text" required oninput="javascript:GuardarCambios();" disabled></p>

                                                                                    <span class="shape_option">
                                                                                        &nbsp;&nbsp;<b>Escolaridad:</b>&nbsp;
                                                                                        <label><input type="radio" name = "hc_escolaridad" value = "1" <?php if( $hc->{'Escolaridad'} == "1" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Sin estudios</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_escolaridad" value = "2" <?php if( $hc->{'Escolaridad'} == "2" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Preescolar</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_escolaridad" value = "3" <?php if( $hc->{'Escolaridad'} == "3" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Primaria</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_escolaridad" value = "4" <?php if( $hc->{'Escolaridad'} == "4" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Secundaria</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_escolaridad" value = "5" <?php if( $hc->{'Escolaridad'} == "5" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Bachillerato</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_escolaridad" value = "6" <?php if( $hc->{'Escolaridad'} == "6" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Licenciatura</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_escolaridad" value = "7" <?php if( $hc->{'Escolaridad'} == "7" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Maestría</label>&nbsp;
                                                                                        <label><input type="radio" name = "hc_escolaridad" value = "8" <?php if( $hc->{'Escolaridad'} == "8" ) echo "checked"; ?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Doctorado</label>
                                                                                    </span>
                                                                                    
                                                                                    <hr>

                                                                                    <p>Lugar de nacimiento:
                                                                                    <input  name="hc_lnacimiento" class="form-control" value="<?=$hc->{'Lugar de nacimiento'}?>" type="text" required oninput="javascript:GuardarCambios();" disabled></p>
                                                                                    <p>Lugar de residencia:
                                                                                    <input  name="hc_lresidencia" class="form-control" value="<?=$hc->{'Lugar de residencia'}?>" type="text" required oninput="javascript:GuardarCambios();" disabled></p>
                                                                                    <p>Domicilio:
                                                                                    <input  name="hc_domicilio" class="form-control" value="<?=$hc->{'Domicilio'}?>" type="text" required oninput="javascript:GuardarCambios();" disabled></p>

                                                                                    <p>Teléfono:
                                                                                    <input  name="hc_telefono" class="form-control" value="<?=$hc->{'Teléfono'}?>" type="text" required oninput="javascript:GuardarCambios();" disabled>
                                                                                    </p>
                                                                                    <p>   
                                                                                    Celular:
                                                                                    <input  name="hc_celular" class="form-control" value="<?=$hc->{'Celular'}?>" type="text" required oninput="javascript:GuardarCambios();" disabled>
                                                                                    </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                        <?php 
                                                        if( $detalle['estado'] == 7 ){ //IF Trans-operatorio 
                                                            if( file_exists('../alumnos/apm/pm_files/'.$detalle['id_alumno'].'/trans_'.$_GET['idexp'].'.txt') ){
                                                                $hct = info_trans( $detalle['id_alumno'], $_GET['idexp'] );?>
              


                                                    <!--*************************************************************************************************************************-->
                                                    <?php  
                                                        if( $detalle['estado'] == 7 ){ //IF Trans-operatorio 
                                                            if( file_exists('../alumnos/apm/pm_files/'.$detalle['id_alumno'].'/trans_'.$_GET['idexp'].'.txt') ){
                                                                $hct = info_trans( $detalle['id_alumno'], $_GET['idexp'] );?>
                                                                <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading2">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse2-2" aria-expanded="false" aria-controls="collapse2-2">TRANS-OPERATORIO</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse2-2" class="collapse" aria-labelledby="heading2" data-parent="#accordion-test-2">
                                                                            <div class="card-body">


                                                                            <!--LISTADO TRANSOPERATORIO-->
                                                                            <h3>TRANS-OPERATORIO</h3>

                                                                            <ul class="list-group" style="font-size:smaller">
                                                                                
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                HISTORIA CL&Iacute;NICA:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'historia_clinica_trans'} ) ) echo $hct->{'historia_clinica_trans'};?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                CANALIZACIÓN:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                        <?php if( isset( $hct->{'canalizacion_trans'} ) ) echo $hct->{'canalizacion_trans'};
                                                                                        else{  ?>&nbsp;&nbsp;
                                                                                        <label><input name="canalizacion_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                        <label><input name="canalizacion_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                        <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                VENDAJE DE COMPRESIÓN:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'vendaje_compresion_trans'} ) ) echo $hct->{'vendaje_compresion_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="vendaje_compresion_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="vendaje_compresion_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                SONDA URETRAL:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'sonda_uretral_trans'} ) ) echo $hct->{'sonda_uretral_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="sonda_uretral_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="sonda_uretral_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ASEPSIA Y ANTISEPSIA:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'asepsia_trans'} ) ) echo $hct->{'asepsia_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="asepsia_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="asepsia_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                CAMPOS ESTÉRILES:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'campos_esteriles_trans'} ) ) echo $hct->{'campos_esteriles_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="campos_esteriles_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="campos_esteriles_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                INFILTRACION CON SOLUCIÓN DE KLEIN:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'infiltracion_trans'} ) ) echo $hct->{'infiltracion_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="infiltracion_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="infiltracion_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                LIPOSUCCIÓN:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'liposuccion_trans'} ) ) echo $hct->{'liposuccion_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="liposuccion_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="liposuccion_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                DISECCIÓN DE PARED ANTERIOR:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'diseccion_trans'} ) ) echo $hct->{'diseccion_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="diseccion_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="diseccion_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                PLASTIA DE RECTOS:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'plastia_rectos_trans'} ) ) echo $hct->{'plastia_rectos_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="plastia_rectos_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="plastia_rectos_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                PLASTIA DE OBLICUOS:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'plastia_oblicuos_trans'} ) ) echo $hct->{'plastia_oblicuos_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="plastia_oblicuos_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="plastia_oblicuos_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                <span>NEOOFALOPLASTIA:</span>
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'neoo_trans'} ) ) echo $hct->{'neoo_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="neoo_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="neoo_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                <span>DERMOLIPECTOMIA:</span>
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'dermo_trans'} ) ) echo $hct->{'dermo_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="dermo_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="dermo_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                CIERRE POR PLANOS:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'cierre_trans'} ) ) echo $hct->{'cierre_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="cierre_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="cierre_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                DRENAJES:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'drenajes_trans'} ) ) echo $hct->{'drenajes_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="drenajes_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="drenajes_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                VENDAJE:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'vendaje_trans'} ) ) echo $hct->{'vendaje_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="vendaje_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="vendaje_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                FAJA:
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'faja_trans'} ) ) echo $hct->{'faja_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="faja_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="faja_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                <span>MINI ABDOMINOPLASTIA:</span>
                                                                                    <span class="badge badge-dark badge-pill">
                                                                                    <?php if( isset( $hct->{'mini_trans'} ) ) echo $hct->{'mini_trans'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="mini_trans" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="mini_trans" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?>
                                                                                    </span>
                                                                                </li>
                                                                                
                                                                            </ul>                                                                          

                                                                            <!--FIN LISTADO TRANSPERATORIO-->

                                                                            <!--LISTADO POST-OPERATORIO-->
                                                                            <h3>POST-OPERATORIO</h3>

                                                                            <ul class="list-group" style="font-size:smaller">
                                                                                
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                BAÑO A PACIENTE:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'bano_pos'} ) ) echo $hct->{'bano_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="bano_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="bano_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                CAMBIO DE APOSITOS/CURACIÓN:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'cambio_pos'} ) ) echo $hct->{'cambio_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="cambio_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="cambio_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ALIMENTACIÓN:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'alimentacion_pos'} ) ) echo $hct->{'alimentacion_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="alimentacion_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="alimentacion_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ANTIBIÓTICOS:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'antibioticos_pos'} ) ) echo $hct->{'antibioticos_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="antibioticos_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="antibioticos_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ANALGESIA:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'analgesia_pos'} ) ) echo $hct->{'analgesia_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="analgesia_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="analgesia_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                VENDAJE:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'vendaje_pos'} ) ) echo $hct->{'vendaje_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="vendaje_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="vendaje_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                FAJA:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'faja_pos'} ) ) echo $hct->{'faja_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="faja_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="faja_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                COMPLICACIONES:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'complicaciones_pos'} ) ) echo $hct->{'complicaciones_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="complicaciones_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="complicaciones_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                TRATAMIENTO DE COMPLICACIONES:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'tratamiento_pos'} ) ) echo $hct->{'tratamiento_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="tratamiento_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="tratamiento_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                TRATAMIENTOS ADYACENTES (OZONO, MEDILPREDNISONA, MVI):
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'adyacentes_pos'} ) ) echo $hct->{'adyacentes_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="adyacentes_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="adyacentes_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ALTA:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'alta_pos'} ) ) echo $hct->{'alta_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="alta_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="alta_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                MEDICAMENTOS EN CASA:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'medicamentos_pos'} ) ) echo $hct->{'medicamentos_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="medicamentos_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="medicamentos_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                TERAPIAS:
                                                                                    <span class="badge badge-dark badge-pill"><?php if( isset( $hct->{'terapias_pos'} ) ) echo $hct->{'terapias_pos'};
                                                                                    else{  ?>&nbsp;&nbsp;
                                                                                    <label><input name="terapias_pos" type="radio" value="SI" required oninput="javascript:GuardarCambios();">S&Iacute; </label>
                                                                                    <label><input name="terapias_pos" type="radio" value="NO" required oninput="javascript:GuardarCambios();">NO </label>
                                                                                    <?php }//else?></span>
                                                                                </li>

                                                                            </ul>

                                                                            </div>
                                                                        </div>
                                                                    </div> 

                                                    <?php
                                                            }//if verificacion de existencia de archivo trans-pos operatorio
                                                        ?>
                                                    
                                                    <?php }//if trans-operatorio?>
                                                                
                                                            <?php
                                                            }//if verificacion de existencia de archivo trans-pos operatorio
                                                        ?>
                                                    
                                                    <?php }//if trans-operatorio?>

                                                    <!--***********************************************************-->

                                                    <!--****************************************************-->
                                                    <?php if( $detalle['estado'] == 7 && file_exists('../alumnos/apm/pm_files/'.$detalle['id_alumno'].'/nota_'.$_GET['idexp'].'.txt')){ //IF NOTA POST-OPERATORIA 
                                                            
                                                                $nota = info_nota( $detalle['id_alumno'], $_GET['idexp'] );
                                                                
                                                           
                                                        ?>
                                                    <!--NOTA POST-OPERATORIA-->

                                                    <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading20">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse20-2" aria-expanded="false" aria-controls="collapse20-2">NOTA POST-OPERATORIA</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse20-2" class="collapse" aria-labelledby="heading20" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                            <?php 
                                                                if( isset( $nota->{'hora_ingreso'} ) ) 
                                                                    echo "<b>Hora de ingreso:</b> ".$nota->{'hora_ingreso'};
                                                                else{?>
                                                                    Hora de ingreso: <input name = "hora_ingreso" type="time" class="shape_input" required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                echo '<br><br>';

                                                                if( isset( $nota->{'servicio'} ) ) 
                                                                echo "<b>Servicio:</b> ".$nota->{'servicio'};
                                                                else{?>
                                                                <b>Servicio:</b><input name = "servicio" type="text" class="form-control" placeholder="Servicio..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'diagnostico'} ) ) 
                                                                echo "<br><b>Diagnóstico de Ingreso:</b> ".$nota->{'diagnostico'};
                                                                else{?>
                                                                <b><br>Diagnóstico de Ingreso:</b><input name = "diagnostico" type="text" class="form-control" placeholder="Diagnóstico de Ingreso..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'cirujano'} ) ) 
                                                                echo "<br><b>Cirujano:</b> ".$nota->{'cirujano'};
                                                                else{?>
                                                                <b><br>Cirujano:</b><input name = "cirujano" type="text" class="form-control" placeholder="Cirujano..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'instrumentista'} ) ) 
                                                                echo "<br><b>Instrumentista:</b> ".$nota->{'instrumentista'};
                                                                else{?>
                                                                <b><br>Instrumentista:</b><input name = "instrumentista" type="text" class="form-control" placeholder="instrumentista..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'primer_ayudante'} ) ) 
                                                                echo "<br><b>Primer ayudante:</b> ".$nota->{'primer_ayudante'};
                                                                else{?>
                                                                <b><br>Primer ayudante:</b><input name = "primer_ayudante" type="text" class="form-control" placeholder="Primer ayudante..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'anestesiologo'} ) ) 
                                                                echo "<br><b>Anestesiólogo:</b> ".$nota->{'anestesiologo'};
                                                                else{?>
                                                                <b><br>Anestesiólogo:</b><input name = "anestesiologo" type="text" class="form-control" placeholder="Anestesiólogo..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'segundo_ayudante'} ) ) 
                                                                echo "<br><b>Segundo ayudante:</b> ".$nota->{'segundo_ayudante'};
                                                                else{?>
                                                                <b><br>Segundo ayudante:</b><input name = "segundo_ayudante" type="text" class="form-control" placeholder="Segundo ayudante..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'otros'} ) ) 
                                                                echo "<br><b>Otros:</b> ".$nota->{'otros'};
                                                                else{?>
                                                                <b><br>Otros:</b><input name = "otros" type="text" class="form-control" placeholder="Otros..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                echo '<hr>';

                                                                if( isset( $nota->{'pre-operatorio'} ) ) 
                                                                echo "<b>Diagnóstico Pre-operatorio:</b> ".$nota->{'pre-operatorio'};
                                                                else{?>
                                                                <b>Diagnóstico Pre-operatorio:</b><input name = "pre-operatorio" type="text" class="form-control" placeholder="Diagnóstico Pre-operatorio..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'anestesia'} ) ) 
                                                                echo "<br><b>Anestesia utilizada:</b> ".$nota->{'anestesia'};
                                                                else{?>
                                                                <b><br>Anestesia utilizada:</b><input name = "anestesia" type="text" class="form-control" placeholder="Anestesia utilizada..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'operacion_planeada'} ) ) 
                                                                echo "<br><b>Operación planeada:</b> ".$nota->{'operacion_planeada'};
                                                                else{?>
                                                                <b><br>Operación planeada:</b><input name = "operacion_planeada" type="text" class="form-control" placeholder="Operación planeada..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'operacion_realizada'} ) ) 
                                                                echo "<br><b>Operación realizada:</b> ".$nota->{'operacion_realizada'};
                                                                else{?>
                                                                <b><br>Operación realizada:</b><input name = "operacion_realizada" type="text" class="form-control" placeholder="Operación realizada..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'hallazgos_trans_operatorios'} ) ) 
                                                                echo "<br><b>Hallazgos Trans-operatorios:</b> ".$nota->{'hallazgos_trans_operatorios'};
                                                                else{?>
                                                                <b><br>Hallazgos Trans-operatorios:</b><input name = "hallazgos_trans_operatorios" type="text" class="form-control" placeholder="Hallazgos Trans-operatorios..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'cuantificacion'} ) ) 
                                                                echo "<br><b>Cuantificación de sangre:</b> ".$nota->{'cuantificacion'};
                                                                else{?>
                                                                <b><br>Cuantificación de sangre:</b><input name = "cuantificacion" type="text" class="form-control" placeholder="Cuantificación de sangre..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                echo "<hr>";
                                                                //Exclusivo Abdominoplastía
                                                                if( $detalle['idpm'] == 4 ){
                                                                    if( isset( $nota->{'tipo_abdominoplastia'} ) ) 
                                                                    echo "<b>Tipo abdominoplastía:</b> ".$nota->{'tipo_abdominoplastia'};
                                                                    else{?>
                                                                    <b>Tipo abdominoplastía:</b><input name = "tipo_abdominoplastia" type="text" class="form-control" placeholder="Tipo abdominoplastía..." required oninput="javascript:GuardarCambios();"></input>
                                                                    <?php }
                                                                }//Fin if(4)

                                                                //Exclusivo Liposucción
                                                                if( $detalle['idpm'] == 2 ){
                                                                    if( isset( $nota->{'tipo_liposuccion'} ) ) 
                                                                    echo "<b>Tipo liposucción:</b> ".$nota->{'tipo_liposuccion'};
                                                                    else{?>
                                                                    <b>Tipo liposucción:</b><input name = "tipo_liposuccion" type="text" class="form-control" placeholder="Tipo liposucción..." required oninput="javascript:GuardarCambios();"></input>
                                                                    <?php }
                                                                    if( isset( $nota->{'canulas'} ) ) 
                                                                    echo "<br><b>No. de cánulas utilizadas:</b> ".$nota->{'canulas'};
                                                                    else{?>
                                                                    <b><br>No. de cánulas utilizadas:</b><input name = "canulas" type="number" min="0" max="100" class="form-control" value="0" required oninput="javascript:GuardarCambios();"></input>
                                                                    <?php }
                                                                }//Fin if(2)

                                                                //Exclusivo Mastopexia
                                                                if( $detalle['idpm'] == 3 ){
                                                                    if( isset( $nota->{'tecnica_mastopexia'} ) ) 
                                                                    echo "<b>Técnica utilizada:</b> ".$nota->{'tecnica_mastopexia'};
                                                                    else{?>
                                                                    <b>Técnica utilizada:</b>
                                                                    <select name="tecnica_mastopexia" id="tecnica_mastopexia" class="form-control">
                                                                        <option value="Mastopexia circumareolar" selected>Mastopexia circumareolar</option>
                                                                        <option value="Mastopexia mamaria con cicatriz vertical">Mastopexia mamaria con cicatriz vertical</option>
                                                                        <option value="Mastopexia con cicatriz en T invertida o ancla">Mastopexia con cicatriz en T invertida o ancla</option>
                                                                        <option value="Mastopexia con aumento mamario">Mastopexia con aumento mamario</option>
                                                                    </select>
                                                                    <?php }
                                                                }//Fin if(3)

                                                                //Exclusivo Mamoplastía
                                                                if( $detalle['idpm'] == 5 ){
                                                                    if( isset( $nota->{'marca_protesis'} ) ) 
                                                                    echo "<b>Marca de prótesis utilizada:</b> ".$nota->{'marca_protesis'};
                                                                    else{?>
                                                                    <b>Marca de prótesis utilizada:</b><input name = "marca_protesis" type="text" class="form-control" placeholder="Marca de prótesis utilizada..." required oninput="javascript:GuardarCambios();"></input>
                                                                    <?php }

                                                                    if( isset( $nota->{'volumen_protesis'} ) ) 
                                                                    echo "<b><br>Volumen de prótesis:</b> ".$nota->{'volumen_protesis'};
                                                                    else{?>
                                                                    <b><br>Volumen de prótesis:</b><input name = "volumen_protesis" type="text" class="form-control" placeholder="Volumen de prótesis..." required oninput="javascript:GuardarCambios();"></input>
                                                                    <?php }

                                                                    if( isset( $nota->{'perfil_protesis'} ) ) 
                                                                    echo "<b><br>Perfil y tipo de prótesis:</b> ".$nota->{'perfil_protesis'};
                                                                    else{?>
                                                                    <b><br>Perfil y tipo de prótesis:</b><input name = "perfil_protesis" type="text" class="form-control" placeholder="Perfil y tipo de prótesis..." required oninput="javascript:GuardarCambios();"></input>
                                                                    <?php }
                                                                }//Fin if(5)

                                                                echo "<hr>";

                                                                if( isset( $nota->{'incidentes'} ) ) 
                                                                echo "<b>Incidentes y accidentes:</b> ".$nota->{'incidentes'};
                                                                else{?>
                                                                <b>Incidentes y accidentes:</b><input name = "incidentes" type="text" class="form-control" placeholder="Incidentes y accidentes..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'tecnica_quirurgica'} ) ) 
                                                                echo "<br><b>Descripción de la técnica quirurgica:</b> ".$nota->{'tecnica_quirurgica'};
                                                                else{?>
                                                                <b><br>Descripción de la técnica quirurgica:</b><input name = "tecnica_quirurgica" type="text" class="form-control" placeholder="Descripción de la técnica quirurgica..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'zona_tratada'} ) ) 
                                                                echo "<br><b>Zona tratada y tipo de sutura:</b> ".$nota->{'zona_tratada'};
                                                                else{?>
                                                                <b><br>Zona tratada y tipo de sutura:</b><input name = "zona_tratada" type="text" class="form-control" placeholder="Zona tratada y tipo de sutura..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'servicios_auxiliares'} ) ) 
                                                                echo "<br><b>Estudios de Servicio Auxiliares de Diagnóstico tras-operatorio:</b> ".$nota->{'servicios_auxiliares'};
                                                                else{?>
                                                                <b><br>Estudios de Servicio Auxiliares de Diagnóstico tras-operatorio:</b><input name = "servicios_auxiliares" type="text" class="form-control" placeholder="Estudios de Servicio Auxiliares de Diagnóstico tras-operatorio..." required oninput="javascript:GuardarCambios();"></input>
                                                                <?php }

                                                                if( isset( $nota->{'plan_manejo'} ) ) 
                                                                echo "<br><b>Plan de Manejo/Tratamiento Post-operatorio inmediato::</b> ".$nota->{'plan_manejo'};
                                                                else{?>
                                                                <b><br>Plan de Manejo/Tratamiento Post-operatorio inmediato:</b><textarea name = "plan_manejo" rows="10" class="form-control" placeholder="Plan de Manejo/Tratamiento Post-operatorio inmediato..." required oninput="javascript:GuardarCambios();"></textarea>
                                                                <?php }

                                                            ?>
                                                                            </div>
                                                                        </div>
                                                                    </div> 


                                            
                                                    <!--FIN NOTA POST-OPERATORIA-->
                                                    <?php }//if NOTA POST-OPERATORIA?>
                                                    <!--****************************************************-->

                                                                    <!--*************************************************************************************************************************-->

                                                                    <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading3">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse3-2" aria-expanded="false" aria-controls="collapse3-2">ANTECEDENTES HEREDOFAMILIARES:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse3-2" class="collapse" aria-labelledby="heading3" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                            <textarea  name="hc_antecedenteshf" class="form-control" rows="10" required oninput="javascript:GuardarCambios();"  disabled><?=$hc->{'Antecedentes Heredofamiliares'}?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div> 

                                                                    <!--FIN ANTECEDENTES HEREDOFAMILIARES-->

                                                                    <!--ANTECEDENTES PERSONALES NO PATOLÓGICOS-->

                                                                    <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading4">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse4-2" aria-expanded="false" aria-controls="collapse4-2">ANTECEDENTES PERSONALES NO PATOLÓGICOS:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse4-2" class="collapse" aria-labelledby="heading4" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                            <p class="shape_option">
                                                                                            <b>HÁBITOS PERSONALES:</b><br><br>
                                                                                            <label><input name="bano" type="checkbox" id="bano" value="1"<?php if( $hc->{'Baño'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Baño</label>&nbsp;
                                                                                            <label><input name="defecacion" type="checkbox" id="defecacion" value="1"<?php if( $hc->{'Defecación'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Defecación</label>&nbsp;
                                                                                            <b>Actividad física que realiza:</b>
                                                                                        <input  name="actividadfisica" value="<?=$hc->{'Actividad Física'}?>" type="text" required class="shape_input" oninput="javascript:GuardarCambios();" disabled>
                                                                                    </p>

                                                                                    <p class="shape_option">
                                                                                            <b>¿QUÉ TIPO DE ALIMENTOS/BEBIDAS CONSUME HABITUALMENTE?:</b><br><br>
                                                                                            <label><input name="res" type="checkbox" id="res" value="1"<?php if( $hc->{'Res'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Res</label>&nbsp;
                                                                                            <label><input name="cerdo" type="checkbox" id="cerdo" value="1"<?php if( $hc->{'Cerdo'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Cerdo</label>&nbsp;
                                                                                            <label><input name="pescado" type="checkbox" id="pescado" value="1"<?php if( $hc->{'Pescado'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Pescado</label>&nbsp;
                                                                                            <label><input name="pollo" type="checkbox" id="pollo" value="1"<?php if( $hc->{'Pollo'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Pollo</label>&nbsp;
                                                                                            <label><input name="verduras" type="checkbox" id="verduras" value="1"<?php if( $hc->{'Verduras'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Verduras</label>&nbsp;
                                                                                            <label><input name="frutas" type="checkbox" id="frutas" value="1"<?php if( $hc->{'Frutas'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Frutas</label>&nbsp;
                                                                                            <label><input name="lacteos" type="checkbox" id="lacteos" value="1"<?php if( $hc->{'Lácteos'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Lácteos</label>&nbsp;
                                                                                            <label><input name="agua" type="checkbox" id="agua" value="1"<?php if( $hc->{'Agua'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Agua</label>&nbsp;
                                                                                            <label><input name="refresco" type="checkbox" id="regresco" value="1"<?php if( $hc->{'Refresco'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Refresco</label>&nbsp;
                                                                                            <b>Comidas por días: </b><input type="number" id="comidas" name="comidas" min="1" max="10" value="<?=$hc->{'Comidas por día'}?>" class="shape_input"  oninput="javascript:GuardarCambios();" disabled>
                                                                                    </p>

                                                                                    <p class="shape_option">
                                                                                            <b>SOBRE SU VIVIENDA:</b><br><br>
                                                                                            <b>Cuartos: </b><input type="number" id="cuartos" name="cuartos" min="1" max="100" value="<?=$hc->{'Cuartos'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled>
                                                                                            <b>Habitaciones: </b><input type="number" id="habitaciones" name="habitaciones" min="1" max="100" value="<?=$hc->{'Habitaciones'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled>
                                                                                            <label><input name="hacinamiento" type="checkbox" id="hacinamiento" value="1"<?php if( $hc->{'Hacinamiento'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Hacinamiento</label>&nbsp;
                                                                                            <b>Construcción: </b><input type="number" id="construccion" name="construccion" min="1" max="1000" value="<?=$hc->{'Construcción'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled> m<sup>2</sup>

                                                                                            </b><br><br><b>Cuenta con:</b><br>
                                                                                            <label><input name="drenaje" type="checkbox" id="drenaje" value="1"<?php if( $hc->{'Drenaje'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Drenaje</label>&nbsp;
                                                                                            <label><input name="luz" type="checkbox" id="luz" value="1"<?php if( $hc->{'Luz'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Luz</label>&nbsp;
                                                                                            <label><input name="aguapotable" type="checkbox" id="aguapotable" value="1"<?php if( $hc->{'Agua potable'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Agua Potable</label>&nbsp;
                                                                                            <label><input name="gas" type="checkbox" id="gas" value="1"<?php if( $hc->{'Gas'} ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled> Gas</label>&nbsp;
                                                                                    </p>

                                                                                    <p class="shape_option">
                                                                                            <b>SOBRE SU SALUD:</b><br><br>
                                                                                            <label><input name="zoonosis" type="checkbox" id="zonoosis" value="1" <?php if( $hc->{'Zoonosis'} ) echo "checked";?> disabled> Zoonosis</label>&nbsp;
                                                                                            <b>Tipo de sangre: </b>
                                                                                            <select name="tiposangre" id="tiposangre" class="shape_input" onChange="javascript:GuardarCambios();" disabled>
                                                                                                <option value="A+" <?php if( $hc->{'Tipo de sangre'} == "A+" ) echo "selected";?>>A+</option>
                                                                                                <option value="O+" <?php if( $hc->{'Tipo de sangre'} == "O+" ) echo "selected";?>>O+</option>
                                                                                                <option value="B+" <?php if( $hc->{'Tipo de sangre'} == "B+" ) echo "selected";?>>B+</option>
                                                                                                <option value="AB+" <?php if( $hc->{'Tipo de sangre'} == "AB+" ) echo "selected";?>>AB+</option>
                                                                                                <option value="A-" <?php if( $hc->{'Tipo de sangre'} == "A-" ) echo "selected";?>>A-</option>
                                                                                                <option value="O-" <?php if( $hc->{'Tipo de sangre'} == "B-" ) echo "selected";?>>O-</option>
                                                                                                <option value="B-" <?php if( $hc->{'Tipo de sangre'} == "B-" ) echo "selected";?>>B-</option>
                                                                                                <option value="AB-" <?php if( $hc->{'Tipo de sangre'} == "AB-" ) echo "selected";?>>AB-</option>
                                                                                            </select>
                                                                                            <b>Esquema de vacunación:</b>
                                                                                            <label><input type="radio" name = "esquema" value = "Completo" <?php if( $hc->{'Esquema de vacunación'} == "Completo" ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Completo</label>
                                                                                            <label><input type="radio" name = "esquema" value = "Incompleto" <?php if( $hc->{'Esquema de vacunación'} == "Incompleto" ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Incompleto</label>
                                                                                            <label><input type="radio" name = "esquema" value = "Desconoce" <?php if( $hc->{'Esquema de vacunación'} == "Desconoce" ) echo "checked";?> onChange="javascript:GuardarCambios();" disabled></input>&nbsp;Desconoce</label>

                                                                                            <label  >
                                                                                            <div onClick="javascript:GuardarCambios();">
                                                                                                <br><b>Inmunizaciones recientes:</b>
                                                                                            <input name="inmunizaciones" id="inmunizaciones" type="text" class="shape_input" value="<?=$hc->{'Inmunizaciones'}?>" disabled>

                                                                                            <b>Toxicomanías:</b>
                                                                                            <input name="toxicomanias" id="toxicomanias" type="text" class="shape_input" value="<?=$hc->{'Toxicomanías'}?>" oninput="javascript:GuardarCambios();" disabled>

                                                                                            <b>Medicación actual:</b>
                                                                                            <input name="medicacion" id="medicacion" type="text" class="shape_input" value="<?=$hc->{'Medicación'}?>" oninput="javascript:GuardarCambios();" disabled>
                                                                                    </div>
                                                                                            </label>
                                                                                    </p>
                                                                            </div>
                                                                        </div>
                                                                    </div> 

                                                                        <!--FIN ANTECEDENTES PERSONALES NO PATOLÓGICOS-->

                                                                        <!--ANTECEDENTES PERSONALES PATOLÓGICOS-->

                                                                        <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading5">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse5-2" aria-expanded="false" aria-controls="collapse5-2">ANTECEDENTES PERSONALES PATOLÓGICOS:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse5-2" class="collapse" aria-labelledby="heading5" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                            <textarea name="app" rows="10" class="form-control" oninput="javascript:GuardarCambios();" disabled><?=$hc->{'Antecedentes personales patológicos'}?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                        <!--FIN ANTECEDENTES PERSONALES PATOLÓGICOS-->


                                                                        <!--ANTECEDENTES GINECO-OBSTÉTRICOS-->
                                                                        <?php 
                                                                            if( $hc->{'Sexo'} == 'M' ) $required = "required"; else $required = ""; 
                                                                            if( $hc->{'Sexo'} == 'M' ){
                                                                        ?>

                                                                        <div id="accordion-test-2" class="card-box">
                                                                            <div class="card">
                                                                                <div class="card-header bg-primary" id="heading6">
                                                                                    <h5 class="m-0 card-title">
                                                                                    <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse6-2" aria-expanded="false" aria-controls="collapse6-2">ANTECEDENTES GINECO-OBSTÉTRICOS:</a>
                                                                                    </h5>
                                                                                </div>

                                                                            <div id="collapse6-2" class="collapse" aria-labelledby="heading6" data-parent="#accordion-test-2">
                                                                                <div class="card-body">
                                                                                <p class="shape_option">
                                                                                                        <b>Menarca</b> a los <input id = "Menarca" name = "Menarca" type="number" min="0" max="18" value="<?=$hc->{'Menarca'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input> años
                                                                                                        <b>Ritmo:</b> <input id="Ritmo" name="Ritmo" type="text" class="shape_input" value="<?=$hc->{'Ritmo'}?>" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input>
                                                                                                        <b>FUR:</b> <input id="FUR" name="FUR" type="date" class="shape_input" value="<?=$hc->{'FUR'}?>" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input>
                                                                                                        <label><input name="Norrea" value="EU" type="radio" class="shape_input" <?php if( $hc->{'Norrea'} == 'EU' ) echo "checked"; ?>  oninput="javascript:GuardarCambios();" disabled></input> Eumenorrea</label>&nbsp;<label><input name="Norrea" value="DIS" type="radio" class="shape_input" <?php if( $hc->{'Norrea'} == 'DIS' ) echo "checked"; ?> oninput="javascript:GuardarCambios();" disabled></input> Dismenorrea</label>                          

                                                                                                    <br><br>
                                                                                                    <b>IVSA</b> a los <input id="IVSA" name = "IVSA" type="number" min="0" max="18" value="<?=$hc->{'IVSA'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input> años
                                                                                                    <b>Parejas sexuales: </b> <input id="Parejas_Sexuales" name = "Parejas_Sexuales" type="number" min="0" max="100" value="<?=$hc->{'Parejas Sexuales'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input>
                                                                                                    <b>Gestas: </b> <input id="Gestas" name = "Gestas" type="number" min="0" max="20" value="<?=$hc->{'Gestas'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input><br><br>
                                                                                                    <b>Partos: </b> <input id="Partos" name = "Partos" type="number" min="0" max="20" value="<?=$hc->{'Partos'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input>
                                                                                                    <b>Cesáreas: </b> <input id="Cesareas" name = "Cesareas" type="number" min="0" max="20" value="<?=$hc->{'Cesáreas'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input>
                                                                                                    <b>Abortos: </b> <input id="Abortos" name = "Abortos" type="number" min="0" max="20" value="<?=$hc->{'Abortos'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input>
                                                                                                    <b>Óbitos: </b> <input id="Obitos" name = "Obitos" type="number" min="0" max="20" value="<?=$hc->{'Óbitos'}?>" class="shape_input" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input>

                                                                                                    <br><br><b>Antecedentes de ETS:</b>
                                                                                                    <label><input name="ETS" value="Sí" type="radio" class="shape_input" <?php if( $hc->{'ETS'} == 'Sí' ) echo "checked"; ?> onchange="javascript:GuardarCambios();" disabled></input> Sí</label>&nbsp;<label><input name="ETS" value="No" type="radio" class="shape_input" <?php if( $hc->{'ETS'} == 'No' ) echo "checked"; ?> onchange="javascript:GuardarCambios();" disabled></input> No</label>

                                                                                                    </p>

                                                                                                    <hr>
                                                                                                    Especificar años de los eventos, embarazos normoevolutivos/riesgo, pretérmino, término, postérmino, complicaciones obstétricas y en postparto. Indicar trimestre de los abortos y especificar causa si se conoce y las indicaciones de cesáreas:
                                                                                                    <textarea <?=$required?> id="Especificaciones" name="Especificaciones" rows="10" class="form-control" oninput="javascript:GuardarCambios();" disabled ><?=$hc->{'Especificaciones'}?></textarea>
                                                                                                                                
                                                                                                    <hr>
                                                                                                    Menopausia:
                                                                                                    <input id="Menopausia" name="Menopausia" type="text" class="form-control" value="<?=$hc->{'Menopausia'}?>" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input><br>
                                                                                                    Climaterio:
                                                                                                    <input id="Climaterio" name="Climaterio" type="text" class="form-control" value="<?=$hc->{'Climaterio'}?>" oninput="javascript:GuardarCambios();"  disabled <?=$required?>></input><br>
                                                                                                    Terapia de reemplazo hormonal:
                                                                                                    <input id="Terapia" name="Terapia" type="text" class="form-control" value="<?=$hc->{'Terapia'}?>" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input><br>                            
                                                                                                    MPF:
                                                                                                    <input id="MPF" name="MPF" type="text" class="form-control" value="<?=$hc->{'MPF'}?>" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input><br>
                                                                                                    Papanicolau:
                                                                                                    <input id="Papanicolau" name="Papanicolau" type="text" class="form-control" value="<?=$hc->{'Papanicolau'}?>" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input><br>
                                                                                                    Colposcopia:
                                                                                                    <input id="Colposcopia" name="Colposcopia" type="text" class="form-control" value="<?=$hc->{'Colposcopia'}?>" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input><br>
                                                                                                    Mastografía:
                                                                                                    <input id="Mastografia" name="Mastografía" type="text" class="form-control" value="<?=$hc->{'Mastografía'}?>" oninput="javascript:GuardarCambios();" disabled <?=$required?>></input>

                                                                                </div>
                                                                            </div>
                                                                        </div> 
                                                                        <?php }?>
                                                                            <!--ANTECEDENTES GINECO-OBSTÉTRICOS-->

                                                                            <!--PADECIMIENTOS ACTUALES-->

                                                                            <div id="accordion-test-2" class="card-box">
                                                                            <div class="card">
                                                                                <div class="card-header bg-primary" id="heading7">
                                                                                    <h5 class="m-0 card-title">
                                                                                    <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse7-2" aria-expanded="false" aria-controls="collapse7-2">PADECIMIENTOS ACTUALES:</a>
                                                                                    </h5>
                                                                                </div>

                                                                                <div id="collapse7-2" class="collapse" aria-labelledby="heading7" data-parent="#accordion-test-2">
                                                                                    <div class="card-body">
                                                                                    <textarea name="padecimientos_actuales" rows="10" class="form-control" placeholder="Agregue aquí los padecimientos actuales que tiene el paciente..." oninput="javascript:GuardarCambios();" disabled><?=$hc->{'Padecimientos actuales'}?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div> 
                                                                            
                                                                                <!--FIN PADECIMIENTOS ACTUALES-->


                                                                                <!--SIGNOS VITALES-->
                                                                                <div id="accordion-test-2" class="card-box">
                                                                                <div class="card">
                                                                                    <div class="card-header bg-primary" id="heading8">
                                                                                        <h5 class="m-0 card-title">
                                                                                        <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse8-2" aria-expanded="false" aria-controls="collapse8-2">SIGNOS VITALES:</a>
                                                                                        </h5>
                                                                                    </div>

                                                                                    <div id="collapse8-2" class="collapse" aria-labelledby="heading8" data-parent="#accordion-test-2">
                                                                                        <div class="card-body">
                                                                                        <p class="shape_option">
                                                                                            <b>Frecuencia cardíaca: </b><input id="frecuencia_cardiaca" name="frecuencia_cardiaca" type="number" class="shape_input" min="0" max="200" value="<?=$hc->{'Frecuencia Cardíaca'}?>" required oninput="javascript:GuardarCambios();" disabled></input> lpm
                                                                                            <b>Frecuencia respiratoria: </b><input id="frecuencia_respiratoria" name="frecuencia_respiratoria" type="number" class="shape_input" min="0" max="200" value="<?=$hc->{'Frecuencia Respiratoria'}?>" required oninput="javascript:GuardarCambios();" disabled></input> rpm
                                                                                            <br><br><b>TA: </b><input id="TA1" name="TA1" type="number" class="shape_input" min="0" max="200" value="<?=$hc->{'TA1'}?>" disabled required></input>/<input id="TA2" name="TA2" type="number" class="shape_input" min="0" max="200" value="<?=$hc->{'TA2'}?>" required oninput="javascript:GuardarCambios();" disabled></input> mmHg
                                                                                            <b>Temperatura: </b><input id="Temperatura" name="Temperatura" type="number" class="shape_input" min="0.5" max="200.5" step="0.1" value="<?=$hc->{'Temperatura'}?>" required oninput="javascript:GuardarCambios();" disabled></input><span style="font-size: x-large;">°</span>C 
                                                                                            <b>SPO<sub>2</sub>: </b><input id="SPO2" name="SPO2" type="number" class="shape_input" min="0.5" max="200.5" value="<?=$hc->{'SPO2'}?>" step="0.1" required oninput="javascript:GuardarCambios();" disabled></input> %
                                                                                            <br><br><b>Peso actual: </b><input id="peso_actual" name="peso_actual" type="number" class="shape_input" min="0.5" max="200.5" step="0.1" value="<?=$hc->{'Peso actual'}?>" required oninput="javascript:GuardarCambios();" disabled></input> Kgs
                                                                                            <b>Peso ideal: </b><input id="peso_ideal" name="peso_ideal" type="number" class="shape_input" min="0.5" max="200.5" step="0.1" value="<?=$hc->{'Peso ideal'}?>" required oninput="javascript:GuardarCambios();" disabled></input> Kgs
                                                                                            <b>Talla: </b><input id="Talla" name="talla" type="number" class="shape_input" min="0.5" max="200.5" value="<?=$hc->{'Talla'}?>" step="0.1" required oninput="javascript:GuardarCambios();" disabled></input> cm
                                                                                            <b>IMC: </b><input id="IMC" name="IMC" type="number" class="shape_input" min="0.5" max="200.5" value="<?=$hc->{'IMC'}?>" step="0.1" required oninput="javascript:GuardarCambios();" disabled></input> Kg/m<sup>2</sup><br><br>
                                                                                            Otros:<br><input id="Otros" name="otros" type="text" class="form-control" placeholder="Otros..." oninput="javascript:GuardarCambios();" disabled value="<?=$hc->{'Otros'}?>"></input>
                                                                                        </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> 
                                                                                <!--FIN SIGNOS VITALES-->


                                                                                <!--EXPLORACIÓN FÍSICA-->

                                                                                <div id="accordion-test-2" class="card-box">
                                                                                <div class="card">
                                                                                    <div class="card-header bg-primary" id="heading9">
                                                                                        <h5 class="m-0 card-title">
                                                                                        <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse9-2" aria-expanded="false" aria-controls="collapse9-2">EXPLORACIÓN FÍSICA:</a>
                                                                                        </h5>
                                                                                    </div>

                                                                                    <div id="collapse9-2" class="collapse" aria-labelledby="heading9" data-parent="#accordion-test-2">
                                                                                        <div class="card-body">
                                                                                        <h2>Inspección general</h2>
                                                                                        <p class="shape_option">
                                                                                            <b>Exploración regional:</b><br><br>
                                                                                            Cabeza:<br><input id="cabeza" name="cabeza" type="text" class="form-control" placeholder="Cabeza..." value="<?=$hc->{'Cabeza'}?>" oninput="javascript:GuardarCambios();" disabled required></input><br>
                                                                                            Cuello:<br><input id="cuello" name="cuello" type="text" class="form-control" placeholder="Cuello..." value="<?=$hc->{'Cuello'}?>" oninput="javascript:GuardarCambios();" disabled required></input><br>
                                                                                            Tórax:<br><input id="torax" name="torax" type="text" class="form-control" placeholder="Tórax..." value="<?=$hc->{'Tórax'}?>" oninput="javascript:GuardarCambios();" disabled required></input><br>
                                                                                            Abdomen:<br><input id="abdomen" name="abdomen" type="text" class="form-control" placeholder="Abdomen..." value="<?=$hc->{'Abdomen'}?>" oninput="javascript:GuardarCambios();" disabled required></input><br>
                                                                                            Extremidades:<br><input id="extremidades" name="extremidades" type="text" class="form-control" placeholder="Extremidades..." value="<?=$hc->{'Extremidades'}?>" oninput="javascript:GuardarCambios();" disabled required></input><br>
                                                                                            Genitales:<br><input id="genitales" name="genitales" type="text" class="form-control" placeholder="Genitales..." value="<?=$hc->{'Genitales'}?>" oninput="javascript:GuardarCambios();" disabled required></input><br>
                                                                                            Piel y Fanéras:<br><input id="pielfaneras" name="pielfaneras" type="text" class="form-control" placeholder="Piel y Fanéras..." value="<?=$hc->{'Piel y Fanéras'}?>" oninput="javascript:GuardarCambios();" disabled required></input>
                                                                                        </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> 
                                                                            <!--FIN EXPLORACIÓN FÍSICA-->

                                                                <!--DIAGNÓSTICO-->
                                                                <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading10">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse10-2" aria-expanded="false" aria-controls="collapse10-2">DIAGNÓSTICO:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse10-2" class="collapse" aria-labelledby="heading10" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                            <textarea name="diagnostico" rows="10" class="form-control" placeholder="Agregue aquí su diagnóstico..." required oninput="javascript:GuardarCambios();" disabled><?=$hc->{'Diagnóstico'}?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                <!--FIN DIAGNÓSTICO-->


                                                                <!--TRATAMIENTO-->
                                                                <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading11">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse11-2" aria-expanded="false" aria-controls="collapse11-2">TRATAMIENTO:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse11-2" class="collapse" aria-labelledby="heading11" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                                <textarea name="tratamiento" rows="10" class="form-control" placeholder="Agregue aquí el tratamiento a seguir..." required oninput="javascript:GuardarCambios();" disabled><?=$hc->{'Tratamiento'}?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div> 

                                                                <!--FIN TRATAMIENTO-->

                                                                <!--PRONÓSTICO-->
                                                                <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading12">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse12-2" aria-expanded="false" aria-controls="collapse12-2">PRONÓSTICO:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse12-2" class="collapse" aria-labelledby="heading12" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                            <textarea name="pronostico" rows="10" class="form-control" placeholder="Agregue aquí el pronóstico..." required oninput="javascript:GuardarCambios();" disabled><?=$hc->{'Pronóstico'}?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                <!--FIN PRONÓSTICO-->

                                                    <!--PREOPERATORIO-->
                                                    <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading13">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse13-2" aria-expanded="false" aria-controls="collapse13-2">PRE-OPERATORIO:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse13-2" class="collapse" aria-labelledby="heading13" data-parent="#accordion-test-2">
                                                                            <div class="card-body">

                                                                            <ul class="list-group" style="font-size:smaller">
                                                                                
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                HISTORIA CL&Iacute;NICA:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'historia_clinica'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                EXPLORACI&Oacute;N F&Iacute;SICA:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'exploracion_fisica'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                CONSENTIMIENTO INFORMADO:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'consentimiento'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ESTUDIOS DE LABORATORIO:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'estudios_laboratorio'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ESTUDIOS DE GABINETE:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'estudios_gabinete'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ESTUDIO DE PLAQUETAS:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'estudios_plaquetas'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ESTUDIOS DE FIBRIN&Oacute;GENO:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'estudios_fibrinogeno'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ESTUDIO DE HEMOGLOBINA:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'estudios_hemoglobina'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ESTUDIO DE VIH:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'vih'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                PROFILAXIS CON ENOXAPARINA:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'profilaxis'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                CICATRICES:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'cicatrices'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ASIMETR&Iacute;AS:
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'asimetrias'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                VARIACIONES ANAT&Oacute;MICAS
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'variaciones'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                USO DE OTROS MEDICAMENTOS PREQUIR&Uacute;RGICOS&nbsp;
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'otros_medicamentos'}?></span>
                                                                                </li>

                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                FOTOS PREQUIR&Uacute;RGICAS
                                                                                    <span class="badge badge-dark badge-pill"><?=$hc->{'fotos'}?></span>
                                                                                </li>
                                                                            </u>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                    

                                                    <!--TROMBOEMBOLIA-->
                                                    <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading14">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse14-2" aria-expanded="false" aria-controls="collapse14-2">TROMBOEMBOLIA EN CIRUGÍA ESTÉTICA:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse14-2" class="collapse" aria-labelledby="heading14" data-parent="#accordion-test-2">
                                                                            <div style="padding: 10px;">

                                                                            <h3>CLÍNICOS:</h3>
                                                                                <ul class="list-group" style="font-size:small">
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Edad &gt; 60 a&ntilde;os:</b> <?=$hc->{'p1'}?> pts.</li>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Obesidad (IMC &gt; 30):</b> <?=$hc->{'p2'}?> pts.</li>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Fumador:</b> <?=$hc->{'p3'}?> pts. </li>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Inmovilizaci&oacute;n previa a la cirug&iacute;a por m&aacute;s de 24 horas: </b><?=$hc->{'p4'}?> pts. </li>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Insuficiencia venosa o edema de Ms. ls.: </b><?=$hc->{'p5'}?> pts.</li>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Trombosis venosa profunda o embolia previa: </b><?=$hc->{'p6'}?> pts. </li>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Quemaduras: </b> <?=$hc->{'p7'}?> pts. </li>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Anticoncepci&oacute;n o terapia de reemplazo estrog&eacute;nico: </b><?=$hc->{'p8'}?> pts. </li>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center"><b>Neoplasia presente: </b><?=$hc->{'p9'}?> pts.
                                                                                </ul>

                                                                            <h3>QUIRÚRGICOS:</h3>
                                                                            
                                                                            <ul class="list-group" style="font-size:small">
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center"><b>Tiempo de cirug&iacute;a mayor a 60 min.: </b><?=$hc->{'p10'}?> pts.</li>
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center"><b>Posici&oacute;n de Fowler: </b><?=$hc->{'p11'}?> pts.</li>
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center"><b>Lipoaspiración: </b><?=$hc->{'p14'}?> pts.</li>
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center"><b>Implantes de silic&oacute;n corporales: </b><?=$hc->{'p12'}?> pts.</li>
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center"><b>Cirug&iacute;as est&eacute;ticas asociadas o combinadas:</b> <?=$hc->{'p13'}?> pts.</li>
                                                                            </ul>
                                                                            <br>
                                                                            <div class="alert alert-info" role="alert">
                                                                            <strong class="d-block d-sm-inline-block-force">
                                                                                <b>Puntos que obtiene su paciente de acuerdo a los factores de riesgo para tromboembolia en cirugía estética que usted anotó:</b> 
                                                                                <b><span id="puntos" style="color:red; font-size:x-large;"><?php $puntos = 0; for( $i=1; $i<=14; $i++ ) $puntos += $hc->{"p$i"}; echo $puntos; ?></span></b>
                                                                            </strong>
                                                                        </div><!-- alert -->
                               
                                <input type="hidden" name="trombo" class="shape_input" placeholder="0" min="0" max="19" required></input>                                

                               <!--TABLAS PROCEDIMIENTOS MEDIDAS PREVENTIVAAS--> 
                               <div id="tp1" style="font-size:small;">
                                    <h3><?=$proc_nombre?></h3>
                            
                                            <div style="background-color:#eeeeee; border-radius: 10px; margin: 10px; padding: 5px;">
                                                <strong>Medidas preventivas: </strong><br><br>
                                                <p>
                                                <label><input name="medida1" type="radio" value="1" onChange="javascript:GuardarCambios();" disabled <?php if( $hc->{'Medida1'} == 1 ) echo 'checked'; ?> >
                                                <strong>Bajo riesgo (1 punto): </strong></label><br>
                                                    -Movilizaci&oacute;n precoz.<br>
                                                    -Medias el&aacute;sticas.<br>
                                                    -Prendas de compresi&oacute;n graduada.
                                                </p>

                                                <p>
                                                <label><input name="medida1" type="radio" value="2" onChange="javascript:GuardarCambios();" disabled  <?php if( $hc->{'Medida1'} == 2 ) echo 'checked'; ?>>
                                                <strong>Riesgo moderado (2 a 4 puntos): </strong></label><br>
                                                    -Movilizaci&oacute;n precoz.<br>
                                                    -Medias el&aacute;sticas.<br>
                                                    -Prendas de compresi&oacute;n graduada.
                                                </p>

                                                <p>
                                                <label><input name="medida1" type="radio" value="3" onChange="javascript:GuardarCambios();" disabled  <?php if( $hc->{'Medida1'} == 3 ) echo 'checked'; ?>>
                                                <strong>Alto riesgo (5 puntos): </strong></label><br>
                                                    -Movilizaci&oacute;n precoz.<br>
                                                    -Medias el&aacute;sticas.<br>
                                                    -Prendas de compresi&oacute;n graduada.
                                                </p>
                                            </div>


                                            <div style="background-color:#eeeeee; border-radius: 10px; margin: 10px; padding: 5px;">
                                                <strong>Trans operatorio: </strong><br><br>
                                                <p>
                                                <label><input name="medida2" type="radio" value="1" onChange="javascript:GuardarCambios();" disabled <?php if( $hc->{'Medida2'} == 1 ) echo 'checked'; ?> >
                                                <strong>Bajo riesgo (1 punto): </strong></label><br>
                                                -Compresi&oacute;n graduada intermitente.<br>
                                                        -Manipulaci&oacute;n de Ms. lfs. trans. y postquir&uacute;rgico. 
                                                </p>

                                                <p>
                                                <label><input name="medida2" type="radio" value="2" onChange="javascript:GuardarCambios();" disabled  <?php if( $hc->{'Medida2'} == 2 ) echo 'checked'; ?>>
                                                <strong>Riesgo moderado (2 a 4 puntos): </strong></label><br>
                                                -Compresi&oacute;n graduada intermitente.<br>
                                                        -Manipulaci&oacute;n de Ms. Ps. trans. y postquir&uacute;rgico. <br>
                                                        -Evitar trauma quir&uacute;rgico mayor.<br>
                                                        -Evitar cirug&iacute;a mayor a 5 hrs. <br>
                                                        -Evitar uso excesivo de epinefrina. <br>
                                                </p>

                                                <p>
                                                <label><input name="medida2" type="radio" value="3" onChange="javascript:GuardarCambios();" disabled  <?php if( $hc->{'Medida2'} == 3 ) echo 'checked'; ?>>
                                                <strong>Alto riesgo (5 puntos): </strong></label><br>
                                                -Compresi&oacute;n graduada intermitente.<br>
                                                        -Manipulaci&oacute;n de Ms. Ps. trans. y postquir&uacute;rgico.<br>
                                                        -Evitar trauma quir&uacute;rgico mayor.<br>
                                                        -Evitar cirug&iacute;a mayor a 5 hrs. <br>
                                                        -Evitar uso excesivo de epinefrina. <br>
                                                </p>
                                            </div>

                                            <div style="background-color:#eeeeee; border-radius: 10px; margin: 10px; padding: 5px;">
                                                <strong>Medidas farmacol&oacute;gicas: </strong><br><br>
                                                
                                                <p>
                                                <label><input name="medida3" type="radio" value="2" onChange="javascript:GuardarCambios();" disabled  <?php if( $hc->{'Medida3'} == 2 ) echo 'checked'; ?>>
                                                <strong>Riesgo moderado (2 a 4 puntos): </strong></label><br>
                                                -Heparina de bajo peso molecular (subcut&aacute;neo). <br>
                                                        -Enoxaparina 40 mg-1 por d&iacute;a. Inicio 12 horas antes de la cirug&iacute;a.<br>
                                                        -Postoperatorio inyecciones diarias.<br>
                                                        -Metilprednisolona: 6-9 mg/kg en infusi&oacute;n para 20 min. dosis preoperatoria.<br>
                                                        -Ciclesonida: 200mg/dosis 600 mgs. antes de la cirug&iacute;a y repetir a las 24 hrs. ALVESCO&reg; <br>
                                                </p>

                                                <p>
                                                <label><input name="medida3" type="radio" value="3" onChange="javascript:GuardarCambios();" disabled  <?php if( $hc->{'Medida3'} == 3 ) echo 'checked'; ?>>
                                                <strong>Alto riesgo (5 puntos): </strong></label><br>
                                                -Heparina de bajo peso molecular (subcut&aacute;neo). <br>
                                                        -Enoxaparina 40 mg-1 por d&iacute;a. Inicio 12 horas antes de la cirug&iacute;a.<br>
                                                        -Postoperatorio inyecciones diarias.<br>
                                                        -Metilprednisolona: 6-9 mg/kg en infusi&oacute;n para 20 min. dosis preoperatoria.<br>
                                                        -Ciclesonida: 200mg/dosis 600 mgs. antes de la cirug&iacute;a y repetir a las 24 hrs. ALVESCO&reg; <br>
                                                </p>
                                            </div>
                                </div>
                            </div>                                                                                
                               <!--FIN TABLAS PROCEDIMIENTOS MEDIDADS PREVENTIVAS-->
                                                                            </div>
                                                                        </div>
                                                                    </div> 

                                                    <!--FIN TROMBOEMBOLIA-->

                                                    <!--COMENTARIOS-->
                                                    <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading15">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse15-2" aria-expanded="false" aria-controls="collapse15-2">COMENTARIOS/OBSERVACIONES:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse15-2" class="collapse" aria-labelledby="heading15" data-parent="#accordion-test-2">
                                                                            <div class="card-body">
                                                                            <?php if( $detalle['estado'] <= 6 ){ ?>
                                                                                        <b>Agregar Comentario/Observación:</b> 
                                                                                        <input id="cnuevo" type="text" class="form-control" 
                                                                                        <?php if( ($_GET['tk'] == 't' && $detalle['estado'] < 4) || ($_GET['tk'] == 'c' && $detalle['estado'] >=4) ) echo "disabled"; ?> ><a style="cursor:pointer;" class="btn-primary" onClick="javascript:agregar_comentario();"> 
                                                                                        <i class="fas fa-plus"></i>Agregar</a><br><br>
                                                                                        <?php }?>
                                                                                        <textarea name="comentarios" id="comentarios" rows="10" class="form-control" readonly><?=str_replace(" [", "\n[", $detalle['comentarios']);?></textarea><br>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                    <!--FIN COMENTARIOS-->

                                                    <!--ARCHIVOS-->
                                                    <div id="accordion-test-2" class="card-box">
                                                                    <div class="card">
                                                                        <div class="card-header bg-primary" id="heading16">
                                                                            <h5 class="m-0 card-title">
                                                                            <a href="" class="collapsed" data-toggle="collapse" data-target="#collapse16-2" aria-expanded="false" aria-controls="collapse16-2">ARCHIVOS DEL EXPEDIENTE:</a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse16-2" class="collapse" aria-labelledby="heading16" data-parent="#accordion-test-2">
                                                                            <div style="padding:5px; max-witdh:100%">
                                                                                    <?php listarArchivos( $_GET['idexp'], $cx, $detalle['id_alumno']);?>
                                                                            </div>
                                                                        </div>
                                                                    </div> 

                                                                <!--FIN ARCHIVOS-->  
                                                                
                                                                <?php if( $detalle['estado'] <= 6 ){ ?>
                                                                    <p><br><button id="guardar" type="submit" class="btn btn-info" disabled>Guardar cambios</button>
                                                                    <button type="reset" class="btn btn-secondary">Restablecer</button>
                                                                <?php }?>

                                                                <?php if( $detalle['estado'] <= 3 ){ ?>
                                                                <a onClick="javascript: requerimientos( true );" id="modalcancelacion" href="" class="btn btn-danger tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-toggle="modal" data-target="#modaldemo5">Cancelar procedimiento</a>
                                                                <?php }?>

                                                                    <?php if( $detalle['estado'] <= 3 ){ ?>
                                                                        <div id="modaldemo5" class="modal fade">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content tx-size-sm">
                                                                                <div class="modal-body tx-center pd-y-20 pd-x-20">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close2">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                    <i class="icon icon ion-ios-close-outline tx-100 tx-danger lh-1 mg-t-20 d-inline-block"></i>
                                                                                    <h4 class="tx-danger  tx-semibold mg-b-20">¡ADVERTENCIA!</h4>
                                                                                    <p class="mg-b-20 mg-x-20">Si cancela este procedimiento no podrá recuperarlo.</p>
                                                                                    
                                                                                        <input id="tcancelacion" name="tcancelacion" type="text" placeholder="Introduzca el motivo de la cancelación..." class="form-control"><br>
                                                                                        <button onClick="javascript: document.getElementById('estado').value=8;" class="btn btn-danger tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium mg-b-20" data-dismiss="modal2" aria-label="Close">
                                                                                        Confirmar</button>
                                                                                        <button onClick="javascript: requerimientos( false );" type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium mg-b-20" data-dismiss="modal" aria-label="Close">
                                                                                        Cancelar</button>
                                                                                    
                                                                                    </div>                                                                                    
                                                                                    </div><!-- modal-body -->                                                                                    
                                                                                </div><!-- modal-content -->
                                                                                </div><!-- modal-dialog -->
                                                                            </div><!-- modal -->
                                                                    <?php }//fin if modal?>
                                                                </p>
                                </form>                        
                    </div>
                </div>
        </div>