$(document).ready(function () {
    var linkimage = $("#foto-Perfil").prop("src");
    cargarImagen(linkimage);
 });
 
 function cargarImagen(link){
   var canvas = document.getElementById("canv");
   var ctx = canvas.getContext("2d");

   var img = new Image();
   img.src = link;
   var ancho = $("#LargoFoto").val();
   var largo = $("#AnchoFoto").val();

   $("#canv").prop("width",largo*4);
   $("#canv").prop("height",ancho*4);

   img.onload = function(){
     ctx.drawImage(img, 0,0,(largo*4),(ancho*4));
     console.log(largo+" + "+ancho)
   }
 }

var qrcode = new QRCode(document.getElementById("qrcode"), {
    width : 110,
    height : 110
  });

  function makeCode () {    
    var elText = document.getElementById("text");
    if (!elText.value) {
      alert("Input a text");
      elText.focus();
      return;
    }
    qrcode.makeCode(elText.value);
  }
  makeCode();
  $("#text").
  on("blur", function () {
    makeCode();
  }).

  on("keydown", function (e) {
    if (e.keyCode == 13) {
      makeCode();
    }
  });