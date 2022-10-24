
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Twitter -->
    <meta name="twitter:site" content="@ConsejoCertificador">
    <meta name="twitter:creator" content="@ConsejoCertificador">
    <meta name="twitter:card" content="Consejo Certificador">
    <meta name="twitter:title" content="ConsejoCertificador">
    <meta name="twitter:description" content="">
    <meta name="twitter:image" content="#">
    <!-- Facebook -->
    <meta property="og:url" content="https://www.facebook.com/ConsejoCertificador/">
    <meta property="og:title" content="Consejo Certificador">
    <meta property="og:description" content="">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="Consejo Certificador" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://moni.com.mx/consejo" />
    <meta property="og:image" content="https://moni.com.mx/consejo/app/img/logoMetas.png" />
    <meta property="og:description" content="" />
    <!-- Meta -->
    <meta name="description" content="Consejo Certificador">
    <meta name="author" content="Consejo Certificador TI">

    <title>CONSEJO CERTIFICADOR</title>

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
    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
    <link rel="stylesheet" href="../css/alertas.css">

    <style type="text/css">
      .overlay-evento{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 100%;
            opacity: 0;
            transition: .5s ease;
            /*background-color: #4479ABC4;*/
        }
        .overlay-evento-content {
            color: white;
            font-size: 16px;
            position: absolute;
            top: 50%;
            left: 46%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            /* text-align: center; */
            width: 100%;
        }
        .overlay-evento:hover{
          opacity: 1;
        }

        .ul-two-cols {
          columns: 2;
          -webkit-columns: 2;
          -moz-columns: 2;
        }

        /*// Medium devices (tablets, 768px and up)*/
        @media (min-width: 768px) {
          .modal-lg{
            width: 95%;
          }
        }

        /*// Large devices (desktops, 992px and up)*/
        @media (min-width: 992px) {
          .modal-lg{
            width: 80%;
          }
        }

        /*// X-Large devices (large desktops, 1200px and up)*/
        @media (min-width: 1200px) {
          .modal-lg{
            width: 60%;
          }
        }
        .align-bottom {
            vertical-align: bottom !important;
        }
        .custom-file-control:lang(en):empty::after {
          content: "Elegir archivo...";
        }
              .custom-file-control:lang(en)::before {
          content: "Buscar";
        }
         .my-class a {
			background:#3A658F !important;
		  }
    </style>
  </head>

  <body>

    <!-- ########## START: LEFT PANEL ########## -->
    <div class="br-logo"><a href=""><span><img src="img/logoT.png" width="90%"></span></a></div>
    <div class="br-sideleft overflow-y-auto">
      <div class="br-sideleft-menu">
        <label class="sidebar-label pd-x-15 mg-t-20">Menú</label>
        <a href="panel.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-home-outline tx-22"></i>
            <span class="menu-item-label">Panel</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->

        <a href="cursos.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-pencil tx-22"></i>
            <span class="menu-item-label">Clases</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->

        <a href="gestionarHotel.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-hotel tx-22"></i>
            <span class="menu-item-label"> Gestionar Hotel</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->

        <a href="gestionarAlimentos.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-apple tx-22"></i>
            <span class="menu-item-label">Cupones Alimentos</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->

        <a href="gestionarTransporte.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-bus tx-22"></i>
            <!-- <i class="menu-item-icon fa fa-apple tx-22"></i> -->
            <span class="menu-item-label">Gestionar Transporte</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->

        <a href="proximosEventos.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-calendar tx-22"></i>
            <span class="menu-item-label">Eventos</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        
        <!--<a href="cismac.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-calendar tx-22"></i>
            <span class="menu-item-label">Pagos</span>
          </div>--><!-- menu-item -->
        <!--</a>--><!-- br-menu-link -->

        <!--<a href="inicioCursos.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-calendar tx-22"></i>
            <span class="menu-item-label">Certificaciones</span>
          </div>--><!-- menu-item -->
        <!--</a>--><!-- br-menu-link -->

        <!--<a href="memoriaDigital.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-monitor tx-22"></i>
            <span class="menu-item-label">Videoteca</span>
          </div>--><!-- menu-item -->
        <!--</a>--><!-- br-menu-link -->

        <!--<a href="subirDocumentos.php" class="br-menu-link">
            <div class="br-menu-item">
                <i class="menu-item-icon icon ion-ios-folder tx-22"></i>
                <span class="menu-item-label">Documentación</span>
            </div>--><!-- menu-item -->
        <!--</a>--><!-- br-menu-link -->

        <!--<a href="identidad.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-users tx-20"></i>
            <span class="menu-item-label">Identidad</span>
          </div>--><!-- menu-item -->
        <!--</a>-->

        <a href="editar-perfil.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-compose tx-22"></i>
            <span class="menu-item-label">Editar Perfil</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->

        <a href="page-profile.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-person tx-22"></i>
            <span class="menu-item-label">Mi perfil</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
		
		    <a href="pagos.php" class="br-menu-link">
          <div class="br-menu-item">
			      <i class="fa fa-address-card tx-17 lh-0 tx-white op-7"></i>
            <!--<i class="menu-item-icon icon ion-ios-person tx-22"></i>-->
            <span class="menu-item-label">Pagos</span>
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
              <span class="logged-name hidden-md-down"><?php echo $usuario['data']['nombre']; ?></span>
              <img src="img/afiliados/<?php echo $usuario['data']['foto']; ?>" class="wd-32 rounded-circle" alt="">
              <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-200">
              <ul class="list-unstyled user-profile-nav">
              <li><a href="log-out.php"><i class="icon ion-power"></i> Cerrar sesion</a></li>
              <li><a href="editar_acceso.php"><i class="icon ion-unlocked"></i> Cambiar Contraseña</a></li>
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
			  Certificacion OTA
		 </label>
          <div class="datepicker sidebar-datepicker"></div>

			


        </div>
      </div><!-- tab-content -->
    </div><!-- br-sideright -->
    <!-- ########## END: RIGHT PANEL ########## --->
