<?php
  // $solicitud = $_SERVER['REQUEST_URI'];
  // $solicitud = explode("/",$solicitud);
  // array_shift($solicitud);
  // Bootstrap Icons  https://icons.getbootstrap.com/
  $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Jun.","Jul.","Agosto","Sept.","Oct.","Nov.","Dic."];
  $detalles = null;

  if(isset($_GET['e'])){
    $solicitud = $_GET['e'];
  }else{
    $solicitud = "";
  }
  $solicitud = str_replace('/','',$solicitud);
  $detalles = null;
  require '../assets/data/Model/conexion/conexion.php';
  require '../assets/data/Model/eventos/eventosModel.php';
  require '../assets/data/Controller/eventos/initControler.php';
  require '../assets/data/Model/institucion/institucionModel.php';
    
    $info = getDataEvento($solicitud)['data'];
    
	$nextTuesday = strtotime('next thursday');
    $nextTuesday = date("Y-m-d", $nextTuesday);

    if(sizeof($info) > 0){
      $detalles = $info[0];
      $fechaE = explode("-", $nextTuesday);
      $fechaE = $fechaE[2]." de ".$meses[intval($fechaE[1])-1];
    }
	
	$instM = new Institucion();
    $instituciones = $instM->consultarTodoInstituciones();
    $options = "";
    for ($i=0; $i < sizeof($instituciones['data']); $i++) { 
        if($instituciones['data'][$i]['fundacion'] == '1'){
            $options.="<option value='{$instituciones["data"][$i]["id_institucion"]}'>{$instituciones["data"][$i]["nombre"]}</option>";
        }
    }
    $options.="<option value='87'>OTRO</option>";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="icon" href="assets/images/favicon.png" sizes="35x35" type="image/png">
        <title>Registro</title>

        <link rel="stylesheet" href="assets/css/all.min.css">
        <link rel="stylesheet" href="assets/css/flaticon.css">
        <link rel="stylesheet" href="assets/css/animate.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/jquery.fancybox.min.css">
        <link rel="stylesheet" href="assets/css/perfect-scrollbar.css">
        <link rel="stylesheet" href="assets/css/slick.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/responsive.css">
        <link rel="stylesheet" href="assets/css/color.css">
		
		<link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">
		
		  <meta name="image" content="https://conacon.org/assets/img/logoMetas_amorconamor.png">
		  <!-- Open Graph data -->
		  <meta property="og:title" content="REGISTRO" />
		  <meta property="og:type" content="website" />
		  <meta property="og:url" content="https://moni.com.mx/eventos/?e=<?php echo $detalles["nombreClave"];?>" />
		  <meta property="og:image" content="https://conacon.org/assets/img/logoMetas_amorconamor.png" />
		  <meta property="og:description" content="<?php echo $detalles["descripcion"];?>" />
		  <!-- Schema.org for Google -->
		  <meta itemprop="name" content="REGISTRO">
		  <meta itemprop="description" content="https://moni.com.mx/eventos/?e=<?php echo $detalles["nombreClave"];?>" />
		  <meta itemprop="image" content="Colegio Nacional De Consejeros" />
			  
		  <!-- Open Graph - Article -->
		  <meta name="article:section" content="Social Media">
		  <meta name="article:author" content="Colegio Nacional De Consejeros">
		  <meta name="article:tag" content="https://moni.com.mx/eventos/?e=<?php echo $detalles["nombreClave"];?>" />
            <style>
                .nav-item {
                    color: #fff;
                }
            </style>
    </head>
    <body>
        <main>
            <header class="stick style1 w-100">
                <div class="container">
                    <div class="logo-menu-wrap w-100 d-flex flex-wrap justify-content-between align-items-start">
                        <!--<div class="logo"><h1 class="mb-0"><a href="https://cismac.com.mx/" title="Home"><img class="img-fluid" src="assets/images/logo.png" alt="Logo" srcset="assets/images/retina-logo.png"></a></h1></div>Logo -->
                        <nav class="d-inline-flex align-items-center">
                            <div class="header-left">
                                <ul class="mb-0 list-unstyled d-inline-flex">
                                    <li class="menu-item-has-children"><a href="https://cismac.com.mx/" title="">Inicio</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div><!-- Logo Menu Wrap -->
                </div>
            </header><!-- Header -->
            <div class="menu-wrap">
                <span class="menu-close"><i class="fas fa-times"></i></span>
                <ul class="mb-0 list-unstyled w-100">
                    <li class="menu-item-has-children"><a href="https://cismac.com.mx/" title="">Inicio</a></li> 
                </ul>
            </div><!-- Menu Wrap -->
            <div class="login-popup-wrap position-fixed h-100 text-center d-flex flex-wrap align-items-center justify-content-center w-100">
                <div class="login-popup-inner d-inline-block w-100">
                    <h3 class="mb-0">Sign In</h3>
                    <form>
                        <input class="w-100" type="email" placeholder="Email Address">
                        <input class="w-100" type="password" placeholder="Password">
                        <button class="thm-btn fill-btn" type="submit">Login<span></span></button>
                        <a class="d-inline-block" href="javascript:void(0);" title="">Forget A Password</a>
                    </form>
                </div>
            </div><!-- Login Popup -->
            <?php if($detalles !== null): ?>
            <section>
                <div class="w-100 pt-180 pb-180 page-title-wrap text-center black-layer opc5 position-relative">
                    <div class="fixed-bg" style="background-image: url(../assets/images/generales/fondos/<?php echo $detalles["imgFondo"] ?>);"></div>
                    <div class="container">
                        <div class="page-title-inner d-inline-block">
                            <h1 class="mb-0"><?php echo $detalles["tipo"];?></h1>
                            <h1 class="mb-0"><?php echo $detalles["titulo"];?></h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Registro</li>
                                </ol>
                        </div>
                    </div>
                </div><!-- Page Title Wrap -->
            </section>
            <?php else: ?>

            <section>
                <div class="w-100 pt-140 pb-120 position-relative">
                    <div class="container">
                        <div class="event-detail w-100">
                            <div class="event-detail-info w-100">
                            <h1 class="mb-0">No tenemos información referente al evento que estás buscando</h1>
                                <div class="row align-items-center">
                                    <div class="col-md-12 col-sm-12 col-lg-6">
                                        <span class="thm-clr d-block">¿Necesitas <strong>ayuda?</strong></span>
                                        <h2 class="mv-0">Contáctanos</h2>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-lg-6">
                                        <div class="about-info-wrap w-100">
                                            <div class="row">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>                     
                            </div>        
                        </div>
                    </div>     
                </div>                  
            </section>
            <?php endif; ?>                        
            <?php if($detalles !== null): ?>

            <section>
                <div class="w-100 pt-140 pb-120 position-relative">
                    <div class="container">
                        <div class="event-detail w-100">
                            <div class="event-detail-info w-100">
                                <div class="row align-items-center">
                                    <div class="col-md-12 col-sm-12 col-lg-6">
                                        <span class="thm-clr d-block">¡Te esperamos! <strong> </strong></span>
                                        <h2 class="mv-0"><?php echo $detalles["titulo"];?></h2>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-lg-6">
                                        <div class="about-info-wrap w-100">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr flaticon-tickets"></i>
                                                        <div class="about-info-inner">
                                                            <span>Accesos:</span>
                                                            <p class="mb-0">Disponibles</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr far fa-calendar-alt"></i>
                                                        <div class="about-info-inner">
                                                            <span>Inicio</span>
                                                            <p class="mb-0"><?php echo $fechaE; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr flaticon-coupon"></i>
                                                        <div class="about-info-inner">
                                                            <span>Código promocional:</span>
                                                            <p class="mb-0"><?php echo $detalles["codigoPromocional"] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr flaticon-work"></i>
                                                        <div class="about-info-inner">
                                                            <span>Modalidad:</span>
                                                            <p class="mb-0"><?php echo $detalles["modalidadEvento"] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--<div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr flaticon-pin-1"></i>
                                                        <div class="about-info-inner">
                                                            <span><?php echo $detalles["direccion"] ?></span>
                                                            <p class="mb-0"><?php echo $detalles["estado_nom"] ?></p>
                                                            <p class="mb-0"><?php echo $detalles["pais_nom"] ?></p>
                                                        </div>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-detail-img position-relative w-100">
                                    <img class="img-fluid w-100" src="../assets/images/generales/flyers/<?php echo $detalles["imagen"] ?>" alt="Event Detail Image">
                                </div>

                                <h2 class="mv-0 my-4">Programa</h2>
                                <!--Codigo carrusel de imagenes-->
                                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                          <ol class="carousel-indicators">
                                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                                            <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                                            <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
                                          </ol>
                                         <div class="carousel-inner">
                                            <div class="carousel-item active">
                                              <img class="d-block w-100" src="../assets/images/generales/flyers/modulo1.jpg" alt="primer slide">
                                            </div>
                                           <div class="carousel-item">
                                             <img class="d-block w-100" src="../assets/images/generales/flyers/modulo2.jpg" alt="segundo slide">
                                           </div>
                                           <div class="carousel-item">
                                             <img class="d-block w-100" src="../assets/images/generales/flyers/modulo3.jpg" alt="tercer slide">
                                           </div>
                                           <div class="carousel-item">
                                             <img class="d-block w-100" src="../assets/images/generales/flyers/modulo4.jpg" alt="cuarto slide">
                                           </div>
                                           <div class="carousel-item">
                                             <img class="d-block w-100" src="../assets/images/generales/flyers/modulo5.jpg" alt="quinto slide">
                                           </div>
                                           <div class="carousel-item">
                                             <img class="d-block w-100" src="../assets/images/generales/flyers/modulo6.jpg" alt="sexto slide">
                                           </div>

                                          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                          </a>
                                          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                          </a>
                                        </div>
                                    <!--Codigo carrusel de imagenes-->
                            </div>
                            <div class="event-detail-content position-relative w-100">
                            <div class="event-detail-feat position-relative w-100">
                                    <h4 class="mb-0">Instrucciones</h4>
                                    <ul class="event-detail-features-list mb-0 list-unstyled w-100">
                                        <li><i class="far fa-calendar-check"></i>Regístrate<span class="d-block">Llena tu formulario, te enviaremos un email con los accesos a tu panel</span></li>
                                        <li><i class="flaticon-listen"></i>Ingresa<span class="d-block"></span></li>
                                        <li><i class="flaticon-user"></i>Perfil<span class="d-block">Completa tu perfil</span></li>
                                    </ul>
                                </div>
                                <div class="event-detail-desc mt-30 position-relative w-100">
                                    <h4 class="mb-0">Registro</h4>
                                    <ul class="nav nav-tabs bg-color1">
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="javascript:void(0)" tofade="persona">Persona</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0)" tofade="clinica">Clínica</a>
                                        </li>
                                    </ul>
                                    <section id="form-persona">
                                    <div class="contact-form-wrap p-0 w-100">
                                        <h3 class="my-4">Ingrese sus datos de contacto.</h3>
                                            <form class="w-100" id="formRegisterToEvent">
                                                <input type="hidden" name="nombre_clave_destino" value="<?php echo($_GET['e']); ?>">
                                                <div class="form-group w-100">
                                                    <div class="response w-100"></div>
                                                </div>
                                                <div class="row mrg20">
                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                        <input class="w-100 name" type="text" name="name"  id="name"placeholder="Nombre" required>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                                        <input class="w-100 fname" type="text" name="paterno" id="paterno" placeholder="Apellido Paterno" required>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                                        <input class="w-100 lname" type="text" name="materno" id="materno" placeholder="Apellido Materno" required>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                        <input class="w-100 email" type="email" name="email" id="email" placeholder="Email" required>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                        <input class="w-100 phone" type="tel" name="telefono" id="telefono" placeholder="Celular" required maxlength="14">
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                        <select name="IDOrganizacion" id="IDOrganizacion" class="w-100 select select-texto">
                                                            <option value="0" selected>Si pertenece a una asociación, elijala</option>
                                                            <!--<option value="3">GENTE DESPERTAR </option>
                                                            <option value="1">RENAPRE A.C. </option>
                                                            <option value="13">AVE FENIX SANACIÓN INTEGRAL </option>
                                                            <option value="2">CASA DE LA ESPERANZA </option>
                                                            <option value="10">COTAI </option>
                                                            <option value="8">CRREAD ZONA 1 </option>
                                                            <option value="5">FUNDACIÓN DEL CONDE </option>
                                                            <option value="12">MI VIDA ES MEJOR A.C. </option>
                                                            <option value="4">RED RCP </option>
                                                            <option value="11">SAWABONA </option>-->
                                                            <?php echo $options; ?>
                                                        </select>
                                                    </div>
                                                    <!--<div class="col-md-10 col-sm-10 col-lg-10">
                                                        <input class="w-100 codigo " type="text" name="inp_codigo_pro" id="inp_codigo_pro" placeholder="Código promocional">
                                                    </div>
                                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                                        <button class="btn btn-primary" type="button" onclick="button_validar_c()">Validar</button>
                                                    </div>-->
                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                    <p class="mt-30">*Regístrate para más información</p>    
                                                    <button class="thm-btn fill-btn" id="submitReg"  type="submit">Regístrate<span></span></button>
                                                </div>
                                                </div>
                                            </form>
                                        </div>
                                    </section>
                                    <section id="form-clinica" style="display:none;">
                                        <div class="contact-form-wrap p-0 w-100">
                                            <h3 class="my-4">CUESTIONARIO PARA AFILIACIÓN A CONACON CENTRO DE TRATAMIENTO</h3>
                                            <form class="w-100" id="formRegisterClinicToEvent">
                                                <input type="hidden" name="nombre_clave_destino" value="<?php echo($_GET['e']); ?>">
                                                    <div class="form-group w-100">
                                                        <div class="response w-100"></div>
                                                    </div>
                                                    <div class="row mrg20">
                                                        <div class="col-12">
                                                            <div class="border rounded mt-3 p-3">
                                                                <div class="row">
                                                                    <label for="">Datos del responsable de la clínica.</label>
                                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                                        <input class="w-100 name" type="text" name="name_cl"  id="name_cl"placeholder="Nombre" required>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                                                        <input class="w-100 fname" type="text" name="paterno_cl" id="paterno_cl" placeholder="Apellido Paterno" required>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                                                        <input class="w-100 lname" type="text" name="materno_cl" id="materno_cl" placeholder="Apellido Materno" required>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                                                        <input class="w-100 lname" type="email" name="emailResp" id="emailRes" placeholder="Email" required>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                                                        <input class="w-100 lname" type="tel" name="telefonoResp" id="telefonoRes" placeholder="Teléfono" required maxlength="14">    
                                                                    </div>
                                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                                        <input class="w-100 lname" type="text" name="Curp" id="Curp" placeholder="CURP" required maxlength="18">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                                            <input class="w-100 name" type="text" id="name_clinica_cl" name="name_clinica_cl" placeholder="Nombre de la clínica o centro" required>
                                                        </div>

                                                        <div class="form-group col-md-12 col-sm-12 col-lg-12 d-none" id = "CoincidenciasClinica">
                                                            <label for="name_clinica_clselect">¿Su clinica se encuentra aquí?</label>
                                                            <select id ="name_clinica_clselect" class="form-control special" type="text">
                                                            </select>
                                                        </div>
                                                        <div id = "FormularioCompleto" class="d-none col-md-12">
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 email" type="email" name="email_cl" id="email_cl" placeholder="Email de la clínica" required>
                                                            </div>
    
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 phone" type="tel" name="telefono_cl" id="telefono_cl" placeholder="Teléfono de contacto" required maxlength="14">
                                                            </div>

                                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                                <select class= "form-control" name="pais_cl" id="pais_cl" required>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                                <select class= "form-control" name="estado_cl" id="estado_cl" required>
                                                                    <option selected="true" value="null" disabled="disabled">Seleccione el estado en el que se encuentra la clínica</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name" type="text" name="direccion_cl" id="direccion_cl" placeholder="Dirección" required maxlength="200">
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name" type="text" name="ciudad_cl" id="ciudad_cl" placeholder="Ciudad" required maxlength="200">
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>Atención a:</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" value="1"  checked="true" id="atencionMixto">
                                                                        <label class="form-check-label" for="atencionMixto">Mixto</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" value="3" id="atencionHombres">
                                                                        <label class="form-check-label" for="atencionHombres">Hombres</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" value="2" id="atencionMujeres">
                                                                        <label class="form-check-label" for="atencionMujeres">Mujeres</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="capacidad_cl" id="capacidad_cl" placeholder="¿Cuál es la capacidad máxima de pacientes?" required >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="pacientes12_cl" placeholder="¿Cuantos pacientes han recibido en los últimos 12 meses?" required >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="    " id="pacientes12_cl" placeholder="¿Qué porcentaje de estos pacientes han culminado su tratamiento?" required >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="pacientesMes_cl" id="pacientesMes_cl" placeholder="¿Cuántos pacientes han recibido en el último mes?" required >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>Reciben pacientes con:</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPacientes1" value="1"  checked="true" id="atencionSustancias">
                                                                        <label class="form-check-label" for="atencionSustancias">Problemas de abuso de sustancias</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPacientes2" value="2" id="atencionAlcohol">
                                                                        <label class="form-check-label" for="atencionAlcohol">Problemas de abuso de alcohol</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPacientes3" value="3" id="atencionOtro">
                                                                        <label class="form-check-label" for="atencionOtro">Otro tipo de abuso</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name" type="text" name="otroTipo" id="otroTipo" placeholder="¿Cuál?">
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Dentro del Centro se cuenta con habitaciones individuales?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultIndividuales" value="1"  checked="true" id="habitacionesIndividualesSi">
                                                                        <label class="form-check-label" for="habitacionesIndividualesSi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultIndividuales" value="2" id="habitacionesIndividualesNo">
                                                                        <label class="form-check-label" for="habitacionesIndividualesNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="numeroHabitacionesIndividuales" required id="numeroHabitacionesIndividuales" placeholder="¿Cuántas?">
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Dentro del Centro se cuenta con habitaciones compartidas?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultCompartidas" value="1"  checked="true" id="habitacionesCompartidasSi">
                                                                        <label class="form-check-label" for="habitacionesCompartidasSi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultCompartidas" value="2" id="habitacionesCompartidasNo">
                                                                        <label class="form-check-label" for="habitacionesCompartidasNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="numeroHabitacionesCompartidas" required id="numeroHabitacionesCompartidas" placeholder="¿Cuántas?" >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="promedioPersonasHabitacion" id="promedioPersonasHabitacion" required placeholder="¿Promedio de personas dentro de la habitación?" >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Dentro de del Centro se cuenta con áreas comunes de descanso y recreación?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultAreasDescanso" value="1"  checked="true" id="areasDescansoSi">
                                                                        <label class="form-check-label" for="areasDescansoSi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultAreasDescanso" value="3" id="areasDescansoNo">
                                                                        <label class="form-check-label" for="areasDescansoNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="numeroareasDescanso" id="numeroareasDescanso" placeholder="¿Cuántas?" required>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Dentro del Centro se realizan sesiones terapéuticas en grupo?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTerapiasGrupo" value="1"  checked="" id="TerapiasGrupoSi">
                                                                        <label class="form-check-label" for="TerapiasGrupoSi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTerapiasGrupo" value="3" id="TerapiasGrupoNo">
                                                                        <label class="form-check-label" for="TerapiasGrupoNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Dentro del Centro se realizan sesiones terapéuticas individuales?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTerapiasIndividuales" value="1"  checked="true" id="TerapiasIndividualesSi">
                                                                        <label class="form-check-label" for="TerapiasIndividualesSi">Si</label>
                                                                    </div>
    
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTerapiasIndividuales" value="2" id="TerapiasIndividualesNo">
                                                                        <label class="form-check-label" for="TerapiasIndividualesNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Se cuenta con los servicios de medicina general?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultMedicina" value="1"  checked="true" id="medicinasi">
                                                                        <label class="form-check-label" for="medicinasi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultMedicina" value="2" id="medicinano">
                                                                        <label class="form-check-label" for="medicinano">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="numerosemanaMedicina" id="numerosemanaMedicina" required placeholder="¿Cuántas horas a la semana?" >
                                                            </div> -->
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Se cuenta con los servicios de psiquiatría?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPsiquiatria" value="1"  checked="true" id="PsiquiatriaSi">
                                                                        <label class="form-check-label" for="PsiquiatriaSi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPsiquiatria" value="2" id="PsiquiatriaNo">
                                                                        <label class="form-check-label" for="PsiquiatriaNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="numerosemanaPsiq" placeholder="¿Cuántas horas a la semana?" id="numerosemanaPsiq" required>
                                                            </div> -->
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Se cuenta con los servicios de psicología?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPsicologia" value="1"  checked="true" id="psicologiaSi">
                                                                        <label class="form-check-label" for="psicologiaSi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPsicologia" value="2" id="psicologiaNo">
                                                                        <label class="form-check-label" for="psicologiaNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="numerosemanaPsicologia" id="numeroareasPsicologia" required placeholder="¿Cuántas horas a la semana?" >
                                                            </div> -->
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Se cuenta con los servicios de enfermería?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultEnfermeria" value="1"  checked="true" id="enfermeriaSi">
                                                                        <label class="form-check-label" for="enfermeriaSi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultEnfermeria" value="3" id="enfermeriaNo">
                                                                        <label class="form-check-label" for="enfermeriaNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="horasemanaEnfe" placeholder="¿Cuántas horas a la semana?" id="horasemanaEnfe" required>
                                                            </div> -->
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿La duración del tratamiento varía según el caso individual de cada persona?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTratamiento" value="1"  checked="true" id="tratamientosi">
                                                                        <label class="form-check-label" for="tratamientosi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTratamiento" value="3" id="tratamientono">
                                                                        <label class="form-check-label" for="tratamientono">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="promedioInternados" id="promedioInternados" required placeholder="Si respondió “Sí” en la pregunta número 12, ¿en promedio cuánto  duran internados los residentes en su institución? (*en meses)" >
                                                            </div> -->
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Se cuenta con un período mínimo de tratamiento intensivo?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPeriodoMin" value="1"  checked="true" id="periodoMinsi">
                                                                        <label class="form-check-label" for="periodoMinsi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPeriodoMin" value="3" id="periodoMinno">
                                                                        <label class="form-check-label" for="periodoMinno">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="tiempoduraMin" id="tiempoduraMin" required placeholder="¿Cuánto tiempo dura? (*En meses)" >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Se cuenta con un período máximo de tratamiento intensivo?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPeriodoMax" value="1"  checked="true" id="periodoMaxsi">
                                                                        <label class="form-check-label" for="periodoMaxsi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPeriodoMax" value="3" id="periodoMaxno">
                                                                        <label class="form-check-label" for="periodoMaxno">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="tiempoduraMax" id="tiempoduraMax" required placeholder="¿Cuánto tiempo dura? (*En meses)"  >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Los pacientes permanecen apartados del exterior las 24 horas del día durante varios meses?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultExt" value="1"  checked="true" id="tiempoduraMaxsi">
                                                                        <label class="form-check-label" for="tiempoduraMaxsi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultExt" value="3" id="tiempoduraMaxno">
                                                                        <label class="form-check-label" for="tiempoduraMaxno">No</label>
                                                                    </div>  
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <input class="w-100 name onlyNumer" type="number" name="cantmeses" id="cantmeses" placeholder="¿Cuántos meses?" >
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                                <label>¿Se cuenta con disponibilidad para visita por parte de familiares durante ese periodo?</label>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultVisit" value="1"  checked="true" id="VisitSi">
                                                                        <label class="form-check-label" for="VisitSi">Si</label>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultVisit" value="3" id="VisitNo">
                                                                        <label class="form-check-label" for="VisitNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>¿Se realizan actividades que impulsen el sentido de comunidad?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultSentido" value="1"  checked="true" id="Sentidosi">
                                                                    <label class="form-check-label" for="Sentidosi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultSentido" value="3" id="Sentidono">
                                                                    <label class="form-check-label" for="Sentidono">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                                            <input class="w-100 name" type="text" name="tipoAct" id="tipoAct" required placeholder="¿Qué tipo de actividades? Describir al mayor detalle posible." >
                                                        </div> -->
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>¿Los miembros que demuestran las conductas esperadas y reflejan los valores y necesidades del Centro son utilizados como ejemplo (modelos de rol)?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultWait" value="1"  checked="true" id="waitSi">
                                                                    <label class="form-check-label" for="waitSi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultWait" value="3" id="waitNo">
                                                                    <label class="form-check-label" for="waitNo">No</label>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>¿Las actividades realizadas se caracterizan por ser ordenadas, divertidas y optimistas?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultCarac" value="1"  checked="true" id="Caracsi">
                                                                    <label class="form-check-label" for="Caracsi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultCarac" value="3" id="Caracno">
                                                                    <label class="form-check-label" for="Caracno">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                                            <input class="w-100 name" type="text" name="example" id="example" required placeholder="Por favor dá algunos ejemplos:">
                                                        </div> -->
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>¿Todos los usuarios son responsables de la gestión diaria de las instalaciones?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultGestion" value="1"  checked="true" id="gestionSi">
                                                                    <label class="form-check-label" for="gestionSi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultGestion" value="3" id="gestionNo">
                                                                    <label class="form-check-label" for="gestionNo">No</label>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>¿Existe un repertorio formal e informal para instruir acerca de la perspectiva de del Centro?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultRepertorio" value="1"  checked="true" id="repertoriosi">
                                                                    <label class="form-check-label" for="repertoriosi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultRepertorio" value="3" id="repertoriono">
                                                                    <label class="form-check-label" for="repertoriono">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                                            <input class="w-100 name" type="text" name="cualrep" id="cualrep" required placeholder="¿Cuál?" >
                                                        </div> -->
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>¿El común denominador de todas las sesiones terapéuticas es el enfoque en los patrones actitudinales o conductuales específicos que el individuo debe modificar?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultComun" value="1"  checked="true" id="comunsi">
                                                                    <label class="form-check-label" for="comunsi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultComun" value="3" id="comunno">
                                                                    <label class="form-check-label" for="comunno">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                                            <input class="w-100 name" type="text" name="denominadorSesion" id="denominadorSesion"  required placeholder="¿Cuál es el común denominador de las sesiones?" >
                                                        </div> -->
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>¿Durante las intervenciones terapéuticas se trata de concientizar al individuo acerca del impacto de su conducta y actitudes tanto en sí mismo como en su entorno social?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultIntervencion" value="1"  checked="true" id="intervencionSi">
                                                                    <label class="form-check-label" for="intervencionSi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultIntervencion" value="3" id="intervencionNo">
                                                                    <label class="form-check-label" for="intervencionNo">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                                            <input class="w-100 name" type="text" name="whointerv" id="whointerv" required placeholder="¿Cómo?" >
                                                        </div> -->
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>¿Durante las intervenciones terapéuticas se instruye a los individuos cómo identificar sentimientos de manera que puedan expresarlos apropiadamente y manejarlos de manera constructiva?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultTerap" value="1"  checked="true" id="Terapsi">
                                                                    <label class="form-check-label" for="Terapsi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultTerap" value="3" id="Terapno">
                                                                    <label class="form-check-label" for="Terapno">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                                            <input class="w-100 name" type="text" name="whotera" id="whotera" required placeholder="¿Cómo?" >
                                                        </div> -->
                                                        <!-- <div class="col-md-12 col-sm-12 col-lg-12 pl-4 pb-4 mt-2 border-bottom">
                                                            <label>Tras la realización del programa, ¿el individuo adquiere las visiones de la vida correcta que le permite adaptarse nuevamente en la sociedad?</label>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultVision" value="1"  checked="true" id="visonsi">
                                                                    <label class="form-check-label" for="visonsi">Si</label>
                                                                </div>
                                                                <div class="col">
                                                                    <input class="form-check-input" type="radio" name="flexRadioDefaultVision" value="3" id="visonno">
                                                                    <label class="form-check-label" for="visonno">No</label>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                                        <p class="mt-30">*Regístrate para más información</p>    
                                                        <button class="thm-btn fill-btn" id="submit" type="submit">Regístrate<span></span></button>
                                                    </div>
                                                       
                                                </div>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div><!-- Event Detail -->
                    </div>
                </div>
            </section>
           
            <?php endif; ?>
            <footer>
                <div class="w-100 pt-120 blue-layer opc1 position-relative">
                    <div class="fixed-bg back-blend-multiply bg-color1" style="background-image: url(assets/images/parallax2.png);"></div>
                    <div class="container position-relative">
                        <div class="clrs-wrap d-flex position-absolute">
                            <i class="bg-color1"></i>
                            <i class="bg-color3"></i>
                            <i class="bg-color1"></i>
                            <i class="bg-color3"></i>
                            <i class="bg-color1"></i>
                            <i class="bg-color3"></i>
                        </div>
                        <div class="footer-wrap w-100 text-center">
                            <div class="footer-inner d-inline-block">
                                <h2 class="mb-0" style="color: #fff;">Evento auspiciado por</h2>
                                <p class="mb-0" style="font-size: 25px;"><b> UNIVERSIDAD DEL CONDE</b></p>
                                <div class="logo d-inline-block"><h1 class="mb-0"><a href="index.html" title=""><img class="img-fluid" src="assets/images/logo2.png" alt="Logo"></a></h1></div>
                                <p class="mb-0">Copyright 2021 TI</p>
                            </div>
                            <div class="footer-bottom d-flex flex-wrap justify-content-between w-100">
                                <p class="mb-0"><i class="thm-clr flaticon-headset"></i>Llámanos:<strong><a  href="tel:+5585262516" title=""> 558 526 25 16 <span></span></a></strong></p>
                            </div><!-- Footer Bottom -->
                        </div>
                    </div>
                </div>
            </footer><!-- Footer -->
        </main><!-- Main Wrapper -->

        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/counterup.min.js"></script>
        <script src="assets/js/jquery.downCount.js"></script>
        <script src="assets/js/jquery.fancybox.min.js"></script>
        <script src="assets/js/perfect-scrollbar.min.js"></script>
        <script src="assets/js/slick.min.js"></script>
        <script src="assets/js/isotope.min.js"></script>
        <script src="assets/js/custom-scripts.js"></script>
		<script src="assets/js/sweetalert.min.js"></script>
        <!-- formulario de registro-->
        <script type="text/javascript">
            $(document).ready(function () {
                cargarPaises();
            });
            $("#name_clinica_clselect").on("change",function(e){
                swal({
                    title: '¡Clinica registrada anteriormente!',
                    icon: 'error',
                    text: 'No es necesario registrarla nuevamente o si lo requiere puede ingresar un nombre de clinica distinto'
                }).then(()=>{
                    $("#name_clinica_cl").val("");
                    $("#CoincidenciasClinica").addClass("d-none");
                    $("#FormularioCompleto").addClass("d-none");
                    //$("#name_clinica_clselect").empty();
                });
            });

            $('#name_clinica_cl').bind('mouseover', function(){
                $(this).attr('multiple','multiple').
                attr('size', $(this).length);   
            }).bind('mouseout', function(){
                $(this).removeAttr('multiple size');
            });

            $('#name_clinica_cl').on("input",function(e){
            var searching = $(this).val();
            $.ajax({
                    type: "POST",
                    url: "../assets/data/Controller/instituciones/institucionesControl.php",
                    data: {action:'busqueda_clinica',
                            search: searching},
                    dataType: 'JSON',
                    success: function (response) {
                        try{
                            if(response.length != 0){
                                $("#CoincidenciasClinica").removeClass("d-none");
                                $("#name_clinica_clselect").empty();
                            }else{
                                $("#CoincidenciasClinica").addClass("d-none");
                                $("#FormularioCompleto").addClass("d-none");
                                $("#name_clinica_clselect").empty();
                            }
                            // var coinc = JSON.parse(response);
                            $("#name_clinica_clselect").html('<option selected="true" value="" disabled="disabled">Seleccione su clinica</option>');
                            $.each(response, function(key, registro){
                                $("#name_clinica_clselect").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
                            });
                            $('#name_clinica_clselect').attr('size',$('#name_clinica_clselect option').length);
                        }catch(e){
                            console.log(e);
                            console.log(response);
                        }
                    }
                });
            });
            function buscar(event){
                    if (event.key === "Enter") {
                        event.preventDefault();
                        var searching = $("#name_clinica_cl").val();
                        var bandera = $("#name_clinica_cl").prop("readonly");
                        $.ajax({
                            type: "POST",
                            url: "../assets/data/Controller/instituciones/institucionesControl.php",
                            data: {action:'busqueda_clinicaCompleta',
                                    search: searching},
                            dataType: 'JSON',
                            success: function (response) {
                                try{
                                    if(response.length != 0){
                                        if(!bandera){
                                            swal({
                                                title: '¡El nombre de clinica ya está registrado!',
                                                icon:'error'
                                            }).then(()=>{
                                                $("#name_clinica_cl").val("");
                                                $("#FormularioCompleto").addClass("d-none");
                                                $("#CoincidenciasClinica").addClass("d-none");
                                                $("#name_clinica_clselect").empty();
                                            });
                                        }
                                    }else{
                                        $("#FormularioCompleto").removeClass("d-none");
                                        $("#CoincidenciasClinica").addClass("d-none");
                                        //$("#name_clinica_clselect").empty();
                                    }
                                }catch(e){
                                    console.log(e);
                                    console.log(response);
                                }
                            }
                        });
                    }
            }
            var el = document.getElementById("name_clinica_cl");
            el.addEventListener("keydown", buscar(e));

            el.addEventListener("focusout", buscar(e));


            $('input[name=flexRadioDefaultPacientes]').on('click',function(){
                if($(this).attr('id') == 'atencionOtro'){
                    $('#otroTipo').attr('required','required');
                }else{
                    $('#otroTipo').removeAttr('required');
                }
                
            });

            $('input[name=flexRadioDefaultIndividuales]').on('click',function(){
                if($(this).attr('id') == 'habitacionesIndividualesNo'){
                    $('#numeroHabitacionesIndividuales').removeAttr('required');
                }else{
                    $('#numeroHabitacionesIndividuales').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultCompartidas]').on('click',function(){
                if($(this).attr('id') == 'habitacionesCompartidasNo'){
                    $('#numeroHabitacionesCompartidas').removeAttr('required');
                    $('#promedioPersonasHabitacion').removeAttr('required');
                }else{
                    $('#numeroHabitacionesCompartidas').attr('required','required');
                    $('#promedioPersonasHabitacion').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultAreasDescanso]').on('click',function(){
                if($(this).attr('id') == 'areasDescansoNo'){
                    $('#numeroareasDescanso').removeAttr('required');
                }else{
                    $('#numeroareasDescanso').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultMedicina]').on('click',function(){
                if($(this).attr('id') == 'medicinano'){
                    $('#numerosemanaMedicina').removeAttr('required');
                }else{
                    $('#numerosemanaMedicina').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultPsiquiatria]').on('click',function(){
                if($(this).attr('id') == 'PsiquiatriaNo'){
                    $('#numerosemanaPsiq').removeAttr('required');
                }else{
                    $('#numerosemanaPsiq').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultPsicologia]').on('click',function(){
                if($(this).attr('id') == 'psicologiaNo'){
                    $('#numeroareasPsicologia').removeAttr('required');
                }else{
                    $('#numeroareasPsicologia').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultEnfermeria]').on('click',function(){
                if($(this).attr('id') == 'enfermeriaNo'){
                    $('#horasemanaEnfe').removeAttr('required');
                }else{
                    $('#horasemanaEnfe').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultTratamiento]').on('click',function(){
                if($(this).attr('id') == 'tratamientono'){
                    $('#promedioInternados').removeAttr('required');
                }else{
                    $('#promedioInternados').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultPeriodoMin]').on('click',function(){
                if($(this).attr('id') == 'periodoMinno'){
                    $('#tiempoduraMin').removeAttr('required');
                }else{
                    $('#tiempoduraMin').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultPeriodoMax]').on('click',function(){
                if($(this).attr('id') == 'periodoMaxno'){
                    $('#tiempoduraMax').removeAttr('required');
                }else{
                    $('#tiempoduraMax').attr('required','required');
                }
                
            });
            $('input[name=flexRadioDefaultExt]').on('click',function(){
                if($(this).attr('id') == 'tiempoduraMaxno'){
                    $('#cantmeses').removeAttr('required');
                }else{
                    $('#cantmeses').attr('required','required');
                }
                
            });
            $('input[name=flexRadioDefaultSentido]').on('click',function(){
                if($(this).attr('id') == 'Sentidono'){
                    $('#tipoAct').removeAttr('required');
                }else{
                    $('#tipoAct').attr('required','required');
                }
                
            });
            $('input[name=flexRadioDefaultCarac]').on('click',function(){
                if($(this).attr('id') == 'Caracno'){
                    $('#example').removeAttr('required');
                }else{
                    $('#example').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultRepertorio]').on('click',function(){
                if($(this).attr('id') == 'repertoriono'){
                    $('#cualrep').removeAttr('required');
                }else{
                    $('#cualrep').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultComun]').on('click',function(){
                if($(this).attr('id') == 'comunno'){
                    $('#denominadorSesion').removeAttr('required');
                }else{
                    $('#denominadorSesion').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultIntervencion]').on('click',function(){
                if($(this).attr('id') == 'intervencionNo'){
                    $('#whointerv').removeAttr('required');
                }else{
                    $('#whointerv').attr('required','required');
                }
                
            });

            $('input[name=flexRadioDefaultTerap]').on('click',function(){
                if($(this).attr('id') == 'Terapno'){
                    $('#whotera').removeAttr('required');
                }else{
                    $('#whotera').attr('required','required');
                }
                
            });


            $("#formRegisterToEvent").on("submit", function(e){
                e.preventDefault();
                fData = new FormData(this);
                fData.append("action","registrar_prospecto");
                fData.append("tipo_prospecto","evento");

                $.ajax({
                    url: '../assets/data/Controller/prospectos/prospectoControl.php',
                    type: "POST",
                    data: fData,
                    contentType: false,
                    processData:false,
                    beforeSend : function(){
                    $("#btnSubmit").attr("disabled","true");
                    $("#name").attr("disabled","true");
                    $("#email").attr("disabled","true");
                    $("#paterno").attr("disabled","true");
                    $("#materno").attr("disabled","true");
                    $("#telefono").attr("disabled","true");
                    },
                    success: function(data){
                    try{
                        json = JSON.parse(data);
                        console.log(json)
                        if (json.estatus == "ok") {
                            if(json.info == 'correo_no_enviado'){
                                swal({
                                    title: 'Registro exitoso',
                                    text: 'sin embargo ocurrió un error al enviar correo, te contactaremos.',
                                    icon: 'info'
                                })
                            }else if(json.info == 'registrado_como_prospecto'){
                                swal({
                                    title: 'Registro exitoso!',
                                    icon: 'success'
                                })
                            }else{
                                swal({
                                    title: 'Registro exitoso!',
                                    text: 'Tus accesos serán enviados por mail, por favor revisa todas tus bandejas.',
                                    icon: 'success'
                                })
                            }
                        }else{
                        mensaje = "Ha ocurrido algo";
                        switch(json.info){
                            case "ya_existe_registro":
                            mensaje = "Parece ser que ya estás registrado para asistir a este evento, confirma tu información."
                            break;
                            case "error_in_back":
                            mensaje = "Ha ocurrido un error al intentar registrarte. Intente mas tarde.";
                            break;
                            case "correo_no_enviado":
                            mensaje = "Ocurrió un error al intentar contactarte, contacta con soporte.";
                            break;
                            case "evento_error":
                            mensaje = "El evento al que estás intentando registrarte no está disponible.";
                            break;
                            case "faltan_datos":
                            mensaje = "Complete el formulario de registro.";
                            break;
                            case "lugares_cubiertos":
                            mensaje = "Por el momento no podemos registrarte, ya que tenemos todos los lugares cubiertos.";
                            break;
                            case "membresia_existente":
                            mensaje = "Ya cuentas con una membresía a CONACON dirijite a tu panel para iniciar sesión.";
                            break;
                            case "correo_existente":
                            mensaje = "El correo ya existe, revisa tu bandeja de entrada para ver tus accesos.";
                            break;
                            case "telefono_no_valido":
                            mensaje = "El numero de telefono introducido no es valido, verifique.";
                            break;
                            default:
                                mensaje = json.info;
                            break;
                        }
                        
                        var link = document.createElement("a");
                        link.href= 'https://conacon.org/moni/siscon';
                        link.text= "conacon.org/moni/siscon"
                        if(json.info == 'membresia_existente'){
                            swal({
                                title: "Información!",
                                text: mensaje,
                                content:link,
                                icon: "info",
                            }).then((value)=>{
                                window.location.reload(true);
                            });
                        }else{
                            swal({
                                title: "Información!",
                                text: mensaje,
                                icon: "info",
                            }).then((value)=>{
                                window.location.reload(true);
                            });
                        }
                        }
                    }catch(e){
                        console.log(e);
                        console.log(data);
                    }
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#formRegisterToEvent")[0].reset();
                    }
                })
            });

            $("#formRegisterClinicToEvent").on("submit", function(e){
                e.preventDefault();
                fData = new FormData(this);
                fData.append("action","registrar_clinica");

                $.ajax({
                    url: '../assets/data/Controller/instituciones/institucionesControl.php',
                    type: "POST",
                    data: fData,
                    contentType: false,
                    processData:false,
                    beforeSend : function(){
                        $("#formRegisterClinicToEvent button[type='submit']").attr("disabled",true);
                    },
                    success: function(data){
                        console.log(data);
                    try{
                        var insert = JSON.parse(data);
                        if (insert.estatus == "ok") {
                            nuevo_alumn = {
                                "IDOrganizacion":insert.data,
                                "nombre_clave_destino":"amor-con-amor-se-paga",
                                "name":$("#name_cl").val(),
                                "paterno":$("#paterno_cl").val(),
                                "materno":$("#materno_cl").val(),
                                "email":$("#emailRes").val(),
                                "telefono":$("#telefono_cl").val(),
                                "action":"registrar_prospecto",
                                "tipo_prospecto":"evento",
                                "estatus":'10',
                                "inp_codigo_pro":''
                            };
                            $.ajax({
                                url: '../assets/data/Controller/prospectos/prospectoControl.php',
                                type: "POST",
                                data: nuevo_alumn,
                                success: function(data){
                                    console.log(data);
                                }
                            });
                            swal({
                                title: 'Registro exitoso!',
                                icon:'success'
                            }).then(()=>{
								window.location.reload()
							})
                        }else{
                            swal({
                                text: insert.info,
                                icon: 'info'
                            }).then(()=>{
								window.location.reload()
							})
                        }
                    }catch(e){
                        console.log(e);
                        console.log(data);
                    }
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#formRegisterClinicToEvent")[0].reset();
                        $("#formRegisterClinicToEvent button[type='submit']").attr("disabled",false);
                    }
                })
            });
            
            let telEl = document.querySelector('#telefono');
            let telEl_cl = document.querySelector('#telefono_cl');
            let telResp = document.querySelector('#telefonoRes');

            telEl.addEventListener('keyup', (e) => {
            let val = e.target.value;
            e.target.value = val
                .replace(/\D/g, '')
                .replace(/(\d{1,3})(\d{1,4})?(\d{1,3})?/g, function(txt, f, s, t) {
                if (t) {
                    return `(${f}) ${s}-${t}`
                } else if (s) {
                    return `(${f}) ${s}`
                } else if (f) {
                    return `(${f})`
                }
                });
            });

            telResp.addEventListener('keyup', (e) => {
            let val = e.target.value;
            e.target.value = val
                .replace(/\D/g, '')
                .replace(/(\d{1,3})(\d{1,4})?(\d{1,3})?/g, function(txt, f, s, t) {
                if (t) {
                    return `(${f}) ${s}-${t}`
                } else if (s) {
                    return `(${f}) ${s}`
                } else if (f) {
                    return `(${f})`
                }
                });
            });

            telEl_cl.addEventListener('keyup', (e) => {
            let val = e.target.value;
            e.target.value = val
                .replace(/\D/g, '')
                .replace(/(\d{1,3})(\d{1,4})?(\d{1,3})?/g, function(txt, f, s, t) {
                if (t) {
                    return `(${f}) ${s}-${t}`
                } else if (s) {
                    return `(${f}) ${s}`
                } else if (f) {
                    return `(${f})`
                }
                });
            });

            $(".onlyNumer").on('keypress',function(evt){
            if (evt.which < 46 || evt.which > 57){
                evt.preventDefault();
            }
            })

            function button_validar_c(){
                $.ajax({
                    url: '../assets/data/Controller/prospectos/prospectoControl.php',
                    type: "POST",
                    data: {action:'validar_codigo', cod:$("#inp_codigo_pro").val(), event:'<?php echo $_GET['e']; ?>'},
                    beforeSend : function(){

                    },
                    success: function(data){
                        try{
                            console.log(data)
                            if(Boolean(parseInt(data))){
                                bool_cod = true;
                                swal({
                                    icon:'success',
                                    text:'Código valido'
                                })
                            }else{
                                bool_cod = false;
                                swal({
                                    icon:'info',
                                    text:'Código no valido'
                                })
                                $("#inp_codigo_pro").val('')
                            }
                        }catch(e){
                            console.log(e);
                            console.log(data);
                        }
                    },
                    error: function(){
                    },
                    complete: function(){
                        
                    }
                }) 
            }// fin func

            $("#inp_codigo_pro").on('focusout', function(){
                button_validar_c();
            })

            $(".nav-link").click(function(e, i){
                var out = $(this).parent().siblings().find(".active").attr('tofade');
                $(this).parent().siblings().find(".active").removeClass("active");
                $(this).addClass("active");
                var inc = $(this).attr('tofade');
                // console.log($(this).parent().siblings().find(".active"));
                // console.log(inc);
                $("#form-"+out).fadeOut(100, function(){
                    $("#form-"+inc).fadeIn(100);
                });
            })

            function cargarPaises(){
                $.ajax({
                    url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
                    type: 'POST',
                    data: {
                        action: "cargarPaisesDirectorio",
                        alumnoAmor: "amor_con_amor"
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $("#pais_cl").html('<option selected="true" value="" disabled="disabled">Seleccione el país</option>');
                        $.each(data, function (key, registro) {
                            $("#pais_cl").append('<option value=' + registro.IDPais + '>' + registro.Pais + '</option>');
                        });
                    },
                    error: function(data){
                        console.log(data.error());
                    }
                });
            }

            $("#pais_cl").on('change', function () {
                $("#estado_cl").empty();
                idPais = $("#pais_cl").val();
                $.ajax({
                    url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
                    type: 'POST',
                    data: {
                        action: "cargarEstadosDirectorio",
                        idPais: idPais,
                        alumnoAmor: "amor_con_amor"
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $("#estado_cl").html('<option selected="true" value="null" disabled="disabled">Seleccione el estado en el que se encuentra la clínica</option>');
                        $.each(data, function (key, registro) {
                            $("#estado_cl").prop('disabled', false);
                            $("#estado_cl").append('<option value =' + registro.IDEstado + '>' + registro.Estado + '</option>');
                        });
                        if (data == '') {
                            swal({
                                title: 'País sin estados',
                                icon: 'info',
                                text: 'Selecciona otro país, si es el caso.',
                                button: false,
                                timer: 3000,
                            });
                            $("#estado_cl").val("null");
                        }
                    }
                });
            });
        </script>
  <!--Fin  Formulario registro --> 
    </body>	
</html>
