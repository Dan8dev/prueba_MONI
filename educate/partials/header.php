<?php
$url = $_SERVER['REQUEST_URI'];

$link = explode("/",$url);
$link = $link[array_keys($link)];

if($link == 'aplicar_examen.php'){
    $applyTest = '<link rel="stylesheet" href="design/css/bracket.css">
                 <link rel="stylesheet" href="design/css/alertas.css">';
}else{
    $applyTest = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./design/css/styles.css">
    <?= $applyTest;?>
    <link rel="icon" type="imge/png" href="design/imgs/capa11.png">
    <title>EducaT</title>
</head>
<body>

