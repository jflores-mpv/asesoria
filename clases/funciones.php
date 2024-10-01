<?php

function covertirFecha($fecha){
    $cadena=split("-",$fecha);// elimina el /
    $dia1 = $cadena[2];// guarda en variable
    $dia=floatval($dia1);// elima el cero
    $mes1 = $cadena[1];// guarda en variable
    $mes=floatval($mes1);// elima el cero
    $ano = $cadena[0];// guarda en variable
    $mesletra = "";
    switch($mes)
    {
        case 1:$mesletra="Enero";break;
        case 2:$mesletra="Febrero";break;
        case 3:$mesletra="Marzo";break;
        case 4:$mesletra="Abril";break;
        case 5:$mesletra="Mayo";break;
        case 6:$mesletra="Junio";break;
        case 7:$mesletra="Julio";break;
        case 8:$mesletra="Agosto";break;
        case 9:$mesletra="Septiembre";break;
        case 10:$mesletra="Octubre";break;
        case 11:$mesletra="Noviembre";break;
        case 12:$mesletra="Diciembre";break;
    }
    $fechanueva = $dia." de ".$mesletra." del ".$ano;
    return $fechanueva;
}

/*
// FUCNION CALCULA EL TIEMPO TRASCURIDO
function calculo_tiempo ($fechaActual, $fechaInicio){

        $matrisFechaActual = split("-",$fechaActual);
        $anoFechaActual =$matrisFechaActual[0];
        $mesFechaActual=$matrisFechaActual[1];
        $diaFechaActual=$matrisFechaActual[2];
        $matrisFechaInicio = split("-",$fechaInicio);
        $anoFechaInicio =$matrisFechaInicio[0];
        $mesFechaInicio=$matrisFechaInicio[1];
        $diaFechaInicio=$matrisFechaInicio[2];

        $ano=0;
        $mes=0;
        $dia=0;

        $diasComparacion=0;
        switch($mesFechaInicio){
            case 1:$diasComparacion=31;break;
            case 2:$diasComparacion=28;break;
            case 3:$diasComparacion=31;break;
            case 4:$diasComparacion=30;break;
            case 5:$diasComparacion=31;break;
            case 6:$diasComparacion=30;break;
            case 7:$diasComparacion=31;break;
            case 8:$diasComparacion=31;break;
            case 9:$diasComparacion=30;break;
            case 10:$diasComparacion=31;break;
            case 11:$diasComparacion=30;break;
            case 12:$diasComparacion=31;break;
        }
        if($anoFechaInicio== $anoFechaActual){
            if($mesFechaInicio == $mesFechaActual){
                if($diaFechaInicio == $diaFechaActual){
                    echo $ano." Ano. " .$mes." Mes. ".$dia." Dias. ";
                }else{
                    while($diaFechaInicio<$diaFechaActual){
                        $dia++;
                        $diaFechaInicio++;
                    }
                    echo $ano." Ano. " .$mes." Mes. ".$dia." Dias. ";
                }
            }else{
                ////**********************

                while($mesFechaInicio<$mesFechaActual){
                    $diasComparacion=0;
                    switch($mesFechaInicio){
                        case 1:$diasComparacion=31;break;
                        case 2:$diasComparacion=28;break;
                        case 3:$diasComparacion=31;break;
                        case 4:$diasComparacion=30;break;
                        case 5:$diasComparacion=31;break;
                        case 6:$diasComparacion=30;break;
                        case 7:$diasComparacion=31;break;
                        case 8:$diasComparacion=31;break;
                        case 9:$diasComparacion=30;break;
                        case 10:$diasComparacion=31;break;
                        case 11:$diasComparacion=30;break;
                        case 12:$diasComparacion=31;break;
                    }
                    for($i=1;$i<=$diasComparacion;$i++){
                        $dia++;
                    }
                    $mesFechaInicio= $mesFechaInicio+1;
                    $mes++;
                }

                if($mesFechaInicio == $mesFechaActual){
                    $diaFechaInicio = 1;
                    if($diaFechaInicio == $diaFechaActual){
                        echo $ano." Ano. " .$mes." Mes. ".$dia." Dias. ";
                    }
                    else{
                        $diaFechaInicio = 1;
                        while($diaFechaInicio<$diaFechaActual){
                            $dia++;
                            $diaFechaInicio++;
                        }
                        echo $ano." Ano. " .$mes." Mes. ".$dia." Dias. ";
                    }
                }
                //////*********************
//                while($diaFechaInicio<=$diasComparacion){
//                    $dia++;
//                    $diaFechaInicio++;
//                }
//                $mes++;

            }
            $mesFechaInicio = $mesFechaInicio+1; // este es el problema
        }else{

            if($dia ==$diasComparacion){
                $mes++;
                $diaFechaInicio = 1;
            }
            //************************
            while($anoFechaInicio<$anoFechaActual){
            while($mesFechaInicio<=12){
                $diasComparacion=0;
                switch($mesFechaInicio){
                    case 1:$diasComparacion=31;break;
                    case 2:$diasComparacion=28;break;
                    case 3:$diasComparacion=31;break;
                    case 4:$diasComparacion=30;break;
                    case 5:$diasComparacion=31;break;
                    case 6:$diasComparacion=30;break;
                    case 7:$diasComparacion=31;break;
                    case 8:$diasComparacion=31;break;
                    case 9:$diasComparacion=30;break;
                    case 10:$diasComparacion=31;break;
                    case 11:$diasComparacion=30;break;
                    case 12:$diasComparacion=31;break;
                }
                for($i=1;$i<=$diasComparacion;$i++){
                    $dia++;
                }
                $mesFechaInicio = $mesFechaInicio+1;
                $mes++;
            }
            if($mesFechaInicio>=13){
                $ano++;
                $anoFechaInicio++;
                $mesFechaInicio=1;
            }
        }
        //**********************
        if($anoFechaInicio==$anoFechaActual){
            while($mesFechaInicio<$mesFechaActual){
                $diasComparacion=0;
                switch($mesFechaInicio){
                    case 1:$diasComparacion=31;break;
                    case 2:$diasComparacion=28;break;
                    case 3:$diasComparacion=31;break;
                    case 4:$diasComparacion=30;break;
                    case 5:$diasComparacion=31;break;
                    case 6:$diasComparacion=30;break;
                    case 7:$diasComparacion=31;break;
                    case 8:$diasComparacion=31;break;
                    case 9:$diasComparacion=30;break;
                    case 10:$diasComparacion=31;break;
                    case 11:$diasComparacion=30;break;
                    case 12:$diasComparacion=31;break;
                }
                for($i=1;$i<=$diasComparacion;$i++){
                    $dia++;
                }
                $mesFechaInicio= $mesFechaInicio+1;
                $mes++;
            }

            if($mesFechaInicio == $mesFechaActual){
                $diaFechaInicio = 1;
                if($diaFechaInicio == $diaFechaActual){
                    echo $ano." Ano. " .$mes." Mes. ".$dia." Dias. ";
                }else{
                    $diaFechaInicio = 1;
                    while($diaFechaInicio<$diaFechaActual){
                        $dia++;
                        $diaFechaInicio++;
                    }
                    echo $ano." Ano. " .$mes." Mes. ".$dia." Dias. ";
                }
            }

        }

        }

    }
    $respuesta = calculo_tiempo("2013-10-28", "2013-01-01");
    echo $respuesta;



    // FUNCION 2 CALCULA EL TIEMPO TRASCURRIDO
     function dateDiff($start, $end) {

        $start_ts = strtotime($start);

        $end_ts = strtotime($end);

        $diff = $end_ts - $start_ts;

        return round($diff / (60*60*60*12));// AÑO: 365*60*60*24 / MES: 60 * 60 * 60 *12 / DIAS: 86400 / HORAS: 3600

        }
        $tiempo_trabajado2 = dateDiff("2013-07-29", "2013-11-10");
        echo $tiempo_trabajado2;


    */
?>