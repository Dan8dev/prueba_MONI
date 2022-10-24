<?php 

include 'partials/header.php' ?>


<div class="wrapper">
<?php include 'partials/navigation.php' ?>  

    <div class="container container-fluid">
        <div class="row">
            <div class="col-sm-8 ps-0">
            <div class="nav-left">
            <h4 class="title-class" id="titleSession"></h4>
              <!-- <p class="mb-0 color-white text-center">DOCENTE <span id="teacher"></span></p> -->
            </div>
            <div class="card blogs">
            <div class="card-body">
              <div id="headerBlogs"></div>
              <div id="contentBlogs"></div>
              <div id="OutcontentBlogs" class="color-vine text-center fw-bold"></div>                
            </div>
            </div>
            </div>
            <div class="col-sm-4 pe-0">
                <div class="mx-auto mb-3 prevSession">
                  <!-- <h5 class="color-vine text-uppercase fw-bold">Ver clases</h5> -->
                  <a class="content-video">
                    <!-- <div class="icon-play">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#fff" class="bi bi-play-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                      <path d="M6.271 5.055a.5.5 0 0 1 .52.038l3.5 2.5a.5.5 0 0 1 0 .814l-3.5 2.5A.5.5 0 0 1 6 10.5v-5a.5.5 0 0 1 .271-.445z"/>
                    </svg>
                    </div> -->
                    <img id="previewSession" class="w-100" src="" onError="this.onerror=null;this.src='./design/imgs/img-no-disponible.jpg';">
                  </a>
                </div>
                <div class="mb-2">
                <a class="btn prev"><img class="rotation-left" src="./design/icons/left-arrow-gy.png"> Tema previo </a>
                <a class="btn float-end next"> Siguiente tema <img class="rotation-right" src="./design/icons/left-arrow-gy.png" alt=""> </a>
                </div>
                <div class="mx-auto mb-2">
                  <h4 class="color-vine text-center">Material descargable</h4>
                <ul id="showFiles"></ul>
                </div>
                <?php //include 'partials/right-lat.php'; ?>
            </div>
        </div>
        <div class="border-top mt-5 p-5">

          <div class="container-comments">
            <div class="mb-5">
              <form id="commitsForm">
              <h6 class="text-uppercase color-gray-opacity">Comentarios</h6>
                <input type="hidden" name="idClass" id="idClass">
              <textarea name="commitsForm" placeholder="Ingresa un comentario de la clase" id="commitsClass" required></textarea>
              <button class="btn btn-danger btn-danger-vine d-block ms-auto">Enviar</button>
              </form>
            </div>
            <div class="listCommits container"></div>
          </div>
           
        </div>
    </div>
</div>

<div class="modal fade" id="tareasModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Entregar tarea</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
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
                    <textarea class="form-control" name="inp_comentario_tarea" id="inp_comentario_tarea" rows="4" maxlength="255"></textarea>
                  </div>
                    <span style="float: right;" id="size_comment"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
      const bl = <?= $_GET['bl']?>;
      const tp = <?= $_GET['tp']?>;
        $(document).ready(()=>{
 
            cargar_clases(mat,gen,ses,0,bl,tp);
            saveCommits();
        });
        
</script>