<?php 

include 'partials/header.php' ?>
    <div class="wrapper">
    <?php 
    
    
    include 'partials/navigation.php' ?>  
        <div class="container">
            <div class="row" id="courses">         
            </div>
        </div>
 <?php include 'partials/footer.php' ?>
 <script>
     $(document).ready(()=>{
        cargar_cursos_pagos();
        });
 </script>