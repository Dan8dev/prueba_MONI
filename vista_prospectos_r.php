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
            <center><h2>Prospectos con pago</h2></center>
            <div class="card card-pages">

                <div class="card-body">
                    <table class="table" id="table_prospectos_con_pago">
                        <thead>
                            <th>Fecha registro</th>
                            <th>Pago</th>
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

            <center><h2>Todos prospectos</h2></center>
            <div class="card card-pages">

                <div class="card-body">
                    <table class="table" id="table_prospectos">
                        <thead>
                            <th>Fecha registro</th>
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
            list_eventos = [
            {
                "idEvento": "2",
                "tipo": "CONGRESO",
                "titulo": "CISMAC 2021",
                "nombreClave": "cismac-congreso",
                "fechaE": "2021-11-12",
                "fechaDisponible": "2021-08-07 00:00:00",
                "fechaLimite": "2021-08-22 00:00:00",
                "limiteProspectos": "300",
                "duracion": "5",
                "tipoDuracion": "h",
                "direccion": "Aurelio Aceves 225 Vallarta Poniente, CP 44110 Guadalajara",
                "estado": "14",
                "pais": "37",
                "codigoPromocional": "CISMAC201",
                "estatus": "1",
                "modalidadEvento": "Presencial",
                "idInstitucion": "13",
                "imagen": "cismac.png",
                "imgFondo": "slide1.jpg",
                "plantilla_bienvenida": "eventos/plantilla_confirmar_registro.html",
                "descripcion": "Evento patrocinado por CONACON",
                "video_url": "",
                "lugares_reserv": 95,
                "estatus_info": {
                  "pendientes": 79,
                  "espera": 0,
                  "confirmado": 13,
                  "rechazo": 3,
                  "no_interes": 0
              }
          }
          ]
          list_carreras = [
                          {
                            "idCarrera": "1",
                            "idInstitucion": "13",
                            "nombre": "Operador en Adicciones Y Salud Mental",
                            "nombre_clave": "operador-terapeutico",
                            "fechaE": "2021-09-29",
                            "tipo": "Certificación",
                            "modalidadCarrera": "En línea",
                            "codigoPromocional": "OTA2021",
                            "direccion": "Carretera Antigua Xalapa-Coatepec, Mariano Escobedo KM 5.5, 91608 Coatepec",
                            "estado": "",
                            "pais": "México. Mex",
                            "plantilla_bienvenida": "carreras/plantilla_ota_registro.html",
                            "imagen": "opt.png",
                            "imgFondo": "hero-bg.png",
                            "estatus": "1",
                            "institucion_nombre": "CONACON",
                            "lugares_reserv": 33,
                            "estatus_info": {
                              "pendientes": 33,
                              "espera": 0,
                              "confirmado": 0,
                              "rechazo": 0,
                              "no_interes": 0
                          },
                          "prospectos_carrera": []
                      },
                      {
                        "idCarrera": "3",
                        "idInstitucion": "2",
                        "nombre": "CONSEJERÍA Y EDUCADOR DE ESTRATEGIAS DE PREVENCIÓN DE CONDUCTAS ANTISOCIALES",
                        "nombre_clave": "consejeria-estrategias",
                        "fechaE": "0000-00-00",
                        "tipo": "TSU",
                        "modalidadCarrera": "",
                        "codigoPromocional": "",
                        "direccion": "",
                        "estado": "",
                        "pais": "",
                        "plantilla_bienvenida": "eventos/plantilla_confirmar_registro.html",
                        "imagen": "",
                        "imgFondo": "",
                        "estatus": "1",
                        "institucion_nombre": "TSU",
                        "lugares_reserv": 2,
                        "estatus_info": {
                          "pendientes": 2,
                          "espera": 0,
                          "confirmado": 0,
                          "rechazo": 0,
                          "no_interes": 0
                      },
                      "prospectos_carrera": []
                  },
                  {
                    "idCarrera": "4",
                    "idInstitucion": "13",
                    "nombre": "AFILIACIÓN CONACON",
                    "nombre_clave": "afiliacion-conacon",
                    "fechaE": "2021-08-01",
                    "tipo": "",
                    "modalidadCarrera": "En línea",
                    "codigoPromocional": "",
                    "direccion": "",
                    "estado": "",
                    "pais": "México. Mex",
                    "plantilla_bienvenida": "carreras/plantilla_afiliacion_registro.html",
                    "imagen": "afiliaciones_fly.jpg",
                    "imgFondo": "hero-bg-conacon.png",
                    "estatus": "1",
                    "institucion_nombre": "CONACON",
                    "lugares_reserv": 16,
                    "estatus_info": {
                      "pendientes": 16,
                      "espera": 0,
                      "confirmado": 0,
                      "rechazo": 0,
                      "no_interes": 0
                  },
                  "prospectos_carrera": []
                },
                {
                    "idCarrera": "5",
                    "idInstitucion": "13",
                    "nombre": "DISTINTIVO CONACON",
                    "nombre_clave": "distintivo-conacon",
                    "fechaE": "2021-08-01",
                    "tipo": "",
                    "modalidadCarrera": "En línea",
                    "codigoPromocional": "",
                    "direccion": "",
                    "estado": "",
                    "pais": "México. Mex",
                    "plantilla_bienvenida": "carreras/plantilla_distintivo_registro.html",
                    "imagen": "distintivo1a.jpg",
                    "imgFondo": "tab1-1.jpg",
                    "estatus": "1",
                    "institucion_nombre": "CONACON",
                    "lugares_reserv": 0
                }
]
            $(document).ready(function(){
                $("#table_prospectos").DataTable({
                    pageLength: 25
                })

                $("#table_prospectos_con_pago").DataTable({
                    pageLength: 25
                })
                $.ajax({
                    url: "assets/data/Controller/marketing/marketingControl.php",
                    type: "POST",
                    data: {action:'consultar_todo_ejecutivas'},
                    // contentType: false,
                    // processData:false,
                    beforeSend : function(){
                    },
                    success: function(data){
                        try{
                            prospectos_arr = JSON.parse(data);
                            list_ejecutivas = prospectos_arr;
                            $("#table_prospectos").DataTable().clear();
                            $("#table_prospectos_con_pago").DataTable().clear();
                            
                            conteo_prospectos = 0;
                            conteo_inscritos = 0;
                            for (i = 0; i < list_ejecutivas.length; i++) {
                                conteo_prospectos+=list_ejecutivas[i].prospectos_carreras.length;
                                conteo_prospectos+=list_ejecutivas[i].prospectos_eventos.length;
                                for (j = 0; j < list_ejecutivas[i].prospectos_carreras.length; j++) {
                                    prospecto = list_ejecutivas[i].prospectos_carreras[j];
                                     //console.log(prospecto)
                                    carrera_f = list_carreras.find(elm => elm.idCarrera == prospecto.idCarrera);
                                    
                                    seguim_c = '';
                                    $("#table_prospectos").DataTable().row.add([
                                        prospecto.fecha_registro.substr(0,10),
                                        `<span title="${prospecto.idAsistente}">${prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre}</span>`,
                                        prospecto.telefono,
                                        prospecto.correo,
                                        prospecto.contrasen
                                    ]);
                                    
                                    if(prospecto.pagos.length > 0){
                                        p_h = prospecto.pagos.reduce((acc,item)=>{acc.push(item.id_concepto); return acc;},[])
                                        
                                        strPago = (p_h.includes('7') || p_h.includes('5') || p_h.includes('2') || p_h.includes('16') || p_h.includes('9'))?'Certificación':''
                                        console.log(p_h)
                                        conteo_inscritos++;
                                        string_pago = JSON.stringify(prospecto.pagos.reduce((accm,item)=>{
                                              item.detalle_pago = null;
                                                accm.push([item.fechapago, item.plan_pago]);
                                                return accm;
                                            },[]))
                                        $("#table_prospectos_con_pago").DataTable().row.add([
                                            prospecto.fecha_registro.substr(0,10),
                                            strPago,
                                            "<p title='"+string_pago+"'>"+prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre+"</p>",
                                            prospecto.telefono,
                                            prospecto.correo,
                                            prospecto.contrasen
                                        ]);
                                    }
                                }

                                for (h = 0; h < list_ejecutivas[i].prospectos_eventos.length; h++) {
                                    prospecto = list_ejecutivas[i].prospectos_eventos[h];
                                    
                                    evento_f = list_eventos.find(elm => elm.idEvento == prospecto.idEvento);
                                    
                                    seguim_e ='';
                                    $("#table_prospectos").DataTable().row.add([
                                        prospecto.fecha_registro.substr(0,10),
                                        `<span title="${prospecto.idAsistente}">${prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre}</span>`,
                                        prospecto.telefono,
                                        prospecto.correo,
                                        prospecto.contrasen
                                    ]);

                                    if(prospecto.pagos.length > 0){
                                        p_h = prospecto.pagos.reduce((acc,item)=>{acc.push(item.id_concepto); return acc;},[])
                                        
                                        strPago = (p_h.includes('7') || p_h.includes('5') || p_h.includes('2') || p_h.includes('16') || p_h.includes('9'))?'Certificación':''
                                        console.log(p_h)
                                        conteo_inscritos++;
                                        
                                        string_pago_i = JSON.stringify(prospecto.pagos.reduce((accm,item)=>{
                                              item.detalle_pago = null;
                                                accm.push([item.fechapago, item.plan_pago]);
                                                return accm;
                                            },[]))
                                        $("#table_prospectos_con_pago").DataTable().row.add([
                                            prospecto.fecha_registro.substr(0,10),
                                            strPago,
                                            "<p title='"+string_pago_i+"'>"+prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre+"</p>",
                                            prospecto.telefono,
                                            prospecto.correo,
                                            prospecto.contrasen
                                        ]);
                                    }
                                    

                                }

                                
                            }

                            $("#conteo_prospectos").html(conteo_prospectos);
                            $("#conteo_inscritos").html(conteo_inscritos);

                            $("#table_prospectos").DataTable().draw();
                            $("#table_prospectos").DataTable().columns.adjust();

                            $("#table_prospectos_con_pago").DataTable().draw();
                            $("#table_prospectos_con_pago").DataTable().columns.adjust();

                        }catch(e){
                            console.log(e);
                            console.log(data);
                        }
                    },
                    error: function(){
                    },
                    complete: function(){
                    }
                });
            })
        </script>
    </body>
</html>