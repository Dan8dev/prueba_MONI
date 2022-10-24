<?php
$exp = explode('/', $_SERVER['PHP_SELF']);
$direcc = array_pop($exp);
$dir = substr($direcc, 0, strrpos($direcc,'.'));
$nAfiliado = new Afiliados();
$d = $nAfiliado->buscarDireccion($dir, $usuario['data']['idAsistente']);
$contactos = $nAfiliado->consultar_directorio_institucion(13);
$carreras_asign = $nAfiliado->cunsultar_si_generacion_institucion($usuario['data']['idAsistente'], 13);
//echo $d;
$p = [];
$vistas=$nAfiliado->obtenerVistas();

foreach($vistas as $campo => $valor){
  if($campo == 'data'){
    $vis = $valor;
  }
}
$publica = false;
foreach($vis as $campo => $valor){
  if($valor['directorio'] == $dir && $valor['publica'] == 1){
    $publica = true;
  }
}
foreach ($vistas['data'] as $vista) {
  if(intval($vista['publica']) == 1 && intval($vista['menu']) == 1){
    $p[] = $vista['idVista'];
  }
}

if($d['data'] > 0 || $publica){

  $permisos=$nAfiliado->buscarPermisos($usuario['data']['idAsistente']);

  foreach($permisos['data'] as $campo => $valor){
    array_push($p, $valor['vista']);
  }
  $obtener_institucion = $nAfiliado->obtenerCarrera($_SESSION['alumno']['id_prospecto']); //si es alumno de la carrera TSU se habilita vista documentación si no se quita
}else{
   if($dir != 'pagos'){
    header('Location: page-profile.php');
    // die();
   }
}

$alumnoD = $_SESSION['alumno']['id_prospecto'].'-'.$_SESSION['alumno']['id_afiliado'];
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Twitter -->
    <meta name="twitter:site" content="@ColegioConacon">
    <meta name="twitter:creator" content="@ColegioConacon">
    <meta name="twitter:card" content="Colegio Nacional de Consejeros">
    <meta name="twitter:title" content="CONACON">
    <meta name="twitter:description" content="Únete a la red más grande de Consejeros">
    <meta name="twitter:image" content="#">
    <!-- Facebook -->
    <meta property="og:url" content="https://www.facebook.com/ColegioConacon/">
    <meta property="og:title" content="CONACON">
    <meta property="og:description" content="Únete a la red más grande de Consejeros">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="CONACON" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://conacon.org/moni/siscon" />
    <meta property="og:image" content="https://conacon.org/moni/siscon/app/img/logoMetas.png" />
    <meta property="og:description" content="Únete a la red más grande de Consejeros" />
    <!-- Meta -->
    <meta name="description" content="Colegio nacional de consejeros. CONACON">
    <meta name="author" content="CONACON TI">

    <title>CONACON</title>

    <!-- vendor css -->
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
    <link href="../lib/highlightjs/github.css" rel="stylesheet">
    <link href="../lib/jquery.steps/jquery.steps.css" rel="stylesheet">
    
    <link rel="icon" type="imge/png" href="img/favicon.png">

    <!--Datatables-->
    <link href="../../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- CANVAS -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <script src="../../assets/js/educate/scripts.js"></script>
    <script>
      
      var encrypt = CryptoJS.AES.encrypt('<?=$alumnoD?>', key).toString();
      localStorage.setItem('alumno',encrypt);
    </script>
    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
    <link rel="stylesheet" href="../css/alertas.css">
    <link rel="stylesheet" href="../css/stylesnew.css">
    <link rel="stylesheet" href="../lib/datatables/jquery.dataTables.css">
    <link href="../lib/SpinKit/spinkit.css" rel="stylesheet">
    <link rel="stylesheet" href="../../facturacion/design/css/styles.css">

    <style type="text/css">
      #tablaComentariosArchivo th{
        width: 100%!important; 
      }
      .fullscreen{
        width:90%!important;
      }
      .img_apoyo{
        box-shadow: black 0px 0px 1px 0px;
      }
      .swal-button--cancel {
        color: #555;
        background-color: #efefef !important;
      }
    </style>

  </head>

  <body>
    <!-- ########## START: LEFT PANEL ########## -->
    <div class="br-logo"><a href="#"><span><img src="img/logoT.png" width="100%"></span></a></div>
    <div class="br-sideleft overflow-y-auto">
      <div class="br-sideleft-menu">
      <?php
      // if($d['data'] == 1){
        $arr_v = [];
        $excluir = [22, 24];
        
        if(!in_array(7, $p) && $carreras_asign > 0){
          array_push($p, 7);
        }
        if(!in_array(2, $p) && $carreras_asign > 0){
          array_push($p, 2);
        }
        for($i=0 ; $i < count($vis) ; $i++){
          if(in_array($vis[$i]['idVista'], $p) && !in_array($vis[$i]['idVista'],$excluir)){
            $item = '<a href="'.$vis[$i]['directorio'].'.php" class="br-menu-link"><div class="br-menu-item"><img src="../app/img/icons/'.$vis[$i]['icono'].'.svg"><span class="menu-item-label">'.$vis[$i]['nombre'].'</span></div></a>';
            array_push($arr_v, $item);
          }
        }
        if(!empty($contactos)){
          $item = '<!--<a href="" data-toggle="modal" data-target="#contactos_modal" class="br-menu-link"><div class="br-menu-item">
          <i class="menu-item-icon fa fa-whatsapp tx-22"></i><span class="menu-item-label">Directorio de contacto</span></div></a>-->';
            array_push($arr_v, $item);
        }
        foreach($arr_v as $value){
          echo $value;
        }
      // }
      ?>
      <?php if($usuario['data']['estatus'] == 10): ?>
      <a href="RegistrarAlumno.php" class="br-menu-link"><div class="br-menu-item"><img src="../app/img/icons/moreuser.svg"><span class="menu-item-label">Registrar Alumno</span></div></a>
      <a href="ExpedienteClinico.php" class="br-menu-link"><div class="br-menu-item"><img src="../app/img/icons/moreuser.svg"><span class="menu-item-label">Expediente Clínico</span></div></a>
      <?php endif; ?>
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
              <span class="logged-name hidden-md-down"><?php echo $usuario['data']['nombre']; ?></span>
              <?php $foto = (file_exists("img/afiliados/".$usuario['data']['foto']) ? "img/afiliados/".$usuario['data']['foto'] : 'https://conacon.org/moni/siscon/img/default.jpg'); ?>
              <img src="<?php echo $foto; ?>" class="wd-32 rounded-circle" alt="">
              <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-200">
              <ul class="list-unstyled user-profile-nav">
              <li><a href="page-profile.php"><i class="icon ion-person"></i> Mi Perfil</a></li>
              <li><a href="editar-perfil.php"><i class="icon ion-gear-b"></i> Editar Perfil</a></li>
              <li><a href="editar_acceso.php"><i class="icon ion-unlocked"></i> Cambiar Contraseña</a></li>
              <li><a href="log-out.php"><i class="icon ion-power"></i> Cerrar sesion</a></li>
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
          <label class="sidebar-label pd-x-25 mg-t-25">Calendario Clases</label>
		  <label class="pd-x-25" style="font-size: smaller;">
			  <span style="width: 15px;background-color: #3a658f;" class="badge tx-12">&nbsp;</span>
			  Certificación OTA
		 </label>
		 <label class="pd-x-25" style="font-size: smaller;">
			  <span style="width: 15px;background-color: #8F8E3A;" class="badge tx-12">&nbsp;</span>
			  Eventos
		 </label>
          <div class="datepicker sidebar-datepicker"></div>

			


        </div>
      </div><!-- tab-content -->
    </div><!-- br-sideright -->
    <!-- ########## END: RIGHT PANEL ########## --->
    <div id="contactos_modal" class="modal fade">
      <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14">
          <div class="modal-header pd-y-20 pd-x-25">
            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Directorio de contacto</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pd-25">
            <!-- <h4 class="lh-3 mg-b-20"><a href="" class="tx-inverse hover-primary">Why We Use Electoral College, Not Popular Vote</a></h4> -->
            <?php
              foreach($contactos as $contact){
                $whats = "";
                  if($contact['telefono'] != ''){
                    $whats = '<p class="mb-0"><a class="text-primary" href="https://wa.me/521'.$contact['telefono'].'" target="_blank"><i class="fa fa-whatsapp"></i> Enviar un mensaje</a></p>';
                  }
                  if($contact['correo'] != ''){
                    $whats .= '<p class="mb-0"><a class="text-primary" href="mailto:'.$contact['correo'].'" target="_blank"><i class="fa fa-envelope"></i> Enviar un correo electrónico</a></p>';
                  }
                  if($contact['clave'] != 'control' || ($contact['clave'] == 'control' && $carreras_asign > 0)):
                ?>
              <div class="col-12 card mb-3">
                <div class="card-body">
                  <h4 class="mb-3"><?php echo $contact['nombre_contacto']; ?></h4>
                  <?php echo $whats; ?>
                </div>
              </div>
            <?php
               endif;
              }
            ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div><!-- modal-dialog -->
    </div>
