<?php 
session_start();
if(isset($_SESSION['idpanel'])){
  $get = $_SESSION['idpanel'];
}

if (!isset($_SESSION["alumno"])) {
  header('Location: /'.$get.'/app');
  die();
}
include 'partials/header.php' ?>

<div class="wrapper" id="session">
    <div class="nav-left blog-nav d-flex align-items-center">
        <a href="blog.php?panel=<?=$get?>&gn=<?=$_GET['gn']?>&mt=<?=$_GET['mt']?>&se=<?=$_GET['se']?>" class="pt-2 ps-3 d-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="30" fill="#fff" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
            </svg>
        </a>
        <img src="../educate/design/imgs/capa11.png">
        <div class="content-title">
        <h6 class="mb-0" id="title"></h6>
        <p class="mb-0 small-size">Impartido: <span id="teacher"></span></p>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <h2 class="title-session">¿Qué es la Consejería y Procesos Administrativos?</h2>
                <p>Session <span id="numberSession"></span></p>
                <div id="videoSession" class="m-5">
                    <p class="text-center color-vine"></p>
                   <video src="" width="100%" controls controlsList="nodownload"></video>
                </div>
                <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean suscipit viverra eleifend. Maecenas imperdiet elementum sapien vitae vulputate. Phasellus ligula tortor, rhoncus non convallis nec, convallis id velit. Duis posuere lacus ac feugiat pharetra. Suspendisse pulvinar mattis nibh sit amet ullamcorper.</p>-->
                <button class="btn prev"><img class="rotation-left" src="./design/icons/left-arrow-gy.png"> Tema previo </button>
                <button class="btn float-end next"> Siguiente tema <img class="rotation-right" src="./design/icons/left-arrow-gy.png" alt=""> </button>
                <div class="brand-resource percentage mt-5 mb-5">
                    <p class="mb-1"><span></span></p>
                    <p>avance del curso 35%</p>
                </div>
                <div class="brand-resource brand-online">
                    <p class="bg-transparent">Clases online</p>
                    <ul class="list-online" id="listOnline">
                    </ul>
                </div>
            </div>
            <div class="col-sm-4 pe-0">
               <?php include 'partials/right-lat.php'?>
            </div>
        </div>

        <div class="border-top mt-5 p-5">
           <div class="mb-5">
            <h6 class="text-uppercase color-gray-opacity">Comentarios</h6>
            <form id="commitsForm">
              <input type="hidden" name="idClass" id="idClass">
            <textarea name="commitsForm" placeholder="Ingresa un comentario de la clase" id="commitsClass" required></textarea>
            <button class="btn btn-danger btn-danger-vine d-block ms-auto">Enviar</button>
            </form>
           </div>

            <div class="listCommits container">
                
            </div>
        </div>
    </div>
   
</div>
<div class="modal fade" id="tareasModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Entregar tarea</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true"></button>
          </div>

          <form id="form_entrega_tarea">
            <div class="modal-body">
              <h5 id="titulo_tarea" class="mb-4"></h5>
              <div class="row">
                <div class="col-sm-12">
                  <label>Adjunte el archivo correspondiente a la entrega de su tarea.</label>
                  <input type="hidden" name="tarea_entrega" id="tarea_entrega">
                  <input type="hidden" name="clase_tarea" id="clase_tarea">
                  <div class="bootstrap-filestyle input-group input-group-sm mb-3">
                    <input type="file" class="filestyle" data-buttonname="btn-secondary" name="inp_adjunto_tarea" id="inp_adjunto_tarea" required="" accept=".doc,.docx,application/msword,.pptx,.pdf,.jpg,.jpeg,.png">
                  </div>
                  <label>Comentario (opcional)</label>
                  <div class="input-group mb-3">
                    <textarea class="form-control" name="inp_comentario_tarea" id="inp_comentario_tarea" rows="4" maxlength="255" required></textarea>
                  </div>
                    <span style="float: right;" id="size_comment"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
          </form>

        </div>
      </div>
</div>
<?php include 'partials/footer.php' ?>

<script>    
    const mat = <?= $_GET['mt']?>;
    const gen = <?= $_GET['gn']?>;
    const ses = <?= $_GET['se']?>;
    $(document).ready(()=>{
        cargar_clases(mat,gen,ses);
        cargar_materias(gen,mat);
        saveCommits();
    });
</script>