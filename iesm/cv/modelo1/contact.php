<?php
mail(
  $_POST['paraF'],
  $_POST['nombreF'].' [CONTACTO CONACON]',
  $_POST['msgF']." - ".$_POST['nombreF'] .' '.$_POST['emailF']
);
header( "Location: index.php?perfil=".$_GET['perfil'] );

//mkdir( $_POST['paraF'].$_POST['nombreF'].' [CONTACTO CONACON]'.$_POST['msgF']." - ".$_POST['nombreF'] .' '.$_POST['emailF'],0777 );
?>