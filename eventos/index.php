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
  $detalles = null;
  require '../assets/data/Model/conexion/conexion.php';
  require '../assets/data/Model/eventos/eventosModel.php';
  require '../assets/data/Controller/eventos/initControler.php';
  require '../assets/data/Model/institucion/institucionModel.php';
    
    $info = getDataEvento($solicitud)['data'];
    if(sizeof($info) > 0){
      $detalles = $info[0];
      $fechaE = explode("-", $detalles["fechaE"]);
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
		
		  <meta name="image" content="https://conacon.org/assets/img/logoMetas_foroitnernacional.png">
		  <!-- Open Graph data -->
		  <meta property="og:title" content="REGISTRO" />
		  <meta property="og:type" content="website" />
		  <meta property="og:url" content="https://moni.com.mx/eventos/?e=<?php echo $detalles["nombreClave"];?>" />
		  <meta property="og:image" content="https://conacon.org/assets/img/logoMetas_foroitnernacional.png" />
		  <meta property="og:description" content="<?php echo $detalles["descripcion"];?>" />
		  <!-- Schema.org for Google -->
		  <meta itemprop="name" content="REGISTRO">
		  <meta itemprop="description" content="https://moni.com.mx/eventos/?e=<?php echo $detalles["nombreClave"];?>" />
		  <meta itemprop="image" content="Colegio Nacional De Consejeros" />
			  
		  <!-- Open Graph - Article -->
		  <meta name="article:section" content="Social Media">
		  <meta name="article:author" content="Colegio Nacional De Consejeros">
		  <meta name="article:tag" content="https://moni.com.mx/eventos/?e=<?php echo $detalles["nombreClave"];?>" />
		
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
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr flaticon-headset"></i>
                                                        <div class="about-info-inner">
                                                            <span>Teléfono:</span>
                                                            <p class="mb-0">55 852 625 16</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr far fa-calendar-alt"></i>
                                                        <div class="about-info-inner">
                                                            <span>Horario</span>
                                                            <p class="mb-0">Lun - Vie 9am - 7pm</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr flaticon-pin-1"></i>
                                                        <div class="about-info-inner">
                                                            <span>Puebla, Pue.</span>
                                                            <p class="mb-0">México</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr flaticon-minute"></i>
                                                        <div class="about-info-inner">
                                                            <span>Sábados:</span>
                                                            <p class="mb-0">9AM-2PM</p>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="about-info w-100">
                                                        <i class="thm-clr flaticon-pin-1"></i>
                                                        <div class="about-info-inner">
                                                            <span><?php echo $detalles["direccion"] ?></span>
                                                            <p class="mb-0"><?php echo $detalles["estado_nom"] ?></p>
                                                            <p class="mb-0"><?php echo $detalles["pais_nom"] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-detail-img position-relative w-100">
                                    <img class="img-fluid w-100" src="../assets/images/generales/flyers/<?php echo $detalles["imagen"] ?>" alt="Event Detail Image">
                                </div>
                            </div>
                            <div class="event-detail-content position-relative w-100">
                            <div class="event-detail-feat position-relative w-100">
                                    <h4 class="mb-0">Instrucciones</h4>
                                    <ul class="event-detail-features-list mb-0 list-unstyled w-100">
                                        <li><i class="far fa-calendar-check"></i>Regístrate<span class="d-block">Llena tu formulario, te enviaremos un email con los accesos a tu panel</span></li>
                                        <li><i class="flaticon-listen"></i>Ingresa<span class="d-block">Realiza tu pago dentro de tu panel</span></li>
                                        <li><i class="flaticon-user"></i>Perfil<span class="d-block">Completa tu perfil</span></li>
                                    </ul>
                                </div>
                                <div class="event-detail-desc mt-30 position-relative w-100">
                                    <h4 class="mb-0">Registro</h4>
                                    <div class="contact-form-wrap p-0 w-100">
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
                                                    <button class="thm-btn fill-btn" id="submit" type="submit">Regístrate<span></span></button>
                                                </div>
                                                   
                                                </div>
                                        </form>
                                    </div>
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
            
            let telEl = document.querySelector('#telefono')

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
            })

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
        </script>
  <!--Fin  Formulario registro --> 
    </body>	
</html>
