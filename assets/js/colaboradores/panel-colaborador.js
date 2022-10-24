let periodos = []; // contiene todos los cortes realizados hasta la fecha
let bandCorteActual = false;

let materiasAlumnos_arr = null; // un nuevo objeto que se estrucutrara con cada carrera como indice y dentro del indice todos los alumnos
let originalObj = null;

let listaColaboradores = [];

const todayFecha = new Date();

$(document).ready(function(){
	if(usrInfo.persona.tipo == 1){
		$("#alumnos_faltos").css("display","block");
	}
	cargarColaboradores();
	
	consultar_movimientos(); // consulta las operaciones del periodo actual (AAAA-MM-01> <AAAA-MM-DD)
	consultarCortes(); // consulta el historico de todos los cortes realizados hasta la fecha
});

function consultar_movimientos(){
	if(listaColaboradores.length == 0 && usrInfo.persona.tipo == 1){
		setTimeout(()=>{
			consultar_movimientos();
		},300)
	}else{

		/*
			* En esta funcion se inicializan materiasAlumnos_arr
			*	que contiene el concentrado de los movimientos de los alumnos, agrupados por carreras;
			* originalObj, que contiene el formato original de todo el calculo de la comisión para el colaborador
		*/	
		fData = {
			action:"consultar_movimientos",
			colaborador:usrInfo.persona.idColaborador
		}
		$.ajax({
			url: "../assets/data/Controller/colaboradores/colaboradorControl.php",
			type: "POST",
			data: fData,
			// contentType: false,
			// processData:false,
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				materiasAlumnos_arr = null;
				originalObj = null;
				try{
					json = JSON.parse(data);
					tempObj = JSON.parse(data);
					originalObj = json;
					carreras = {};
					
					htmlNoP = "";
					
					if(json.hasOwnProperty("corte_generado")){
						json.corte_generado = json.corte_generado.data[0]
						
						$("#lblFechaSaldoActual").html(json.corte_generado.fechaCorte)
						$("#lblFechaSaldoActual").attr('title','El cortel periodo fue generado esta fecha');
						$("#lblEstatusPagoActual").html((json.corte_generado.pagado == 1)? "Pagado" : "Pendiente de pago.");
						json = json.operaciones;
						originalObj = json;
					}else{
						$("#lblFechaSaldoActual").html(new Date().toISOString().substr(0, 10))
						$("#lblEstatusPagoActual").html('En curso.');
					}

					$("#count-alumn").html(json.alumnos.length);

					$("#datatable-tabla-main").DataTable().clear();

					for (i = 0; i < json.alumnos.length; i++) {
						alm = json.alumnos[i];
						// llenado para tablas
						if(usrInfo.persona.tipo == 1){
							vocero_refer = getElm(listaColaboradores, 'idColaborador', alm.idColaborador);
							arrTodoAlumn = [alm.nombre+" "+alm.aPaterno+" "+alm.aMaterno,`<a href="callto:${alm.telefono}">${alm.telefono}</a>`,`<a href="mailto:${alm.correo}">${alm.correo}</a>`,((alm.movimientos.length > 0)? "SI" : "NO"),vocero_refer.nombres+" "+vocero_refer.apellidoPaterno,`<a href="callto:${vocero_refer.celular}">${vocero_refer.celular}</a>`,`<a href="mailto:${vocero_refer.correo}">${vocero_refer.correo}</a>`];
							$("#datatable-tabla-main").DataTable().row.add(arrTodoAlumn);
						}
						
						if(alm.movimientos.length == 0){
							htmlNoP += `<tr>
								<td>${alm.nombre}</td>
								<td>${alm.aPaterno}</td>
								<td>${alm.aMaterno}</td>
								<td><a href="callto:${alm.telefono}">${alm.telefono}</a></td>
								<td><a href="mailto:${alm.correo}">${alm.correo}</a></td>
							</tr>`;
						}
						for (j = 0; j < alm.movimientos.length; j++) {
							mv = alm.movimientos[j];
						
							if(!carreras.hasOwnProperty(("C"+String(mv.id_carrera)))){
								carreras["C"+String(mv.id_carrera)] = [alm];
							}else{
								if(!carreras["C"+String(mv.id_carrera)].includes(alm)){
									carreras["C"+String(mv.id_carrera)].push(alm);
								}
							}
						
						}
					}
					$("#datatable-tabla-main").DataTable().draw();
					$("#datatable-tabla-main").DataTable().columns.adjust();

					$("#body-table-alumno_sp").html(htmlNoP) // tabla de alumnos que adeudan la mensualidad

					totalMonto = 0;
					totalAlumnos = 0;
					materiasAlumnos_arr = carreras; // se setea la variable globar materiasAlumnos_arr de la linea 7, con el objeto creado
					$("#datatable-buttons").DataTable().clear()
					for (const item in carreras) {
						sumaRec = 0;
						totalAlumnos += carreras[item].length;
						// se evalua que no haya un error en el calculo de las comisiones (en caso de que supere el maximo de una comision)
						for (i = 0; i < carreras[item].length; i++) {
							for (j = 0; j < carreras[item][i].movimientos.length; j++) {
								movmnt = carreras[item][i].movimientos[j];
								if(movmnt.comision != 'fuera_de_rango' && sumaRec != 'fuera_de_rango'){
									sumaRec+=parseFloat(movmnt.comision.monto_u);
								}else{
									sumaRec = "fuera_de_rango";
								}
							}
						}
						/*El boton mostrar alumnos muestra el desglose de los alumnos con movimientos en el periodo*/
	                    rowCarr = [
	                    	item,
	                    	`<button type="button" onclick="mostrarAlumnos('${item}')" class="btn btn-transparencia waves-effect">${carreras[item].length} alumnos</button>`,
	                    	carreras[item][0].movimientos[0].comision[0].porcentaje+'%',
	                    	((sumaRec !== "fuera_de_rango")?moneyFormat.format(sumaRec) : 'No comisionable')
	                    ];
	                    $("#datatable-buttons").DataTable().row.add(rowCarr);
	                    if (sumaRec != "fuera_de_rango") {
	                    	totalMonto+=sumaRec;
	                    }else{
	                    	totalMonto=sumaRec;
	                    }
					}

					$("#datatable-buttons").DataTable().draw();
					$("#datatable-buttons").DataTable().columns.adjust();

					$("#lblTotalAlumnosActual").html(totalAlumnos);
					$("#lblTotalComisionActual").html(((totalMonto != 'fuera_de_rango')?moneyFormat.format(totalMonto) : 'No comisionable'));
					
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
	}
}

function mostrarAlumnos(materia){
	/*
		* Rellenar la tabla donde se desglosa la lista de alumnos por carrera, y sus operaciones del periodo
	*/
	html = "";
	$("#lblHeaderModalAlumnoCarrera").html(materia);
	if(materiasAlumnos_arr !== null){
		alumnos = materiasAlumnos_arr[materia];
		for (i = 0; i < alumnos.length; i++) {
			for (j = 0; j < alumnos[i].movimientos.length; j++) {
				mv = alumnos[i].movimientos[j];

				porcent = ( mv.comision !== "fuera_de_rango")? mv.comision["0"].porcentaje+"%" : "Sin param";
				montoComision = ( mv.comision !== "fuera_de_rango")? moneyFormat.format(alumnos[i].movimientos[j].comision["monto_u"]) : "Sin param";
				if(usrInfo.persona.tipo == 1){
					c = getElm(listaColaboradores, "idColaborador", alumnos[i].idColaborador);
					colabN = `<a href="javascript:void(0)" onclick="card_colaborador(${alumnos[i].idColaborador})">${c.nombres + " " + c.apellidoPaterno}</a>`;
				}else{
					colabN = "";
				}

				html += `<tr>
							<td>${alumnos[i].nombre} ${alumnos[i].aPaterno}</td>
							<td>${alumnos[i].movimientos[j].fechapago.substr(0, 10)}</td>
							<td>${moneyFormat.format(alumnos[i].movimientos[j].montopagado)}</td>
							<td>${porcent}</td>
							<td>${montoComision}</td>
							<td>${colabN}</td>
						</tr>`;
			}
		}
	}

	$("#table-desglose").html(html);
	$("#myModal").modal("show");
}

function generarCorte(){
	/*
	Cuando se 
	*/
	$(".auto-genr").each(function(){
	  $(this).remove()
	});
	$("#lblPeriodoResumen").html("actual");
	$("#estatusPagoPeriodo").html("");
	if(originalObj.total_Movimientos !== 0 && originalObj.total_comision_calculo !== "fuera_de_rango"){
		rows = "";
		$("#tdMontoCorte").html(moneyFormat.format(originalObj.total_comision_calculo))
		$("#fechaCorte").html(new Date().toISOString().substr(0,10))
		rows = "";
		for (i = 0; i < originalObj.alumnos.length; i++) {
			almn = originalObj.alumnos[i];
			for (j = 0; j < almn.movimientos.length; j++) {
				mvm = originalObj.alumnos[i].movimientos[j];

				porcent = ( mvm.comision !== "fuera_de_rango")? mvm.comision["0"].porcentaje+"%" : "Sin param";
				montoComision = ( mvm.comision !== "fuera_de_rango")? moneyFormat.format(mvm.comision["monto_u"]) : "Sin param";
				
				rows += `<tr class="auto-genr">
							<td>${mvm.fechapago.substr(0, 10)}</td>
							<td>${porcent}</td>
							<td>${montoComision}</td>
							<td>${almn.aPaterno} ${almn.aMaterno} ${almn.nombre}</td>
							<td>${mvm.id_carrera}</td>
						</tr>`;
			}
		}
		$("#buttonGenerarCorte").attr("disabled", ((originalObj.total_comision_calculo == "fuera_de_rango" || bandCorteActual)? true: false));
		$("#tablaCrearCorte").append(rows);
	}else{
		$("#buttonGenerarCorte").attr("disabled","true");
	}
	$(".outerDiv_S").css("display", "none")
	$("#modalCorte").modal("show")
}

function cargarColaboradores(){
	fData = {
		action:"cargarColaboradores",
		colaborador:usrInfo
	}
	$.ajax({
		url: "../assets/data/Controller/colaboradores/colaboradorControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				if(data != ""){
					colabs = JSON.parse(data);
					console.log(colabs)
					if(colabs.hasOwnProperty('mis_prospectos')){
						$("#count-prospect").html(colabs.mis_prospectos.length)
						$("#datatable-tabla-prospectos").DataTable().clear()
						for (i = 0; i < colabs.mis_prospectos.length; i++) {
							$("#datatable-tabla-prospectos").DataTable().row.add([
									colabs.mis_prospectos[i].aPaterno+' '+colabs.mis_prospectos[i].aMaterno+' '+colabs.mis_prospectos[i].nombre,
									`<a href="tel:${colabs.mis_prospectos[i].telefono}">${colabs.mis_prospectos[i].telefono}</a>`,
									`<a href="mailto:${colabs.mis_prospectos[i].correo}">${colabs.mis_prospectos[i].correo}</a>`,
									colabs.mis_prospectos[i].fecha_registro.substr(0, 10)
								])
						}
						$("#datatable-tabla-prospectos").DataTable().draw()
						$("#datatable-tabla-prospectos").DataTable().columns.adjust();
					}else{
						prospectos_v = colabs.prospectos;
						if(colabs.estatus == "ok"){
							listaColaboradores = [];

							colabs= colabs.data;
							
							listaColaboradores = colabs;
							
							numC = colabs.reduce( (acc, item) => {return acc += (item.tipo == 2)? 1 : 0;}, 0)
							$("#count-vocer").html(numC)
							html = "";
							$("#datatable-tabla-main-vocer").DataTable().clear();
							for (i = 0; i < colabs.length; i++) {
								if(colabs[i].hasOwnProperty("alumnos")){
									alumnosStr = colabs[i].alumnos.reduce( (acc, item)=>{return acc += item.nombre+"<br>"},"");
									html += `<div class="card">
		                                    <div class="card-header" id="heading${colabs[i].idColaborador}">
		                                        <h5 class="m-0 card-title">
		                                        <a href="" class="text-dark collapsed" data-toggle="collapse" data-target="#collapse${colabs[i].idColaborador}" aria-expanded="false" aria-controls="collapse${colabs[i].idColaborador}">${colabs[i].nombres + " " + colabs[i].apellidoPaterno}
		                                        </a>
		                                        </h5>
		                                    </div>

		                                    <div id="collapse${colabs[i].idColaborador}" class="collapse" aria-labelledby="heading${colabs[i].idColaborador}" data-parent="#accordion-test">
		                                        <div class="card-body py-0">
		                                            <ul class="list-group list-group-flush">
				                                    <li class="list-group-item px-0"><div class="row"><div class="col-6">Nombre:</div><div class="col-6">${colabs[i].nombres + " " + colabs[i].apellidoPaterno + " " +colabs[i].apellidoMaterno}</div></div></li>
				                                    <li class="list-group-item px-0"><div class="row"><div class="col-6">Telefono:</div><div class="col-6"> <a href="tel:${colabs[i].celular}">${colabs[i].celular}</a></div></div></li>
				                                    <li class="list-group-item px-0"><div class="row"><div class="col-6">Correo:</div><div class="col-6"> <a href="mailto:${colabs[i].correo}">${colabs[i].correo}</a></div></div></li>
				                                    <li class="list-group-item px-0"><div class="row"><div class="col-6">Código:</div><div class="col-6"> ${colabs[i].codigo}</li>
				                                    <li class="list-group-item px-0"><div class="row"><div class="col-6">Alumnos:</div>
				                                    	<div class="col-6">
				                                    	<button type="button" class="btn btn-dark btn-xs" data-container="#modalInfoVocero" title="" data-toggle="popover" data-placement="top" data-bs-trigger="focus" data-content="<div>${alumnosStr}</div>" data-original-title="">${colabs[i].alumnos.length}</button>
				                                    	</div>
			                                    	</div></li>
				                                  </ul>
		                                        </div>
		                                    </div>
		                                </div>`;
		                                strTag = "";
		                                if(colabs[i].corteExiste.length > 0 && colabs[i].corteExiste[0].pagado == "1"){
											
		                                	strTag = "Pagado";
		                                }else{
		                                	strTag = "Corte pendiente";
		                                }
		                               arrvocer = [
		                               	colabs[i].nombres + " " + colabs[i].apellidoPaterno + " " +colabs[i].apellidoMaterno,
		                               	`<a href="callto:${colabs[i].celular}">${colabs[i].celular}</a>`,
		                               	`<a href="mailto:${colabs[i].correo}">${colabs[i].correo}</a>`,
		                               	colabs[i].codigo,
		                               	`<button type="button" class="btn btn-dark btn-xs" data-container="body" title="" data-toggle="popover" data-placement="top" data-bs-trigger="focus" data-content="<div>${alumnosStr}</div>" data-original-title="">${colabs[i].alumnos.length}</button>`,
		                               	//`<div class="bg-secondary text-light"><center>${colabs[i].alumnos.length}<center></div>`,
		                               	strTag
		                               ];

		                               $("#datatable-tabla-main-vocer").DataTable().row.add(arrvocer);
								}
								$("#datatable-tabla-main-vocer").DataTable().draw();
								$("#datatable-tabla-main-vocer").DataTable().columns.adjust();
							}
							$("#accordion-test").html(html)

							/*
							 *
							 * FOR PARA OBTENER DETALLES DE PROSPECTOS
							 *
							*/
							$("#count-prospect").html(prospectos_v.length)
							$("#datatable-tabla-prospectos").DataTable().clear()
							for (i = 0; i < prospectos_v.length; i++) {
								$("#datatable-tabla-prospectos").DataTable().row.add([
										prospectos_v[i].aPaterno+' '+prospectos_v[i].aMaterno+' '+prospectos_v[i].nombre,
										`<a href="tel:${prospectos_v[i].telefono}">${prospectos_v[i].telefono}</a>`,
										`<a href="mailto:${prospectos_v[i].correo}">${prospectos_v[i].correo}</a>`,
										prospectos_v[i].fecha_registro.substr(0, 10)
									])
							}
							$("#datatable-tabla-prospectos").DataTable().draw()
							$("#datatable-tabla-prospectos").DataTable().columns.adjust();
						}
					}
				}
				$("[data-toggle=popover]").popover({'html':true})
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
}

function consultarCortes(){
	fData = {
		action:"consultarTodoCortesColaborador",
		colaborador:usrInfo.persona.idColaborador
	}
	$.ajax({
		url: "../assets/data/Controller/colaboradores/colaboradorControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				periodos = [];
				json = JSON.parse(data);
				options = "<option disabled selected>Seleccione opción</option>";

				if(json.estatus == "ok" && json.data.length > 0){
					options = "<option disabled selected>Seleccione opción</option>";
					for (i = 0; i < json.data.length; i++) {
						periodos.push(json.data[i]);
						fch = json.data[i].fechaCorte;
						
						bandCorteActual = ((parseInt(fch.substr(5,2)) == (todayFecha.getMonth()+1)) && (parseInt(fch.substr(0,4)) == todayFecha.getFullYear()))? true : bandCorteActual;

						options += `<option value="${json.data[i].idPago}">${meses[parseInt(fch.substr(5,2))-1]} - ${fch.substr(0,4)}</option>`;
					}
				}
				
				$("#selectPeriodoCuenta").html(options);
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
}

function card_colaborador(clb){
	$("#collapse"+clb).collapse("toggle")
	$("#modalInfoVocero").modal("show")
	setTimeout(function(){location.href="#collapse"+clb},100)
}

$("#openSecondModal").on("click", function(){
	$("#secondModal").modal("show")
})

$("#buttonGenerarCorte").on("click", function(){

	fData = {
		action:"generarCorte",
		colaborador:usrInfo.persona.idColaborador
	}
	$.ajax({
		url: "../assets/data/Controller/colaboradores/colaboradorControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				json = JSON.parse(data);


				if(json.estatus == "ok"){
					swal({
		                title: "Corte generado!",
		                text: "Corte generado con exito, en espera de aplicación de pago.",
		                icon: "success",
		              });
				}else{
					message = "";
					titulo = "";
					if(json.info == "corte_existente"){
						titulo = "Ya existe un corte para este periodo.";
						message = "Consulte la informacion referente al corte antes generado";
					}else{
						titulo = "Ocurrió un error interno.";
						message = `Notifique al administrador`;
					}
					swal({
						title: titulo,
		                text: message,
		                icon: "info",
					});
					consultarCortes();
				}
				$("#modalCorte").modal("hide")
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

$("#selectPeriodoCuenta").on("change", function(){
	formatTablaPeriodos()
})

function formatTablaPeriodos(callb = false){
	idReferd = $("#selectPeriodoCuenta option:selected").val();
	periodoSelect = periodos.find(element => element.idPago == idReferd);
	if (periodoSelect !== undefined) {
		jsonEC = JSON.parse(periodoSelect.jsonEC);
		//rellenar tabla de movimientos en #modalCorte
		$(".auto-genr").each(function(){$(this).remove()});

		html = "";
		$("#tdMontoCorte").html(moneyFormat.format(periodoSelect.montoCalculado))
		$("#fechaCorte").html(jsonEC.fecha_corte.substr(0, 10))
		rows = "";
		for (i = 0; i < jsonEC.operaciones.length; i++) {
			rows += `<tr class="auto-genr">
							<td>${jsonEC.operaciones[i].fecha_operacion}</td>
							<td>${jsonEC.operaciones[i].porcentaje_comision}%</td>
							<td>${moneyFormat.format(jsonEC.operaciones[i].comision_operacion)}</td>
							<td>${jsonEC.operaciones[i].alumno}</td>
							<td>${jsonEC.operaciones[i].id_carrera}</td>
						</tr>`;	
		}
		$("#buttonGenerarCorte").attr("disabled", "true");
		$("#tablaCrearCorte").append(rows);
		//fin rellenado

		$("#lblPeriodoResumen").html($("#selectPeriodoCuenta option:selected").html().split("-")[0])
		$("#estatusPagoPeriodo").html((periodoSelect.pagado == 1)? "Pagado" : "Pendiente de pago");
		$("#lblTotalComision").html(moneyFormat.format(periodoSelect.montoCalculado));
		$("#lblFechaSaldo").html(jsonEC.fecha_corte.substr(0, 10));
		$("#lblTotalOperaciones").html(jsonEC.total_operaciones);
		$("#lblEstatusPago").html((periodoSelect.pagado == 1)? "Pagado" : "Pendiente de pago");
	}else{
		$("#lblTotalComision").html("");
		$("#lblFechaSaldo").html("");
		$("#lblTotalOperaciones").html("");
		$("#lblEstatusPago").html("");
	}
	if(callb){
		$("#modalCorte").modal("show");
	}
}

$("#selectPeriodoCuenta").on("change", function(){
	formatTablaPeriodos();
})

$("#btnDesglosePeriodoAnterior").on("click", ()=>{
	formatTablaPeriodos(true);
});

$("#modalCorte").on("hidden.bs.modal", function(){
	$(".auto-genr").each(function(){$(this).remove()});
	$("#tdMontoCorte").html("")
	$("#fechaCorte").html("")
	$("#buttonGenerarCorte").attr("disabled", "true");
})

$("#alumnos_faltos").on("click", function(){
	$("#modalAlumnos_sinPago").modal("show")
})


function getElm(arr, key, id){
	found = null;
	for (var i = 0; i < arr.length; i++) {
		if(arr[i][key] == id){
			found = arr[i];
		}
	}

	return found;
}
