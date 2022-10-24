<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>MONI</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
		<link href="assets/css/alertas.css" rel="stylesheet" type="text/css">

        <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    </head>


    <body>

        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="container">
            <center><h2>Afiliados</h2></center>
            <div class="card card-pages">

                <div class="card-body">
                    <table class="table" id="table_prospectos_con_pago">
                        <thead>
                            <th>Fecha registro</th>
                            <th>Tipo A.</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Contraseña</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <center><h2>Todos scae</h2></center>
            <div class="card card-pages">

                <div class="card-body">
                    <table class="table" id="table_prospectos">
                        <thead>
                            <th>Tipo asistente</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <!-- jQuery  -->
        <script src="assets/js/template/jquery.min.js"></script>
        <script src="assets/js/template/bootstrap.bundle.min.js"></script>
        <script src="assets/js/template/modernizr.min.js"></script>
        <script src="assets/js/template/detect.js"></script>
        <script src="assets/js/template/fastclick.js"></script>
        <script src="assets/js/template/jquery.slimscroll.js"></script>
        <script src="assets/js/template/jquery.blockUI.js"></script>
        <script src="assets/js/template/waves.js"></script>
        <script src="assets/js/template/wow.min.js"></script>
        <script src="assets/js/template/jquery.nicescroll.js"></script>
        <script src="assets/js/template/jquery.scrollTo.min.js"></script>
        <script src="assets/js/template/sweetalert.min.js"></script>

        <script src="assets/js/template/app.js"></script>

            <!--  datatable (tablas) js-->
          <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
          <script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
          <!-- Tabla con botones excel, pdf, imprimir -->
          <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
          <script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>

          <script src="assets/plugins/datatables/jszip.min.js"></script>
          <script src="assets/plugins/datatables/pdfmake.min.js"></script>
          <script src="assets/plugins/datatables/vfs_fonts.js"></script>
          <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
          <script src="assets/plugins/datatables/buttons.print.min.js"></script>
          <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
          <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
          <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>
    <!-- Tablas responsivas -->
          <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
          <script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

        <!-- <script src="assets/pages/datatables.init.class.js"></script> -->

        <script>
			let tbl1 = null
			$(document).ready(function() {
				alumnos_institucion()
				tbl1 = $("#table_prospectos_con_pago").DataTable();
				tbl2 = $("#table_prospectos").DataTable();
			});

			function alumnos_institucion(){
				tbl1 = $("#table_prospectos_con_pago").DataTable();
				$.ajax({
					url: 'assets/data/Controller/alumnos/alumnosInstitucionesControl.php',
					type: 'POST',
					data:{action:'concentrado_alumnos_institucion'},
					success: function(data){
						try{
							var jsn = JSON.parse(data);
							if(jsn.estatus == 'ok'){
								tbl1.clear();
								alumn_c = jsn.data 
								for(i in alumn_c){
									tbl1.row.add([
										alumn_c[i].fecha_registro,
										alumn_c[i].nombre_institucion,
										`<span title="${alumn_c[i].idAsistente}">${alumn_c[i].aPaterno} ${alumn_c[i].aMaterno} ${alumn_c[i].nombre}</span>`,
										alumn_c[i].telefono,
										alumn_c[i].correo,
										alumn_c[i].contrasenia
									])
								}
								tbl1.draw();
								asistentes_scae();
								// tbl1.columns.adjust();
							}
						}catch(e){
							console.log(e)
							console.log(data);
						}
					}
				});
			}

			function asistentes_scae(){
				$.ajax({
					url: 'assets/data/Controller/alumnos/alumnosInstitucionesControl.php',
					type: 'POST',
					data:{action:'asistentes_scae'},
					success: function(data){
						try{
							var jsn = JSON.parse(data);
							if(jsn.estatus == 'ok'){
								tbl2.clear();
								alumn_s = jsn.data 
								for(i in alumn_s){
									tbl2.row.add([
										alumn_s[i].nom_tipo_asistente,
										`<span title="${alumn_s[i].IDAlumno}">${alumn_s[i].paternoalumno} ${alumn_s[i].maternoalumno} ${alumn_s[i].nombrealumno}</span>`,
										alumn_s[i].Celular+"<br>"+alumn_s[i].TelefonoParticular,
										alumn_s[i].Correo
									])
								}
								tbl2.draw();
								// tbl1.columns.adjust();
							}
						}catch(e){
							console.log(e)
							console.log(data);
						}
					}
				});
			}
        </script>
    </body>
</html>