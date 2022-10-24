<!-- Iconografía fontawesom  (fa) https://fontawesome.com/ -->

<?php 
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
} 
session_start();
  if (!isset($_SESSION["alumno"])) {
    header('Location: index.php');
    die();
  }else{
    $idusuario=$_SESSION['alumno']['id_afiliado'];
    require_once '../data/Model/AfiliadosModel.php';
    $porospM = new Afiliados();
    $usuario = $porospM->obtenerusuario($idusuario);
    $fechafinmembresia=$porospM->fechafinmembresia($usuario['data']['idAsistente']);
    $fechaactual= date('Y-m-d H:i:s');
    $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

    $datetime1 = new DateTime($fechaactual);
    $datetime2 = new DateTime($fechafinmembresia);
    $interval = $datetime1->diff($datetime2);
    $diasrestantes= substr($interval->format('%R%a días'), 1);
    $dias = rtrim($diasrestantes, ' días');

}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Bracket">
    <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="twitter:image" content="http://themepixels.me/bracket/img/bracket-social.png">

    <!-- Facebook -->
    <meta property="og:url" content="http://themepixels.me/bracket">
    <meta property="og:title" content="Bracket">
    <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

    <meta property="og:image" content="http://themepixels.me/bracket/img/bracket-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/bracket/img/bracket-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

     <!-- Meta -->
    <meta name="description" content="Colegio nacional de consejeros. CONACON">
    <meta name="author" content="CONACON TI">

    <title>CONACON</title>

    <!-- vendor css -->
    <link href="../../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../../lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
	  
	  <link rel="icon" type="imge/png" href="../img/favicon.png">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../../css/bracket.css">
  </head>

  <body>

    <!-- ########## START: LEFT PANEL ########## -->
    <div class="br-logo"><a href=""><span><img src="../img/logoT.png" width="90%"></span></a></div>
    <div class="br-sideleft overflow-y-auto">
      <label class="sidebar-label pd-x-15 mg-t-20">Menú</label>
      <div class="br-sideleft-menu">
        <a href="../panel.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-home-outline tx-22"></i>
            <span class="menu-item-label">Panel</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
		
		<a href="../clases.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-pencil tx-22"></i>
            <span class="menu-item-label">Clases</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
		
        <a href="../proximosEventos.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-calendar tx-22"></i>
            <span class="menu-item-label">Eventos</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <a href="index2.php?event=2" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-calendar tx-22"></i>
            <span class="menu-item-label">Cismac 2021</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <a href="../inicioCursos.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-calendar tx-22"></i>
            <span class="menu-item-label">Certificaciones</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <a href="../memoriaDigital.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-monitor tx-22"></i>
            <span class="menu-item-label">Videoteca</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <a href="../identidad.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-person tx-22"></i>
            <span class="menu-item-label">Identidad corporativa</span>
          </div><!-- menu-item -->
        </a>
		
        <a href="../editar-perfil.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-compose tx-22"></i>
            <span class="menu-item-label">Editar Perfil</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <a href="../page-profile.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-person tx-22"></i>
            <span class="menu-item-label">Mi perfil</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->

      </div><!-- br-sideleft-menu -->
    </div><!-- br-sideleft -->
    <!-- ########## END: LEFT PANEL ########## -->

    <!-- ########## START: HEAD PANEL ########## -->
    <div class="br-header">
      <div class="br-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>
        
      </div><!-- br-header-left -->
      <div class="br-header-right">
        <nav class="nav">
          <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
              <span class="logged-name hidden-md-down"><?php echo $usuario["data"]["nombre"]; ?></span>
              <img src="../img/afiliados/<?php echo $usuario["data"]["foto"]; ?>" class="wd-32 rounded-circle" alt=""> 
              <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-200">
              <ul class="list-unstyled user-profile-nav">
              <li><a href="../log-out.php"><i class="icon ion-power"></i> Cerrar sesion</a></li>
              <li><a href="../editar_acceso.php"><i class="icon ion-unlocked"></i> Cambiar Contraseña</a></li>
              </ul>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        </nav>
        <div class="navicon-right">
          <a id="btnRightMenu" href="" class="pos-relative">
            <i class="icon ion-ios-calendar"></i>
            <!-- start: if statement -->
            <!-- <span class="square-8 bg-danger pos-absolute t-10 r--5 rounded-circle"></span> -->
            <!-- end: if statement -->
          </a>
        </div><!-- navicon-right -->
      </div><!-- br-header-right -->
    </div><!-- br-header -->
    <!-- ########## END: HEAD PANEL ########## -->

    <!-- ########## START: RIGHT PANEL ########## -->
    <div class="br-sideright">
      <ul class="nav nav-tabs sidebar-tabs" role="tablist">

        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" role="tab" href="#calendar"><i class="icon ion-ios-calendar-outline tx-24"></i></a>
        </li>

      </ul><!-- sidebar-tabs -->

      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane pos-absolute a-0 mg-t-60 overflow-y-auto active" id="calendar" role="tabpanel">
          <label class="sidebar-label pd-x-25 mg-t-25">Hoy</label>
          <div class="pd-x-25">
            <h2 id="brTime" class="tx-white tx-lato mg-b-5"></h2>
            <h6 id="brDate" class="tx-white tx-light op-3"></h6>
          </div>
          <label class="sidebar-label pd-x-25 mg-t-25">Calendario Eventos</label>
          <div class="datepicker sidebar-datepicker"></div>




        </div>
      </div><!-- tab-content -->
    </div><!-- br-sideright -->
    <!-- ########## END: RIGHT PANEL ########## --->

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="../panel.php">INICIO</a>
          <span class="breadcrumb-item active">PANEL</span>
        </nav>
      </div><!-- br-pageheader -->

      <div class="br-pagebody">
      <?php
        if (rtrim($interval->format('%R%a días'), ' días')<0) {
          ?>
          <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Tu <strong class="d-block d-sm-inline-block-force"> Membresía</strong> a finalizado <strong class="d-block d-sm-inline-block-force"> renuévala por 1 año </strong> asistiendo al congreso
          </div><!-- alert -->
          <?php  
        }
        else { if($dias<31) {
          # code...
        ?>
        <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php echo $diasrestantes?> </strong> 
        </div><!-- alert -->
        <?php } } ?>
        <!-- start you own content here -->
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <div class="pd-30">
				<h4 class="tx-gray-800 mg-b-5">Evento CISMAC 2021</h4>
				
				<div class="col-sm-12 mb-4">
					<div class="card bd-0">
						<div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
							<h6 class="mg-b-3"><a href="https://cismac.com.mx/" target="_blank" class="text-white mb-10">CISMAC 2021</a></h6>
							<a href="https://cismac.com.mx/" target="_blank" class="btn btn-block btn-primary active btn-with-icon">
								<div class="ht-40 justify-content-between">
									<span class="pd-x-15">Más información</span>
									<span class="icon wd-40"><i class="fa fa-globe"></i></span>
								</div>
							</a>
							<!--<a href="#" target="_blank" class="btn btn-block btn-primary active btn-with-icon">
								<div class="ht-40 justify-content-between">
									<span class="pd-x-15">Pagar ahora</span>
									<span class="icon wd-40"><i class="fa fa-credit-card"></i></span>
								</div>
							</a>-->
						</div><!-- card-body -->
						<img class="card-img-bottom img-fluid" src="https://conacon.org/moni/assets/images/generales/flyers/cismac.png" alt="Image">
              		</div><!-- card -->
					</div>
			
            </div><!-- d-flex -->
        <div class="br-pagebody mg-t-5 pd-x-30">
          <div class="row row-sm">
                <!-- CODE HERE!!! -->
                <?php
                    require 'html_congreso.php';

                ?>
            <!-- CODE HERE!!! -->
          </div><!-- row -->
        </div>
      </div>  
    </div><!-- br-pagebody -->

  </div><!-- br-mainpanel -->
  <!-- ########## END: MAIN PANEL ########## -->

  <!-- Modals -->
  <div id="modal-confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="lbl_modal-confirm" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title m-0" id="lbl_modal-confirm">Confirmar talleres seleccionados.</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body" >
          <h3>
            Confirma la siguiente lista de talleres para reservar su lugar?
          </h3>
          <ul id="lista_talleres_selected">
            
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
          <button onclick="$('#form_apartar_talleres').submit();" class="btn btn-success waves-effect">Continuar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
  <!-- End Modals -->

  <script src="../../lib/jquery/jquery.js"></script>
  <script src="../../lib/popper.js/popper.js"></script>
  <script src="../../lib/bootstrap/bootstrap.js"></script>
  <script src="../../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="../../lib/moment/moment.js"></script>
  <script src="../../lib/jquery-ui/jquery-ui.js"></script>
  <script src="../../lib/jquery-switchbutton/jquery.switchButton.js"></script>
  <script src="../../lib/peity/jquery.peity.js"></script>

  <script src="../../js/bracket.js"></script>
  <script src="../../../assets/js/template/jquery.slimscroll.js"></script>
  <script src="../../../assets/js/template/sweetalert.min.js"></script>
  <script src="../../../assets/pages/clipboard.js"></script>
  <script src="../../../assets/pages/qrcode.js"></script>
      <script src="../../../assets/pages/qrcode.min.js"></script>
  <script src="https://www.paypal.com/sdk/js?client-id=AfcFm_FJBcwJ0-Urg7O8Jb_E0LpsGoO2_Oy6CFCNHWTIrDm09VNo9kCl6VWiYT9GrlT2B_0f-LYwNHQD&currency=MXN" data-sdk-integration-source="button-factory"></script>
  <?php 
  if ($pago['data']['id_asistente']==62) {

    echo "<script src='panel_congreso.js'></script>";

    
  }
  else {
    echo "<script src='pagos_congreso.js'></script>";
    $pago['data']=array('id_asistente'=>$usuario['data']['idAsistente'],
                        'id_evento'=>$_GET['event']);

  }  
      ?>
      <script type="text/javascript">
              new ClipboardJS('.clpb', {
                text: function(trigger) {
                    return trigger.getAttribute('aria-label');
                }
              });
      </script>
      <script src="../../../assets/js/template/app.js"></script>

      <!-- fin scripts -->
      <?php 
      $str = json_encode($pago['data']);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>

</body>
</html>
