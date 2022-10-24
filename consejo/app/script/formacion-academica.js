
$("input[name='file']").on("change", function(){
   $( "#form-enviar-foto-reconociemiento" ).submit();
    var formData = new FormData($("#form-enviar-foto-reconociemiento")[0]);
    var ruta = "data/CData/formacionControl.php";
    $.ajax({
        url: ruta,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
          $("#imagenreconocimiento").attr("src","img/reconocimientos/"+datos+"");
        }
    });
});