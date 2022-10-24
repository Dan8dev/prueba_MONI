$.post("../../assets/data/Controller/asistentes/asistentesControl.php",{action: "obtenerconstancias",emailobtener:$('#emailobtener').text()}, function(data3, status){
    console.log(data3)
	data3 = JSON.parse(data3);
	
    var listar='';
    for ( index = 0; index < data3.length; index++) {
      listar+= `

      <div class="col-lg-4 col-sm-12 text-center mg-t-25">
        <div class="card shadow-base card-body pd-25 bd-0 mg-t-20 ratio ratio-16x9">
        <a href="../../assets/images/constancias/${data3[index].nombre_reconocimiento}.pdf" target="_blank"><canvas id="my_canvas${index}"></canvas></a>
        </div><!-- card -->
      </div>
      <div class="col-lg-8 col-sm-12 text-center mg-t-25">
        <div class="card shadow-base card-body pd-t-25 mg-t-20">
          <h5 id="tipoevento">${data3[index].tipoevento}</h5>
          <p id="tituloevento" class="tx-normal tx-roboto tx-white">${data3[index].tituloevento}</p>
          <h5 id="duracion">${data3[index].duracion} hrs.</h5>
          <h5 id="lugar">${data3[index].lugarevento}</h5>
          <h5 id="fecha">${data3[index].fechaevento}</h5>
        </div><!-- card -->
      </div>`
    }
    $('#listar').html(listar);

    for (let index2 = 0; index2 < data3.length; index2++) {
      pdfjsLib.getDocument('../../assets/images/constancias/'+data3[index2].nombre_reconocimiento+'.pdf').then(doc =>{
        console.log("this file has"+doc._pdfInfo.numPages+"pages");
    
        doc.getPage(1).then(page=>{
          var myCanvas=document.getElementById("my_canvas"+index2);
          var context=myCanvas.getContext("2d");
          var viewport=page.getViewport(.33);
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
    console.log(data4)
	data4 = JSON.parse(data4);
	
    var listar_grado='';
    for ( index2 = 0; index2 < data4.length; index2++) {
      listar_grado+= `
          <li>${data4[index2].grado} ${data4[index2].titulo}-${data4[index2].cedula}</li>
          `
    }
    $('#listar_grado').html(listar_grado);

  });
