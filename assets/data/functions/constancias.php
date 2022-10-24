<?php 
	function generar_pdf_constancia($plantilla, $nombre, $nombre_reconocimiento, $salida, $altura = 10, $offset = 0){
		require_once('fpdf183/fpdf.php');
        $pdf = new FPDF('L','cm','Letter');
        $pdf->AddPage();
        $img_type = explode('.',$plantilla);
        $img_type = $img_type[sizeof($img_type)-1];
        $pdf->Image($plantilla,0,0,28,0,strtoupper($img_type));
        // Nombre y Apellido
        $f_size = 35;
        $pdf->SetFont('helvetica','B',$f_size);

        $nombre = strtoupper($nombre);

        $nombre = str_replace('ñ', 'Ñ', $nombre);
        $nombre = str_replace('á', 'Á', $nombre);
        $nombre = str_replace('é', 'É', $nombre);
        $nombre = str_replace('í', 'Í', $nombre);
        $nombre = str_replace('ó', 'Ó', $nombre);
        $nombre = str_replace('ú', 'Ú', $nombre);
        
        $nombre = strtoupper(utf8_decode($nombre));

        $width_plantilla = $pdf->GetPageWidth();
        if($offset > 0){
            $offset = $width_plantilla * ($offset / 97);
            $width_plantilla = $width_plantilla - $offset;
        }
        $max_w_nombre = $width_plantilla - ($width_plantilla * 0.2);

        $current_width_text = $pdf->GetStringWidth($nombre);
        // var_dump($current_width_text);
        // echo "<br>";
        // var_dump($max_w_nombre);
        // die();
        if($current_width_text > $max_w_nombre){
            while ($current_width_text > $max_w_nombre) {
                $f_size--;
                $pdf->SetFont('helvetica','B',$f_size);
                $current_width_text = $pdf->GetStringWidth($nombre);
            }
        }



        // $nom = strtoupper(utf8_decode($nombre));
        $start_t = ($width_plantilla - $current_width_text) / 2 + $offset;
        $pdf->Text($start_t, $altura ,$nombre);
        
        $nombre_r = $nombre_reconocimiento;
        $nombre_reconocimiento = $nombre_reconocimiento.'.pdf';
        $pdf->Output('F', $salida.$nombre_reconocimiento);
        return $nombre_r;
	}
?>
