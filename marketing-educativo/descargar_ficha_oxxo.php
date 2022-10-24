<?php
include_once "../assets/data/functions/vendor/autoload.php";
$tipo_moneda = $_POST['tipo_moneda_ficha'];
$nombre_concepto = $_POST['nombre_concepto_ficha'];
$monto_pago = $_POST['monto_pago_ficha'];
$referencia = $_POST['referencia_ficha'];
$bar_code_ficha = $_POST['bar_code_ficha'];
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$nombreImagen = "../assets/images/oxxopay_brand.png";
$imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($nombreImagen));


if (@$_POST['sitiomoni']=="monicom") {
	$bar_code_ficha="https://conacon.org/moni/assets/images/bar_codes_oxxo/".$bar_code_ficha;
}else {
	$bar_code_ficha = "../assets/images/bar_codes_oxxo/".$bar_code_ficha;
}	

$bar_code_ficha = "data:image/png;base64," . base64_encode(file_get_contents($bar_code_ficha));
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
			<div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
			<div class="opps-info">
				<div class="opps-brand"><img src="<?php echo $imagenBase64 ?>" alt="OXXOPay"></div>
				<div class="opps-ammount">
					<h3>Monto a pagar</h3>
					<h2><?php echo $monto_pago ?> <sup><?php echo $tipo_moneda ?></sup></h2>
					<p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
				</div>
			</div>
			<div class="opps-reference">
				<h3>Referencia: </h3>
				<h1><?php echo $referencia ?></h1>
			</div>
			<br>
			<div>
				<img src="<?php echo $bar_code_ficha ?>" alt="">
			</div>
			<div class="opps-reference">
				<h5><?php echo $nombre_concepto ?></h5>
			</div>
		</div>
		<div class="opps-instructions">
			<h3>Instrucciones</h3>
			<ol>
				<li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
				<li>Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
				<li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
				<li>Realiza el pago correspondiente con dinero en efectivo.</li>
				<li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
			</ol>
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