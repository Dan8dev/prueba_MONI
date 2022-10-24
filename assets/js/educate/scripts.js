
var listedContentBlogs = [];
var listedCarrer = [];
var listedExm = [];
var listItems = [];
var dragStartIndex,dragStartIndexB;
var idBStart,idBStartB,$options,$optionM;
var idOrderG,idOrderM,idOrderB,idOrderC,idOrderGIndex,idOrderMIndex,idOrderBIndex,idOrderCIndex;
var key = 'SystemsUDC';
var idCarGlobals = '';
var idGlobalsG = '';
var idGlobalsIndexC = '';
var FilesImg = [];
var ImageUpload;
$(document).ready(()=>{

    link = location.pathname;
    linkCurrent = link.split('/')[1];

    //var url = 'https://moni.com.mx/educate/mycourses.php';
    var url = '../../educate/mycourses.php';
    var encrypt = localStorage.getItem('alumno');
    $('.br-sideleft-menu a[href="../../educate/mycourses.php"]').attr('target','_blank');
    $('.br-sideleft-menu a[href="../../educate/mycourses.php"]').attr('href',url+'?alumno='+encodeURIComponent(encrypt)+'&panel='+linkCurrent);
    if(link.includes('/admineducate')){
        CreatedBlog();
    }
    $("#blogs-tab").on('click', function(){
        fullSSelects();
    });
    $('#class,#actB').on('change', function(){
        $idClass = $(this).val().split(',')[1];
        elements = listedContentBlogs.find(elem => elem.id_blog == $idClass);
        if(elements.content_blog != '' && elements.content_blog != null){
            $('.note-editable').html(elements.content_blog);
        }else{
            $('.note-editable').html('<h2>Redacta tu contenido</h2>');
        }
        if(elements.title != '' && elements.title != null){
            $('#title').val(elements.title);
            
        }else{
            $('#title').val(elements.titulo);
        }
        //console.log(elements.content_blog);
        contentImage = '';
        
        if(elements.archivo != null && elements.archivo != '' && elements.archivo != 'Array'){

            $files = JSON.parse(elements.archivo);
            //console.log($files);
            $.each($files,(i,item)=>{
                contentImage += '<div class="miniaturas">'+
                '<button type="button" class="delete_file" id="'+i+'">'+
                'X</button>'+
                '<img src="../assets/icons/files.png"><span class="tit_files">'+item+'</span></div>';
            });
        }
        $('#oldfiles').val(elements.archivo);
        $('#show-elements').html('');
        $('#show-elementsServer').html(contentImage);
        $('.imgpreviewC').attr('style','background-image:url('+elements.foto+')');
        $('#saveBlogs').attr('disabled',false);
        uploadFiles();

    });
    $('#return').on('click',function(){
        $href = $(this).attr('href');
        $('#cardcontent').removeClass('d-none');
        $('#cardsummer').addClass('d-none');
        $('#formBlogs')[0].reset();
        $('.note-editable').html('<h2>Redacta tu contenido</h2>');
        $('.imgpreviewC').attr('style','background-image: url(../assets/images/default-1.png)');
        $('#imgSend').remove();
        $('#typeB').remove();
        $('#bloqs').remove();
        $('#oldfiles').val('');
        $('#show-elements').html('');
        $('#show-elementsServer').html('');
        $('.note-popover.popover.in.note-link-popover.bottom').css('display','none');
        $('html, body').stop().animate({
            scrollTop: $($href).offset().top - $('#topnav').height()
            }, 1000);

    });
    $('#openFile, #openFileClass').on('click',function(){
        $('.'+$(this).attr('id')).click();
    });
    $('#modalEditContent .close').on('click',function(){
        $('#modalEditContent input[name=idCarrera]').remove();
        $('#modalEditContent input[name=idMateria]').remove();
        $('#modalEditContent input[name=idMateriaB]').remove();
        $('#modalEditContent input[name=img]').remove();
        $('#modalEditContent input[name=Blq]').remove();
        $('#modalEditContent form')[0].reset();
        $('#imgSend').remove();
        $('#formEditContent input[name=idExamen]').remove();
        $('#formEditContent input[name=idBloque]').remove();
        $('.imgpreviewModal').attr('style','background-image:url(../assets/images/default-1.png)');
    });
    $('.openFile, .openFileClass').on('change',function(){

        $parent = $(this).parents('form').attr('id');
        // if($('#'+$parent+' input[name=img]').length > 0){
        //     $('#'+$parent+' input[name=img]').remove();
        // }

        $class = $(this).attr('id');
        $imgFile = $(this).prop('files')[0];
        ImageUpload = $imgFile
        $urlFile = URL.createObjectURL($imgFile);
        var reader = new FileReader(),
		urlBase64;
        // Os esperábais algo más complicado?
        $('.'+$class).attr('style','background-image:url('+$urlFile+')');
        $('#'+$parent).append('<input type="hidden" id="imgSend" value="'+$urlFile+'">');
        console.log($imgFile, ImageUpload, $urlFile)
        /*reader.onload = function(){
            urlBase64 = reader.result;
            //console.log(urlBase64);
            // Hacer lo que se quiera con la url 
           
        }*/
//        reader.readAsDataURL($imgFile);
        //reader.readAsDataUrl($imgFile);

    });
    $('#searchC').on('change', function(e){
        $al = $(this).val();
        if($al == ''){
            showCarrer(listedCarrer);
        }
    });
    $('#searchC').on('keyup', function(e){
        
        if(e.keyCode == 13){
            searchList($(this).val());
        }
    });
    $("#modalEditContent").on('hidden.bs.modal', function () {
        $('#modalEditContent input[name=idCarrera]').remove();
        $('#modalEditContent input[name=idMateria]').remove();
        $('#modalEditContent input[name=idMateriaB]').remove();
        $('#modalEditContent input[name=img]').remove();
        $('#modalEditContent input[name=Blq]').remove();
        $('#modalEditContent form')[0].reset();
        $('.imgpreviewModal').attr('style','background-image:url(../assets/images/default-1.png)');
    });
    $("#modalCrearEvaluacion").on('hidden.bs.modal', function () {
        $('#modalCrearEvaluacion form input[name=idGen]').remove();
        $('#modalCrearEvaluacion form input[name=idCar]').remove();
        $('#modalCrearEvaluacion form input[name=idMat]').remove();
        $('#modalCrearEvaluacion form input[name=idBl]').remove();
        $('#modalCrearEvaluacion form')[0].reset();
    });
    $('#modalCrearEvaluacion .close, #cerrarCrearEva').on('click',function(){
        $('#modalCrearEvaluacion form input[name=idGen]').remove();
        $('#modalCrearEvaluacion form input[name=idCar]').remove();
        $('#modalCrearEvaluacion form input[name=idMat]').remove();
        $('#modalCrearEvaluacion form input[name=idBl]').remove();
        $('#modalCrearEvaluacion form')[0].reset();
    });
    $("#modalAsignarPreguntas").on('hidden.bs.modal', function () {
        $('#modalAsignarPreguntas form input[name=idExamen]').remove();
        $('#modalAsignarPreguntas form')[0].reset();

        html = '<div class="form-group">'+
            '<strong>Pregunta 1</strong>'+
                '<input type="text" class="form-control" name="preguntaExamen0" required="required">'+
                '<input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="A" title="Marcar ésta opción como la correcta" checked>'+
                '<input type="text" name="TextoOpcionExamen0_A" style="border-color: transparent;" placeholder="Opción A..." required="required">'+
                '<input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="B" title="Marcar ésta opción como la correcta">'+
                '<input type="text" name="TextoOpcionExamen0_B"  style="border-color: transparent;" placeholder="Opción B...">'+
                '<input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="C" title="Marcar ésta opción como la correcta">'+
                '<input type="text" name="TextoOpcionExamen0_C"  style="border-color: transparent;" placeholder="Opción C...">'+
                '<input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="D" title="Marcar ésta opción como la correcta">'+
                '<input type="text" name="TextoOpcionExamen0_D" style="border-color: transparent;" placeholder="Opción D...">'+
            '</div>'+
            '<div class="form-group">'+
            '<strong>Pregunta 2</strong>'+
                '<input type="text" class="form-control" name="preguntaExamen1" required="required">'+
                '<input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="A" title="Marcar ésta opción como la correcta" checked>'+
                '<input type="text" name="TextoOpcionExamen1_A" style="border-color: transparent;" placeholder="Opción A..." required="required">'+
                '<input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="B" title="Marcar ésta opción como la correcta">'+
                '<input type="text" name="TextoOpcionExamen1_B"  style="border-color: transparent;" placeholder="Opción B...">'+
                '<input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="C" title="Marcar ésta opción como la correcta">'+
                '<input type="text" name="TextoOpcionExamen1_C"  style="border-color: transparent;" placeholder="Opción C...">'+
                '<input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="D" title="Marcar ésta opción como la correcta">'+
                '<input type="text" name="TextoOpcionExamen1_D" style="border-color: transparent;" placeholder="Opción D...">'+
            '</div>';

            $('#divAgregarPregunta').html(html);
            $('#numeroPreguntaExamen').val(2);
    });
    $('#formEditContent').on('submit', function(e){ 
        e.preventDefault();
        $('#modalEditContent form button').attr('disabled',true);
        imgs = $('#imgSend').val();

        $form = new FormData(this);
        $form.append('action','updateCarrerMat');
        console.log(ImageUpload);
        $form.append('imgSend',ImageUpload);
        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/educate/educateControl.php',
            data: $form,
            processData: false,  // tell jQuery not to process the data
            contentType: false ,
            success: function(data){
            
            json = JSON.parse(data);

            if(json.estatus == 'ok'){
                
                $('#modalEditContent form button').removeAttr('disabled');
                //$("#blogs-tab").click();
                //fullSSelects(

                    $div = $('.first.mats');
                    $id = -1;
                    $bl = undefined;
                    $mat = undefined;
                    
                    switch(json.valueId){
                        case 'Carrera':
                            fullSSelects();
                            $('#formEditContent')[0].reset();
                            $('#imgSend').remove();
                        break;
                        case 'Materia':
                            $('#formEditContent')[0].reset();
                            $('#imgSend').remove();
                            mat(idGlobalsIndexC,idCarGlobals,idGlobalsG);
                        break;
                        case 'Examen':
                            $('#imgSend').remove();
                            $('#formEditContent input[name=idExamen]').remove();
                            $mat = json.idMat.idCurso
                            $bl = json.idMat.idBloq;
                            getBl($div,$mat);
                        break;
                        case 'Bloque':
                            $id = $('input[name=idMateriaB]').val();
                            if($id == undefined){
                                $id = json.idMat;
                            }
                            $('#formEditContent input[name=idMateriaB]').remove();
                            $('#formEditContent input[name=idBloque]').remove();
                            getBl($div,$id);
                        break;
                        default:
                            location.reload(false);
                            break;

                    }
                swal({
                    title: 'actualización exitosa.',
                    icon: 'success',
                    text: '',
                    button: true,
                }).then((result)=>{
                    $('#modalEditContent .close').click();
                    if($bl != undefined){
                        $('#fbloqs-'+$bl).click();
                    }
                });
            }else{
                swal({
                    title: 'conexión inestable intenta más tarde.',
                    icon: 'warning',
                    text: '',
                    button: true,
                }).then((result)=>{
                    location.reload();
                });
                $("#blogs-tab").click();
              }
            }
        });
    });
    $("#formularioAsignarPreguntasEval").on('submit', function(e){
        e.preventDefault();
        fData = new FormData(this);
        fData.append('action', 'asignarPreguntas');
        $.ajax({
            url: '../assets/data/Controller/educate/educateControl.php',
            type: 'POST',
            data: fData,
            contentType: false,
            processData: false,
            success: function(data){
                if(data == 'preguntas_aplicar'){
                    swal({
                        title: "Cantidad incorrecta.",
                        text: "La cantidad de preguntas a aplicar debe ser menor o igual a la cantidad de preguntas creadas.",
                        icon: "info",
                        timer: 5200
                    });
                }
                try{
                    pr = JSON.parse(data)
                    if(pr.estatus == "ok"){
                        swal({
                            title: 'Preguntas Asignadas Correctamente',
                            icon: 'success',
                            text: '',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            $("#formularioAsignarPreguntasEval")[0].reset();
                            $('#modalAsignarPreguntas .close').click();
                        })
                    }
                }catch(e){
                    console.log(data)
                    console.log(e)
                }
            }
        });
    });
    $("#formularioCrearEval").on('submit', function(e){ 
        e.preventDefault();
        $fm =  $(this);
        fData = new FormData(this);
        fData.append('action', 'agregarQuiz');
        $.ajax({
            url: '../assets/data/Controller/educate/educateControl.php',
            type: 'POST',
            data: fData,
            contentType: false,
            processData: false,
            success: function(data){
                if(data == 'preguntas_aplicar'){
                    swal({
                        title: "Cantidad incorrecta.",
                        text: "La cantidad de preguntas a aplicar debe ser menor o igual a la cantidad de preguntas creadas.",
                        icon: "info",
                        timer: 5200
                    });
                }
                try{
                    pr = JSON.parse(data)
                    if(pr.estatus == "ok"){
                        $div = $('.first.mats');
                        $id = $('input[name=idMat]').val();
                        $idB = $('input[name=idBl]').val();
                        getBl($div,$id);
                        swal({
                            title: 'Evaluación creada correctamente, ahora puedes asignarle preguntas(Seleccionando la Evaluación)',
                            icon: 'success',
                            text: '',
                            button: true,
                        }).then((result)=>{
                            $fm[0].reset();
                            $('#fbloqs-'+$idB).click();
                            $('#modalCrearEvaluacion .close').click();
                        })
                    }
                }catch(e){
                    console.log(data)
                    console.log(e)
                }
            }
        });
    });
    $('#btn-updf').on('click',function(){

        $('#upload-pdf').click();
        uploadFiles();
    });
    $('#show-elementsServer').on('click','.delete_file',function(e){
        e.preventDefault();

            $form = new FormData();
            $oldfiles = $('#oldfiles').val();
            $deleteParent = $(this).parent();

            $hidden = $('#class').hasClass('hidden');
            $selected = $('#class').val();
            $selectedAct = $('#actB').val();
            $idB = $('#bloqs').val();
            $type = $('#typeB').val();
            if(!$hidden){
                $idMat = $selected.split(',')[0];
                $idClass = $selected.split(',')[1];
                
            }else{
                $idMat = $selectedAct.split(',')[0];
                $idClass = $selectedAct.split(',')[1];
                
            }

            $form.append('action','deleteFilesBlogs');
            $form.append('oldfiles',$oldfiles);
            $form.append('idClass',$idClass);
            $form.append('index',$(this).attr('id'));

            $.ajax({
                type: 'POST',
                url:'../assets/data/Controller/educate/educateControl.php',
                data: $form,
                processData: false,
                contentType: false,
                success:function(data){

                    console.log(data);

                    json = JSON.parse(data);

                    $idBlogs = json.id;
                    $idBloqs = json.idB.id_bloq;
                    $ordB = json.idB.orderB;

                    if(json.estatus == 'ok'){
                        BloqInfo($idMat);
                        $('.toast-success').html('Elemento(s) eliminados.');
                        $('.toast-success').addClass('show');
                        setTimeout(()=>{
                            $('.toast-success').removeClass('show');
                            //$deleteParent.remove();
                            slideClssOpen($idBloqs,idGlobalsG,idCarGlobals);
                            editClass($idMat,$idBlogs,$type,parseInt($ordB),$idBloqs);
                        },3000);
                    }else{
                        $('.toast-success').html('no se pudo eliminar el archivo, intenta de nuevo.');
                        $('.toast-success').addClass('show');
                        setTimeout(()=>{
                            $('.toast-success').removeClass('show');
                        },3000);
                    }
                }
            });
    });
});
function getBl($div,$id){
    
    $.each($div,(i,el)=>{
        vls = $(el).attr('value');
        if(vls == $id){
            
            $cl = $('.fa-angle-up',el);
            $cl.click();
            $cl.click();
        }

    });
}
function uploadFiles(){

    $('#upload-pdf').on('change',function(){
        var lengthImgs = $(this).prop('files'),
            contentImage = '',
            imgsCount = 0,
            imgUP = '',
            tit = '';

            input = document.getElementById('upload-pdf');
            console.log(input);

        for(var i = 0; i < lengthImgs.length; i++){

            // console.log(lengthImgs[i]);

            var extension = lengthImgs[i].name.replace(/^.*\./, ''),
                imgURL = URL.createObjectURL(lengthImgs[i]);
            if(extension != lengthImgs[i].name){
                extension = extension.toLowerCase();
                
                if((extension === 'png') || (extension === 'jpg') || 
                (extension === 'jpeg')){
                    imgUP = imgURL;
                    tit = '';
                }else{
                    imgUP = '../assets/icons/files.png';
                    tit = '<span class="tit_files">'+lengthImgs[i].name+'</span>';
                }

                contentImage += '<div class="miniaturas">'+
                                    '<button type="button" class="delete_file" id="'+i+'">'+
                                    'X</button>'+
                                    '<img src="'+imgUP+'">'+tit+'</div>';
            }
            // console.log(lengthImgs[i]);
            imgsCount++;
            FilesImg.push(lengthImgs[i]);
        }

        $('#show-elements').html(contentImage);
        console.log(FilesImg);
    });
    $('#show-elements').on('click','.delete_file',function(e){

        $(this).parent().remove();
        //console.log(FilesImg);
        var index = $(this).attr('id');
        FilesImg.splice(index,1);
        console.log(FilesImg,index);
        const dt = new DataTransfer(),
            input = document.getElementById('upload-pdf'),
            { files } = input;

        if(input.length > 0){
            for(var i = 0; i < files.length; i++){
    
                if(index != i){
                    const file = files[i];
                    dt.items.add(file);
                }
            }
            input.files = dt.files;
        }
    });
}
function searchList(vlas){

    let expr = new RegExp(`${vlas}.*`,"i");
    let newArray = listedCarrer.filter(list => expr.test(list.title || list.nombre));

    //console.log(newArray);
    $listed = showCarrer(newArray);

    if($listed != '' && $listed != null){
        $('#carr').html($listed);
    }else{
        $('#carr').html('sin resultados que mostrar intenta de nuevo.');
    }
    
}
function showCarrer(listCarrer){

    $selectClass = '';
    
    $.each(listCarrer, (i, item)=>{

        if(item.fecha_actualizacion != '' && item.fecha_actualizacion != null){
            d = formatDate(item.fecha_actualizacion);
            dates = d[0]+' / '+d[1];
       }else{
        d = formatDate(item.fecha_creado);
        dates = d[0]+' / '+d[1];
       }

       if(item.title != '' && item.title != null){
        title = item.title;
       }else{
        title = item.nombre;
       }
       $selExm = '';

       //var s = "hello world!";
       if (item.imagen == null || item.imagen == ''/* || !item.imagen.match(/;base64,.* /)*/) {
        item.imagen = '../assets/images/default-1.png';
       }
       
       $selectClass += '<div class="row mb-2 border-bottom">'+
       '<div class="col-sm-1 img-carr imgC'+item.idCarrera+'" style="background-image: url('+item.imagen+')'+
       ',url(../assets/images/default-1.png)" onclick="showgen('+(i+1)+',this,'+item.idCarrera+','+item.idGn+')">'+
       '<p class="hidden desC'+item.idCarrera+'">'+item.descriptionC+'</p>'+
       '</div>'+
       '<div class="col-sm-10"><p class="mb-0 titleC'+item.idCarrera+'">'+title+'</p><p class="mb-0"><i class="fa fa-calendar-alt"></i> '+dates+'</p>'+
    //    '<p><i class="fa fa-list"></i> '+item.clavep+'</p>'+
       '<a class="position-absolute d-block a-color-black preview" onclick="previewCar('+item.idGn+')" href="#"><i class="fa fa-eye"></i></a>'+
       '</div>'+
       
       '<div class="col-sm-1 text-right"><button class="btn btn-default" onclick="editContent('+item.idCarrera+',`Editar Carrera`,1,'+item.idCarrera+')"><i class="fa fa-pencil-alt"></i></button></div>'+
       '<div class=" col-12 generation d-none gn'+(i+1)+' mb-3"></div>'+
       '</div>'; 
    });

    return $selectClass;
}
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
function fullSSelects(){
    //console.log()
     $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/educate/educateControl.php',
        data: {action: 'listarCarrerasEduc'},
        success: function(data){

            listedCarrer = JSON.parse(data);
            $listed = showCarrer(listedCarrer);

            if($listed != '' && $listed != null){
                $('#carr').html($listed);
            }else{
                $('#carr').html('sin resultados que mostrar intenta de nuevo.');
            }
        }
    });
}
function mat($ciclo,idCarrera,idG){

    $selExm = '';
    $selectMat = '';
    $num = $ciclo;
    idCarGlobals = idCarrera; 
    idGlobalsG = idG;
    $ciclo = '';
    json = $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/educate/educateControl.php',
        data: {action: 'listarMaterias' ,idCiclo: $ciclo, idPlan: idCarrera },
        success: function(data){

            $.each(JSON.parse(data),(i, item) => {

                if(item.title != '' && item.title != null){
                    title = item.title;
                }else{
                    title = item.nombre;
                }
               //console.log(item)
                $selectMat += '<div class="first mats" value="'+item.id_materia+'"><i class="fa fa-folder foldm'+item.id_materia+'"></i> <p class="mb-0 d-inline-block titleM'+item.id_materia+'">'+title+'</p>'+
                '<p class="hidden desM'+item.id_materia+'">'+item.descriptionM+'</p>'+
                '<button class="btn btn-default" onclick="editContent('+item.id_materia+',`Editar Materia`)"><i class="fa fa-pencil-alt"></i></button>'+
                '<button class="btn btn-default" onclick="editContent('+item.id_materia+',`Blq`)"><i class="fa fa-folder-plus"> Agregar bloque</i></button>'+
                '<a class="position-absolute d-block a-color-black preview" onclick="previewClss('+idG+','+item.id_materia+')" href="#"><i class="fa fa-eye"></i></a>'+
                '<div class="hidden imgM'+item.id_materia+'" style="background-image: url('+item.imagen+')'+
                ',url(../assets/images/default-1.png)"></div>'+
                '<i class="fa fa-angle-down drop" onclick="slideBl('+item.id_materia+',this,'+idG+','+idCarrera+')"></i>'+
                '<div class="mt-3 bloq d-none bl'+item.id_materia+'"></div>'+
                '</div>';
    
            });
            if($selectMat != ''){
                $('.gn'+$num).removeClass('d-none');
                $('.gn'+$num).html($selectMat);
            }else{
                $('.toast-success').html('Sin contenido que mostrar.');
                $('.toast-success').addClass('show');
                setTimeout(()=>{
                    $('.toast-success').removeClass('show');
                },3000);
            }
            //$('.gn'+$ciclo).removeClass('d-none');
            //$('.generation').html($selectMat);
        }
    });
    

    //console.log(JSON.parse(json.responseText));
    

}
function BloqInfo(idMat,idG,idCarrera){

    $selectBl = '';
    json = $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/educate/educateControl.php',
        data: {action: 'getClass' ,idBuscar: idMat },
        success: function(data){

            if(listedContentBlogs.length > 0){
                listedContentBlogs = [];
            }
            if(listedExm.length > 0){
                listedExm = [];
            }

            $.each(JSON.parse(data).data.mat,(i, item) => {

                listedContentBlogs.push(item); 
            });

            $.each(JSON.parse(data).data.exm,(i,item)=>{
        
                listedExm.push(item);
            });

            if(!isNaN(idCarrera)){
                $.each(JSON.parse(data).data.bloq,(i,item)=>{
            
                    if(item.title_bloque != '' && item.title_bloque != null){
                        title = item.title_bloque;
                    }else{
                        title = 'Bloque '+(i+1);
                    }
                    $selectBl += '<div id="car'+item.id+'" class="main-blo" data-index="'+(i+1)+'" data-index-op="0" data-index-oc="'+idCarrera+'" data-index-om="'+idMat+'" data-index-b="'+item.id+'" data-index-ob="'+item.id+'" data-index-og="'+idG+'"><div class="first bloqs draggable" draggable=true value="'+item.id+'"><i class="fas fa-grip-lines"></i> <i class="folders fa fa-folder foldbl'+item.id+'"></i> <p class="mb-0 d-inline-block titleB'+item.id+'">'+title+'</p>'+
                    '<p class="hidden desB'+item.id+'">'+item.descripB+'</p>'+
                    '<div class="hidden imgB'+item.id+'" style="background-image: url('+item.img_bloq+')'+
                    ',url(../assets/images/default-1.png)"></div>'+
                    '<button class="btn btn-default" onclick="editContent('+item.id+',`Editar Bloque`)"><i class="fa fa-pencil-alt"></i></button>'+
                    '<button class="btn btn-default" onclick="newClass('+idMat+',`'+title+'`,1,'+item.id+')"><i class="fa fa-plus"></i> Agregar contenido</button>'+
                    '<button class="btn btn-default" onclick="newClass('+idMat+',`'+title+'`,2,'+item.id+')"><i class="fa fa-plus"></i> Agregar actividades</button>'+
                    '<button class="btn btn-default" onclick="newClass('+idMat+',`'+title+'`,3,'+idG+','+idCarrera+','+item.id+')"><i class="fa fa-plus"></i> Agregar autoevaluaciones</button>'+
                    '<button class="btn btn-default px-0" onclick="deleteContent('+item.id+',0,'+idCarrera+','+idMat+','+idG+')"><i class="fas fa-trash"></i></button> '+
                    '<i class="fa fa-angle-down drop" onclick="slideClss('+item.id+',this,'+idG+','+idCarrera+')" id="fbloqs-'+item.id+'"></i>'+
                    '</div></div>'+
                    '<div class="mt-3 sscl d-none cls'+item.id+'"></div>';
                });

                $('.bl'+idMat).removeClass('d-none');
                $('.bl'+idMat).html($selectBl);
                addEventListeners(idMat,'bloque'); 
            }
        }
    });
}
function ClassInfo(id,idG,idC){

    $selectClss = '';
    $selExm = '';
    $selClass = '<option value="" selected disabled>Selecciona una clase</option>';
    $act = ''
    $selAct = '<option value="" selected disabled>Selecciona una actividad</option>';

    $.each(listedContentBlogs,(i, item) => {

        if(item.id_bloq == id){
           
            if(item.tipo_de_blog != 2){

                $selectClss += '<div class="position-relative" data-index-op="1" data-index="'+item.orderB+'" data-index-oc="'+idC+'" data-index-b="'+item.id_blog+'" data-index-om="'+item.id_materia+'" data-index-ob="'+id+'" data-index-og="'+idG+'"><a class="first d-block draggable" id="e'+item.id_blog+'" draggable=true onclick="editClass('+item.id_materia+','+item.id_blog+',1,this)"><i class="fa fa-file-code"></i> '+item.title+
                '<i class="fas fa-grip-lines"></i></a>'+
                '<a class="position-absolute d-block a-color-black preview" onclick="previewClss('+idG+','+item.id_materia+','+item.orderB+','+item.id_bloq+',1)"><i class="fa fa-eye"></i></a>'+
                '<a class="position-absolute d-block a-color-black trash" onclick="deleteContent('+item.id_blog+',1,'+idC+','+item.id_materia+','+idG+','+id+')"><i class="fas fa-trash"></i></a> '+
                '</div>';
                $selClass += '<option value="'+item.id_materia+','+item.id_blog+'">'+item.title+'</option>';
            }else{
                $act += '<div class="position-relative" data-index-op="1" data-index="'+item.orderB+'" data-index-oc="'+idC+'" data-index-b="'+item.id_blog+'" data-index-om="'+item.id_materia+'" data-index-ob="'+id+'" data-index-og="'+idG+'"><a class="first d-block draggable" draggable=true id="e'+item.id_blog+'" onclick="editClass('+item.id_materia+','+item.id_blog+',2)"><i class="fa fa-file-code"></i> '+item.title+
                '<i class="fas fa-grip-lines"></i></a>'+
                '<a class="position-absolute d-block a-color-black preview" onclick="previewClss('+idG+','+item.id_materia+','+item.orderB+','+item.id_bloq+',2)" href="#"><i class="fa fa-eye"></i></a>'+
                '<a class="position-absolute d-block a-color-black trash" onclick="deleteContent('+item.id_blog+',1,'+idC+','+item.id_materia+','+idG+','+id+')"><i class="fas fa-trash"></i></a> '+
                '</div>';
                $selAct += '<option value="'+item.id_materia+','+item.id_blog+'">'+item.title+'</option>';
            }

        }
    
    });

    //idM = elements.id_materia;

    $.each(listedExm,(i,item)=>{

        if(item.id_bloq == id){
           
            if(item.tipo_examen != 1 && item.tipo_examen != 2){
                $selExm += '<div style="background-image: url('+item.foto+')'+
                ',url(../assets/images/default-1.png)" class="hidden imgE'+item.idExamen+'"></div>'+
                '<button class="btn btn-default first d-block text-left w-100 titleE'+item.idExamen+'"><p class="d-inline mb-0" onclick="editContent('+item.idExamen+',`Editar Examen`,3)">'+item.Nombre+'</p> <i class="fa fa-pencil-alt" onclick="editContent('+item.idExamen+',`Editar Examen`)"></i> <i class="fas fa-trash float-right m-2" onclick="deleteContent('+item.idExamen+',2,'+idC+','+item.idCurso+','+idG+','+id+')"></i></button>';
            }
        }

    });

    $('#class').html($selClass);
    $('#actB').html($selAct);

    $titExm = $selExm != '' ? '<h4>Espacio de Evaluación</h4>' : ($act != '' ? '<h4>Espacio de Evaluación</h4>' : '');
    $selectClss += $titExm+ $selExm +$act;

    return $selectClss;
}
function showgen(num,_this,idCar,idG){

        idGlobalsIndexC = num;
        if($('.gn'+num).hasClass('d-none')){
            $('.generation').addClass('d-none');
            mat(num,idCar,idG);
        }else{
            $('.gn'+num).addClass('d-none');
        }
}
function slideClssOpen(blog,gen,car){

    $inf = ClassInfo(blog,gen,car);
    $('.cls'+blog).html($inf);
    addEventListeners(blog);
}
function getExm(idCarrera){
    elem = listedCarrer.find(elm => elm.idCarrera == idCarrera);
    $selExm = '';
    //console.log(elem);
    $.each(elem.exm,(i,exm)=>{

        if(exm.tipo_examen != 3){
            $selExm += '<div style="background-image: url('+exm.foto+')'+
            ',url(../assets/images/default-1.png)" class="hidden imgE'+exm.idExamen+'"></div>'+
            '<button class="btn btn-default first d-block text-left w-100 titleE'+exm.idExamen+'" onclick="editContent('+exm.idExamen+',`Editar Examen`)">'+exm.Nombre+' <i class="fa fa-pencil-alt"></i></button>';
        }
    });
    return $selExm;
}
function slideBl(num,_this,idG,idC){

    $('.bloq').addClass('d-none');
    if($(_this).hasClass('fa-angle-down')){
        $('.drop').addClass('fa-angle-down');
        $('.drop').removeClass('fa-angle-up');
        if(num != null){
            $('.fold'+num).addClass('fa-folder-open');
            $('.fold'+num).removeClass('fa-folder');
            $(_this).addClass('fa-angle-up');
            $(_this).removeClass('fa-angle-down');
            BloqInfo(num,idG,idC);
        }else{
            $('.fold'+num).addClass('fa-folder');
            $('.fold'+num).removeClass('fa-folder-open');
            $('.toast-success').html('Sin contenido que mostrar');
            $('.toast-success').addClass('show');
            setTimeout(()=>{
                $('.toast-success').removeClass('show');
            },3000);
        }
    }else{
        $('.fold'+num).addClass('fa-folder');
        $('.fold'+num).removeClass('fa-folder-open');
        $(_this).addClass('fa-angle-down');
            $(_this).removeClass('fa-angle-up');
    }
}
function slideClss(num,_this,idG,idC){


    $('#return').attr('href','#car'+num);

    $('.sscl').addClass('d-none');
    $('.exms').addClass('d-none');
    if($(_this).hasClass('fa-angle-down')){
        
        $('.bloqs .drop').addClass('fa-angle-down');
        $('.bloqs .drop').removeClass('fa-angle-up');

        if(num != null){
            
            $('.foldbl'+num).addClass('fa-folder-open');
            $('.foldbl'+num).removeClass('fa-folder');
            $(_this).addClass('fa-angle-up');
            $(_this).removeClass('fa-angle-down');
            $('.cls'+num).removeClass('d-none');
            $inf = ClassInfo(num,idG,idC);  
            $('.cls'+num).html($inf);
        }else{
            $('.toast-success').html('Sin contenido que mostrar');
            $('.toast-success').addClass('show');
            setTimeout(()=>{
                $('.toast-success').removeClass('show');
            },3000);
        }
    }else{
        $('.foldbl'+num).addClass('fa-folder');
        $('.foldbl'+num).removeClass('fa-folder-open');
        $(_this).addClass('fa-angle-down');
            $(_this).removeClass('fa-angle-up');
    }
    addEventListeners(num);
}
function newClass(idMat,titleM,tp,idG,idC,idBl){

    if(tp == 3){
        $('#modalCrearEvaluacion').modal();
        $('#modalCrearEvaluacion form').append('<input name="idGen" type="hidden" value="'+idG+'">');
        $('#modalCrearEvaluacion form').append('<input name="idCar" type="hidden" value="'+idC+'">');
        $('#modalCrearEvaluacion form').append('<input name="idMat" type="hidden" value="'+idMat+'">');
        $('#modalCrearEvaluacion form').append('<input name="idBl" type="hidden" value="'+idBl+'">');
    }else{

        $('#previewBl').attr('onclick','woutClass()');
        window.scrollTo(0, 0);
        $('#cardcontent').addClass('d-none');
        $('#cardsummer').removeClass('d-none');
        $('#newClass').val(idMat);
        $('#newtitleClass').val(titleM);
        $('#class').addClass('hidden');
        $('#actB').addClass('hidden');
        $('#newMClass').removeClass('hidden');
        $('#saveBlogs').attr('disabled',false);
        $('#formBlogs').append('<input type="hidden" id="typeB" value="'+tp+'"/>');
        $('#formBlogs').append('<input type="hidden" id="bloqs" value="'+idG+'"/>');
    }
} 
function woutClass(){
    swal('Aún no haz agregado contenido');
}
function editClass(idMat,idClass,tp,_this,bloq){

    window.scrollTo(0, 0);

    order = $(_this).parent().attr('data-index');
    bloqs = $(_this).parent().attr('data-index-ob');
    se =  order != undefined ? order : _this;
    bl = bloqs != undefined ? bloqs : bloq;
    //console.log(se);

    $('#previewBl').attr('onclick','previewClss('+idGlobalsG+','+idMat+','+se+','+bl+','+tp+')');

    $('#cardcontent').addClass('d-none');
    $('#cardsummer').removeClass('d-none');
    $('#newMClass').addClass('hidden');
    if(tp == '1'){
    $('#actB').val();
    $('#actB').addClass('hidden');
    $('#class').val(idMat+','+idClass);
    $('#class').removeClass('hidden');
    $('#class').change();

    }else{
    $('#class').val();
    $('#class').addClass('hidden');
    
    $('#actB').val(idMat+','+idClass);
    $('#actB').removeClass('hidden');
    $('#actB').change();
    }
    $('#formBlogs').append('<input type="hidden" id="typeB" value="'+tp+'"/>');
    
}
function editContent(idContent,typeContent,tp,idCarr){

    if(tp == 3){
    
    	$answers = '';
        $totals = 0;
        $.ajax({
            type: 'POST',
            url:'../assets/data/Controller/educate/educateControl.php',
            data: {action: 'getQuiz',idExamen: idContent},
            success: function(data){
                

                json = JSON.parse(data);

                $totals = json.length;
                $.each(json, (i,ele)=>{


                    //console.log(ele);
                    $answers +='<div class="form-group">'+
                    '<strong>Pregunta '+(i+1)+'</strong>'+
                        '<input type="text" class="form-control" name="preguntaExamen'+i+'" required="required" value="'+ele.pregunta+'">';

                        op = JSON.parse(ele.opciones);

                        cont = 0;
                        incisos = "ABCD";
                        $.each(op, (key,elem)=>{
                            
                            if(elem == 1){
                                $check = 'checked';
 
                            }else{
                                $check = '';
                            }
                            $answers += '<input type="radio" class="OpcionExamen0" name="OpcionExamen'+i+'" value="'+incisos[cont]+'" title="Marcar ésta opción como la correcta" '+$check+'>'+
                            '<input type="text" name="TextoOpcionExamen'+i+'_'+incisos[cont]+'" style="border-color: transparent;" placeholder="Opción '+incisos[cont]+'..." required="required" value="'+key+'">';
                            cont++;
                        });

                        while(cont<4){
                            $answers += '<input type="radio" class="OpcionExamen0" name="OpcionExamen'+i+'" value="'+incisos[cont]+'" title="Marcar ésta opción como la correcta">'+
                            '<input type="text" name="TextoOpcionExamen'+i+'_'+incisos[cont]+'" style="border-color: transparent;" placeholder="Opción '+incisos[cont]+'...">';
                            cont++;
                        }
                        

                    $answers += '</div>'; 
                   
                })
                
                if($totals != 0 && $totals != ''){
                    $('#numeroPreguntaExamen').val($totals);
                    $('#divAgregarPregunta').html($answers);   
                }
            }
        });
        $('#modalAsignarPreguntas').modal();
        $('#modalAsignarPreguntas form').append('<input name="idExamen" type="hidden" value="'+idContent+'">');

    }else{

        if(idContent != null){

            $('#modalEditContent').modal();

            if(typeContent == 'Blq'){
                $('#CustomLabel').text('Agregar Bloque');
                $('#modalEditContent form').append('<input type="hidden" value="1" name="'+typeContent+'">');
                $('#modalEditContent form').append('<input type="hidden" value="'+idContent+'" name="idMateriaB">');
            }else{
                
                $('#CustomLabel').text(typeContent);
                $type = typeContent.split(' ')[1];
                $letter = $type.split('')[0];
                $title = $('.title'+$letter+idContent).text();
                $img = $('.img'+$letter+idContent+'').attr('style');
                $des = $('.des'+$letter+idContent).text();
                $('.imgpreviewModal').attr('style',$img);
                $('#titleform').val($title);
                $('#desc').val($des);
                if($type == 'Examen'){
                    parent = $('#desc').parent(0);
                    parent.addClass('hidden');
                }
        
                if(idCarr != undefined){
                    idContent = idCarr
                }
                $('#modalEditContent form').append('<input type="hidden" value="'+idContent+'" name="id'+$type+'">');
            
            }
            
        }else{
            $('.toast-success').html('La carrera no tiene plan de estudios.');
            $('.toast-success').addClass('show');
            setTimeout(()=>{
                $('.toast-success').removeClass('show');
            },3000);
        }
    }
}
function CreatedBlog(){

    $('#summernote').summernote({
     height: 150,
     tooltip: false
    })

    $('.closed-links').on('click',function(){

        parent = $(this).closest('.modal');
        parent.modal('hide');
    });
 
   $('#formBlogs').on('submit',function(e){

       e.preventDefault();
       htmlBlog = $('.note-editable').html();
       $selected = $('#class').val();
       $selectedAct = $('#actB').val();
       $hidden = $('#class').hasClass('hidden');
       hiddenAct = $('#actB').hasClass('hidden');
       $type = $('#typeB').val();
       $idB = $('#bloqs').val();
       lengthImgs = $('#upload-pdf').prop('files');
       $('#saveBlogs').attr('disabled',true);

       if(!$hidden){
        $idMat = $selected.split(',')[0];
        $idClass = $selected.split(',')[1];
       }else if(!hiddenAct){
        $idMat = $selectedAct.split(',')[0];
        $idClass = $selectedAct.split(',')[1];
       }else{
        $idClass = '';
        $idMat = $('#newClass').val();
       }
       $title = $('#title').val();
       $imgs = $('#imgSend').val();
       $oldfiles = $('#oldfiles').val();

       $form = new FormData();
       $form.append('content',htmlBlog);
       $form.append('action','saveBlogs');
       $form.append('idAssign',$idMat);
       $form.append('idClass',$idClass);
       $form.append('title',$title);
       $form.append('imgSend',$imgs);
       $form.append('typeB',$type);
       $form.append('bloqs',$idB);
       $form.append('oldfiles',$oldfiles);

       for(var i = 0; i < lengthImgs.length; i++){
            
            $form.append('archivo[]', lengthImgs[i]);
       }
 
       $.ajax({
           type: 'POST',
           url: '../assets/data/Controller/educate/educateControl.php',
           data: $form,
           processData: false,  // tell jQuery not to process the data
           contentType: false ,
           success: function(data){

            //console.log(data);
 
             json = JSON.parse(data);
 
             if(json.estatus == 'ok'){
                $('#upload-pdf').val('');

                $idBlogs = json.id;
                $idBloqs = json.idB.id_bloq;
                $ordB = json.idB.orderB;
                
                BloqInfo($idMat);
                $('.toast-success').html('Contenido guardado.');
                $('.toast-success').addClass('show');
                setTimeout(()=>{
                    $('.toast-success').removeClass('show');
                    slideClssOpen($idBloqs,idGlobalsG,idCarGlobals);
                    editClass($idMat,$idBlogs,$type,parseInt($ordB),$idBloqs);
                    $('#saveBlogs').attr('disabled',false);
                },3000);

                 
             }else{
                 if(json.data[2] != ""){
                     swal({
                         title: 'Es posible que no se haya guardado tu contenido contacta a soporte.',
                         icon: 'warning',
                         text: '',
                         button: true,
                     })  
                 }else{
                     swal({
                         title: 'Error de conexión intenta de nuevo.',
                         icon: 'info',
                         text: '',
                         button: true,
                     });
                 }
                 console.log(json.data);
             }
           }
       });
 
 
   });
 
}
function examenVisibility(idAlumno){
 
 //console.log(idAlumno);

    $.ajax({
		url: '../../assets/data/Controller/educate/educateControl.php',
		type: 'POST',
		data: {action: 'validar_examenExtra',
			idAlumno: idAlumno},
		success: function(data){

		//console.log(data);
			json = JSON.parse(data);
            html_sis = "";
            html_udc = "";
            html_isem = "";
			//console.log(json);
		if(json.estatus == 'ok'){
                
                if(json.data.length > 0){
                    //console.log(json.data);

                    $.each(json.data, (i,elem)=>{
                        img = '';
                        if(elem.imgCr != '' && elem.imgCr != null){
                            img = elem.imgCr;
                        }else if(elem.imgCr1 != '' && elem.imgCr1 != null){
                            img = elem.imgCr1;
                        }else if(elem.imgGen != '' && elem.imgGen != null){
                            img = elem.imgGen;
                        }

			 if(elem.presentaciones.length > 0){
                            $.each(elem.presentaciones,(i,test)=>{
                                cali = 'Calificación de examen: '+test.calificacion+'% de aprobación';
                                $colorClass = 'color-success';
                                $overlay = 'overlay active'
                                $function = '';
                                
                                
                            });
                           }else{
                            cali = '';
                            $colorClass = '';
                            $overlay  ='';

                            if(elem.ontime){
                                $function = 'aplicar_examen('+elem.idExamen+')';    
                            }else{
                                if(elem.before){
                                    $function = 'status_examen('+elem.before+')';
                                }else{
                                    $function = 'status_examen('+elem.before+')';;
                                }
                            }
                            }
                           if(elem.idInst == 13){
                               html_sis +='<div class="col-sm-6 col-md-6 col-xl-4">'+
                               '<div class="'+$overlay+'"></div>'+
                               '<div class="card mg-b-40">'+
                                   '<div class="card-body bg-primary">'+
                                   '<p class="card-text text-truncate text-white mb-0">'+elem.nameCr+'</p>'+
                                       '<p class="card-text text-truncate text-white mb-0">'+elem.nameCurs+'</p>'+
                                       '<small class="card-text text-truncate text-white text-sm">'+elem.Nombre+'</small>'+
                                   '</div>'+
                                   '<a href="#" onclick="'+$function+'"><img class="card-img-bottom img-fluid" src="./../assets/images/generales/flyers/'+img+'" alt="Image" onError="this.onerror=null;this.src=`../../assets/images/generales/flyers/default.png`;"></a>'+
                                   '<div class="'+$colorClass+'">'+cali+'</div>'+
                                   '</div></div>';
                           }else if(elem.idInst == 20){
                               html_udc +='<div class="col-sm-6 col-md-6 col-xl-4">'+
                                '<div class="'+$overlay+'"></div>'+
                               '<div class="card mg-b-40">'+
                                   '<div class="card-body bg-primary">'+
                                   '<p class="card-text text-truncate text-white mb-0">'+elem.nameCr+'</p>'+
                                       '<p class="card-text text-truncate text-white mb-0">'+elem.nameCurs+'</p>'+
                                       '<small class="card-text text-truncate text-white text-sm">'+elem.Nombre+'</small>'+
                                   '</div>'+
                                   '<a href="#" onclick="'+$function+'"><img class="card-img-bottom img-fluid" src="./../assets/images/generales/flyers/'+img+'" alt="Image" onError="this.onerror=null;this.src=`../../assets/images/generales/flyers/default_curso_udc.png`;"></a>'+
                                    '<div class="'+$colorClass+'">'+cali+'</div>'+
                                   '</div>'+
                                   '</div>';
                           }
                           else if(elem.idInst == 19){
                               html_isem +='<div class="col-sm-6 col-md-6 col-xl-4">'+
                                 '<div class="'+$overlay+'"></div>'+
                               '<div class="card mg-b-40">'+
                                   '<div class="card-body bg-primary">'+
                                   '<p class="card-text text-truncate text-white mb-0">'+elem.nameCr+'</p>'+
                                       '<p class="card-text text-truncate text-white mb-0">'+elem.nameCurs+'</p>'+
                                       '<small class="card-text text-truncate text-white text-sm">'+elem.Nombre+'</small>'+
                                   '</div>'+
                                   '<a href="#" onclick="'+$function+'"><img class="card-img-bottom img-fluid" src="./../assets/images/generales/flyers/'+img+'" alt="Image" onError="this.onerror=null;this.src=`../../assets/images/generales/flyers/default_curso_udc.png`;"></a>'+
                                   '<div class="'+$colorClass+'">'+cali+'</div>'+
                                   '</div></div>';
                           }
                        
                    });
                    if(window.location.pathname.includes('siscon')){
                    
                    if(html_sis != ''){
                    
                    $("#examn-container").append(html_sis);
                    }else{
                    	html_c = 'Sin exámenes que mostrar o realiza el pago correspondiente a tu examen extraordinario para aplicarlo';
                    	$("#examn-container").append(html_c);
            		}
                }else{
                if(html_udc != ''){
                    
                   $("#examn-container").append(html_udc);
                    }else{
                    	html_c = 'Sin exámenes que mostrar o realiza el pago correspondiente a tu examen extraordinario para aplicarlo';
                    	$("#examn-container").append(html_c);
            		}
                    
                }
                }else{

                    html_c = 'Sin exámenes que mostrar o realiza el pago correspondiente a tu examen extraordinario para aplicarlo';
                    $("#examn-container").append(html_c);
                }

                

				
			}

			
			
		}
	});	
}
function status_examen(before){

    //console.log(before);
    if(before){
        swal('aun no es tiempo de aplicar este examen.');
    }else{
        swal('el tiempo de aplicar este examen ya concluyo.');
    }

}
function agregarMasPreguntas(){
    var numPregExa = $("#numeroPreguntaExamen").val();
    var tituloPregExa = numPregExa;
    tituloPregExa++;
    $content = '<div class="form-group">'+
                '<strong>Pregunta '+tituloPregExa+'</strong>'+
                    '<input type="text" class="form-control" name="preguntaExamen'+numPregExa+'" required="required">'+
                    '<input type="radio" class="OpcionExamen0" name="OpcionExamen'+numPregExa+'" value="A" title="Marcar ésta opción como la correcta" checked>'+
                    '<input type="text" name="TextoOpcionExamen'+numPregExa+'_A" style="border-color: transparent;" placeholder="Opción A..." required="required">'+
                    '<input type="radio" class="OpcionExamen0" name="OpcionExamen'+numPregExa+'" value="B" title="Marcar ésta opción como la correcta">'+
                    '<input type="text" name="TextoOpcionExamen'+numPregExa+'_B"  style="border-color: transparent;" placeholder="Opción B...">'+
                    '<input type="radio" class="OpcionExamen0" name="OpcionExamen'+numPregExa+'" value="C" title="Marcar ésta opción como la correcta">'+
                    '<input type="text" name="TextoOpcionExamen'+numPregExa+'_C"  style="border-color: transparent;" placeholder="Opción C...">'+
                    '<input type="radio" class="OpcionExamen'+numPregExa+'" name="OpcionExamen'+numPregExa+'" value="D" title="Marcar ésta opción como la correcta">'+
                    '<input type="text" name="TextoOpcionExamen'+numPregExa+'_D" style="border-color: transparent;" placeholder="Opción D...">'+
                '</div>';
    $("#divAgregarPregunta").append($content)
                            
    $('#numeroPreguntaExamen').val(tituloPregExa);

}
function previewCar(idGn){ 
    if(idGn != '' && idGn != null){
        window.open('../educate/index.php?gn='+idGn+'&panel=moni','_blank');
    }else{
        $('.toast-success').html('La carrera no tiene una generación asiganada.');
            $('.toast-success').addClass('show');
            setTimeout(()=>{
                $('.toast-success').removeClass('show');
            },3000);
    }
}
function previewClss(idGn,idMat,i,bl,tp){ 
    
    if(i != null && i != undefined){
        window.open('../educate/blog.php?gn='+idGn+'&mt='+idMat+'&bl='+bl+'&se='+i+'&tp='+tp+'&panel=moni','_blank');
    }else{
        window.open('../educate/index.php?gn='+idGn+'&mt='+idMat+'&panel=moni','_blank');
    }
    
}
function deleteContent(id,type,idC,idM,idG,idB){


    if(type > 0){
        message = '¿El contenido se borrara estás de acuerdo?';
    }else{
        message = 'El bloque se borrara junto con el contenido de los blogs. ¿Estás de acuerdo?';
    }
    swal({
        title: 'Borrar contenido.',
        icon: 'info',
        text: message,
        buttons: {
            cancel: {
                text: "Cancel",
                value: false,
                visible: true,
                className: "",
                closeModal: true,
              },
               confirm: {
                text: "Sí borrar",
                value: true,
                visible: true,
                className: "",
                closeModal: true
              }
              },
    }).then((result)=>{
        if(result){
            $.ajax({
                type: 'POST',
                url: '../assets/data/Controller/educate/educateControl.php',
                data: {id: id,action: 'deleteContent',tablet: type,idBloq:idB},
                success: function(data){
                   
                    
                    json = JSON.parse(data);
                    console.log(json);
                    if(json.estatus == 'ok'){

                        if(type != 0){
                            console.log(id,idB,idM,idC,idG);
                            BloqInfo(idM,idG,idC);
                            slideClssOpen(idB,idG,idC);
                        }else{
                            $ciclo = BloqInfo(idM,idG,idC);
                            $('.bl'+idM).html($ciclo);
                            addEventListeners(idM,'bloque');
                            slideClssOpen(id,idG,idC);
                        }
                        $('.toast-success').html('Elemento(s) eliminados.');
                        $('.toast-success').addClass('show');
                        setTimeout(()=>{
                            $('.toast-success').removeClass('show');
                        },3000);
                    }

                }
            });
        }
    });

    

}
function addEventListeners(id,option) {
    
    if(option != 'bloque'){
        var draggables = document.querySelectorAll('.draggable');
        var dragListItems = document.querySelectorAll('.cls'+id+' div');
    }else{
        var draggables = document.querySelectorAll('.draggable');
        var dragListItems = document.querySelectorAll('.bl'+id+' .main-blo');    
    }

    draggables.forEach(draggable => {
      draggable.addEventListener('dragstart', dragStart);
    });
  
    dragListItems.forEach(item => {
      item.addEventListener('dragover', dragOver);
      item.addEventListener('drop', dragDrop);
      item.addEventListener('dragenter', dragEnter);
      item.addEventListener('dragleave', dragLeave);
    });
}
function swapItems(fromIndex, toIndex,fromId,toId) {

    //console.log(fromIndex, toIndex,fromId,toId);    
    console.log(idOrderBIndex,idOrderMIndex,idOrderGIndex,idOrderCIndex);

    if($optionM > 0){
        $options = 'class';
    }

    $form = new FormData();
    $form.append('fromId',fromId);
    $form.append('toId',toId);
    $form.append('fromIndex',fromIndex);
    $form.append('toIndex',toIndex);
    $form.append('action','updateOrder');
    $form.append('options',$options);
    $form.append('idOrderBIndex',idOrderBIndex);
    $form.append('idOrderB',idOrderB);
    $form.append('idOrderMIndex',idOrderMIndex);
    $form.append('idOrderM',idOrderM);
    $.ajax({
        type: 'POST',
        url: "../assets/data/Controller/educate/educateControl.php",
        data: $form,
        processData: false,
        contentType: false,
        success: function(data){

            console.log(data);
            json = JSON.parse(data);
            if(json.estatus == 'ok');

            if($options != 'bloque'){
                BloqInfo(idOrderM,idOrderG,idOrderC);
                slideClssOpen(idOrderB,idOrderG,idOrderC);
            }else{
                $ciclo = BloqInfo(idOrderM,idOrderG,idOrderC);
                $('.bl'+idOrderM).html($ciclo);
                //addEventListeners(idOrderM,'bloque');
                slideClssOpen(idOrderB,idOrderG,idOrderC);
            }
        }
    });
}
function dragStart() {
    // console.log('Event: ', 'dragstart');
    $optionM = +this.closest('div').getAttribute('data-index-op');

    if($optionM > 0){

        dragStartIndex = +this.closest('div').getAttribute('data-index');
        idBStart = +this.closest('div').getAttribute('data-index-b');
        idOrderGIndex = +this.closest('div').getAttribute('data-index-og');
        idOrderBIndex = +this.closest('div').getAttribute('data-index-ob');
        idOrderMIndex = +this.closest('div').getAttribute('data-index-om');
        idOrderCIndex = +this.closest('div').getAttribute('data-index-oc');

    }else{

        dragStartIndex = +this.closest('.main-blo').getAttribute('data-index');
        idBStart = +this.closest('.main-blo').getAttribute('data-index-b');
        idOrderGIndex = +this.closest('.main-blo').getAttribute('data-index-og');
        idOrderBIndex = +this.closest('.main-blo').getAttribute('data-index-ob');
        idOrderMIndex = +this.closest('.main-blo').getAttribute('data-index-om');
        idOrderCIndex = +this.closest('.main-blo').getAttribute('data-index-oc');

    }
}
function dragEnter() {
    // console.log('Event: ', 'dragenter');
    this.classList.add('over');
}
function dragLeave() {
    // console.log('Event: ', 'dragleave');
    this.classList.remove('over');
}
function dragOver(e) {
    // console.log('Event: ', 'dragover');
    e.preventDefault();
    e.stopPropagation();
    option = +this.closest('div').getAttribute('data-index-op');
    Bl = +this.closest('div').getAttribute('data-index-ob');
    Mt = +this.closest('div').getAttribute('data-index-om');
    Gn = +this.closest('div').getAttribute('data-index-og');
    Cr = +this.closest('div').getAttribute('data-index-oc');

    console.log(Bl,idOrderBIndex);

    if($optionM > 0 && Bl != idOrderBIndex){
        $('.sscl').addClass('d-none');
        $('.exms').addClass('d-none');
        $('.bloqs .drop').addClass('fa-angle-down');
        $('.bloqs .drop').removeClass('fa-angle-up');
        $('.bloqs .folders').removeClass('fa-folder-open');
        $('.bloqs .folders').addClass('fa-folder'); 

        $('.foldbl'+Bl).addClass('fa-folder-open');
        $('.foldbl'+Bl).removeClass('fa-folder');
        $('.drop', this).addClass('fa-angle-up');
        $('.drop', this).removeClass('fa-angle-down');
        $('.cls'+Bl).removeClass('d-none');
        $inf = ClassInfo(Bl,Gn,Cr);
        $('.cls'+Bl).html($inf);
        console.log(Bl,Mt);
        addEventListeners(Bl);
    }
}
function dragDrop(ev) {
// console.log('Event: ', 'drop');

    ev.stopPropagation();
    ev.preventDefault();

    const dragEndIndex = +this.getAttribute('data-index');
    const idb = +this.getAttribute('data-index-b');
    idOrderG = +this.getAttribute('data-index-og');
    idOrderB = +this.getAttribute('data-index-ob');
    idOrderM = +this.getAttribute('data-index-om');
    idOrderC = +this.getAttribute('data-index-oc');
    var option = +this.getAttribute('data-index-op');

    if(option > 0){
        $options = 'class';
    }else{
        $options = 'bloque';
    }
    swapItems(dragStartIndex, dragEndIndex,idBStart,idb);
    this.classList.remove('over');
}
