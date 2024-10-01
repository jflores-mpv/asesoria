function asientos(){
   // alert("entro");
	$("#div_oculto").load("ajax/asientos.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '',
				top: '25%',
				left: '25%',
				position: 'absolute',
				width: '50%'
			}
		});
	});
}

/* function libroMayor(){
    //alert("entro");
	$("#div_oculto").load("ajax/libroMayor.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '',
				top: '6px',
				left: '250px',
				position: 'absolute',
				width: '800px'
			}
		});
	});
} */