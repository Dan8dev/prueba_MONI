<?php
$xml = new SimpleXMLElement('<xml version="1.0" encoding="UTF-8" standalone="true"/>');

for ($i = 1; $i <= 1; ++$i) {
    $track = $xml->addChild('Dec');
    $track->addAttribute('xmlns','https://www.siged.sep.gob.mx/certificados/');
    $track->addAttribute('noCertificadoResponsable','00001000000411682602');
    $track->addAttribute('certificadoResponsable','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');
    $track->addAttribute('sello','');
    $track->addAttribute('folioControl','FOLIO DE CONTROL INTERNO DE LA UNIVERSIDAD');
    $track->addAttribute('tipoCertificado','5');
    $track->addAttribute('Version','3.0');

    $ServFirm = $track->addChild('ServicioFirmante');
    $ServFirm->addAttribute('idEntidad','30');

    $Ipes = $track->addChild('Ipes');
    $Ipes->addAttribute('idEntidadFederativa', '30');
    $Ipes->addAttribute('idCampus', '300478');
    $Ipes->addAttribute('idNombreInstitucion', '20103');

    $RespIpes = $Ipes->addChild('Responsable');
    $RespIpes->addAttribute('idCargo','3');
    $RespIpes->addAttribute('segundoApellido','PÉREZ');
    $RespIpes->addAttribute('primerApellido','CONDE');
    $RespIpes->addAttribute('nombre','MARCO ANTONIO');
    $RespIpes->addAttribute('curp','COPM780714HVZNRR01');

    $Rvoe = $track->addChild('Rvoe');
    $Rvoe->addAttribute('fechaExpedicion','');
    $Rvoe->addAttribute('numero','');

    $Carrera = $track->addChild('Carrera');
    $Carrera->addAttribute('calificacionMinimaAprobatoria','6');
    $Carrera->addAttribute('calificacionMaxima','10');
    $Carrera->addAttribute('calificacionMinima','5');
    $Carrera->addAttribute('idNivelEstudios','');
    $Carrera->addAttribute('clavePlan','');
    $Carrera->addAttribute('idTipoPeriodo','');
    $Carrera->addAttribute('idCarrera','');

    //$track->addChild('Carrera');
    $Alumno = $track->addChild('Alumno');
    $Alumno->addAttribute('segundoApellido','');
    $Alumno->addAttribute('primerApellido','');
    $Alumno->addAttribute('nombre','');
    $Alumno->addAttribute('curp','');
    $Alumno->addAttribute('fechaNacimiento','');
    $Alumno->addAttribute('idGenero','');
    $Alumno->addAttribute('numeroControl','');

    $Expedicion = $track->addChild('Expedicion');
    $Expedicion->addAttribute('idLugarExpedicion','30');
    $Expedicion->addAttribute('fecha','');
    $Expedicion->addAttribute('idTipoCertificacion','79');

    $Asignaturas = $track->addChild('Asignaturas');
    $Asignaturas->addAttribute('numeroCiclos','');
    $Asignaturas->addAttribute('creditosObtenidos','');
    $Asignaturas->addAttribute('totalCreditos','');
    $Asignaturas->addAttribute('promedio','');
    $Asignaturas->addAttribute('asignadas','');
    $Asignaturas->addAttribute('total','');

    $AsignaturaChild = $Asignaturas->addChild('Asignatura');
    $AsignaturaChild->addAttribute('creditos','');
    $AsignaturaChild->addAttribute('idTipoAsignatura','');
    $AsignaturaChild->addAttribute('idObservaciones','');
    $AsignaturaChild->addAttribute('calificacion','');
    $AsignaturaChild->addAttribute('ciclo','');
    $AsignaturaChild->addAttribute('idAsignatura','');
}

Header('Content-type: text/xml');
//echo "<xmp>".$xml->saveXML()."</xmp>";
//$xml->save("report.xml");
$xml->asXML("../doc.xml");


?>