/*function listar_XML() {

$.post('sql/archivoXML.php', serializedData, function(response) {
    // Log the response to the console
    console.log("Response: "+response);
});

} // lookup
*/

function listar_XML(){
	    var formData = new FormData(document.getElementById("form"));
	    formData.append("dato", "valor");
	     console.log("formData",formData);
	  $.ajax({
               url: 'sql/archivoXML.php',
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	     		processData: false
            })
                .done(function(res){
                    console.log("res",res);
						alertify.success('Actualizado Datos de Empresa ');
						
                });
}
