// function listar_diarios(page=1){
    
//       let div2 = document.getElementById('filtros_estados_resultados');
//   div2.style.display ='none';

//     var str = $("#form1").serialize();
//     $.ajax({
//             url: 'ajax/listarDiarios.php?page='+page,
//             type: 'get',
//             data: str,
//             success: function(data){
//                 let div1 = document.getElementById('div_listar_libro_diario');
//                 let div2 = document.getElementById('div_libros');
//                 if(div1){
//                      $("#div_listar_libro_diario").html(data);
//                 }
//                 if(div2){
//                     $("#div_libros").html(data);
//                 }
    
//             }
//     });
   
// }

function listar_diarios(page=1){
 //PAGINA: productos.php
 //alert("ssss");
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listarDiarios.php?page='+page,
            type: 'get',
            data: str,
            success: function(data){
                 let div1 = document.getElementById('div_listar_libro_diario');
                let div2 = document.getElementById('div_libros');
                if(div1){
                     $("#div_listar_libro_diario").html(data);
                }
                if(div2){
                    $("#div_libros").html(data);
                }
                    // $("#div_listar_libro_diario").html(data);
                   
                    
            }
    });
}


function listados_contables(){
    let div1 = document.getElementById('div_listar_libro_diario');
    let div2 = document.getElementById('div_libros');
    
    if(div1){
        listar_diarios(1);
        // $("#div_listar_libro_diario").html(data);
    }
    if(div2){
        // $("#div_libros").html(data);
        let activeButton = document.querySelector('button.active');
        let activeButtonId = activeButton.id;	

    	if(activeButtonId=='nav-home-tab'){
    		listar_diarios();
    	}else if(activeButtonId=='nav-profile-tab'){
    		listar_mayor();
    	}else if(activeButtonId=='nav-contact-tab'){
    		listar_estados_resultados();
    	}else if(activeButtonId=='nav-situacion-tab'){
    		listar_estados_situacion();
    	}else if(activeButtonId=='nav-comprobacion-tab'){
    		listar_balance_comprobacion();
    	}else if(activeButtonId=='nav-contact-tab2'){
    		listar_estados_resultados2();
    	}
    }            

}



function listar_mayor(page=1){
    //   let div2 = document.getElementById('filtros_estados_resultados');
//   div2.style.display ='none';
 //PAGINA: productos.php
 //alert("ssss");
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listarMayor.php?page='+page,
            type: 'get',
            data: str,
            success: function(data){
                let div1 = document.getElementById('div_listar_libro_diario');
                let div2 = document.getElementById('div_libros');
                if(div1){
                     $("#div_listar_libro_diario").html(data);
                }
                if(div2){
                    $("#div_libros").html(data);
                }
    
            }
    });
}

function listar_estados_resultados(page=1){
    //   let div2 = document.getElementById('filtros_estados_resultados');
//   div2.style.display ='none';
 //PAGINA: productos.php
 //alert("ssss");
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listar_estados_resultados.php?page='+page,
            type: 'get',
            data: str,
            success: function(data){
                let div1 = document.getElementById('div_listar_libro_diario');
                let div2 = document.getElementById('div_libros');
                if(div1){
                     $("#div_listar_libro_diario").html(data);
                }
                if(div2){
                    $("#div_libros").html(data);
                }
    
            }
    });
}

function listar_estados_situacion(page=1){
    //  let div2 = document.getElementById('filtros_estados_resultados');
//   div2.style.display ='none';
 //PAGINA: productos.php
 //alert("ssss");
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listar_estados_situacion.php?page='+page,
            type: 'get',
            data: str,
            success: function(data){
                let div1 = document.getElementById('div_listar_libro_diario');
                let div2 = document.getElementById('div_libros');
                if(div1){
                     $("#div_listar_libro_diario").html(data);
                }
                if(div2){
                    $("#div_libros").html(data);
                }
    
            }
    });
}

function listar_balance_comprobacion(page=1){
    // let div2 = document.getElementById('filtros_estados_resultados');
//   div2.style.display ='none';
 //PAGINA: productos.php
 //alert("ssss");
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listar_balance_comprobacion.php?page='+page,
            type: 'get',
            data: str,
            success: function(data){
                let div1 = document.getElementById('div_listar_libro_diario');
                let div2 = document.getElementById('div_libros');
                if(div1){
                     $("#div_listar_libro_diario").html(data);
                }
                if(div2){
                    $("#div_libros").html(data);
                }
    
            }
    });
}

function listar_estados_resultados2(page=1){
//   let div2 = document.getElementById('filtros_estados_resultados');
//   div2.style.display ='block';
   
    // let txtProducto = document.getElementById('txtProducto').value;
    // let areas = document.getElementById('areas').value;
   
   
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listar_estados_resultados2.php?page='+page,
            type: 'get',
            data: str,
            // data: str+'&txtProducto='+txtProducto+'&areas='+areas,
            success: function(data){
                let div1 = document.getElementById('div_listar_libro_diario');
                let div2 = document.getElementById('div_libros');
                if(div1){
                     $("#div_listar_libro_diario").html(data);
                }
                if(div2){
                    $("#div_libros").html(data);
                }
    
            }
    });
}
