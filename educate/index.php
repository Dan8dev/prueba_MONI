<?php 

include 'partials/header.php'?>
    <div class="wrapper">
        <?php include 'partials/navigation.php' ?>  
        <div class="container container-fluid px-0">
            <div class="content-nav-index">
                <div class="brand-of-teacher mb-5">
                    <p class="mb-0 small-size">próxima clase</p>
                    <p class="mb-0 color-vine small-size-middle" id="nextClass"></p>
                    <p class="small-min" id="dateClass"></p>
                </div>
            </div>
            <div class="container my-0 mx-auto">
                <h1 class="color-vine text-center mb-5" id="titleC"></h1>
                <h1 class="color-vine text-center mb-5" id="titleM"></h1>
                <div class="brand-resource brand-online d-none">
                    <p class="bg-transparent">Clases en línea</p>
                    <div id="links_clase"><p></p></div>
                </div>
                
                <div class="row">
                  <div class="col-sm-8 px-5" id="temary"></div>
                  <div class="col-sm-4" id="gTemary">
                    <div id="mat-avance" class="hidden">
                      <div class="content-img ct-main">
                      </div>
                      <div class="brand-resource percentage">
                      <p class="mb-1 mt-2"><span></span></p>
                      <p>avance de la materia <span id="txt-per"></span>%</p></div>
                      </div>
                    <div class="mb-4 hidden" id="examOficial">
                    <button type="button" class="btn-vine btn btn-primary d-block mx-auto btn-exam" onclick="modalExam()">Visualizar exámenes</button>
                    </div>
                    <div class="hidden" id="docentes"></div>  
                  </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalExam" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalVideoLabel">Exámenes de la materia</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="brand-resource brand-testing">
            <div id="examO"></div>
            <div id="examEx"></div>
            </div>

        </div>
      </div>
</div>
 <?php include 'partials/footer.php' ?>
 <script>
      const gen = <?= $_GET['gn']?>;
     $(document).ready(()=>{
 
        cargar_materias(gen);
        //consultar_sesiones(gen);
      
      });
 </script>