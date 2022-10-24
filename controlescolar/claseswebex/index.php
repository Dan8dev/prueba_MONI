<?php
    session_start();
    $Nombre = "";
    if(isset($_SESSION['usuario']['persona']['nombres'])){
        $Nombre = $_SESSION['usuario']['persona']['nombres'];
        
    }else{
        if(isset($_SESSION['usuario']['persona']['nombre'])){
            $Nombre = $_SESSION['usuario']['persona']['nombre'];
        }
    }
    if($Nombre != ""){
        $Nombre = explode(" ",$Nombre);
        $Nombre = $Nombre[0];
    }
    if($_SESSION['usuario']['estatus_acceso'] == 4){
        $Nombre = $_SESSION['usuario']['idAcceso'];

    }

    $rol = strtoupper($_SESSION['usuario']['directorio']);
    
    //$Nombre = $_SESSION['usuario']['idAcceso'];
    if(isset($_SESSION["alumno"]) || isset($_GET['android_id_afiliado']) || isset($_SESSION['usuario']))
    {
        //--LO MANDO A REGISTRAR A CISCO WEBEX--//
        ?>
        <form id="IDForm" method="POST">
            <input type="hidden" name="AT" value="RM" />
            <input type="hidden" name="MK" value="<?php echo $_GET['id_sesion']; ?>" /> <!--Sesión WebEx-->
            <input type="hidden" name="FN" value="<?php echo "{$rol}"; ?>" /> <!--Apellidos-->
            <input type="hidden" name="LN" value="<?php echo "{$Nombre}"; ?>" /> <!--Nombres-->
            <input type="hidden" name="EM" value="<?php echo $_SESSION['usuario']['correo']; ?>" /> <!--Correo-->
            <input type="hidden" name="JT" value="Asistentes" />
            <input type="hidden" name="CY" value="ControlEscolar" /> <!--Carrera-->
            <input type="hidden" name="BU" value="https://sandbox.conacon.org/controlescolar/claseswebex/respuestawebex.php?sesion=<?php echo $_GET['id_sesion']; ?>" /> -->
        </form>
        <script>
            document.getElementById("IDForm").action = "https://universidaddelconde.webex.com/universidaddelconde/m.php";
            document.getElementById("IDForm").submit();
        </script>
        <?PHP
    }
    else {

        echo 'sin session';
    }
?>
