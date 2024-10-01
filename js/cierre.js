function objetoAjax(){
    var xmlhttp=false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function CierreCaja(){
    //alert("entro");
	$("#div_oculto").load("ajax/Cierre.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
/* 
				background: '',
				top: '6px',
				left: '250px',
				position: 'absolute',
				width: '800px'
		 */		
				top: '5%',
				position: 'absolute',
				width: '700px',
                left: ($(window).width() - $('.caja').outerWidth())/2
			}
		});
	});
}

/* function repCierre(){
//alert('cierre');
    $("#div_oculto").load("ajax/reporteCierre.php", function(){
	$.blockUI({
		message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
		css:{
			'-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
				background: '', /* #f9f9f9*/
//				top: '5%',
//				position: 'absolute',
//				width: '350px',
//                left: ($(window).width() - $('.caja').outerWidth())/2
//			}
//		});
       // listar_vendedores();
//	});
//} */