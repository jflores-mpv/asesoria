<?php
    session_start();
    require_once('../conexion.php');

	$accion=$_POST['txtAccion'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    
	$doc=new DOMDocument('1.0','UTF-8');
	$doc->formatOutput=true;
	//$sesion_id_empresa=58;
	$IdEmpresa=$sesion_id_empresa;
	
	function registroAnexo ($sesion_id_empresa,$mes,$anio,$url ){
    // echo $sesion_id_empresa."==".$mes."==".  $anio."==".  $url."</br>";
    date_default_timezone_set('America/Guayaquil');
    $fecha = date('Y-m-d H:i:s');
    $sql="INSERT INTO `anexosCreados`(`id_empresa`, `mes`, `anio`, `url`,`fecha`) 
                    VALUES ('$sesion_id_empresa','$mes','$anio','$url','$fecha')";
    $result = mysql_query($sql);
    $resultado = ($result)?'1':'2';
    return $resultado;
}

	function ceros3($valor){
	for($i=1;$i<=3-strlen($valor);$i++)
		$s.="0";
	return $s.$valor;
}
	
if ($accion==1)
{
	$tipoDeclaracion=$_POST['tipoDeclaracion']; 
	$anio=$_POST['anio'];
	$mes= $_POST['periodo'];	
	
	$regimen_Microempresa="NO";
	$raiz=$doc->createElement("iva");
	$raiz=$doc->appendChild($raiz);
	$TipoIDInformante="R";
	
	$Tipo_IdInformante=$doc->createElement("TipoIDInformante");
	$Tipo_IdInformante=$raiz->appendChild($Tipo_IdInformante);	
	$text_TipoIdInformante=$doc->createTextNode($TipoIDInformante);
	$text_TipoIdInformante=$Tipo_IdInformante->appendChild($text_TipoIdInformante);
	
	$sql="select ruc,razonSocial from empresa where id_empresa='".$sesion_id_empresa."'";
	$resultado=mysql_query($sql);
	$ruc="";
	$razon_Social="";
	while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
	{
		$ruc=$row['ruc'];
		$razonSocial=$row['razonSocial'];
	}
	
	$IdInformante=$doc->createElement("IdInformante");
	$IdInformante=$raiz->appendChild($IdInformante);	
	$textIdInformante=$doc->createTextNode($ruc);
	$textIdInformante=$IdInformante->appendChild($textIdInformante);

	$razon_Social=$doc->createElement("razonSocial");
	$razon_Social=$raiz->appendChild($razon_Social);
	$text_RazonSocial=$doc->createTextNode($razonSocial);
	$text_RazonSocial=$razon_Social->appendChild($text_RazonSocial);
	
	$anioInformado=$doc->createElement("Anio");
	$anioInformado=$raiz->appendChild($anioInformado);
	$textAnio=$doc->createTextNode($anio);
	$textAnio=$anioInformado->appendChild($textAnio);
	
	$mesInformado=$doc->createElement("Mes");
	$mesInformado=$raiz->appendChild($mesInformado);
	$textMes=$doc->createTextNode($mes);
	$textMes=$mesInformado->appendChild($textMes);
	
	/* $regimenMicroempresa=$doc->createElement("RegimenMicroempresa");
	$regimenMicroempresa=$raiz->appendChild($regimenMicroempresa);
	$textRegimenMicroempresa=$doc->createTextNode($regimen_Microempresa);
	$textRegimenMicroempresa=$regimenMicroempresa->appendChild($textRegimenMicroempresa);
 */
	$sql="select COUNT(codigo) as establecimimientosNum, codigo as codigo from establecimientos where id_empresa=".$sesion_id_empresa;
	$resultado=mysql_query($sql);
	$numEstRuc='';
	$codEstab='';
	while($row=mysql_fetch_array($resultado)) //permite ir de fila en fila de la tabla
	{
		$numEstabRuc=ceros3($row['establecimimientosNum']);	
		$codEstab=$row['codigo'];
	}
	
	$num_EstRuc=$doc->createElement("numEstabRuc");
	$num_EstRuc=$raiz->appendChild($num_EstRuc);
	$text_numEstRuc=$doc->createTextNode($numEstabRuc);
	$text_numEstRuc=$num_EstRuc->appendChild($text_numEstRuc);

    // 	$sql="select if(Autorizacion is null, 'F','E') tipoEm,sum(sub_total) total_vtas from ventas where id_empresa='".$sesion_id_empresa."'
    // 	and year(fecha_venta)='".$anio."' and month(fecha_venta)='".$mes."'   
    // 	and tipo_documento=1 group by tipoEm";
    // // echo $sql;
    // 	$resultado=mysql_query($sql);
    	$totalVentas=0;
    	$ventasEstab=0;
    // 	while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
    // 	{
    // 		if ($row['tipoEm']=='F')
    // 		{
    // 			$totalVentas=$row['total_vtas'];	
    // 			$ventasEstab=$totalVentas;
    // 		}
    // 	}
    
    // 	$sql="select if(Autorizacion is null, 'F','E') tipoEm,sum(sub_total) total_vtas from ventas where id_empresa='".$sesion_id_empresa."'
    //  	and year(fecha_venta)='".$anio."' and month(fecha_venta)='".$mes."'   
    // 	and tipo_documento=1 group by tipoEm";
    // // echo $sql;
    // 	$resultado=mysql_query($sql);
    // 	$totalVentas=0;
    // 	$ventasEstab=0;
    // 	while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
    // 	{
            
    // 			$totalVentas=$row['total_vtas'];	
    // 			$ventasEstab=$totalVentas;
    
    // 	}

	$totalVentas=number_format($totalVentas,2,".","");		
	$total_Ventas=$doc->createElement("totalVentas");
	$total_Ventas=$raiz->appendChild($total_Ventas);
	$text_totalVentas=$doc->createTextNode($totalVentas);
	$text_totalVentas=$total_Ventas->appendChild($text_totalVentas);

	$codigoOperativo=$doc->createElement("codigoOperativo");
	$codigoOperativo=$raiz->appendChild($codigoOperativo);
	$textcodigoOperativo=$doc->createTextNode("IVA");
	$textcodigoOperativo=$codigoOperativo->appendChild($textcodigoOperativo);
		
	$raiz_compras=$doc->createElement("compras");
	$raiz_compras=$raiz->appendChild($raiz_compras);

	$sql="select a.id_compra,@empresa,@mes,@anio,0,'COMP',a.TipoComprobante,a.codSustento as codSustento ,
		case length(prov.ruc) when 13 then '01' when 10 then '02' else '03' end tpIdProv,
		prov.ruc ruc,prov.parteRel parteRel,TipoComprobante, 	
		prov.id_tipo_proveedor as tipoProv,
		prov.nombre_comercial denopr , a.fecha_compra fechaEmision, a.numSerie,a.txtEmision,a.txtNum,
		sub_Total,subTotal0,subTotal12,	exentoIVA,noObjetoIVA,a.autorizacion,prov.tipo_pago,total_iva as totalivaats
	from
		compras a inner join proveedores prov
		on a.id_empresa=prov.id_empresa and a.id_proveedor=prov.id_proveedor
		where a.id_empresa = '". $sesion_id_empresa ."' and 
		year(a.fecha_compra)=".$anio." and month(a.fecha_compra)=".$mes."  and ats!='1' and anulado='0' ";

	$resultado=mysql_query($sql);
	$codSustento1=0;

	while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
	{
		$id_compra=$row['id_compra'];
		$codSustento1=$row['codSustento'];
		$codSustento2=strval($codSustento1);
		if (strlen($codSustento2)<2)
		{
			$codSustento="0".$codSustento2;
		}else{
            $codSustento = $codSustento2;
        }
        
		$tpIdProv=$row['tpIdProv'];
		$idProv=$row['ruc'];
		$tipoComprobante1=$row['TipoComprobante'];		
		$tipoComprobante2=strval($tipoComprobante1);
		if (strlen($tipoComprobante2)<2)
		{
			$tipoComprobante="0".$tipoComprobante2;
		}
		
		$parteRel=$row['parteRel'];
		$tipoProv=$row['tipoProv'];
		$denopr=$row['denopr'];
		$pagoLocExt1=$row['tipo_pago'];	
		
		$fechaRegistro1=$row['fechaEmision'];
		$fechaRegistro2=date('d/m/Y',strtotime($fechaRegistro1));
		$fechaRegistro=$fechaRegistro2;

		$establecimiento=$row['numSerie'];
		$puntoEmision=$row['txtEmision'];
		$secuencial=$row['txtNum'];
		$fechaEmision=$fechaRegistro;
		$autorizacion=$row['autorizacion'];
		
		$totalivaats=$row['totalivaats'];
		

		$sqlImpuestos = "SELECT SUM(dc.valor_total) AS suma_iva, i.iva, i.codigo 
                FROM detalle_compras dc 
                INNER JOIN impuestos i ON dc.iva = i.id_iva 
                WHERE dc.id_compra = $id_compra
                GROUP BY i.codigo, i.iva";

            $resultadoImpuestos = mysql_query($sqlImpuestos);
            
            // Inicializar totales
            $totales = array(
                'baseNoGraIva' => 0,
                'baseImponible0' => 0,
                'baseImponibleGrav' => 0,
                'baseImponibleExe' => 0
            );
            
            // Recorrer los resultados de la consulta
            while($rowImpuestos = mysql_fetch_array($resultadoImpuestos)){
                // echo "Suma de iva para el cè´—digo " . $rowImpuestos["codigo"] . " con iva " . $rowImpuestos["iva"] . ": " . $rowImpuestos["suma_iva"] . "<br>";
                
                // Actualizar los totales segè´‚n corresponda
                switch ($rowImpuestos["codigo"]) {
                    case 6:
                        $totales['baseNoGraIva'] += $rowImpuestos["suma_iva"];
                        break;
                    case 0:
                        $totales['baseImponible0'] += $rowImpuestos["suma_iva"];
                        break;
                    case 8:
                    case 3:
                    case 2:
                    case 4:
                    case 5:
                        $totales['baseImponibleGrav'] += $rowImpuestos["suma_iva"];
                        break;
                    case 7:
                        $totales['baseImponibleExe'] += $rowImpuestos["suma_iva"];
                        break;
                    default:
                        // Cè´—digo de impuesto no reconocido
                        break;
                }
            }

        //Base Imponible No objeto de IVA CODIGOS: 6
		$baseNoGraIva= number_format($totales['baseNoGraIva'], 2) ;
		
		//Base Imponible tarifa 0% IVA CODIGOS: 0
		$baseImponible1=$row['subTotal0'];
        $baseImponible = number_format($totales['baseImponible0'], 2, '.', '');		
		//Base Imponible tarifa IVA diferente de 0%   CODIGOS: 8 - 3 - 2 - 4 
// 		$baseImpGrav2=$row['subTotal12'];
		      $baseImpGrav2 = number_format($totales['subTotal12'], 2, '.', '');	
        // $baseImpGrav = number_format($totales['baseImponibleGrav'], 2);
		 $baseImpGrav = number_format($totales['baseImponibleGrav'], 2, '.', '');
		// Base imponible exentade IVA CODIGOS: 7
		$baseImpExe=number_format($totales['baseImponibleExe'], 2);
		
		$montoIce=0.00;      
		// De donde se puede tomar este dato	
				
		$raiz_detalleCompras=$doc->createElement("detalleCompras");
		$raiz_detalleCompras=$raiz_compras->appendChild($raiz_detalleCompras);

		$cod_Sustento=$doc->createElement("codSustento");
		$cod_Sustento=$raiz_detalleCompras->appendChild($cod_Sustento);
		$text_codSustento=$doc->createTextNode($codSustento);
		$text_codSustento=$cod_Sustento->appendChild($text_codSustento);
	
		$tp_IdProv=$doc->createElement("tpIdProv");
		$tp_IdProv=$raiz_detalleCompras->appendChild($tp_IdProv);
		$text_tpIdProv=$doc->createTextNode($tpIdProv);
		$text_tpIdProv=$tp_IdProv->appendChild($text_tpIdProv);

		$tp_IdProv=$doc->createElement("idProv");
		$tp_IdProv=$raiz_detalleCompras->appendChild($tp_IdProv);
		$text_tpIdProv=$doc->createTextNode($idProv);
		$text_tpIdProv=$tp_IdProv->appendChild($text_tpIdProv);
		
		$tipo_Comprobante=$doc->createElement("tipoComprobante");
		$tipo_Comprobante=$raiz_detalleCompras->appendChild($tipo_Comprobante);
		$text_tipoComprobante=$doc->createTextNode($tipoComprobante);
		$text_tipoComprobante=$tipo_Comprobante->appendChild($text_tipoComprobante);

		$parte_Rel=$doc->createElement("parteRel");
		$parte_Rel=$raiz_detalleCompras->appendChild($parte_Rel);
		$text_parteRel=$doc->createTextNode($parteRel);
		$text_parteRel=$parte_Rel->appendChild($text_parteRel);

		if ($tipoProv=="03"){
			$tipo_Prov=$doc->createElement("tipoProv");
			$tipo_Prov=$raiz_detalleCompras->appendChild($tipo_Prov);
			$text_tipoProv=$doc->createTextNode($tipoProv);
			$text_tipoProv=$tipo_Prov->appendChild($text_tipoProv);
	
			$deno_pr=$doc->createElement("denopr");
			$deno_pr=$raiz_detalleCompras->appendChild($deno_pr);
			$text_denopr=$doc->createTextNode($denopr);
			$text_denopr=$deno_pr->appendChild($text_denopr);
		}

		$fecha_Registro=$doc->createElement("fechaRegistro");
		$fecha_Registro=$raiz_detalleCompras->appendChild($fecha_Registro);
		$text_fechaRegistro=$doc->createTextNode($fechaRegistro);
		$text_fechaRegistro=$fecha_Registro->appendChild($text_fechaRegistro);
		
		$serie=$doc->createElement("establecimiento");
		$serie=$raiz_detalleCompras->appendChild($serie);
		$text_serie=$doc->createTextNode($establecimiento);
		$text_serie=$serie->appendChild($text_serie);

		$punto_Emision=$doc->createElement("puntoEmision");
		$punto_Emision=$raiz_detalleCompras->appendChild($punto_Emision);
		$text_puntoEmision=$doc->createTextNode($puntoEmision);
		$text_puntoEmision=$punto_Emision->appendChild($text_puntoEmision);

		$numero_cpra=$doc->createElement("secuencial");
		$numero_cpra=$raiz_detalleCompras->appendChild($numero_cpra);
		$text_secuencial=$doc->createTextNode($secuencial);
		$text_secuencial=$numero_cpra->appendChild($text_secuencial);
		
		$fecha_Emision=$doc->createElement("fechaEmision");
		$fecha_Emision=$raiz_detalleCompras->appendChild($fecha_Emision);
		$text_fechaEmision=$doc->createTextNode($fechaEmision);
		$text_fechaEmision=$fecha_Emision->appendChild($text_fechaEmision);
		
		$autoriz_cpra=$doc->createElement("autorizacion");
		$autoriz_cpra=$raiz_detalleCompras->appendChild($autoriz_cpra);
		$text_autorizacion=$doc->createTextNode(trim($autorizacion));
		$text_autorizacion=$autoriz_cpra->appendChild($text_autorizacion);
		
		$base_NoGraIva=$doc->createElement("baseNoGraIva");
		$base_NoGraIva=$raiz_detalleCompras->appendChild($base_NoGraIva);
		$text_baseNoGraIva=$doc->createTextNode($baseNoGraIva);
		$text_baseNoGraIva=$base_NoGraIva->appendChild($text_baseNoGraIva);
		
		$base_Imponible=$doc->createElement("baseImponible");
		$base_Imponible=$raiz_detalleCompras->appendChild($base_Imponible);
		$text_baseImponible=$doc->createTextNode($baseImponible);
		$text_baseImponible=$base_Imponible->appendChild($text_baseImponible);
		
		$base_ImpGrav=$doc->createElement("baseImpGrav");
		$base_ImpGrav=$raiz_detalleCompras->appendChild($base_ImpGrav);
		$text_baseImpGrav=$doc->createTextNode($baseImpGrav);
		$text_baseImpGrav=$base_ImpGrav->appendChild($text_baseImpGrav);
		
		$base_ImpExe=$doc->createElement("baseImpExe");
		$base_ImpExe=$raiz_detalleCompras->appendChild($base_ImpExe);
		$text_baseImpExe=$doc->createTextNode($baseImpExe);
		$text_baseImpExe=$base_ImpExe->appendChild($text_baseImpExe);
	
	    $monto_Ice=$doc->createElement("montoIce");
		$monto_Ice=$raiz_detalleCompras->appendChild($monto_Ice);
		$text_montoice=$doc->createTextNode($montoIce);
		$text_montoice=$monto_Ice->appendChild($text_montoice);
	
	
	
	
	
	    $montoIva=round($totalivaats,2);
	    
		$montoIva=number_format($montoIva,2,".","");
		$monto_iva=$doc->createElement("montoIva");
		$monto_iva=$raiz_detalleCompras->appendChild($monto_iva);
		$text_montoiva=$doc->createTextNode($montoIva);
		$text_montoiva=$monto_iva->appendChild($text_montoiva);
    
    
    	$sql_retenciones="select *
		
		
		from
			compras compras 
			
			INNER JOIN `mcretencion` mcretencion on mcretencion.Factura_Id = compras.id_compra 
			
			
			INNER JOIN `dcretencion` dcretencion on dcretencion.Retencion_Id=mcretencion.Id 
			
			where compras.id_empresa ='". $sesion_id_empresa ."' 
			
			and compras.id_compra='".$id_compra."' and TipoImp='2' and mcretencion.anulado='0'";
    

		$retenciones=mysql_query($sql_retenciones);
		$numero_filas = mysql_num_rows($retenciones);
		$valor_retenido=0;
		$valRetBien10=0;
		$valRetServ20=0;
		$valorRetBienes=0;
		$valRetServ50=0;		
		$valorRetServicios=0;
		$valRetServ100=0;
//		echo "filas=".$numero_filas;
		if ($numero_filas>0)
		{
			while($row_reten=mysql_fetch_array($retenciones))//permite ir de fila en fila de la tabla
			{
	//			echo "entro";
				$porcentaje=$row_reten['Porcentaje'];
				// $valor_retenido=$row_reten['valor'];
				
				$valor_retenido=number_format(($row_reten['BaseImp']*$row_reten['Porcentaje'])/100, 2, '.', '');
				    // echo "XXXX==>".$porcentaje."</br>";
				switch ($porcentaje)
				{
					case 10.00:
					   // echo "PORCENTAJE==".$porcentaje."</br>";
						$valRetBien10=$valor_retenido;
						break;
					case 20.00:
					   //  echo "PORCENTAJE==".$porcentaje."</br>";
						$valRetServ20=$valor_retenido;
						break;
					case 30.00:
					   //  echo "PORCENTAJE==".$porcentaje."</br>";
						$valorRetBienes=$valor_retenido;
						break;
					case 50.00:
					   //  echo "PORCENTAJE==".$porcentaje."</br>";
						$valRetServ50=$valor_retenido;
						break;
					case 70.00:
					   //  echo "PORCENTAJE==".$porcentaje."</br>";
						$valorRetServicios=$valor_retenido;
						break;
					case 100.00:
					   //  echo "PORCENTAJE==".$porcentaje."</br>";
						$valRetServ100=$valor_retenido;
						break;
				}		
			}
		}
		$val_RetBien10=$doc->createElement("valRetBien10");
		$val_RetBien10=$raiz_detalleCompras->appendChild($val_RetBien10);
		$text_valRetBien10=$doc->createTextNode($valRetBien10);
		$text_valRetBien10=$val_RetBien10->appendChild($text_valRetBien10);

		$val_RetServ20=$doc->createElement("valRetServ20");
		$val_RetServ20=$raiz_detalleCompras->appendChild($val_RetServ20);
		$text_valRetServ20=$doc->createTextNode($valRetServ20);
		$text_valRetServ20=$val_RetServ20->appendChild($text_valRetServ20);
		
		$valor_RetBienes=$doc->createElement("valorRetBienes");
		$valor_RetBienes=$raiz_detalleCompras->appendChild($valor_RetBienes);
		$text_valorRetBienes=$doc->createTextNode($valorRetBienes);
		$text_valorRetBienes=$valor_RetBienes->appendChild($text_valorRetBienes);
		
		$val_RetServ50=$doc->createElement("valRetServ50");
		$val_RetServ50=$raiz_detalleCompras->appendChild($val_RetServ50);
		$text_valRetServ50=$doc->createTextNode($valRetServ50);
		$text_valRetServ50=$val_RetServ50->appendChild($text_valRetServ50);

		$valor_RetServicios=$doc->createElement("valorRetServicios");
		$valor_RetServicios=$raiz_detalleCompras->appendChild($valor_RetServicios);
		$text_valorRetServicios=$doc->createTextNode($valorRetServicios);
		$text_valorRetServicios=$valor_RetServicios->appendChild($text_valorRetServicios);

		$val_RetServ100=$doc->createElement("valRetServ100");
		$val_RetServ100=$raiz_detalleCompras->appendChild($val_RetServ100);
		$text_valRetServ100=$doc->createTextNode($valRetServ100);
		$text_valRetServ100=$val_RetServ100->appendChild($text_valRetServ100);
				
		$totbasesImpReemb=0;
		$tot_basesImpReemb=$doc->createElement("totbasesImpReemb");
		$tot_basesImpReemb=$raiz_detalleCompras->appendChild($tot_basesImpReemb);
		$text_totbasesImpReemb=$doc->createTextNode($totbasesImpReemb);
		$text_totbasesImpReemb=$tot_basesImpReemb->appendChild($text_totbasesImpReemb);
				
		$pagoLocExt2=strval($pagoLocExt1);
		if (strlen($pagoLocExt2)<2)
		{
			$pagoLocExt="0".$pagoLocExt2;
		}

		$raiz_pagoExterior=$doc->createElement("pagoExterior");
		$raiz_pagoExterior=$raiz_detalleCompras->appendChild($raiz_pagoExterior);
		
		$pago_LocExt=$doc->createElement("pagoLocExt");
		$pago_LocExt=$raiz_pagoExterior->appendChild($pago_LocExt);
		$text_pagoLocExt=$doc->createTextNode($pagoLocExt);
		$text_pagoLocExt=$pago_LocExt->appendChild($text_pagoLocExt);

		if ($pagoLocExt=="01")
		{
			$paisEfecPago="NA";
			$aplicConvDobTrib="NA";
			$pagExtSujRetNorLeg="NA";
			$pagoRegFis="NA";		
			
			$pais_EfecPago=$doc->createElement("paisEfecPago");		
			$pais_EfecPago=$raiz_pagoExterior->appendChild($pais_EfecPago);
			$text_paisEfecPago=$doc->createTextNode($paisEfecPago);
			$text_paisEfecPago=$pais_EfecPago->appendChild($text_paisEfecPago);
	
			$aplic_ConvDobTrib=$doc->createElement("aplicConvDobTrib");		
			$aplic_ConvDobTrib=$raiz_pagoExterior->appendChild($aplic_ConvDobTrib);
			$text_aplicConvDobTrib=$doc->createTextNode($aplicConvDobTrib);
			$text_aplicConvDobTrib=$aplic_ConvDobTrib->appendChild($text_aplicConvDobTrib);

			$pagExt_SujRetNorLeg=$doc->createElement("pagExtSujRetNorLeg");		
			$pagExt_SujRetNorLeg=$raiz_pagoExterior->appendChild($pagExt_SujRetNorLeg);
			$text_pagExtSujRetNorLeg=$doc->createTextNode($pagExtSujRetNorLeg);
			$text_pagExtSujRetNorLeg=$pagExt_SujRetNorLeg->appendChild($text_pagExtSujRetNorLeg);
	
			$pag_RegFis=$doc->createElement("pagoRegFis");		
			$pag_RegFis=$raiz_pagoExterior->appendChild($pag_RegFis);
			$text_pagRegFis=$doc->createTextNode($pagoRegFis);
			$text_pagRegFis=$pag_RegFis->appendChild($text_pagRegFis);	
		}
		
		if ($baseImponible+$baseImpGrav+$baseImpExe+$montoIva>=1000)
		{	
			$formaPago="20";              // Pregunta 
			$raiz_formaPago=$doc->createElement("formasDePago");
			$raiz_formaPago=$raiz_detalleCompras->appendChild($raiz_formaPago);
	
			$pago_formaPago=$doc->createElement("formaPago");
			$pago_formaPago=$raiz_formaPago->appendChild($pago_formaPago);
			$text_formaPago=$doc->createTextNode($formaPago);
			$text_formaPago=$pago_formaPago->appendChild($text_formaPago);	
		}	
		
		$sql_reten_fte="select *
		
		
		from
			compras compras 
			
			INNER JOIN `mcretencion` mcretencion on mcretencion.Factura_Id = compras.id_compra 
			
			
			INNER JOIN `dcretencion` dcretencion on dcretencion.Retencion_Id=mcretencion.Id 
			
			where compras.id_empresa ='". $sesion_id_empresa ."' 
			
			and compras.id_compra='".$id_compra."' and TipoImp='1' and mcretencion.anulado='0' ";
		
// 		$sql_reten_fte="select cob.tipo,enl_cpra.nombre,
// 		cob.porcentaje,cob.valor,codigo_sri 
// 		from
// 			compras compras 
			
// 			INNER JOIN `cobrospagos` cob on cob.id_empresa=compras.id_empresa and cob.id_factura=compras.id_compra
// 			INNER JOIN `enlaces_compras` enl_cpra 
// 			on enl_cpra.id_empresa=cob.id_empresa and enl_cpra.id=cob.id_forma
// 			where compras.id_empresa ='". $sesion_id_empresa ."' 
// 			and compras.id_compra='".$id_compra."' 
// 			and cob.documento=1 
// 			and (cob.tipo = 'retencion-fuente-servicios' OR cob.tipo = 'retencion-fuente-proveeduria' OR cob.tipo = 'retencion-fuente-inventario') ";
	
// 		echo "<br/>".$sql_reten_fte;
		
		
		$retencion_fte=mysql_query($sql_reten_fte);
		$numero_filas = mysql_num_rows($retencion_fte);
		if ($numero_filas>0)
		{
			$raiz_air=$doc->createElement("air");
			$raiz_air=$raiz_detalleCompras->appendChild($raiz_air);

			$retencion="";
			while($row_reten_fte=mysql_fetch_array($retencion_fte))//permite ir de fila en fila de la tabla
			{
				$codigo_sri=$row_reten_fte['CodImp'];
				$porcentajeAir=$row_reten_fte['Porcentaje'];
				$valor_retenido=($row_reten_fte['BaseImp']*$row_reten_fte['Porcentaje'])/100;
				
				$valor_retenido=number_format($valor_retenido, 2, '.', '');
			
				$raiz_detalleAir=$doc->createElement("detalleAir");
				$raiz_detalleAir=$raiz_air->appendChild($raiz_detalleAir);
		
				$codRetAir=$codigo_sri;
				$cod_RetAir=$doc->createElement("codRetAir");
				$cod_RetAir=$raiz_detalleAir->appendChild($cod_RetAir);
				$text_codRetAir=$doc->createTextNode($codRetAir);
				$text_codRetAir=$cod_RetAir->appendChild($text_codRetAir);

				$baseImpAir=number_format($row_reten_fte['BaseImp'], 2, '.', '');
				$base_ImpAir=$doc->createElement("baseImpAir");
				$base_ImpAir=$raiz_detalleAir->appendChild($base_ImpAir);
				$text_baseImpAir=$doc->createTextNode($baseImpAir);
				$text_baseImpAir=$base_ImpAir->appendChild($text_baseImpAir);

				$porcentaje_Air=$doc->createElement("porcentajeAir");
				$porcentaje_Air=$raiz_detalleAir->appendChild($porcentaje_Air);
				$text_porcentajeAir=$doc->createTextNode($porcentajeAir);
				$text_porcentajeAir=$porcentaje_Air->appendChild($text_porcentajeAir);
	
				$valRetAir=number_format($valor_retenido, 2, '.', '');				
				$val_RetAir=$doc->createElement("valRetAir");
				$val_RetAir=$raiz_detalleAir->appendChild($val_RetAir);
				$text_valRetAir=$doc->createTextNode($valRetAir);
				$text_valRetAir=$val_RetAir->appendChild($text_valRetAir);		
			}
		}
 	
		$sql_compra="select serie,numero,autorizacion,fecha from mcretencion
		where factura_id='".$id_compra."'";
//echo $sql;
		$result_compra=mysql_query($sql_compra);
		$numero_filas = mysql_num_rows($result_compra);
		if ($numero_filas>0)
		{
			$id_empresa=0;
			while($row_compra=mysql_fetch_array($result_compra))//permite ir de fila en fila de la tabla
			{
				$estabRetencion1=substr($row_compra['serie'],0,3);
				$ptoEmiRetencion1=substr($row_compra['serie'],4,6);
				$secRetencion1=$row_compra['numero'];	
				
				
				// $autRetencion1 =(trim($row_compra['autorizacion'])!='')?$row_compra['autorizacion'] : '9999999999';
				$autRetencion1= $row_compra['autorizacion'];
	
				$fechaRegistrox=$row_compra['fecha'];
				$fechaRegistro2=date('d/m/Y',strtotime($fechaRegistrox));
				$fechaEmiRet1=$fechaRegistro2;
			}		

			$estab_Retencion1=$doc->createElement("estabRetencion1");
			$estab_Retencion1=$raiz_detalleCompras->appendChild($estab_Retencion1);
			$text_estabRetencion1=$doc->createTextNode($estabRetencion1);
			$text_estabRetencion1=$estab_Retencion1->appendChild($text_estabRetencion1);
		
			$ptoEmi_Retencion1=$doc->createElement("ptoEmiRetencion1");
			$ptoEmi_Retencion1=$raiz_detalleCompras->appendChild($ptoEmi_Retencion1);
			$text_ptoEmiRetencion1=$doc->createTextNode($ptoEmiRetencion1);
			$text_ptoEmiRetencion1=$ptoEmi_Retencion1->appendChild($text_ptoEmiRetencion1);
			
			$sec_Retencion1=$doc->createElement("secRetencion1");
			$sec_Retencion1=$raiz_detalleCompras->appendChild($sec_Retencion1);
			$text_secRetencion1=$doc->createTextNode($secRetencion1);
			$text_secRetencion1=$sec_Retencion1->appendChild($text_secRetencion1);
		
		/* 	if ($autRetencion1=="")
			{
				$autRetencion1="1234567890";
			}
		 */	
		    $aut_Retencion1=$doc->createElement("autRetencion1");
			$aut_Retencion1=$raiz_detalleCompras->appendChild($aut_Retencion1);
			$text_autRetencion1=$doc->createTextNode($autRetencion1);
			$text_autRetencion1=$aut_Retencion1->appendChild($text_autRetencion1);
							
			$fechaEmi_Ret1=$doc->createElement("fechaEmiRet1");
			$fechaEmi_Ret1=$raiz_detalleCompras->appendChild($fechaEmi_Ret1);
			$text_fechaEmiRet1=$doc->createTextNode($fechaEmiRet1);
			$text_fechaEmiRet1=$fechaEmi_Ret1->appendChild($text_fechaEmiRet1);
		}			
	}
  
	$raiz_ventas=$doc->createElement("ventas");
	$raiz_ventas=$raiz->appendChild($raiz_ventas);

	$sql="SELECT id_venta,a.id_cliente,cedula,tipo_documento,
		if(Autorizacion is null, 'F','E') tipoEm,id_forma_pago,count(*) numComprobantes,
		0 baseNoGraIva,sum(sub0) baseImponible,sum(sub12) baseImpGrav,
		b.cedula ruc,
		case length(b.cedula) when 13 then '04' when 10 then '05' else '06' end tpIdCliente,
		'NO' parteRel,concat_ws(' ',b.nombre,b.apellido) as denoCli, montoIce
	 FROM `ventas` a inner join clientes b
		on a.id_empresa=b.id_empresa and a.id_cliente = b.id_cliente 
		WHERE a.id_empresa='". $sesion_id_empresa."' 
		and year(fecha_venta)=".$anio." and month(fecha_venta)=".$mes." 
		and tipo_documento=1  and Autorizacion is null
		and ventas.estado='Activo'
		group by cedula,tipo_documento,tipoem ";

// 	echo $sql;
// 	exit;
	
	$resultado=mysql_query($sql);
	$montoIce=0.00;
	while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
	{
	   
		$id_venta=$row['id_venta'];
		$id_cliente=$row['id_cliente'];
	
		$tpIdCliente=$row['tpIdCliente'];
		$idCliente=$row['cedula'];
	
		if ($idCliente=='9999999999999')
		{
			$tpIdCliente="07";
		}	
					
		$parteRel=$row['parteRel'];
	//	$tipoCliente=$row['tipoCliente'];
		$denoCli=$row['denoCli'];
		$tipoComprobante1=$row['tipo_documento'];
		if ($tipoComprobante1==1)
		{
			$tipoComprobante=18;
		}
		$tipoEm=$row['tipoEm'];
		$numeroComprobantes=$row['numComprobantes'];
		$baseNoGraIva=$row['baseNoGraIva'];
		
// 		$baseImponible=$row['baseImponible'];
// 		$baseImpGrav=round($row['baseImpGrav'],3);

        $baseImponible2 = round($row['baseImponible'], 2);
        $baseImponible = number_format($baseImponible2, 2, '.', '');

		$baseImpGrav2 = round($row['baseImpGrav'], 2);
        $baseImpGrav = number_format($baseImpGrav2, 2, '.', '');
		
		
// 		$montoIce=$row['montoIce'];
		$montoIce=0.00;
		
		$formaPago="";
		$id_forma_pago=$row['id_forma_pago'];
		$formaPago1=strval($id_forma_pago);
		
		if (strlen($formaPago1)<2)
		{
			$formaPago="0".$formaPago1;
		}else if (strlen($formaPago1)>=2){
		    $formaPago="20";
		}
		
		$raiz_detalleVentas=$doc->createElement("detalleVentas");
		$raiz_detalleVentas=$raiz_ventas->appendChild($raiz_detalleVentas);

		$tp_IdCliente=$doc->createElement("tpIdCliente");
		$tp_IdCliente=$raiz_detalleVentas->appendChild($tp_IdCliente);
		$text_tpIdCliente=$doc->createTextNode($tpIdCliente);
		$text_tpIdCliente=$tp_IdCliente->appendChild($text_tpIdCliente);

		$id_Cliente=$doc->createElement("idCliente");
		$id_Cliente=$raiz_detalleVentas->appendChild($id_Cliente);
		$text_idCliente=$doc->createTextNode($idCliente);
// 			echo	"LLIENTE ==".$idCliente=$row['cedula']."<br>";
		$text_idCliente=$id_Cliente->appendChild($text_idCliente);

		if ($tpIdCliente!='07')
		{
			$parte_Rel=$doc->createElement("parteRelVtas");
			$parte_Rel=$raiz_detalleVentas->appendChild($parte_Rel);
			$text_parteRel=$doc->createTextNode($parteRel);
			$text_parteRel=$parte_Rel->appendChild($text_parteRel);
		}
	
		if ($tpIdCliente=="06")
		{
			$tipoCliente=$tpIdCliente;
			$tipo_Cliente=$doc->createElement("tipoCliente");
			$tipo_Cliente=$raiz_detalleVentas->appendChild($tipo_Cliente);
			$text_tipoCliente=$doc->createTextNode($tipoCliente);
			$text_tipoCliente=$tipo_Cliente->appendChild($text_tipoCliente);
	
			$deno_cli=$doc->createElement("DenoCli");
			$deno_cli=$raiz_detalleVentas->appendChild($deno_cli);
			$text_denocli=$doc->createTextNode($denoCli);
			$text_denocli=$deno_cli->appendChild($text_denocli);
		}
		
		$tipo_Comprobante=$doc->createElement("tipoComprobante");
		$tipo_Comprobante=$raiz_detalleVentas->appendChild($tipo_Comprobante);
		$text_tipoComprobante=$doc->createTextNode($tipoComprobante);
		$text_tipoComprobante=$tipo_Comprobante->appendChild($text_tipoComprobante);
	
		$tipo_Em=$doc->createElement("tipoEmision");
		$tipo_Em=$raiz_detalleVentas->appendChild($tipo_Em);
		$text_tipoEm=$doc->createTextNode($tipoEm);
		$text_tipoEm=$tipo_Em->appendChild($text_tipoEm);
	
		$numero_Comprobantes=$doc->createElement("numeroComprobantes");
		$numero_Comprobantes=$raiz_detalleVentas->appendChild($numero_Comprobantes);
		$text_numeroComprobantes=$doc->createTextNode($numeroComprobantes);
		$text_numeroComprobantes=$numero_Comprobantes->appendChild($text_numeroComprobantes);
		
		$base_NoGraIva=$doc->createElement("baseNoGraIva");
		$base_NoGraIva=$raiz_detalleVentas->appendChild($base_NoGraIva);
		$text_baseNoGraIva=$doc->createTextNode($baseNoGraIva);
		$text_baseNoGraIva=$base_NoGraIva->appendChild($text_baseNoGraIva);
		
		$base_Imponible=$doc->createElement("baseImponible");
		$base_Imponible=$raiz_detalleVentas->appendChild($base_Imponible);
		$text_baseImponible=$doc->createTextNode($baseImponible);
		$text_baseImponible=$base_Imponible->appendChild($text_baseImponible);
		
		$base_ImpGrav=$doc->createElement("baseImpGrav");
		$base_ImpGrav=$raiz_detalleVentas->appendChild($base_ImpGrav);
		$text_baseImpGrav=$doc->createTextNode($baseImpGrav);
		$text_baseImpGrav=$base_ImpGrav->appendChild($text_baseImpGrav);
		
		$montoIva=round($baseImpGrav*12/100,2);
		$montoIva=number_format($montoIva,2,".","");
		$monto_iva=$doc->createElement("montoIva");
		$monto_iva=$raiz_detalleVentas->appendChild($monto_iva);
		$text_montoiva=$doc->createTextNode($montoIva);
		$text_montoiva=$monto_iva->appendChild($text_montoiva);

		$monto_ice=$doc->createElement("montoIce");
		$monto_ice=$raiz_detalleVentas->appendChild($monto_ice);
		$text_montoice=$doc->createTextNode($montoIce);
		$text_montoice=$monto_ice->appendChild($text_montoice);
		
		$sql_retenciones="SELECT a.id_cliente,tipo,sum(valor) valor_retenido
			FROM `ventas` a inner join cobrospagos b
			on a.id_venta=b.id_factura and a.id_empresa=b.id_empresa
			where a.id_empresa='". $sesion_id_empresa ."' and 
			year(fecha_venta)=".$anio." and month(fecha_venta)=".$mes."
			and (tipo='5' or tipo='6') and a.id_cliente=".$id_cliente ."
			group by id_cliente,tipo";

		//echo "<br/>".$sql_retenciones;
		$retenciones=mysql_query($sql_retenciones);
		$valor_retenido=0;
		$valorRetIva=0;
		$valorRetRenta=0;
		$numero_filas = mysql_num_rows($retenciones);
		if ($numero_filas>0)
		{
			$valor_retenido=0;
			$valorRetIva=0;
			$valorRetRenta=0;
			while($row_reten=mysql_fetch_array($retenciones))//permite ir de fila en fila de la tabla
			{
				$tipo=$row_reten['tipo'];
				$valor_retenido=$row_reten['valor_retenido'];
				if ($tipo=="5")
				{
					$valorRetIva=$valor_retenido;
					$valorRetIva=number_format($valorRetIva,2,".","");		
				}
				else
				{
					$valorRetRenta=$valor_retenido;
					$valorRetRenta=number_format($valorRetRenta,2,".","");
				}
			}
		}

		$val_RetIva=$doc->createElement("valorRetIva");
		$val_RetIva=$raiz_detalleVentas->appendChild($val_RetIva);
		$text_valRetIva=$doc->createTextNode($valorRetIva);
		$text_valRetIva=$val_RetIva->appendChild($text_valRetIva);
		
		$val_RetRenta=$doc->createElement("valorRetRenta");
		$val_RetRenta=$raiz_detalleVentas->appendChild($val_RetRenta);
		$text_valRetRenta=$doc->createTextNode($valorRetRenta);
		$text_valRetRenta=$val_RetRenta->appendChild($text_valRetRenta);
		
		$raiz_formaPago=$doc->createElement("formasDePago");
		$raiz_formaPago=$raiz_detalleVentas->appendChild($raiz_formaPago);
	
		$forma_Pago=$doc->createElement("formaPago");
		$forma_Pago=$raiz_formaPago->appendChild($forma_Pago);
		$text_formaPago=$doc->createTextNode($formaPago);
		$text_formaPago=$forma_Pago->appendChild($text_formaPago);
	}
	
	$raiz_ventasEstab=$doc->createElement("ventasEstablecimiento");
	$raiz_ventasEstab=$raiz->appendChild($raiz_ventasEstab);

	$raiz_ventaEst=$doc->createElement("ventaEst");
	$raiz_ventaEst=$raiz_ventasEstab->appendChild($raiz_ventaEst);
	
// // Primera consulta para obtener todos los establecimientos
// $sql = "SELECT * FROM establecimientos WHERE id_empresa=".$sesion_id_empresa;
// $resultado = mysql_query($sql);

// while ($row = mysql_fetch_array($resultado)) {
//     $idesta = $row["id"];
//     $codEstab = $row["codigo"]; // Asumiendo que 'codigo' es una columna de 'establecimientos'

//     // Consulta para obtener el total de ventas por establecimiento
//     $sqlTotalEstventas = "SELECT SUM(sub_total) AS total_vtas FROM ventas WHERE id_empresa=".$sesion_id_empresa." AND codigo_pun=".$idesta;
//     $resultadoTotalVentas = mysql_query($sqlTotalEstventas);

//     if ($rowtotalEst = mysql_fetch_array($resultadoTotalVentas)) {
//         $ventasEstab = number_format((float)$rowtotalEst["total_vtas"], 2, ".", "");

        // Crear y a√±adir elementos al documento XML
        $cod_Estab = $doc->createElement("codEstab");
        $cod_Estab = $raiz_ventaEst->appendChild($cod_Estab);
        $text_codEstab = $doc->createTextNode($codEstab);
        $cod_Estab->appendChild($text_codEstab);

        $ventas_Estab = $doc->createElement("ventasEstab");
        $ventas_Estab = $raiz_ventaEst->appendChild($ventas_Estab);
        $text_ventasEstab = $doc->createTextNode($ventasEstab);
        $ventas_Estab->appendChild($text_ventasEstab);
//     }
// }




    
$url = "xml/ats" . $ruc . $anio . $mes;
$filename = $url . ".xml";

// Asegè´‚rate de que el directorio existe
$directory = dirname($filename);
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

// Guarda el documento XML
$saveResult = $doc->save($filename);

if ($saveResult === false) {
    echo "Error al guardar el archivo.\n";
    foreach (libxml_get_errors() as $error) {
        echo "\t", $error->message, "\n";
    }
    libxml_clear_errors();

    // Verifica errores de escritura en el sistema de archivos
    if (!is_writable($directory)) {
        echo "El directorio no tiene permisos de escritura.\n";
    }
} else {
    echo "El archivo se ha guardado correctamente en: " . $filename;
    if (file_exists($filename)) {
        echo " y se encuentra en el directorio.";
    } else {
        echo " pero no se encuentra en el directorio.";
    }
}
		
	registroAnexo($sesion_id_empresa,$mes,$anio,$url);
}

if ($accion==2)
{
    
        $url=$_POST['url'];
        $id=$_POST['id'];
        
        $file = $url; 
        
        if (file_exists($file)) {
            
            unlink($file);
            
            
            $sql="DELETE FROM `anexosCreados` WHERE id='".$id."'";
            $result = mysql_query($sql);
            if ($result) {
                echo 'El archivo ha sido eliminado.';
            }else{
                echo 'El archivo no fue eliminado de la tabla.';
            }
        
        } else {
            echo 'El archivo no existe.';
            
            $sql="DELETE FROM `anexosCreados` WHERE id='".$id."'";
            $result = mysql_query($sql);
            if ($result) {
                echo 'El archivo ha sido eliminado.';
            }else{
                echo 'El archivo no fue eliminado de la tabla.';
            }
        }
        
        
        
        
}
?>