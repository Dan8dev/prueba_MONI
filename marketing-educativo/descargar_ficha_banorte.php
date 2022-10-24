<?php
include_once "../assets/data/functions/vendor/autoload.php";
$tipo_moneda = $_POST['tipo_moneda_ficha'];
$nombre_concepto = $_POST['nombre_concepto_ficha'];
$monto_pago = $_POST['monto_pago_ficha'];
$referencia = $_POST['referencia_ficha'];
$nombre_alumno = $_POST['nombre_alumno_banorte_pdf'];
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$nombreImagen = "../assets/images/spei_brand.png";
$imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($nombreImagen));

$sevenelevenlogo = "../assets/images/seveneleven.png";
$imagenBase64seveneleven = "data:image/png;base64," . base64_encode(file_get_contents($sevenelevenlogo));
$yastas = "../assets/images/yastas.png";
$imagenBase64yastas = "data:image/png;base64," . base64_encode(file_get_contents($yastas));
$farmacias = "../assets/images/farmacias.png";
$imagenBase64farmacias = "data:image/png;base64," . base64_encode(file_get_contents($farmacias));
$chedraui = "../assets/images/chedraui.png";
$imagenBase64chedraui = "data:image/png;base64," . base64_encode(file_get_contents($chedraui));
$delsol = "../assets/images/delsol.png";
$imagenBase64delsol = "data:image/png;base64," . base64_encode(file_get_contents($delsol));
$woolwrth = "../assets/images/woolwrth.png";
$imagenBase64woolwrth = "data:image/png;base64," . base64_encode(file_get_contents($woolwrth));
$lacomer = "../assets/images/lacomer.png";
$imagenBase64lacomer = "data:image/png;base64," . base64_encode(file_get_contents($lacomer));
$telecom = "../assets/images/telecom.png";
$imagenBase64telecom = "data:image/png;base64," . base64_encode(file_get_contents($telecom));

//$bar_code_ficha = "../assets/images/bar_codes_oxxo/".$bar_code_ficha;
//$bar_code_ficha = "data:image/png;base64," . base64_encode(file_get_contents($bar_code_ficha));
ob_start();
?>
<html>
<head>
	<link href="styles.css" media="all" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<style type="text/css">
	/* Reset -------------------------------------------------------------------- */
	* 	 { margin: 0;padding: 0; }
	body { font-size: 14px; }
	
	/* OPPS --------------------------------------------------------------------- */
	
	h3 {
		margin-bottom: 10px;
		font-size: 15px;
		font-weight: 600;
		text-transform: uppercase;
	}
	
	.opps {
		width: 496px; 
		border-radius: 4px;
		box-sizing: border-box;
		padding: 0 45px;
		margin: 40px auto;
		overflow: hidden;
		border: 1px solid #b0afb5;
		font-family: "Open Sans", sans-serif;
		color: #4f5365;
	}
	
	.opps-reminder {
		position: relative;
		top: -1px;
		padding: 9px 0 10px;
		font-size: 11px;
		text-transform: uppercase;
		text-align: center;
		color: #ffffff;
		background: #000000;
	}
	
	.opps-info {
		margin-top: 26px;
		position: relative;
	}
	
	.opps-info:after {
		visibility: hidden;
		 display: block;
		 font-size: 0;
		 content: " ";
		 clear: both;
		 height: 0;
	
	}
	
	.opps-brand {
		width: 45%;
		float: left;
	}
	
	.opps-brand img {
		max-width: 150px;
		margin-top: 2px;
	}
	
	.opps-ammount {
		width: 55%;
		float: right;
	}
	
	.opps-ammount h2 {
		font-size: 36px;
		color: #000000;
		line-height: 24px;
		margin-bottom: 15px;
	}
	
	.opps-ammount h2 sup {
		font-size: 16px;
		position: relative;
		top: -2px
	}
	
	.opps-ammount p {
		font-size: 10px;
		line-height: 14px;
	}
	
	.opps-reference {
		margin-top: 14px;
	}
	
	h1 {
		font-size: 27px;
		color: #000000;
		text-align: center;
		margin-top: -1px;
		padding: 6px 0 7px;
		border: 1px solid #b0afb5;
		border-radius: 4px;
		background: #f8f9fa;
	}
	
	.opps-instructions {
		margin: 32px -45px 0;
		padding: 32px 45px 45px;
		border-top: 1px solid #b0afb5;
		background: #f8f9fa;
	}
	
	ol {
		margin: 17px 0 0 16px;
	}
	
	li + li {
		margin-top: 10px;
		color: #000000;
	}
	
	a {
		color: #1155cc;
	}
	
	.opps-footnote {
		margin-top: 22px;
		padding: 22px 20 24px;
		color: #108f30;
		text-align: center;
		border: 1px solid #108f30;
		border-radius: 4px;
		background: #ffffff;
	}
    </style>

</head>
<body>
	<div class="opps">
		<div class="opps-header">
			<div class="opps-reminder">Ficha digital.</div>
			<div class="opps-info">
				<div class="opps-brand"><img src="<?php echo $imagenBase64 ?>" alt="OXXOPay"></div>
				<div class="opps-ammount">
					<h3>Monto a pagar</h3>
					<h2><?php echo $monto_pago ?> <sup>MXN</sup></h2>
				</div>
			</div>
			<div class="opps-reference">
				<h3>Referencia: </h3>
				<h1><?php echo $referencia ?></h1>
			</div>
			<br>
			<div>
				<img src="<?php //echo $bar_code_ficha ?>" alt="">
			</div>
			<div class="opps-reference">
                <h5>CONCEPTO:</h5>
				<h5><?php echo $nombre_concepto ?></h5>
			</div>
            <div class="opps-reference">
                <br>
                <h5>UNIVERSIDAD DEL CONDE</h5>
                <br>
                <h5>CONVENIO: 118868</h5>
                <br>
                <h5>CUENTA: 0823622605</h5>
                <br>
                <h5>CUENTA CLABE: 072 650 008236226054</h5>
                <br>
                <h5>ALUMNO: <span> <?php echo $nombre_alumno; ?> </span>
                </h5>
            </div>
		</div>
		<div class="opps-instructions">
			<h3>Instrucciones</h3>
			<ol>
                <li>Acudir con tu ficha de pago a cualquiera de nuestros corresponsales.</li>
                <li>Indicar al cajero que se realizará un <b>pago de servicios</b> y proporcionarle la ficha</li>
                <li>El cajero solicitará el pago. En corresponsales la única forma de pago permitida es en efectivo. es importante considerar que se cobrará una comisión adicional al importe de pago</li>
                <li> <strong>Reporta tu pago.</strong></li>
			</ol>
            <br>
            <div>
                <img src="<?php echo $imagenBase64seveneleven ?>" alt="">
                <img src="<?php echo $imagenBase64yastas ?>" alt="">
                <img src="<?php echo $imagenBase64farmacias ?>" alt="">
                <img src="<?php echo $imagenBase64chedraui ?>" alt="">
                <img src="<?php echo $imagenBase64delsol ?>" alt="">
                <img src="<?php echo $imagenBase64woolwrth ?>" alt="">
                <img src="<?php echo $imagenBase64lacomer ?>" alt="">
                <img src="<?php echo $imagenBase64telecom ?>" alt="">
            </div>
		</div>
	</div>	
</body>
</html>

<?php


$html = ob_get_clean();
$dompdf->loadHtml($html);
$dompdf->render();
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=documento.pdf");
echo $dompdf->output();


?>