$.post("../../assets/data/Controller/asistentes/asistentesControl.php",{action: "obtenerconstancias",emailobtener:$('#emailobtener').text()}, function(data3, status){
    //console.log(data3)
	data3 = JSON.parse(data3);
	
    var listar='';
    for ( index = 0; index < data3.length; index++) {
      // listar+= `

      // <div class="col-lg-4 col-sm-12 text-center mg-t-25">
      //   <div class="card shadow-base card-body pd-25 bd-0 mg-t-20 ratio ratio-16x9">
      //   <a href="../../assets/images/constancias/${data3[index].nombre_reconocimiento}.pdf" target="_blank"><canvas id="my_canvas${index}"></canvas></a>
      //   </div><!-- card -->
      // </div>
      // <div class="col-lg-8 col-sm-12 text-center mg-t-25">
      //   <div class="card shadow-base card-body pd-t-25 mg-t-20">
      //     <h5 id="tipoevento">${(data3[index].nombre_taller==null)?data3[index].tipoevento:'TALLER'}</h5>
      //     <p id="tituloevento" class="tx-normal tx-roboto tx-white">${(data3[index].nombre_taller==null)?data3[index].tituloevento:data3[index].nombre_taller}</p>
      //     <h5 id="duracion">${data3[index].duracion} hrs.</h5>
      //     <h5 id="lugar">${data3[index].lugarevento}</h5>
      //     <h5 id="fecha">${data3[index].fechaevento}</h5>
      //   </div><!-- card -->
      // </div>`
      reconocimiento = 

      listar+=
      `<div class="col-sm-6 bd-0">
      		  <div class="card shadow-base">
              <div class="card-header d-flex justify-content-between align-items-center bg-primary">
                <h5 class="card-title tx-uppercase mg-b-0 text-white">${(data3[index].nombre_taller==null)?data3[index].tipoevento:'TALLER'} ${(data3[index].nombre_taller==null)?data3[index].tituloevento:data3[index].nombre_taller}</h5>
                <span class="tx-12 tx-uppercase"></span>
              </div><!-- card-header -->
              <div class="card-body bg-light">
                <div class="row">
                  <div class="col-sm-12 col-md-12">
                    <div class="card-body mx-auto" style="background-color:gainsboro;">
                      <center>
                        <a href="../../assets/images/constancias/${data3[index].nombre_reconocimiento}.pdf" target="_blank"><canvas id="my_canvas${index}"></canvas></a>
                      </center>
                    </div>
                  </div>
                  <!--<div class="col-sm-12 col-md-6">
                    <h5>Duracion: <span id="duracion">${data3[index].duracion} hrs.</span></h5>
                    <h5>Lugar: <span id="lugar">${data3[index].lugarevento}</span></h5>
                    <h5>Fecha: <span id="fecha">${data3[index].fechaevento}</span></h5>
                  </div>-->
                </div>
              </div><!-- card-body -->
            </div>
        </div>`
    }
    $('#listar').html(listar);

    for (let index2 = 0; index2 < data3.length; index2++) {
      pdfjsLib.getDocument('../../assets/images/constancias/'+data3[index2].nombre_reconocimiento+'.pdf').then(doc =>{
        //console.log("this file has"+doc._pdfInfo.numPages+"pages");
    
        doc.getPage(1).then(page=>{
          var myCanvas=document.getElementById("my_canvas"+index2);
          var context=myCanvas.getContext("2d");
          var viewport=page.getViewport(.32);
          myCanvas.width=viewport.width;
          myCanvas.hight=viewport.height;
    
            page.render({
              canvasContext:context,
              viewport:viewport
            });
        });
      });
    }

  });
 $.post("../../assets/data/Controller/asistentes/asistentesControl.php",{action: "obtenergrados",emailobtener:$('#emailobtener').text()}, function(data4, status){
    //console.log(data4)
	data4 = JSON.parse(data4);
	
    var listar_grado=`
      <div class="row" id="grades">
    `;
    for ( index2 = 0; index2 < data4.length; index2++) {
     listar_grado+= `
            
               <div class="col-sm-3 col-md-3 align-items-center">
                 <div class="card mb-4">
                  <div class="card-body bg-light">
                    <div class="text-center">
                      <div class="mb-3"><strong>${data4[index2].grado}</strong></div>
                      <i class="fa fa-graduation-cap tx-30 lh-0 tx-teal op-5"></i>
                      <p class="mb-0">${data4[index2].titulo}</p><p class="mb-0"><i>${data4[index2].cedula}</i>
                    </div>
                  </div>
                 </div>
                </div>`;
   }
   listar_grado += ` </div>`;
   
    /*<div class="row"><table class="table"><thead><th>Grado</th><th>Titulo</th><th>Cédula</th></thead>
    for ( index2 = 0; index2 < data4.length; index2++) {
      listar_grado+= `
            <tr><td><b>${data4[index2].grado}:</b></td><td>${data4[index2].titulo}</td><td>${data4[index2].cedula}</td></tr>
          `
    }
    listar_grado+=`</table></div></div></div>`;*/
    $('#listar_grado').html(listar_grado);
  });
