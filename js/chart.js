const $grafica = document.querySelector("#grafica");
// Las etiquetas son las que van en el eje X. 

//   console.log({years})
const etiquetas = ["Enero", "Febrero", "Marzo", "Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"]
// Podemos tener varios conjuntos de datos. Comencemos con uno
const datosVentas2020 = {
    label: "Ventas por mes",
    data: [5000, 1500, 8000, 5102,6000], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(54, 162, 235)', // Color de fondo
    borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
    borderWidth: 1,// Ancho del borde
};
const ventasPorMes =new Chart($grafica, {
    type: 'bar',// Tipo de gráfica
    data: {
        labels: etiquetas,
        datasets: [
            datosVentas2020,
            // Aquí más datos...
        ]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
        },
    }
});

const $grafica2 = document.querySelector("#grafica2");

// Podemos tener varios conjuntos de datos. Comencemos con uno
const datosVentas20202 = {
    label: "Compras por mes",
    data: [5000, 1500, 8000, 5102], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    backgroundColor: 'rgba(229,112,126)', // Color de fondo
    borderColor: 'rgba(229,112,126,0.2)', // Color del borde
    borderWidth: 1,// Ancho del borde
};
const comprasPorMes =new Chart($grafica2, {
    type: 'bar',// Tipo de gráfica
    data: {
        labels: etiquetas,
        datasets: [
            datosVentas20202,
            // Aquí más datos...
        ]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
        },
    }
});


// Obtener una referencia al elemento canvas del DOM
const $grafica3 = document.querySelector("#grafica3");
// Las etiquetas son las porciones de la gráfica
const etiquetas3 = ["Ventas", "Donaciones", "Trabajos", "Publicidad"]
// Podemos tener varios conjuntos de datos. Comencemos con uno
const datosIngresos3 = {
    data: [1500, 400, 2000, 7000], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    // Ahora debería haber tantos background colors como datos, es decir, para este ejemplo, 4
    backgroundColor: color => {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgba(" + r + "," + g + "," + b + ", 0.5)";
    },
    borderColor: [
        'rgba(163,221,203,1)',
        'rgba(232,233,161,1)',
        'rgba(230,181,102,1)',
        'rgba(229,112,126,1)',
    ],// Color del borde
    borderWidth: 1,// Ancho del borde
};
const ventasProducto =new Chart($grafica3, {
    type: 'pie',// Tipo de gráfica. Puede ser dougnhut o pie
    data: {
        labels: etiquetas3,
        datasets: [
            datosIngresos3,
            // Aquí más datos...
        ]
    },
});

var randomColorGenerator = function () { 
    return '#' + (Math.random().toString(16) + '0000000').slice(2, 8); 
};
// Obtener una referencia al elemento canvas del DOM
const $grafica4 = document.querySelector("#grafica4");
// Las etiquetas son las porciones de la gráfica
const etiquetas4 = ["Ventas", "Donaciones", "Trabajos", "Publicidad"]
// Podemos tener varios conjuntos de datos. Comencemos con uno
const datosIngresos4 = {
    data: [1500, 400, 2000, 7000], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
    // Ahora debería haber tantos background colors como datos, es decir, para este ejemplo, 4
    backgroundColor: color => {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgba(" + r + "," + g + "," + b + ", 0.5)";
    },
    // backgroundColor: [
    //     randomColorGenerator(),
    //     'rgba(232,233,161)',
    //     'rgba(230,181,102)',
    //     'rgba(229,112,126)',
    //     randomColorGenerator(),
    //     randomColorGenerator(),
    // ],// Color de fondo
    borderColor: [
        randomColorGenerator(),
        'rgba(232,233,161,1)',
        'rgba(230,181,102,1)',
        'rgba(229,112,126,1)',
        randomColorGenerator(),
        randomColorGenerator(),
    ],// Color del borde
    borderWidth: 1,// Ancho del borde
};
const comprasProducto =new Chart($grafica4, {
    type: 'pie',// Tipo de gráfica. Puede ser dougnhut o pie
    data: {
        labels: etiquetas4,
        datasets: [
            datosIngresos4,
            // Aquí más datos...
        ]
    },
});


const cargarGraficos=(identificador)=>{
    // let enviar['anio']= $("#anio option:selected").text();
    let txtFechaDesde=document.getElementById("txtFechaDesde").value
        let txtFechaHasta=document.getElementById("txtFechaHasta").value
        let enviar=$("#anio option:selected").text();
        let desde=$("#mes option:selected").text();
        $.ajax({
		url: 'ajax/datosGraficos.php?anio='+enviar+'&desde='+txtFechaDesde+'&hasta='+txtFechaHasta,
		type: 'get',
		data: '',
		success: function(data){
            var json = JSON.parse(data);
             
               console.log(ventasProducto.data)
                if(identificador===1 ||identificador===3){
                    ventasPorMes.data.datasets[0].data = json.ventas;
               ventasPorMes.update();
               comprasPorMes.data.datasets[0].data=json.compras;
               comprasPorMes.update();
                }
               


if(identificador===2 ||identificador===3){
    ventasProducto.data.datasets[0].data=json.productos;
               ventasProducto.data.labels=json.nProductos;
       
               ventasProducto.update();
               comprasProducto.data.datasets[0].data=json.cantidadCompras;
               comprasProducto.data.labels=json.nombreBodegas;
               comprasProducto.update();
}
              
           }
       });
}







