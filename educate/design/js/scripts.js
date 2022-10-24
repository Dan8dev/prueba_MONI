$(document).ready(()=>{

    window.onscroll = function() {
        var y = window.scrollY;
        if(y > 50){
            $('.header-cover').addClass('active');
        }else{
            $('.header-cover').removeClass('active');
        }
    };
    $('.content-video').on('click',function(){
        $("#modalVideo").modal('show');
    });

    $('#docentes').on('click','#moreT',function(){

        var newShow = $('.rev').last(),
            removeC = $('.rev.hidden');

        if(removeC.length > 0){
           
            for(var i = 0; i < 4; i++){

                removeC.eq(i).removeClass('hidden');
            }
        }else{
            
            addC = $('.rev');
            for (var i = 0; i < addC.length; i++){
                addC.eq(i).addClass('hidden');
            }
            
        }

        if(!newShow.hasClass('hidden')){
            
            $(this).text('Ocultar');
        }else{
            $(this).text('Ver más');
        }
    });
});

listedExam = [];
listedContentBlogs = [];
listedAvance = [];
$st = localStorage.getItem('alumno');

if($st != null){
    var key = 'SystemsUDC';
var decrypt = CryptoJS.AES.decrypt($st, key).toString(CryptoJS.enc.Utf8);
var us = decrypt.split('-')[1];
var pros = decrypt.split('-')[0];

}
else{
    var us = 541;
    var pros = 441; 
}
localStorage.setItem('afiliado',us);

function formatDate(newdate){
    months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
    'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    days=["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

    date = new Date(newdate);
    month = months[date.getMonth()];
    dayWeek = days[date.getDay()];
    if(date.getMinutes() < 10){
        minutes = '0'+date.getMinutes();
    }else{
     minutes =   date.getMinutes();
    }
    day = dayWeek+', '+date.getDate()+' de '+month+' del '+date.getFullYear();
    hours = date.getHours()+':'+minutes+' hrs.';
    return date = [day,hours];
}
function slides(id,_this,idMat){
    $('.blContent').addClass('hidden');
    $('.blocks').addClass('hidden');
    $('#titleC').addClass('hidden');

    if($(_this).hasClass('fa-angle-down')){
        
        tit = $('#titleM'+idMat).text();
        $('#titleM').text(tit);
        $('#titleM'+idMat).addClass('hidden');
        $('.drop').addClass('fa-angle-down');
        $('.drop').removeClass('fa-angle-up');
        $(_this).addClass('fa-angle-up');
        $(_this).removeClass('fa-angle-down');
        $('#bl'+id).removeClass('hidden');
        parent = $(_this).parents('.blocks');
        parent.removeClass('hidden');
        $('.blocks').removeClass('active');

    }else{
        $('.blocks').addClass('active');
        $('#titleC').removeClass('hidden');
        $('#titleM').text('');
        $('#titleM'+idMat).removeClass('hidden');
        $('.blocks').removeClass('hidden');
        $('.drop').addClass('fa-angle-down');
        $('.drop').removeClass('fa-angle-up');
    }

    if($('#mat-avance').hasClass('hidden')){
        $('#mat-avance').removeClass('hidden');
    }else{
        $('#mat-avance').addClass('hidden');
    }
    //console.log(listedAvance);
    elements = listedAvance.find(elem=>elem.idM == idMat);
    $('#gTemary .content-img.ct-main').css('background-image','url('+elements.imaM+'),url(./design/imgs/img-no-disponible.jpg)');
    $('#gTemary #txt-per').text(elements.porc);
    $('#gTemary .percentage p span').css('width',elements.porc+'%');
    //$(window).scrollTop('#main'+id);
}
function cargar_materias(curso){

    localStorage.setItem('gn',curso);
    $panel= localStorage.getItem('panel');
   
    html = '';
    $li = '';
	$.ajax({
		url: "../assets/data/Controller/educate/educateControl.php",
		type: "POST",
		data: {action:'cargar_materias',curso:curso,edukt:'on',idalumno:pros,panel:$panel},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			
            try{
				materias = JSON.parse(data);
                
                console.log(materias);
				if(materias.estatus == 'ok'){

                    teacher = '<h5 class="border-bottom pb-2">Docente(s)</h5>';

					if(materias.data.materias_ciclo.length > 0){

                        $classNext = [];
                        $className = [];
                        $classPass = [];
                        $classNpass = [];
                        
                        examE  = '';
                        examO = '';
                        imgG = '';
                       
                        
						$.each(materias.data.materias_ciclo,function(i,item){

                            imgG = item.imgc;
                            sessionTotals = '';
                            contentTotals  ='';
                            contPer = 0;
                            totalClass = 0;

                            $panel = localStorage.getItem('panel');

                            $.each(item.clases,function(i, clss){
                                
                                //console.log(clss);
                                $today = new Date();   
                                $dayClass = new Date(clss.fecha_hora_clase);
                                newDate = formatDate(clss.fecha_hora_clase);
                                date = newDate[0]+' / '+newDate[1];
                                if($dayClass.valueOf() < $today.valueOf()){
                                    contPer++;
                                    $classPass.push(clss.fecha_hora_clase);
                                    $classNpass.push(clss.titulo);

                                    url = 'href="class.php?gn='+curso+'&mt='+item.id_materia+'&se='+i+'"'; 
                                    target = '';

                                }else{
                                    $classNext.push(clss.fecha_hora_clase);
                                    $className.push(clss.titulo);
                                    $less = 'more';
                                    url = 'onclick="wClass(`'+date+'`,`'+$less+'`)" role="button"';
                                }

                                if($panel != 'moni'){
                                    $sess = consultar_sesiones(curso);
                                    if($sess != ''){

                                        console.log($sess);
                                        var sesiones = $sess;
                                        //var html_sesiones = ``;
                                        if(sesiones[0].fecha_clase.valueOf() === clss.fecha_hora_clase.valueOf()){

                                            if(sesiones[0].idInstitucion == 13){
                                                $panel = 'siscon';
                                                //$url = 'https://conacon.org/moni';
                                                $url = '';
                                            }else{
                                                $panel = 'udc';
                                                $url = '';
                                            }
                                            $alumno = localStorage.getItem('afiliado');
                                            url = `href="${$url}/${$panel}/app/claseswebex/?sesion=${sesiones[0].id}&alumno=${$alumno}"`;
                                            target = 'target="_blank"';
                                        }else{
                                            url = 'href="class.php?gn='+curso+'&mt='+item.id_materia+'&se='+i+'"'; 
                                            target = '';
                                        }
                                    }
                                }
                                

                                if(clss.foto != '' && clss.foto != null && clss.foto != undefined){
                                    imgClass = clss.foto;
                                }else{
                                    imgClass = imgG;
                                }
                                sessionTotals += '<a '+target+'class="row my-3" '+url+'><div class="col-sm-3"><div class="content-img" style="height:80px;background-image: url('+imgClass+'),url(./design/imgs/img-no-disponible.jpg)"></div></div><div class="col-sm-9"><p class="mb-0 fw-bold">'+clss.titulo+'</p><p class="small-size mb-0">'+date+'</p></div></a>';
                                //$classN = clss.titulo;
                            });
                            if(item.bloques.length > 0){

                                $.each(item.bloques,(i,bl)=>{

                                    actiTotals  ='';
                                    elementz = [];
                                    examenTotal = '';
                                  
    
                                    if(bl.title_bloque != null && bl.title_bloque != ''){
                                        titleb = bl.title_bloque;
                                    }else{
                                        titleb = 'Bloque'+(i+1);
                                    }
                                    contentTotals += '<div class="bgrs mb-3"><h5>'+titleb+'</h5><div class="">';

                                    if(item.extra_clases.length > 0){
                                        $.each(item.extra_clases,function(i, clss){
    
                                            if(clss.id_bloq == bl.id){
                                                url = 'href="blog.php?gn='+curso+'&mt='+item.id_materia+'&se='+clss.orderB+'&bl='+clss.id_bloq+'&tp='+clss.tipo_de_blog+'"';
                                                if(clss.tipo_de_blog != 2){
                                                    
                                                    contentTotals += '<a class="row my-3" '+url+'><div class="col-sm-3"><div class="content-img" style="height:80px;background-image: url('+clss.foto+'),url(./design/imgs/img-no-disponible.jpg)"></div></div><div class="col-sm-9"><p class="mb-0 fw-bold">'+clss.title+'</p></div></a>';
                                                }else{
                                                    
                                                    actiTotals += '<a class="row my-3" '+url+'><div class="col-sm-3"><div class="content-img" style="height:80px;background-image: url('+clss.foto+'),url(./design/imgs/img-no-disponible.jpg)"></div></div><div class="col-sm-9"><p class="mb-0 fw-bold">'+clss.title+'</p></div></a>';
                                                }
                                            }
                                            //console.log(clss);
                                            //$classN = clss.titulo;
                                        });
                                    }
                                    
                                    if(item.examenes.length > 0){
                                        elementz = item.examenes.filter((elem)=>elem.id_bloq == bl.id);
                                        examenTotal = getExam(elementz)[0];
                                    }

                                    if(examenTotal != '' || actiTotals != ''){
                                        $title  = '<h5>Espacio de Evaluación</h5>';
                                    }else{
                                        $title = '';
                                    }
                                    
                                    contentTotals += '<div>'+$title+ 
                                    examenTotal+
                                    actiTotals+
                                    '</div></div></div>';
                                });
    
                            }
                            if(item.title != '' && item.title != null){
                                titleM = item.title;
                            }else{
                                titleM = item.nombre;
                            }
                            html += '<div class="blocks active mb-3"><h3 id="main'+(i+1)+'">'+
                            '<i class="fa fa-angle-down float-end drop show'+item.id_materia+'" onclick="slides('+(i+1)+',this,'+item.id_materia+')"></i>'+

                            '</h3><h4 id="titleM'+item.id_materia+'">'+titleM+'</h4>'+
                            '<div class="blContent hidden" id="bl'+(i+1)+'">'+
                            '<div class="border-bottom-2">'+contentTotals+'</div>';

                            if(sessionTotals != ''){
                                html += '<div class="clss mt-3"><h5>Clases en línea</h5>'+
                                sessionTotals+'</div>';
                            }
                            
                            html +='</div></div>';
                            totalClass = item.clases.length;


                            if(totalClass > 0){
                                contPer = parseInt(contPer*100 / totalClass);
                            }
                            //console.log(totalClass,contPer)
                            listedAvance.push({idM: item.id_materia, imaM: item.imagen, porc:contPer});

                        });

                    
                        examO +=  getExam(materias.data.examenes)[2];
                        examE += getExam(materias.data.examenes)[1];

                        if(examO.length > 0 || examE.length > 0){
                            $('#examOficial').removeClass('hidden');
                        }

                        $('#examO').html(examO);
                        $('#examEx').html(examE);

                        if($classNext.length > 0){
                            $nexClass = '';
                            $i = 0;
                            var BreakException = {};
                            try{
                                $.each($classNext, (i, item)=>{

                                    $today = new Date();
                                    $nexClass = new Date(item);
                                    $i = i;

                                    if($nexClass.valueOf() > $today.valueOf()) throw BreakException;
                                    
                                });
                            }catch(e){
                                if (e !== BreakException) throw e;
                            }
                            newDate = formatDate($nexClass);
                            date = newDate[0]+' / '+newDate[1];
                            $classN = $className[$i];
                        }else{

                            let arrayFechas = $classPass.map((initial) => new Date(initial) );

                            var max = new Date(Math.max.apply(null,arrayFechas));

                            var indice = 0;
                            for (let index = 0; index < arrayFechas.length; index++) {
                                if (arrayFechas[index].toUTCString()===max.toUTCString()) {
                                    indice=index;
                                }
                                
                            }

                            newDate = formatDate(max);
                            date = newDate[0]+' / '+newDate[1];
                            $classN = $classNpass[indice];
                        }
                        

                        //console.log($classNext,date,$classN);

                        if(date.split(' ')[0] != 'undefined,'){
                            $('#dateClass').html(date);    
                        }else{
                            $('#dateClass').html('Aún no está programada.');
                        }
                        
                        $('#nextClass').html($classN);
                        if(materias.data.materias_ciclo[0].acTitle != null && materias.data.materias_ciclo[0].acTitle != ''){
                            title = materias.data.materias_ciclo[0].acTitle;
                        }else{
                            title = materias.data.materias_ciclo[0].acNombre;
                        }
                        $('#titleC').text(title);
                        //$('#listOnline').html($li);
						$('#temary').html(html);
					}else{
                        Swal.fire({
                            title: '',
                            html:
                            'No hay materias para este curso.',
                            type: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#2826aa',
                            cancelButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar',
                            cancelButtonText: 'Revisar de nuevo',
                        })
						
					}
                    $.each(materias.data.maestros, (i,item)=>{
                   
                        $open = '';
                        $close = '';
                        $more = '';
                        
                        if(i > 0){
                                
                            $open = '<div class="rev hidden">';
                            $close = '</div>';
                        }
                        $names = item.nombres+' '+item.aPaterno+' '+item.aMaterno
                        if(item.descripcion != null && item.descripcion != ''){
                            des = item.descripcion;
                        }else{
                            des = '';
                        }
                        teacher += $open+'<div class="content-img img-docent" style="background-image: url('+item.foto+'),url(./design/imgs/no-user.jpeg)"></div>'+
                        '<h5 class="text-center my-2">'+$names+'</h5>'+
                        '<p class="fw-normal small-size text-">'+des+'</p>'+$close;
                    });
                    //console.log(teacher);
                    if(teacher.length > 0){
                        
                        if(materias.data.maestros.length > 1){
                            teacher += '<button class="btn btn-default float-end btn-outline-teal" id="moreT">Ver más</button>';
                        }
                        
                        $('#docentes').removeClass('hidden');
                        $('#docentes').html(teacher);
                    }
				}else{
                    Swal.fire({
                        title: '',
                        html:
                        materias.info,
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    })
				}
			}catch(e){
				console.log(e);
				console.log(data);
			}
            if(localStorage.getItem('panel') == 'moni'){

                var params = new URLSearchParams(location.search);
                var mat = params.get('mt');
                $('.drop.show'+mat).click();
            }
		},
		error: function(){
		},
		complete: function(){
			$("#loader").css("display", "none")
		}
	});
}
function cargar_clases(materia, generacion,session,online,bloq,$type){

    localStorage.setItem('mt',materia);
    localStorage.setItem('gn',generacion);
    localStorage.setItem('se',session);

    if(session != undefined){
        session = session
    }else{
        session = 0;
    }
	html = '';
	$.ajax({
		url: "../assets/data/Controller/educate/educateControl.php",
		type: "POST",
		data: {action:'cargar_clases',materia:materia, generacion:generacion,idalumno:pros},
		beforeSend : function(){
		},
		success: function(data){

            clases = JSON.parse(data);
            
			try{
				clases = JSON.parse(data);
				if(clases.estatus == 'ok'){

                    if(clases.data.length > 0 || clases.blogs.length > 0){
                    
                        
                        if(online > 0){

                            $dir = 'class.php';

                            $today = new Date();
                            listedDates = [];
                            listedClass = [];
                            $day = '';
                            $clasN = '';
                            $.each(clases.data,function(i,elem){

                                $dayClass = new Date(elem.fecha_hora_clase);
                                
                                if($dayClass.valueOf() >= $today.valueOf()){
                                    
                                    listedDates.push(elem.fecha_hora_clase);
                                    listedClass.push(elem.titulo);
                                    //console.log($today);
                                }

                                $newDate = formatDate(elem.fecha_hora_clase);
                                $day = $newDate[0]+' / '+$newDate[1];
                                $clasN = elem.titulo;
                            });


                            if(listedDates.length > 0){

                                $nexClass = '';
                                $i = 0;
                                var BreakException = {};
                                try{
                                    $.each(listedDates, (i, item)=>{

                                        $today = new Date();
                                        $nexClass = new Date(item);
                                        $i = i;

                                        if($nexClass.valueOf() >= $today.valueOf()) throw BreakException;
                                        
                                    });
                                }catch(e){
                                    if (e !== BreakException) throw e;
                                }

                                $clasN = listedClass[$i];
                                newDate = formatDate($nexClass);
                                $day = newDate[0]+' / '+newDate[1];
                            }

                            $('#classSe').html(html);
                            var $name = clases.data[session].nombres+' '+clases.data[session].aPaterno+' '+clases.data[session].aMaterno;
                            var clss = clases.data[session].nombre;
                            var title = clases.data[session].titulo;
                            $('#numberSession').text(session+1);
                            $('#title').text(clss.toUpperCase());
                            $('#titleSession').text(title.toUpperCase());
                            $('#teacher').html($name.toUpperCase());
                            $('.title-session').text(title);
                            if(clases.data[session].video != ''){
                                $('video').attr('src',decodeURIComponent(clases.data[session].video));
                            }else{
                                $('#videoSession p').text('Sin videos para esta clase');
                                $('video').addClass('hidden');
                            }
                        
                            if(clases.data[session].apoyo.length > 0){
                                var $li = '';
                                $.each(clases.data[session].apoyo,function(i,support){
                                    $li += ' <li><a class="active" target="_blank"href="../assets/files/clases/apoyos/'+support[0]+'">'+support[1]+'</a></li>' 
                                    $('#listSupport').html($li);
                                });
                            }else{
                                var $li = ' <li><a href="#">Sin material de apoyo</a></li>';
                                    $('#listSupport').html($li);
                            }
                            if(clases.data[session].recursos.length > 0){
                                var $li = '';
                                $.each(clases.data[session].recursos,function(i,resource){
                                    $li += ' <li><a class="active" target="_blank" href="../assets/files/clases/recursos/'+resource[0]+'">'+resource[1]+'</a></li>' 
                                    $('#listResourceB').html($li);
                                    $('#listResource').html($li);
                                });
                            }else{
                                var $li = ' <li><a href="#">Sin recursos descargables</a></li>'; 
                                $('#listResourceB').html($li);
                                $('#listResource').html($li);
                            }

                            if(clases.data[session].tareas.length > 0){
                                var $li = '';
                                $click = '';
                                $status = '';
                                $.each(clases.data[session].tareas,function(i,homework){

                                    $initial = homework.titulo.toLowerCase()[0].toUpperCase();
                                    $rest = homework.titulo.slice(1).toLowerCase();
                                    $names = $initial + $rest;
                                    $deliver = '';
                                    $click = 'onClick="entregar_tarea('+homework.idTareas+',`'+homework.titulo+'`,'+homework.idClase+','+generacion+')"';
                                    $status = 'no entregado';
                                    $button = '<button type="button" class="btn btn-primary mb-2 btn-vine" '+$click+'>'+$names+' / '+$status+'</button>';
                                    $day = 'n/a';
                                    $hours = 'n/a';
                                    $comment = 'N/A';
                                    $note = 'N/A';
                                    $retro = '';
                                    $finLi = '';

                                    $newDate = formatDate(homework.fecha_limite);
                                    $dayS = $newDate[0];
                                    $hoursD = $newDate[1];

                                    if(homework.entregas.length > 0){
                                        $status = 'entregado';
                                        $button = '<button type="button" class="btn btn-primary mb-2 btn-vine" '+$click+'>'+$names+' / '+$status+'</button>';
                                        //$button = '<li class="fw-bold border-bottom p-1 color-black">'+$names+'('+$status+')';
                                        //$finLi = '</li>'; 
                                        $finLi = '';
                                        $.each(homework.entregas,(i,deli)=> {
                                            $newDate = formatDate(deli.fecha_entrega);
                                            $day = $newDate[0];
                                            $hours = $newDate[1];
                                            $comment = deli.comentario;
                                            if(deli.comentario != '' && deli.comentario != null && deli.comentario != 0){
                                                $note = deli.comentario;
                                            }else{
                                                $note = 'Sin comentarios';
                                            }
                                            if(deli.calificacion != '' && deli.calificacion != null && deli.calificacion != 0){
                                                $note = deli.calificacion;
                                                $colorClass = ' color-success';
                                            }else{
                                                $note = 'pendiente';
                                                $colorClass = ' color-orange';
                                            }
                                            if(deli.retroalimentacion != '' && deli.retroalimentacion != null && deli.retroalimentacion != 0){
                                                $retro = deli.retroalimentacion;
                                            }else{
                                                $retro = 'Sin retroalimentación';
                                            }

                                            $deliver += '<li>'+
                                            '<p class="color-gray-opacity bg-transparent small-size mb-0 text-capitalize">Comentario: '+$comment+'</p>'+
                                            '<p class="color-gray-opacity bg-transparent small-size mb-0 text-capitalize">Retroalimetación: '+$retro+'</p>'+
                                            '<p class="color-gray-opacity bg-transparent small-size text-capitalize">Entregado: '+$day+' Hora: '+$hours+'</p>'+
                                            '<p class="bg-transparent small-size text-capitalize'+$colorClass+'">Calificación: '+$note+'</p></li>';
                                        });
                                    }
                                        
                                        

                                
                                    if(homework.flag == 'en tiempo'){
                                    
                                        $li += $button+
                                                '<p class="color-gray-opacity bg-transparent small-size mb-0 text-capitalize">Descripción: '+homework.descripcion+'</p>'+
                                                '<p class="color-gray-opacity small-size text-capitalize bg-transparent">Límite de entrega: '+$dayS+' Hora: '+$hoursD+'</p>'+
                                                $deliver+$finLi+''; 
                                    }else{
                                        $li += '<li class="fw-bold border-bottom p-1 title-session">'+$names+'(fecha limite finalizado / '+$status+')'+
                                        '<p class="color-gray-opacity small-size text-capitalize bg-transparent">Límite de entrega: '+$dayS+' Hora: '+$hoursD+'</p>'+
                                        $deliver+
                                        '</li>';        
                                    }
                                    $('#listHw').html($li);
                                });
                            }else{
                                var $li = ' <li class="color-black"><a href="#">Sin tareas</a></li>'; 
                                $('#listHw').html($li);
                            }
                            if(clases.data[session].examenes.length > 0){
                            
                                var $li = '';
                                var $exam = clases.data[session].examenes;
                                $li = getExam($exam);
                                $('#listTesting').html($li);

                            }else{
                                var $li = ' <li class="color-black"><a href="#">Sin examanes para este tema.</a></li>'; 
                                $('#listTesting').html($li);
                            }

                            // if(clases.data[session].blogs.length > 0){
                            //     //$('#contentBlogs').html(clases.data[session].blogs[0].content_blog);
                            //     //$('#headerBlogs').html(clases.data[session].blogs[0].title);
                            // }else{
                            //     $('#OutcontentBlogs').html('"Sin Blog que mostrar"');
                            // }

                            if(clases.data[session].foto != null && clases.data[session].foto != '' && clases.data[session].foto != 0 && clases.data[session].foto != 'undefined'){
                                $('#previewSession').attr('src',clases.data[session].foto);
                            }else{
                                $('#previewSession').attr('src','./design/imgs/img-no-disponible.jpg');
                            }

                            if(session > 0){
                                prevsession = $dir+'?gn='+generacion+'&mt='+materia+'&se='+(parseInt(session)-1)+'';
                                if(session < (clases.data.length-1)){
                                    nextsession = $dir+'?gn='+generacion+'&mt='+materia+'&se='+(parseInt(session)+1)+'';
                                }else{
                                    nextsession = '#';
                                    $('.next').addClass('inactive');
                                }  
                            }else{
                                prevsession = '#';
                                if(session < (clases.data.length-1)){
                                    nextsession = $dir+'?gn='+generacion+'&mt='+materia+'&se='+(parseInt(session)+1)+'';
                                }else{
                                    nextsession = '#';
                                    $('.next').addClass('inactive');
                                }
                                $('.prev').addClass('inactive');
                            }
                            $('.prev').attr('href',prevsession);
                            $('.next').attr('href',nextsession);
                            
                        }else{

                            localStorage.setItem('bl',bloq);
                            localStorage.setItem('tp',$type);

                            sessions = clases.blogs.find(el=>el.orderB == session && el.id_bloq == bloq && el.tipo_de_blog == $type);
                        
                            if(session.tipo_de_blog == 2){
                                $('.container-comments').addClass('d-none');
                                $button = '<button type="button" class="btn btn-primary mb-2 btn-vine" onClick="entregar_tarea('+sessions.id_blog+',`'+sessions.title+'`,'+generacion+',`act`)">sube tu actividad</button>';
                            }else{
                                $button = '';
                            }
                            $('#contentBlogs').html(sessions.content_blog+$button);
                            $('#titleSession').text(sessions.title.toUpperCase());
                            $('#idClass').val(sessions.id_blog);
                            if(sessions.foto != null && sessions.foto != '' && sessions.foto != 0 && sessions.foto != 'undefined'){
                                $('#previewSession').attr('src',sessions.foto);
                            }else{
                                $('#previewSession').attr('src','./design/imgs/img-no-disponible.jpg');
                            }

                            contentImage = '';
                           
                            $files = JSON.parse(sessions.archivo);
                            $.each($files,(i,item)=>{
                                 contentImage += '<li class=""><a target="_blank" href="../assets/files/educate/'+materia+'/'+item+'">'+item+'</a></li>';
                             });
                             $('#showFiles').html(contentImage);
                            
                            $dir = 'blog.php';
                            $count = clases.blogs.map(el=>
                                el.tipo_de_blog == sessions.tipo_de_blog && el.id_bloq == sessions.id_bloq);

                                $i = 0;
                                $count.find(c=>{
                                    if(c != false){
                                        $i++
                                    }
                                });
                            if(session > 1){
                                prevsession = $dir+'?gn='+generacion+'&mt='+materia+'&se='+(parseInt(session)-1)+'&bl='+sessions.id_bloq+'&tp='+$type+'';
                                
                                if(session < $i){
                                    nextsession = $dir+'?gn='+generacion+'&mt='+materia+'&se='+(parseInt(session)+1)+'&bl='+sessions.id_bloq+'&tp='+$type+'';
                                }else{
                                    nextsession = '#';
                                    $('.next').addClass('inactive');
                                }  
                            }else{
                                prevsession = '#';
                                if(session < $i){
                                    nextsession = $dir+'?gn='+generacion+'&mt='+materia+'&se='+(parseInt(session)+1)+'&bl='+sessions.id_bloq+'&tp='+$type+'';
                                }else{
                                    nextsession = '#';
                                    $('.next').addClass('inactive');
                                }
                                $('.prev').addClass('inactive');
                            }
                            $('.prev').attr('href',prevsession);
                            $('.next').attr('href',nextsession);
                        }
                    }  
				}
				
			}catch(e){
				console.log(e);
				console.log(data);
			}
            getCommits();
		},
	});
}
function entregar_tarea(id, titulo, clase,$act){
	$("#titulo_tarea").html(titulo);
	$("#tarea_entrega").val(id);
	$("#clase_tarea").val(clase);

    if($act != undefined){
        $("#form_entrega_tarea").append('<input type="hidden" name="'+$act+'" value="on">')
    }

	$("#tareasModal").modal('show');
    submitHw($act);
}
function submitHw($act){

    console.log($act);
    
    $("#form_entrega_tarea").on('submit', function(e){
        e.preventDefault();
    
        fdata = new FormData(this)
        fdata.append('action', 'enviar_tarea');
        fdata.append('idalumno', pros);
    
        $.ajax({
            url: "../assets/data/Controller/educate/educateControl.php",
            type: "POST",
            data: fdata,
            contentType:false,
            processData:false,
            beforeSend : function(){
                $("#loader").css("display", "block")
            },
            success: function(data){

                if($act == 'act'){
                    $title = 'Actividad enviada';
                    $cont = '';
                }else{
                    $title = 'Tarea enviada';
                    $cont = 'espere calificación.';
                }
                try{
                    resp = JSON.parse(data)
                    //console.log(resp)
                    if(resp.estatus == 'ok'){
                        Swal.fire({
                            title: $title,
                            html:
                            $cont,
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#2826aa',
                            cancelButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar',
                            cancelButtonText: 'Revisar de nuevo',
                        }).then((result) => {
                            if (result.value == true) {
                                
                                location.reload();
                            }else{
                                location.reload();
                            }
                        });
                        
                    }else{
                        if(resp.info == 'error_al_adjuntar_tarea'){
                            Swal.fire({
                                title: '',
                                html:
                                'Ha ocurrido un error al adjuntar el archivo. Verifique los requisitos.',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#2826aa',
                                cancelButtonColor: '#dc3545',
                                confirmButtonText: 'Aceptar',
                                cancelButtonText: 'Revisar de nuevo',
                            })
                           
                        }else{
                            Swal.fire({
                                title: '',
                                html:
                                'Ha ocurrido un error al entregar su tarea'+resp.info,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#2826aa',
                                cancelButtonColor: '#dc3545',
                                confirmButtonText: 'Aceptar',
                                cancelButtonText: 'Revisar de nuevo',
                            })
                        }
                    }
                    //cargar_clases(clase, gn);
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
            },
            complete: function(){
                $("#loader").css("display", "none")
            }
        });
    })
}
function wClass(date,$less){

    if($less != 'less'){
        $html = 'Tú clase esta programada para la fecha: '+date;
        $title = 'Sin clase activa';
        $status = true;
    }else{
        $title = 'No hubo clase activa';
        $html = 'Tu clase programada para la fecha: '+date+' , <b>no tuvo sesión en linea</b>';
        $status = false;
    }

    Swal.fire({
        title: $title,
        html: $html,
        type: 'info',
        showCancelButton: false,
        showConfirmButton: $status,
        confirmButtonColor: '#2826aa',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Revisar de nuevo',
    });
}
function cargar_cursos_pagos(){ // consultar carreras con generaciones asignadas al alumno

	$.ajax({
		url: "../assets/data/Controller/educate/educateControl.php",
		type: "POST",
		data: {action:'pago_cursos',idalumno:pros},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				cursos_disp = JSON.parse(data);
				//console.log(cursos_disp);
                if(cursos_disp.estatus == 'ok'){

                    if(cursos_disp.data.length > 0){
						$.each(cursos_disp.data,function(i,item){

                            if(item.imagen != null && item.imagen != ''){
                                imgG = item.imagen;
                            }else{
                                imgG = '';
                            }
                            cardsVisibles(item.idInstitucion,item.idalumno,item.idgeneracion,item.nombre,imgG);                           
                        });
						
					}else{
                        Swal.fire({
                            title: '',
                            html:
                            'No hay materias para este curso.',
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#2826aa',
                            cancelButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar',
                            cancelButtonText: 'Revisar de nuevo',
                        })
						
					}
				}else{
                    Swal.fire({
                        title: '',
                        html: materia.info,
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
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
			$("#loader").css("display", "none")
		}
	});
}
function saveCommits(){

    $('#commitsForm').on('submit',function(e){

        e.preventDefault();
        $form = new FormData(this);
        $form.append('action','saveCommits');
        $form.append('idalumno', pros);
        $form.append('gen',localStorage.getItem('gn'));
        $.ajax({
            url: "../assets/data/Controller/educate/educateControl.php",
            type: "POST",
            data: $form,
            processData: false,  // tell jQuery not to process the data
            contentType: false ,
            success: function(data){
               //console.log(data);
                json = JSON.parse(data);
                console.log(json);
                if(json.estatus == 'ok'){
                    getCommits();
                    $('#commitsClass').val('');
                    Swal.fire({
                        title: '',
                        html:
                        'Comentario agregado correctamente.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    })
                }
            },
        });
    });
}
function getCommits(){

    admin = localStorage.getItem('panel');
    if(admin == 'moni'){
        $('.container-comments #commitsForm').addClass('hidden');
    }

    htmlCom = '';
    $idClass = $('#idClass').val();
    months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
    'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    days=["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    
    $.ajax({
        url: "../assets/data/Controller/educate/educateControl.php",
        type: "POST",
        data: {action: 'getCommits',idClass: $idClass,gen: localStorage.getItem('gn'),idAlumno:pros},
        success: function(data){
            
            json = JSON.parse(data);


            if(json.estatus == 'ok'){

                //console.log(json);

                    $.each(json.data,function(i,item){

                        date = new Date(item.created);
                        month = months[date.getMonth()];
                        dayWeek = days[date.getDay()];
                        if(date.getMinutes() < 10){
                            minutes = '0'+date.getMinutes();
                        }else{
                            minutes =   date.getMinutes();
                        }
                        date = dayWeek+', '+date.getDate()+' de '+month+' del '+date.getFullYear()+' '+date.getHours()+':'+minutes+' hrs.';
                        $name = item.nombre+' '+item.aPaterno+' '+item.aMaterno;
                        htmlCom += '<div class="row mb-4">'+
                        '<div class="col-sm-2">'+
                            '<div class="bg-gray">';
                            if(item.foto != null && item.foto != ''){
                                img = item.foto;
                                $class = 'rounded-circle';
                            }else{
                                img = './design/imgs/user.png';
                                $class = '';
                            }
                            htmlCom += '<img class="'+$class+'" src="'+img+'">'+
                            '</div>'+
                            '<p class="mb-0 text-center name-comm">'+$name+'</p>'+
                        '</div>'+
                        '<div class="col-sm-10 content-commits-gray">'+
                            '<p class="mb-0 small-size">'+date+'</p>'+
                            '<p class="mb-0">'+item.description+'</p>'+
                        '</div>'+
                    '</div>';
                    });
                    $('.listCommits').html(htmlCom);
            }       
        },
    });
}
function cardsVisibles(idInst,idAlumno, id, names, img){
    html = '';

    if(idInst == 13){
        $url = '../assets/data/Controller/controlescolar/materiasControl.php';
        //$url = '../siscon/app/data/CData/materiasControl.php';
    }else{
        $url = '../assets/data/Controller/controlescolar/materiasControl.php';
        //$url = '../udc_/app/data/CData/materiasControl.php';
    }
    $.ajax({
        url: $url,
        type: 'POST',
        data: {action: 'validar_adeudos',
            id: id,
            idAlumno: idAlumno},
        success: function(data){

            
            if(data.trim() == 'si'){
                url = 'index.php?gn='+id+'';
                html += '<div class="col-sm-4">'+
                        '<a href="'+url+'">'+
                        '<h6 class="mb-2 title mt-3 text-center">'+names+'</h6>'+
                        '<div class="content-img" style="background-image: url('+img+'),url(../assets/images/default-1.png)">'+
                        '</div>'+
                        '</a>'+
                    '</div>';
            }else{
                var mensaje = '';
                switch(data.trim()){

                    case 'no inscripcion':
                        mensaje = 'Su acceso al curso se ha bloqueado por no contar con registro de pago de inscripción';
                    break;
                    case 'no mensualidad':
                        mensaje = 'Su acceso al curso se ha bloqueado por no contar con registro de pago de mensualidad';
                    break;
                    case 'no documentos':
                        mensaje = 'Su acceso al curso se ha bloqueado por falta de entrega de documentos digitales';
                    break;
                    case 'no documentos fisicos':
                        mensaje = 'Su acceso al curso de se ha bloqueado por falta de entrega de documentos fisicos.';
                    break;
                    case 'no reinscripcion':
                        mensaje = 'Su acceso al curso de se ha bloqueado por no contar con registro de pago de re-inscripción.';
                    break;
                }
                if(mensaje != ''){
                    
                    html += '<div class="col-sm-4">'+
                            '<a href="#">'+
                            '<h6 class="mb-2 title mt-3 text-center">'+names+'</h6>'+
                            '<div class="position-relative content-img" style="background-image: url('+img+'), url(../assets/images/default-1.png)">'+
                            '<div id="overlay"><div class="img-locked"><img class="w-100" src="./design/imgs/blocked.png"></div><p>'+mensaje+'</p></div>'+
                            '</div>'+
                            '</a>'+
                        '</div>';
                }
            }
            
            $('#courses').html(html);    
        }
    });
}
function aplicar_examen(exm,idpros,idafi){
	var form = document.createElement("form");
  var element1 = document.createElement("input"); 
  var pros = document.createElement("input");
  var afi = document.createElement("input");

  form.method = "POST";
  form.action = "aplicar_examen.php";   

  element1.value=exm;
  element1.name="examen";
  pros.value = idpros;
  pros.name = 'idpro';
  afi.value = idafi;
  afi.name = 'idafi';

  form.appendChild(element1);
  form.appendChild(pros);
  form.appendChild(afi);  

  form.setAttribute('hidden',true)

  document.body.appendChild(form);

  form.submit();
  form.remove();
}
function terminar_examen(test,tExm){    


    mat = localStorage.getItem('mt');
    gn = localStorage.getItem('gn');
    se = localStorage.getItem('se');

    finish = false;
    window.onbeforeunload = confirmExit;
    function confirmExit() {
        console.log(finish);
        if(!finish){
            return "Desea cerrar sin terminar su examen?";
        }
    }

    $("#form-examen").on('submit',function(e){
        finish = true;
        e.preventDefault();
        fData = new FormData(this)
        fData.append('action','terminar_examen')
        fData.append('code',test)
        fData.append('tExm',tExm)
        fData.append('idalumno',pros)
        $.ajax({
            url: "../assets/data/Controller/educate/educateControl.php",
            type: "POST",
            data: fData,
            processData:false,
            contentType:false,
            beforeSend : function(){
                $("#loader").css("display", "block")
            },
            success: function(data){

                $cal = '';
                $val = 'Evaluación terminada satisfactoriamente.';
                if(tExm != 3){
                    $cal = 'puede consultar su calificación en su panel de clase.'
                    $val = "Examen terminado satisfactoriamente.";
                }
                
                try{
                    exm = JSON.parse(data)
                    if(exm.estatus == 'ok'){

                        Swal.fire({
                            title: $val,
                            html: $cal,
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#2826aa',
                            cancelButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar',
                            cancelButtonText: 'Revisar de nuevo',
                        }).then((result)=>{
                            window.location.replace('index.php?gn='+gn+'');
                        })
                    }else{
                        Swal.fire({
                            title: '',
                            html:
                            exm.info == 'examen_vencido' ? 'Excediste la hora limite del examen':'ha ocurrido algo al guardar las respuestas del examen, contacte a soporte técnico',
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#2826aa',
                            cancelButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar',
                            cancelButtonText: 'Revisar de nuevo',
                        }).then((result)=>{
                            window.location.replace('index.php?gn='+gn+'');
                        })
                    }
                    //console.log(exm)
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
            },
            complete: function(){
                $("#loader").css("display", "none")
            }
        })
    })
}
function getExam($exam){        

   
    var $li = '', $liE = '', $liO = '';
 

    admin = localStorage.getItem('panel');
    $.each($exam,function(i,testing){

        
        if(testing.tipo_examen == 3){

            //$initial = testing.Nombre.toLowerCase()[0].toUpperCase();
        //$rest = testing.Nombre.slice(1).toLowerCase();
        $names = testing.Nombre;
        $newDate = formatDate(testing.fechaFin);
        $dayS = $newDate[0];
        $hoursD = $newDate[1];


        $li += '<div class="row mb-2">'+
        '<div class="col-sm-3">'+
        '<div class="content-img" style="height:80px;background-image: url('+testing.foto+'),url(./design/imgs/img-no-disponible.jpg)"></div></div>'+
        '<div class="col-sm-9">';

        $li += '<li class="fw-bold border-bottom p-1 color-black">'+$names;
       
    if(admin != 'moni'){
        $li += '<button type="button" class="btn-vine btn btn-primary mb-2 d-block mt-1 ms-2" onclick="aplicar_examen('+testing.idExamen+','+pros+','+us+')">Aplicar evaluación</button>';
    }

                
        // if(testing.presentaciones.length > 0){

        //     cali = 'no aprobado';
        //     $colorClass = ' color-orange';

        //     if(testing.multiple_intento > 1){
        //         $.each(testing.presentaciones,(i,test)=>{
        //             cali = test.calificacion+'% de aprobación';
        //             $colorClass = ' color-success';
                    
        //         });
        //     }else{
        //         $.each(testing.presentaciones,(i,test)=>{
        //             if(test.calificacion >= testing.porcentaje_aprobar){
        //                 cali = 'aprobado';
        //                 $colorClass = ' color-success';
        //             }
        //         });
        //     }
         
        //     if(testing.ontime && cali != 'aprobado' && testing.multiple_intento < 2){
                
        //     }else{
        //       if(testing.before){
        //         $newDate = formatDate(testing.fechaInicio);
        //         $dayS = $newDate[0];
        //         $hoursD = $newDate[1];
        //         $li += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
        //         '<p class="bg-transparent small mb-0 p-0 text-lowercase'+$colorClass+'">'+cali+'</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha inicio del examen: '+$dayS+'</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora inicio del examen: '+$hoursD+'</p>'+
        //         '</li>';
        //       }else{
        //         $li += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
        //         '<p class="bg-transparent small mb-0 p-0 text-lowercase'+$colorClass+'">'+cali+'</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p></li>';
        //       }
        //     }
            
        // }else{
        //     if(testing.ontime){
        //         $li += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
        //         '<button type="button" class="btn-vine btn btn-primary mb-2 d-block mt-1 ms-2" onclick="aplicar_examen('+testing.idExamen+')">Aplicar al examen</button>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p></li>';
        //     }else{
        //       if(testing.before){
        //         $newDate = formatDate(testing.fechaInicio);
        //         $dayS = $newDate[0];
        //         $hoursD = $newDate[1];
        //         $li += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
        //         '<p class="bg-transparent small-size mb-0 p-0 text-capitalize">Aún no es hora del examen.</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha inicio del examen: '+$dayS+'</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora inicio del examen: '+$hoursD+'</p>'+
        //         '</li>';
        //       }else{
        //         $li += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
        //         '<p class="bg-transparent small-size mb-0 p-0 text-capitalize">Examen vencido</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
        //         '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p>'+
        //         '</li>'
        //         }
        //     }               
        // }   
      $li += '</div></div>'; 
     }else if(testing.tipo_examen == 2){
       
             //$initial = testing.Nombre.toLowerCase()[0].toUpperCase();
        //$rest = testing.Nombre.slice(1).toLowerCase();
        $names = testing.Nombre;
        $newDate = formatDate(testing.fechaFin);
        $dayS = $newDate[0];
        $hoursD = $newDate[1];

        $liE += '<div class="row">'+
        '<div class="col-sm-3">'+
        '<div class="content-img" style="height:80px;background-image: url('+testing.foto+'),url(./design/imgs/img-no-disponible.jpg)"></div></div>'+
        '<div class="col-sm-9">';
        if(testing.presentaciones.length > 0){

            //cali = 'no aprobado';
            $colorClass = ' color-orange';

            // if(testing.multiple_intento > 1){
            //     $.each(testing.presentaciones,(i,test)=>{
            //         cali = parseInt(test.calificacion)+'% de aprobación';
            //         $colorClass = ' color-success';
                    
            //     });
            // }else{
               
            // }

            $.each(testing.presentaciones,(i,test)=>{
                cali = 'Calificación de examen: '+test.calificacion+'% de aprobación';
                if(parseInt(test.calificacion) >= parseInt(testing.porcentaje_aprobar)){
                    
                    $colorClass = 'color-success';
                }
            });
         
            if(testing.ontime){
                $liE += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small mb-0 p-0 text-lowercase'+$colorClass+'">'+cali+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0">Fecha fin del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent">Hora fin del examen: '+$hoursD+'</p></li>';
            }else{
              if(testing.before){
                $newDate = formatDate(testing.fechaInicio);
                $dayS = $newDate[0];
                $hoursD = $newDate[1];
                $liE += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small mb-0 p-0 text-lowercase'+$colorClass+'">'+cali+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha inicio del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora inicio del examen: '+$hoursD+'</p>'+
                '</li>';
              }else{
                $liE += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small mb-0 p-0 text-lowercase'+$colorClass+'">'+cali+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p></li>';
              }
            }
            
        }else{
            if(testing.ontime){
                $liE += '<li class="fw-bold border-bottom p-1 color-black">'+$names;

                if(admin != 'moni'){
                    $liE += '<button type="button" class="btn-vine btn btn-primary mb-2 d-block mt-1 ms-2" onclick="aplicar_examen('+testing.idExamen+','+pros+','+us+')">Aplicar al examen</button>';
                }
            
                
               
                $liE += '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p></li>';
            }else{
              if(testing.before){
                $newDate = formatDate(testing.fechaInicio);
                $dayS = $newDate[0];
                $hoursD = $newDate[1];
                $liE += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small-size mb-0 p-0 text-capitalize">Aún no es hora del examen.</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha inicio del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora inicio del examen: '+$hoursD+'</p>'+
                '</li>';
              }else{
                $liE += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small-size mb-0 p-0 text-capitalize">Examen vencido</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p>'+
                '</li>'
                }
            }               
        }   
      $liE += '</div></div>'; 
     }else{
             //$initial = testing.Nombre.toLowerCase()[0].toUpperCase();
        //$rest = testing.Nombre.slice(1).toLowerCase();
        $names = testing.Nombre;
        $newDate = formatDate(testing.fechaFin);
        $dayS = $newDate[0];
        $hoursD = $newDate[1];

        
        $liO += '<div class="row">'+
        '<div class="col-sm-3">'+
        '<div class="content-img" style="height:80px;background-image: url('+testing.foto+'),url(./design/imgs/img-no-disponible.jpg)"></div></div>'+
        '<div class="col-sm-9">';
        if(testing.presentaciones.length > 0){

            cali = 'no aprobado';
            $colorClass = ' color-orange';

            if(testing.multiple_intento > 1){
                $.each(testing.presentaciones,(i,test)=>{
                    cali = parseInt(test.calificacion)+'% de aprobación';
                    $colorClass = ' color-success';
                    
                });
            }else{
                $.each(testing.presentaciones,(i,test)=>{
                   
                    if(parseInt(test.calificacion) >= parseInt(testing.porcentaje_aprobar)){
                        cali = 'aprobado';
                        $colorClass = ' color-success';
                    }
                    
                });
            }

          
         
            if(testing.ontime && cali != 'aprobado' && testing.multiple_intento < 2){
                $liO += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small mb-0 p-0 text-lowercase'+$colorClass+'">'+cali+'</p>';
                if(admin != 'moni'){
                    $liO +=  '<button type="button" class="btn-vine btn btn-primary mb-2 d-block mt-1 ms-2" onclick="aplicar_examen('+testing.idExamen+','+pros+','+us+')">Aplicar al examen</button>';
                }
               
                $liO += '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0">Fecha fin del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent">Hora fin del examen: '+$hoursD+'</p></li>';
            }else{
              if(testing.before){
                $newDate = formatDate(testing.fechaInicio);
                $dayS = $newDate[0];
                $hoursD = $newDate[1];
                $liO += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small mb-0 p-0 text-lowercase'+$colorClass+'">'+cali+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha inicio del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora inicio del examen: '+$hoursD+'</p>'+
                '</li>';
              }else{
                $liO += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small mb-0 p-0 text-lowercase'+$colorClass+'">'+cali+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p></li>';
              }
            }
            
        }else{
            if(testing.ontime){
                $liO += '<li class="fw-bold border-bottom p-1 color-black">'+$names;
                if(admin != 'moni'){
                    $liO +=  '<button type="button" class="btn-vine btn btn-primary mb-2 d-block mt-1 ms-2" onclick="aplicar_examen('+testing.idExamen+','+pros+','+us+')">Aplicar al examen</button>';
                }
                $liO += '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p></li>';
            }else{
              if(testing.before){
                $newDate = formatDate(testing.fechaInicio);
                $dayS = $newDate[0];
                $hoursD = $newDate[1];
                $liO += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small-size mb-0 p-0 text-capitalize">Aún no es hora del examen.</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha inicio del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora inicio del examen: '+$hoursD+'</p>'+
                '</li>';
              }else{
                $liO += '<li class="fw-bold border-bottom p-1 color-black">'+$names+
                '<p class="bg-transparent small-size mb-0 p-0 text-capitalize">Examen vencido</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent mb-0 py-0">Fecha fin del examen: '+$dayS+'</p>'+
                '<p class="color-gray-opacity small-size text-capitalize bg-transparent py-0">Hora fin del examen: '+$hoursD+'</p>'+
                '</li>'
                }
            }               
        }   
      $liO += '</div></div>'; 
     }

    });

    //console.log($liO)
    return [$li,$liE,$liO];
    
}
function modalExam(){

    $('#modalExam').modal('show');

}
function consultar_sesiones(generacion){

    $panel = localStorage.getItem('panel');
    html_sesiones = '';

	json = $.ajax({
		url: "../../assets/data/Controller/controlescolar/materiasControl.php",
		type: "POST",
		data: {action:'consultar_sesiones',generacion:generacion},
        async: false
	});

    var sesiones = JSON.parse(json.responseText);
    //console.log(sesiones);
    return sesiones;
}