<?php
   
    try{
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('	'); 
        $xml->startDocument('1.0', 'UTF-8','yes');
        
        $xml->startElement("Dec"); //elemento colegio
            $xml->writeAttribute('xmlns', 'https://www.siged.sep.gob.mx/certificados/');
            $xml->writeAttribute('noCertificadoResponsable','00001000000411682602');
            $xml->writeAttribute('certificadoResponsable', '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');
            $xml->writeAttribute('sello','');
            $xml->writeAttribute('folioControl','FOLIO DE CONTROL INTERNO DE LA UNIVERSIDAD');
            $xml->writeAttribute('tipoCertificado','5');
            $xml->writeAttribute('Version','3');
                
            $xml->startElement("ServicioFirmante"); //elemento director
                $xml->writeAttribute('idEntidad', '30');
            $xml->endElement();

            $xml->startElement("Ipes"); //elemento director
                $xml->writeAttribute('idEntidadFederativa', '20');
                $xml->writeAttribute('idCampus', '300478');
                $xml->writeAttribute('idNombreInstitucion', '20103');
            $xml->endElement();

            $xml->startElement("Responsable"); //elemento director
                $xml->writeAttribute('idCargo', '3');
                $xml->writeAttribute('segundoApellido', 'PÉREZ');
                $xml->writeAttribute('primerApellido', 'CONDE');
                $xml->writeAttribute('nombre', 'MARCO ANTONIO');
                $xml->writeAttribute('curp', 'COPM780714HVZNRR01');
            $xml->endElement();

            $xml->startElement("Rvoe"); //elemento director
                $xml->writeAttribute('fechaExpedicion', '');
                $xml->writeAttribute('numero', '');
            $xml->endElement();

            $xml->startElement("Carrera"); //elemento director
                $xml->writeAttribute('calificacionMinimaAprobatoria', '');
                $xml->writeAttribute('calificacionMaxima', '');
                $xml->writeAttribute('calificacionMinima', '');
                $xml->writeAttribute('idNivelEstudios', '');
                $xml->writeAttribute('clavePlan', '');
                $xml->writeAttribute('idTipoPeriodo', '');
                $xml->writeAttribute('idCarrera', '');
            $xml->endElement();

            $xml->startElement("Alumno"); //elemento director
                $xml->writeAttribute('segundoApellido', '');
                $xml->writeAttribute('primerApellido', '');
                $xml->writeAttribute('nombre', '');
                $xml->writeAttribute('curp', '');
                $xml->writeAttribute('fechaNacimiento', '');
                $xml->writeAttribute('idGenero', '');
            $xml->endElement();

            $xml->startElement("Expedicion"); //elemento director
                $xml->writeAttribute('idLugarExpedicion', '30');
                $xml->writeAttribute('fecha', '');
                $xml->writeAttribute('idTipoCertificacion', '79');
            $xml->endElement();

            $xml->startElement("Asignaturas"); //elemento Asignaturas
                //Estos campos se calculan durante la ejecucion
                $xml->writeAttribute('numeroCiclos', '');
                $xml->writeAttribute('creditosObtenidos', '');
                $xml->writeAttribute('totalCreditos', '');
                $xml->writeAttribute('promedio', '');
                $xml->writeAttribute('asignadas', '');
                $xml->writeAttribute('total', '');

                //Aqui va el for por cada materia 
                $xml->startElement("Asignatura"); //elemento director
                    $xml->writeAttribute('creditos', '');
                    $xml->writeAttribute('idTipoAsignatura', '');
                    $xml->writeAttribute('calificacion', '');
                    $xml->writeAttribute('ciclo', '');
                    $xml->writeAttribute('idAsignatura', '');
                $xml->endElement();
            $xml->endElement(); //elemento Asignaturas

        $xml->endElement(); //fin colegio
        $xml->save("MyXMLfile.xml");
        
        $content = $xml->outputMemory();
        ob_end_clean();
        ob_start();
        header('Content-Type: application/xml; charset=UTF-8');
        header('Content-Encoding: UTF-8');
        header("Content-Disposition: attachment;filename=ejemplo.xml");
        header('Expires: 0');
        header('Pragma: cache');
        header('Cache-Control: private');
        echo $content;
        //
    }catch(Exception $e){
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }
    
?>