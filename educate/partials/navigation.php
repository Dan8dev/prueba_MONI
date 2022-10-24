<?php
session_start();

if (!isset($_SESSION["alumno"]) && !isset($_SESSION["usuario"])) {

    $_SESSION['alumno'] = $_GET['alumno'];
    if($_GET['alumno'] != ''){
        ?> 
        <script>
        localStorage.setItem('alumno','<?= $_GET['alumno']?>')
        </script>
    <?php
    //header('Location: /'.$get.'/app');
    die();
    }else{
        ?>
        <script>
            panel = localStorage.getItem('panel');
            if(panel == 'siscon'){
                //url = 'https://conacon.org/moni';
                url = '';
            }else{
                url = '';
            }
            window.location.href = url+'/'+panel+'/app';
        </script>
    <?php
    }
} 
if(isset($_GET['panel'])){
    $get = $_SESSION['idpanel'] = $_GET['panel'];
}else{
    $get = $_SESSION['idpanel'];
}
if(isset($_GET['alumno'])){
    $alm = $_GET['alumno'];
}else{
    $alm = '';
}
$url = $_SERVER['REQUEST_URI'];


$url = substr($url,9);

$link = explode("/",$url);

$link = $link[0];
//$link = $link[array_keys($link)];

//$link = $link[3];
?>
<script>
        localStorage.setItem('panel','<?=$get?>');
        if('<?= $alm; ?>' != ''){
            $us = '<?= $alm; ?>';
            localStorage.setItem('alumno',$us);
        }else{
            $us = null;
        }
        
        
    </script>
<div class="header-cover">
    <div class="index-nav">
        <div class="nav-log">
            <img src="../educate/design/imgs/capa11.png">
        </div>
    </div>
    <ul class="nav-list">
            <?php
                if(!isset($_SESSION["usuario"])){

                
                if(strpos($link,"mycourses.php") === 0){
                    ?>
                <li><a href="/<?=$get;?>/app/">Panel del alumno</a></li>
                <li class="active"><a href="mycourses.php?panel=<?=$get?>">Oferta educativa</a></li>
            <?php
                }else if(strpos($link,"index.php") === 0){
            ?>
                <li><a href="/<?=$get;?>/app/">Panel de alumno</a></li>
                <li><a href="mycourses.php?panel=<?=$get?>">Oferta educativa</a></li>
                <li class="active"><a href="index.php?gn=<?=$_GET['gn']?>">Materia</a></li>
            <?php
                }else{
                    ?>
                     <li><a href="/<?=$get;?>/app/">Panel de alumno</a></li>
                    <li><a href="mycourses.php?panel=<?=$get?>">Oferta educativa</a></li>
                    <li><a href="index.php?gn=<?=$_GET['gn']?>">Materia</a></li>
                    <li class="active"><a href="<?=$url?>">Bloque</a></li>
                    <?php
                } 
            }else{
                ?>
                    <!-- <li><a href="javascript:window.history.back();">Panel administrativo</a></li>    -->
                <?php
            }
            ?>
    </ul>
</div>