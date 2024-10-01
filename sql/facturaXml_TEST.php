<?php
    session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
    //Include database connection details
    
    require_once('../conexion.php');
	
	require_once('../tcpdf_include.php');
    //require_once('../reportes/fpdf17/fpdf.php');
    

 	require_once('../include/class.phpmailer.php');
    require_once("../firma.php");
    require_once("../xades.php");
   require '/var/www/html/asesoria/vendor/autoload.php';

use phpseclib3\File\X509;

// Ejemplo de uso de la clase File_X509
$x509 = new X509();
    // $accion=$_POST['txtAccion'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_usuario = $_SESSION['sesion_id_usuario'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_empresa_imagen = $_SESSION['sesion_empresa_imagen'];
    $sesion_empresa_razonSocial =   $_SESSION['sesion_empresa_razonSocial'];
if (isset($_POST['empresa_direccion'])) {
    $empresa_direccion = $_POST['empresa_direccion'];
} else {
    $empresa_direccion = ""; // o un valor por defecto
}
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
    $emision_codigo = $_SESSION["emision_codigo"];
	$establecimiento_codigo = $_SESSION["establecimiento_codigo"];
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    
    $Ocontabilidad = $_SESSION["Ocontabilidad"];
    
    $empresa_contribuyente=$_SESSION['empresa_contribuyente'] ;
    date_default_timezone_set('America/Guayaquil');
	//echo $_SESSION["sesion_id_empresa"];    
	    
function eliminar_tildes($cadena){
 
    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    $cadena = utf8_encode($cadena);
 
    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );
 
    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );
 
    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );
 
    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );
 
    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );
 
    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );
    return $cadena;
}

function ceros($valor) {
    $s = ""; // Inicializar la variable $s
    for ($i = 1; $i <= 9 - strlen($valor); $i++) {
        $s .= "0";
    }
    return $s . $valor;
}

 function invertirCadena($cadena) {
         for ($x = strlen($cadena) - 1; $x >= 0; $x--) 
            $cadenaInvertida.= substr($cadena,$x,1);
        
        return $cadenaInvertida;
   }
 
 function obtenerSumaPorDigitos($cadena) {
        $pivote = 2;
        $longitudCadena = strlen($cadena);
        $cantidadTotal = 0;
		$temporal=0;
        $b = 1;
        for ($i = 0; $i < $longitudCadena; $i++) {
            if ($pivote == 8) 
                $pivote = 2;
            $temporal = (int)substr($cadena,$i,1);
            $b++;
			
            $temporal *= $pivote;
            $pivote++;
            $cantidadTotal += $temporal;
			//echo $cadena."-".$temporal."-".$i." ".$b."-".$pivote."-".$cantidadTotal."</br>";
        
		
		//echo $cantidadTotal;
        $cantidadTotal = 11 - $cantidadTotal % 11;
		 }
        return $cantidadTotal;
   
}
 

function generarXMLCDATA($data){				
      	$s = "";
		$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$s .= "<autorizacion>\n";
			$s .= "<estado>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado."</estado>\n";
			$s .= "<numeroAutorizacion>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion."</numeroAutorizacion>\n";
			$s .= "<fechaAutorizacion>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion."</fechaAutorizacion>\n";
			$s .= "<comprobante><![CDATA[".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante."]]></comprobante>";
		 	$s .= "<mensajes/>\n";
		$s .= "</autorizacion>";
		return $s;
	}
	
	
function consultaSRI($consulta,$ambiente,$claveAcceso,$tipoc,$factura){
    // echo "consulta".$consulta."</br>";
// echo "<==1==>".$consulta."<==1==>".$ambiente."<==2==>".$claveAcceso."<==3==>".$tipoc."<==4==>".$factura;
	
	
if($consulta == 5) {
                $data = 5; //
				echo " Error ruta Archivo";
            } else {
                if($consulta == 6) {
                    $data = 6; // ;
					echo "Contrase?a de token Incorrecta";
                } else {    
				
				    switch($tipoc){
				        
						case "factura":case "notaCredito":case "guiaRemision":
							$instru="UPDATE ventas SET Retfuente=?,Comentario=? where id_venta=?";
							$tabla="ventas";
							//$instru1="select mfactura.Factref,mfactura.Serie, clientes.Correoe,clientes.Id as Cliente_id,mfactura.Autorizacion from clientes,mfactura where mfactura.Cliente_id=clientes.Id and mfactura.Id=?";
							$instru1="SELECT  ventas.numero_factura_venta as Factref, CONCAT(establecimientos.codigo,'-',emision.codigo) as Serie, clientes.email AS  Correoe,
							clientes.id_cliente as Cliente_id,ventas.Autorizacion,empresa.ruc from clientes,ventas,establecimientos,emision,empresa 
							WHERE empresa.id_empresa=ventas.id_empresa and   establecimientos.id=ventas.codigo_pun and emision.id=ventas.codigo_lug 
							AND  emision.id=codigo_lug  and ventas.id_cliente=clientes.id_cliente AND   ventas.id_venta=$factura";
							
							
						break;
						case "comprobanteRetencion":
							$instru="UPDATE mcretencion SET Retfuente=?,Observaciones=? where Id=?";
							$tabla="mcretencion";
							$instru1="select mcretencion.Numero as Factref, mcretencion.Serie,proveedores.email as Correoe,proveedores.id_proveedor as Cliente_id,
							mcretencion.Autorizacion,empresa.ruc from mcretencion INNER JOIN compras ON compras.id_compra = mcretencion.Factura_id 
							INNER JOIN proveedores ON proveedores.id_proveedor = compras.id_proveedor INNER JOIN empresa ON empresa.id_empresa = compras.id_empresa 
							where mcretencion.Id=$factura";
						break;
						case "liquidacionCompra":
							$instru="UPDATE compras SET Retfuente=?,Observaciones=? where id_compra=?";
							$tabla="compras";
							$instru1="select compras.numero_factura_compra as Factref,CONCAT(compras.numSerie,'-',compras.txtEmision) as Serie, compras.txtEmision as establecimiento,
							proveedores.email as Correoe,proveedores.id_proveedor as Cliente_id,compras.autorizacion,empresa.ruc from proveedores,compras,empresa
							where empresa.id_empresa=compras.id_empresa and compras.id_proveedor=proveedores.id_proveedor and compras.id_compra=$factura";
			            break;
						
					}       
					
                    $respWeb = webService($consulta,$ambiente,$claveAcceso,'',$tipoc,$pass,$token,'0'); // ENVIO EL ARCHIVO XML PARA VALIDAR   
                    
                    
                            if(isset($respWeb['RespuestaRecepcionComprobante']['estado'])) {
                        
                            $respuesta = consultarComprobante($ambiente, $claveAcceso);
     
                 
                    //   	print_r($respuesta);
                         


                            $data = $respWeb['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['mensaje'];
                             $dataAdicional = $respWeb['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['informacionAdicional'];
                             if($data==''){
                                 $data=$estado = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;;
                             }
                             if($dataAdicional==''){
                                //  var_dump ($respWeb) ;
                                 $dataAdicional = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;

                             }
                             
               
                             
						 switch($tipoc){
						     
						     
						    case "factura":case "notaCredito":case "guiaRemision":
						     $result=mysql_query( "UPDATE ventas SET Retfuente=-1,Comentario='$dataAdicional',Comentario2='$dataAdicional' where id_venta=$factura");

						     
						    break;
						        case "comprobanteRetencion":
						        $result=mysql_query( "UPDATE mcretencion SET Retfuente=-1,Observaciones='$data',Observaciones2='$dataAdicional' where Id=$factura");   

                                break;
                                
                                case "liquidacionCompra":
					            // echo "liquidacion de comoras";
					           // echo "UPDATE compras SET Retfuente=-1,Observaciones='$data',Observaciones2='$dataAdicional' where id_compra=$factura";
						        $result=mysql_query( "UPDATE compras SET Retfuente=-1,Observaciones='$data',Observaciones2='$dataAdicional' where id_compra=$factura");   

                                break;
						 }

						
                        if(isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
                            if($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
    
                                $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
                                $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
                                $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
                               
                                    if($tabla=="ventas")
									$row=(mysql_query( "UPDATE $tabla SET FechaAutorizacion ='".substr(str_replace("T"," ",$fechaAutorizacion),0,19)."',Retfuente=0,Autorizacion='$numeroAutorizacion',ClaveAcceso='$claveAcceso' where id_venta=$factura"));

								    if($tabla=="mcretencion")
									$row=(mysql_query( "UPDATE $tabla SET FechaAutorizacion ='".substr(str_replace("T"," ",$fechaAutorizacion),0,19)."',Retfuente=0,Autorizacion='$numeroAutorizacion',ClaveAcceso='$claveAcceso' where Id=$factura"));
								
									if($tabla=="compras")
									$row=(mysql_query( "UPDATE $tabla SET FechaAutorizacion ='".substr(str_replace("T"," ",$fechaAutorizacion),0,19)."',Retfuente=0,Autorizacion='$numeroAutorizacion',ClaveAcceso='$claveAcceso' where id_compra=$factura"));
								
                                $dataFile = generarXMLCDATA($respuesta);                        
                                $doc = new DOMDocument('1.0', 'UTF-8');
                                $doc->loadXML($dataFile); // xml  
								$row1=mysql_fetch_array(mysql_query( $instru1));
								

		                  if($doc->save("../xmls/autorizados/".$tipoc."_".$row1['ruc'].str_replace("-","",$row1['Serie']).ceros($row1['Factref']).".xml")) 
								{
                                    if(trim($row1['Correoe']) == '') {
                                        // echo "sin email";
										$data = 3;
										if($tabla=="ventas")
										$res=mysql_query("UPDATE ventas SET Retfuente=$data,Comentario='Cliente sin correo electronico' where id_venta=$factura");
										if($tabla=="mcretencion")
										$res=mysql_query("UPDATE mcretencion SET Retfuente=$data,Observaciones='Correo no Enviado' where Id=$factura");
										if($tabla=="compras")
										$res=mysql_query("UPDATE compras SET Retfuente=$data,Observaciones='Correo no Enviado' where id_compra=$factura");
										$row11=mysql_fetch_array($res);
										
										if (empty($row1['Correoe'])) {
                                            $row1['Correoe'] = "correo@correo.com"; // Cambia "Otro valor" por el valor que desees asignar
                                        }
										$enviado_exitosamente = SendMail("../xmls/autorizados/".$tipoc."_".$row1['ruc'].str_replace("-","",$row1['Serie']).ceros($row1['Factref']).".xml",$row1['Correoe'],$factura,$tipoc);
                                        
                                        if ($enviado_exitosamente) {
                                            // El correo se envió correctamente
                                            $respuestaEmal= "El correo se envió correctamente.";
                                        } else {
                                            // Hubo un error al enviar el correo
                                            $respuestaEmal= "Hubo un error al enviar el correo.";
                                        }
                       
                                        if($res>0) {
                                            $data = 1; // datos actualizados
                                        } else {
                                            $data = 4; // error al momento de guadar
                                        }
                                    } else {
                                        echo "EMAIL";
                                        $email=$row1['Correoe'].";";
                                        $vmail=explode(";",$email);
                                        for($i=0;$i<count($vmail);$i++)

                                        $enviado_exitosamente = SendMail("../xmls/autorizados/".$tipoc."_".$row1['ruc'].str_replace("-","",$row1['Serie']).ceros($row1['Factref']).".xml",$vmail[$i],$factura,$tipoc);
                                        
                                        if ($enviado_exitosamente) {
                                            $respuestaEmal= "El correo se envió correctamente.";
                                        } else {
                                            $respuestaEmal= "Hubo un error al enviar el correo.";
                                        }
                                                                                
                                        if($tabla=="ventas")
                                        $res1=mysql_query("UPDATE ventas SET Retfuente=8,Comentario='$respuestaEmal',Comentario2='$dataAdicional' where id_venta=$factura");
                                        // echo "UPDATE ventas SET Retfuente=8,Comentario='$respuestaEmal',Comentario2=$dataAdicional where id_venta=$factura";
										if($tabla=="mcretencion")
										$res1=mysql_query("UPDATE mcretencion SET Retfuente=8,Observaciones=$dataAdicional where Id=$factura");
										if($tabla=="compras")
										$res1=mysql_query("UPDATE mcretencion SET Retfuente=8,Observaciones=$dataAdicional where Id=$factura");

                                    }
                                } else {
                                    $data = 2;
									$row2=mysql_fetch_array(mysql_query($instru1));
									if($row2['Autorizacion']!=""){
										$data =0;
									}
									if($tabla=="ventas")
									$row2=(mysql_query("UPDATE ventas SET Retfuente=$data,Comentario='{$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje}' where id_venta=$factura"));
									if($tabla=="mcretencion")
										$res1=mysql_query("UPDATE mcretencion SET Retfuente=$data,Observaciones='{$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje}' where Id=$factura");
									if($tabla=="compras")
										$res1=mysql_query("UPDATE compras SET Retfuente=$data,Observaciones='{$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje}' where Id=$factura");
									
                                }
                            } else {    
                                $data = 7;
							if($tabla=="ventas")
								$row2=(mysql_query("UPDATE ventas SET Retfuente=$data,Comentario='{$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje}' where id_venta=$factura"));
                              if($tabla=="mcretencion")
                                	$res1=mysql_query("UPDATE mcretencion SET Retfuente=$data,Observaciones='{$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje}' where Id=$factura");
                               if($tabla=="compras")
                                	$res1=mysql_query("UPDATE compras SET Retfuente=$data,Observaciones='{$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje}' where Id=$factura");
                            }
                        } else {
                            
                          
                            $data = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;
						if($tabla=="ventas")
							$row2=(mysql_query("UPDATE ventas SET Retfuente=10,Comentario='{$respWeb['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['mensaje']}' where id_venta=$factura"));
                         if($tabla=="mcretencion")
                           	$res1=mysql_query("UPDATE mcretencion SET Retfuente=$data,Observaciones='{$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje}' where Id=$factura");
                          if($tabla=="compras")
                           	$res1=mysql_query("UPDATE compras SET Retfuente=$data,Observaciones='{$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje}' where Id=$factura"); 

                        }
                    } else {

                        $data = $respWeb['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['mensaje'];
                        if($tabla=="ventas")
                       $row2=(mysql_query("UPDATE ventas SET Retfuente=9,Comentario='$respuestaEmal' where id_venta=$factura"));
                         if($tabla=="mcretencion")
                         	$res1=mysql_query("UPDATE mcretencion SET Retfuente=9,Observaciones='$respuestaEmal' where Id=$factura");
                         	if($tabla=="compras")
                         	$res1=mysql_query("UPDATE compras SET Retfuente=9,Observaciones='$respuestaEmal' where Id=$factura");

                    }
                }
            }
	
	$conn=null;
}


function newsign($datos){
//Datos que se quieren firmar:

//Se deben crear dos claves aparejadas, una clave pública y otra privada
//A continuación el array de configuración para la creación del juego de claves
$configArgs = array(
    'config' => 'C:\AppServ\php5\extras\ssl\openssl.cnf', //<-- esta ruta es necesaria si trabajas con XAMPP
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA
);
$resourceNewKeyPair = openssl_pkey_new($configArgs);
if (!$resourceNewKeyPair) {
    echo 'Puede que tengas problemas con la ruta indicada en el array de configuración "$configArgs" ';
    echo openssl_error_string(); //en el caso que la función anterior de openssl arrojará algun error, este sería visualizado gracias a esta línea
    exit;
}
//obtengo del recurso $resourceNewKeyPair la clave publica como un string 
$details = openssl_pkey_get_details($resourceNewKeyPair);
$publicKeyPem = $details['key'];
//obtengo la clave privada como string dentro de la variable $privateKeyPem (la cual es pasada por referencia)
if (!openssl_pkey_export($resourceNewKeyPair, $privateKeyPem, NULL, $configArgs)) {
    echo openssl_error_string(); //en el caso que la función anterior de openssl arrojará algun error, este sería visualizado gracias a esta línea
    exit;
}
//guardo la clave publica y privada en disco:
file_put_contents('private_key.pem', $privateKeyPem);
file_put_contents('public_key.pem', $publicKeyPem);
//si bien ya tengo cargado el string de la clave privada, lo voy a buscar a disco para verificar que el archivo private_key.pem haya sido correctamente generado:
$privateKeyPem = file_get_contents('private_key.pem');
//obtengo la clave privada como resource desde el string
$resourcePrivateKey = openssl_get_privatekey($privateKeyPem);
//crear la firma dentro de la variable $firma (la cual es pasada por referencia)
if (!openssl_sign($datos, $firma, $resourcePrivateKey, OPENSSL_ALGO_SHA256)) {
    echo openssl_error_string(); //en el caso que la función anterior de openssl arrojará algun error, este sería visualizado gracias a esta línea
    exit;
}
// guardar la firma en disco:
file_put_contents('signature.dat', $firma);
// comprobar la firma
if (openssl_verify($datos, $firma, $publicKeyPem, 'sha256WithRSAEncryption') === 1) {
    echo 'la firma es valida y los datos son confiables';
} else {
    echo 'la firma es invalida y/o los datos fueron alterados';
}
}

function SendMail($archivo,$correo,$factura,$tipoc){
       
// echo "archivo==".$archivo;
$xml=file_get_contents($archivo);
	$xml = str_replace(array('<?xml version="1.0" encoding="UTF-8"?>'),'', $xml);

        $xml = str_replace(array('<?xml version="1.0" encoding="UTF-8" standalone="no"?>'),'', $xml);

		$xml = str_replace(array('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'),'', $xml);
		$xml = str_replace(array('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'),'', $xml);
		
switch($tipoc){

	case "factura":

	$tcomprobante="FACTURA";
	$cimp=$bimp=$vimp=array();
	$dcod=$ddes=$dcant=$dprecio=$ddes=$dpreciot=array();
	$nadic=$dadic=array();	
	if(strpos($xml,"<razonSocial>")>0){
	    
		$razonSocial= substr($xml,strpos($xml,"<razonSocial>")+strlen("<razonSocial>"),strpos($xml,"</razonSocial>")-strlen("<razonSocial>")-strpos($xml,"<razonSocial>"));
		$nombreComercial= substr($xml,strpos($xml,"<nombreComercial>")+strlen("<nombreComercial>"),strpos($xml,"</nombreComercial>")-strlen("<nombreComercial>")-strpos($xml,"<nombreComercial>"));
			
		if (strpos($xml, "<numeroAutorizacion>") !== false) {
		     $numeroAutorizacion= substr($xml,strpos($xml,"<numeroAutorizacion>")+strlen("<numeroAutorizacion>"),strpos($xml,"</numeroAutorizacion>")-strlen("<numeroAutorizacion>")-strpos($xml,"<numeroAutorizacion>"));
		}
	
		$fechaAutorizacion=substr($xml,strpos($xml,"<fechaAutorizacion")+strlen("<fechaAutorizacion>"),strpos($xml,"</fechaAutorizacion>")-strlen("<fechaAutorizacion>")-strpos($xml,"<fechaAutorizacion"));
		$fechaAutorizacion=substr($fechaAutorizacion,strpos($fechaAutorizacion,">")+1,strlen($fechaAutorizacion));
		$ruc= (substr($xml,strpos($xml,"<ruc>")+strlen("<ruc>"),strpos($xml,"</ruc>")-strlen("<ruc>")-strpos($xml,"<ruc>")));
		$ambiente= substr($xml,strpos($xml,"<ambiente>")+strlen("<ambiente>"),strpos($xml,"</ambiente>")-strlen("<ambiente>")-strpos($xml,"<ambiente>"));
		$ambiente=((int)trim($ambiente)==2)?"PRODUCCION":"PRUEBAS";
		$claveAcceso= substr($xml,strpos($xml,"<claveAcceso>")+strlen("<claveAcceso>"),strpos($xml,"</claveAcceso>")-strlen("<claveAcceso>")-strpos($xml,"<claveAcceso>"));
		$estab= substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
		$tipoEmision= substr($xml,strpos($xml,"<tipoEmision>")+strlen("<tipoEmision>"),strpos($xml,"</tipoEmision>")-strlen("<tipoEmision>")-strpos($xml,"<tipoEmision>"));
		$tipoEmision=($tipoEmision==1)?"NORMAL":"";
		$ptoEmi= substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
		$secuencial= substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));
		$dirMatriz= substr($xml,strpos($xml,"<dirMatriz>")+strlen("<dirMatriz>"),strpos($xml,"</dirMatriz>")-strlen("<dirMatriz>")-strpos($xml,"<dirMatriz>"));
		$fechaEmision= substr($xml,strpos($xml,"<fechaEmision>")+strlen("<fechaEmision>"),strpos($xml,"</fechaEmision>")-strlen("<fechaEmision>")-strpos($xml,"<fechaEmision>"));
		$fechaEmision= substr($fechaEmision,6,4)."-".substr($fechaEmision,3,2)."-".substr($fechaEmision,0,2);
		$dirEstablecimiento= substr($xml,strpos($xml,"<dirEstablecimiento>")+strlen("<dirEstablecimiento>"),strpos($xml,"</dirEstablecimiento>")-strlen("<dirEstablecimiento>")-strpos($xml,"<dirEstablecimiento>"));
		$obligadoContabilidad= substr($xml,strpos($xml,"<obligadoContabilidad>")+strlen("<obligadoContabilidad>"),strpos($xml,"</obligadoContabilidad>")-strlen("<obligadoContabilidad>")-strpos($xml,"<obligadoContabilidad>"));
		$razonSocialComprador= substr($xml,strpos($xml,"<razonSocialComprador>")+strlen("<razonSocialComprador>"),strpos($xml,"</razonSocialComprador>")-strlen("<razonSocialComprador>")-strpos($xml,"<razonSocialComprador>"));
		$identificacionComprador= substr($xml,strpos($xml,"<identificacionComprador>")+strlen("<identificacionComprador>"),strpos($xml,"</identificacionComprador>")-strlen("<identificacionComprador>")-strpos($xml,"<identificacionComprador>"));
		$totalSinImpuestos= substr($xml,strpos($xml,"<totalSinImpuestos>")+strlen("<totalSinImpuestos>"),strpos($xml,"</totalSinImpuestos>")-strlen("<totalSinImpuestos>")-strpos($xml,"<totalSinImpuestos>"));
		$totalDescuento= substr($xml,strpos($xml,"totalDescuento")+strlen("<totalDescuento>"),strpos($xml,"</totalDescuento>")-strlen("<totalDescuento>")-strpos($xml,"<totalDescuento>"));
		$tipoIdentificacionComprador= substr($xml,strpos($xml,"<tipoIdentificacionComprador>")+strlen("<tipoIdentificacionComprador>"),strpos($xml,"</tipoIdentificacionComprador>")-strlen("<tipoIdentificacionComprador>")-strpos($xml,"<tipoIdentificacionComprador>"));


	
					
							$contribuyenteEspecial="";
							if(strpos($xml,"<contribuyenteEspecial>")>0)
								$contribuyenteEspecial= substr($xml,strpos($xml,"<contribuyenteEspecial>")+strlen("<contribuyenteEspecial>"),strpos($xml,"</contribuyenteEspecial>")-strlen("<contribuyenteEspecial>")-strpos($xml,"<contribuyenteEspecial>"));
							$totalDescuento= substr($xml,strpos($xml,"<totalDescuento>")+strlen("<totalDescuento>"),strpos($xml,"</totalDescuento>")-strlen("<totalDescuento>")-strpos($xml,"<totalDescuento>"));
							$baseImponible= substr($xml,strpos($xml,"<baseImponible>")+strlen("<baseImponible>"),strpos($xml,"</baseImponible>")-strlen("<baseImponible>")-strpos($xml,"<baseImponible>"));
							$impuestos=substr($xml,strpos($xml,"<totalConImpuestos>"),strpos($xml,"</totalConImpuestos>")-strpos($xml,"<totalConImpuestos>"));
							$propina=substr($xml,strpos($xml,"<propina>")+strlen("<propina>"),strpos($xml,"</propina>")-strlen("<propina>")-strpos($xml,"<propina>"));
							$importeTotal=($tipoc=="factura")?substr($xml,strpos($xml,"<importeTotal>")+strlen("<importeTotal>"),strpos($xml,"</importeTotal>")-strlen("<importeTotal>")-strpos($xml,"<importeTotal>")):substr($xml,strpos($xml,"<valorModificacion>")+strlen("<valorModificacion>"),strpos($xml,"</valorModificacion>")-strlen("<valorModificacion>")-strpos($xml,"<valorModificacion>"));
							$moneda=substr($xml,strpos($xml,"<moneda>")+strlen("<moneda>"),strpos($xml,"</moneda>")-strlen("<moneda>")-strpos($xml,"<moneda>"));
							$detalles=substr($xml,strpos($xml,"<detalles>")+strlen("<detalles>"),strpos($xml,"</detalles>")-strlen("<detalles>")-strpos($xml,"<detalles>"));
												
							$infoAdicional=substr($xml,strpos($xml,"<infoAdicional>")+strlen("<infoAdicional>"),strpos($xml,"</infoAdicional>")-+strlen("<infoAdicional>")-strpos($xml,"<infoAdicional>"));
								$cont=1;
							do{
								$cimp[]=substr($impuestos,strpos($impuestos,"<codigoPorcentaje>")+strlen("<codigoPorcentaje>"),strpos($impuestos,"</codigoPorcentaje>")-strlen("<codigoPorcentaje>")-strpos($impuestos,"<codigoPorcentaje>"));
								$bimp[]=substr($impuestos,strpos($impuestos,"<baseImponible>")+strlen("<baseImponible>"),strpos($impuestos,"</baseImponible>")-strlen("<baseImponible>")-strpos($impuestos,"<baseImponible>"));
								$vimp[]=substr($impuestos,strpos($impuestos,"<valor>")+strlen("<valor>"),strpos($impuestos,"</valor>")-strlen("<valor>")-strpos($impuestos,"<valor>"));
							$impuestos=substr($impuestos,strpos($impuestos,"</totalImpuesto>"),strlen($impuestos)-strpos($impuestos,"</totalImpuesto>"));
							
							    if($cont>3)
									break;
									$cont++;
							}while(strpos($impuestos,"<totalImpuesto>")>0);	
							$cont=0;
							
							//echo $detalles;
							/*do{
								$cont++;
								$dcod[]=substr($detalles,strpos($detalles,"<codigoPrincipal>")+strlen("<codigoPrincipal>"),strpos($detalles,"</codigoPrincipal>")-strlen("<codigoPrincipal>")-strpos($detalles,"<codigoPrincipal>"));
								$ddes[]=substr($detalles,strpos($detalles,"<descripcion>")+strlen("<descripcion>"),strpos($detalles,"</descripcion>")-strlen("<descripcion>")-strpos($detalles,"<descripcion>"));
								$dcant[]=substr($detalles,strpos($detalles,"<cantidad>")+strlen("<cantidad>"),strpos($detalles,"</cantidad>")-strlen("<cantidad>")-strpos($detalles,"<cantidad>"));
								$dprecio[]=substr($detalles,strpos($detalles,"<precioUnitario>")+strlen("<precioUnitario>"),strpos($detalles,"</precioUnitario>")-strlen("<precioUnitario>")-strpos($detalles,"<precioUnitario>"));
								$ddes[]=substr($detalles,strpos($detalles,"<descuento>")+strlen("<descuento>"),strpos($detalles,"</descuento>")-strlen("<descuento>")-strpos($detalles,"<descuento>"));
								$dpreciot[]=substr($detalles,strpos($detalles,"<precioTotalSinImpuesto>")+strlen("<precioTotalSinImpuesto>"),strpos($detalles,"</precioTotalSinImpuesto>")-strlen("<precioTotalSinImpuesto>")-strpos($detalles,"<precioTotalSinImpuesto>"));
								echo " ".strpos($detalles,"</detalle>"). "de .".strlen($detalles); exit;
								$detalles=substr($detalles,strpos($detalles,"</detalle>"),strlen($detalles)-strpos($detalles,"</detalle>"));
								if($cont>100)
									break;
							}while(strpos($detalles,"<detalle>")>0);	*/
							
												 $dadic[]=substr($infoAdicional,strpos($infoAdicional,">")+1,strpos($infoAdicional,"</campoAdicional>")-1-strpos($infoAdicional,">"));
							 $tmpad=substr($infoAdicional,strpos($infoAdicional,"<campoAdicional"),strpos($infoAdicional,"</campoAdicional>")-strpos($infoAdicional,"<campoAdicional"));
                           
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
                       
                     
						 $tmpad1=$tmpad=substr($infoAdicional,strpos($infoAdicional,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($infoAdicional)-strlen("</campoAdicional>")-strpos($infoAdicional,"</campoAdicional>"));
						 if( trim($tmpad1)!=''){
							$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));
							$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
						 }
							
                          
               $tmpad1=$tmpad=substr($tmpad1,strpos( $tmpad1,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($tmpad1)-strlen("</campoAdicional>")-strpos($tmpad1,"</campoAdicional>"));
			   if( trim($tmpad1)!=''){
				$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));

				$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
					$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
			   }
                    

						   //
                          $tmpad1=$tmpad=substr($tmpad1,strpos( $tmpad1,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($tmpad1)-strlen("</campoAdicional>")-strpos($tmpad1,"</campoAdicional>"));
                       if( trim($tmpad1)!=''){
				
						$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));
   
						$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
					   }
                         
							  
//

 $tmpad1=$tmpad=substr($tmpad1,strpos( $tmpad1,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($tmpad1)-strlen("</campoAdicional>")-strpos($tmpad1,"</campoAdicional>"));

      if( trim($tmpad1)!=''){
		echo 'A';
		$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));

		$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
		   $nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
	  }  
						    
						}else{			
							$impuestos=substr($xml,strpos($xml,"totalConImpuestos")+strlen("totalConImpuestos")+4,strpos($xml,"/totalConImpuestos")-strpos($xml,"totalConImpuestos")-(strlen("totalConImpuestos")+8));
							do{
								$cimp[]=substr($impuestos,strpos($impuestos,"codigoPorcentaje")+strlen("codigoPorcentaje")+4,strpos($impuestos,"/codigoPorcentaje")-strpos($impuestos,"codigoPorcentaje")-(strlen("codigoPorcentaje")+8));
								$bimp[]=substr($impuestos,strpos($impuestos,"baseImponible")+strlen("baseImponible")+4,strpos($impuestos,"/baseImponible")-strpos($impuestos,"baseImponible")-(strlen("baseImponible")+8));
								$vimp[]=substr($impuestos,strpos($impuestos,"valor")+strlen("valor")+4,strpos($impuestos,"/valor")-strpos($impuestos,"valor")-(strlen("valor")+8));
								$impuestos=substr($impuestos,strpos($impuestos,"/totalImpuesto")+4,strlen($impuestos)-strpos($impuestos,"/totalImpuesto")+4);
							}while(strpos($impuestos,"totalImpuesto")>0);
						}
						for($j=0;$j<=count($bimp)-1;$j++){
								if($cimp[$j]==0){
									$subtiva0=$bimp[$j];
									$iva0=$vimp[$j];
								}
								if($cimp[$j]>1 ){
									$subtiva=$bimp[$j];
									$iva=$vimp[$j];
								}	
								
						}		
						if($secuencial!=""){
							//$Ofact=new CMySQL1($conn,"select mfactura.Cliente_id,clientes.Cedula,mfactura.Fecha ,clientes.Nombres,mfactura.FechaAutorizacion,FormaPago,clientes.Correoe from mfactura,clientes where clientes.Id=mfactura.Cliente_id and mfactura.Id=?",array($factura));
							
							$Ofact=mysql_fetch_array(mysql_query("SELECT ventas.id_cliente as Cliente_id, clientes.cedula as Cedula, ventas.fecha_venta as Fecha , 
							concat(clientes.nombre,' ',clientes.apellido) as Nombres, ventas.FechaAutorizacion,id_forma_pago as FormaPago,clientes.email as 
							Correoe,empresa.leyenda as leyenda,empresa.leyenda2 as leyenda2,empresa.leyenda3 as leyenda3,empresa.leyenda4 as leyenda4,ventas.descripcion as descri,
							empresa.nombre as nombreComercial,empresa.email as email,empresa.imagen as imagen,emision.SOCIO as socio,ventas.id_vendedor as vendedor,empresa.telefono1,ventas.vendedor_id_tabla
							
							from ventas, clientes,empresa ,emision
							
							
							where clientes.id_cliente=ventas.id_cliente and ventas.id_venta=$factura
							and empresa.id_empresa=ventas.id_empresa and emision.id=codigo_lug "));
				// 			echo "SELECT ventas.id_cliente as Cliente_id, clientes.cedula as Cedula, ventas.fecha_venta as Fecha , 
				// 			concat(clientes.nombre,' ',clientes.apellido) as Nombres, ventas.FechaAutorizacion,id_forma_pago as FormaPago,clientes.email as 
				// 			Correoe,empresa.leyenda as leyenda,empresa.leyenda2 as leyenda2,empresa.leyenda3 as leyenda3,empresa.leyenda4 as leyenda4,ventas.descripcion as descri,
				// 			empresa.nombre as nombreComercial,empresa.email as email,empresa.imagen as imagen,emision.SOCIO as socio,ventas.id_vendedor as vendedor,empresa.telefono1,ventas.vendedor_id_tabla
							
				// 			from ventas, clientes,empresa ,emision
							
							
				// 			where clientes.id_cliente=ventas.id_cliente and ventas.id_venta=$factura
				// 			and empresa.id_empresa=ventas.id_empresa and emision.id=codigo_lug ";

							$vendedor_id_tabla  = $Ofact['vendedor_id_tabla'];
							$fechaAutorizacion=$Ofact['FechaAutorizacion'];
							$socio=$Ofact['vendedor'];
							$telefono_empresa =$Ofact['telefono1'];
							
						}
						$fffile=$tipoc."_".$ruc.$estab.$ptoEmi.$secuencial;
					//	echo "arch.".$fffile;exit;
						$ffile=$fffile.".xml";
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
						if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
							require_once(dirname(__FILE__).'/lang/eng.php');
							$pdf->setLanguageArray($l);
						}
						
						$pdf->SetFont('helvetica', '', 7);
						$pdf->AddPage();
						$style = array(
							'position' => '',
							'align' => 'C',
							'stretch' => false,
							'fitwidth' => true,
							'cellfitalign' => '',
							'border' => false,
							'hpadding' => 'auto',
							'vpadding' => 'auto',
							'fgcolor' => array(0,0,0),
							'bgcolor' => false, //array(255,255,255),
							'text' => true,
							'font' => 'helvetica',
							'fontsize' => 7,
							'stretchtext' => 4
						);
						

						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$style['position'] = 'R';
						

						$info = pathinfo($Ofact['imagen']);
						$formato = $info['extension'];
						

						$imagenPath = "archivos/" . $Ofact['imagen'];
						list($anchoOriginal, $altoOriginal) = getimagesize($imagenPath);
						$x1 = 96;
						
						// Ancho y alto deseados
						$anchoDeseado = 86;
						$altoDeseado = 28;
						
						// Calcular la relación de aspecto
						$relacionAspecto = $anchoOriginal / $altoOriginal;
						
						// Verificar si el ancho de la imagen es mayor que el espacio disponible
						if ($anchoOriginal > $anchoDeseado) {
							// Redimensionar el ancho al ancho deseado y ajustar el alto en consecuencia
							$anchoNuevo = $anchoDeseado;
							$altoNuevo = $anchoNuevo / $relacionAspecto;
						} else {
							// Si el ancho de la imagen es menor o igual, usar el tamaño original
							$anchoNuevo = $anchoOriginal;
							$altoNuevo = $altoOriginal;
						}
						
						// Verificar si la altura de la imagen es mayor que el espacio disponible
						if ($altoNuevo > $altoDeseado) {
							// Redimensionar la altura al alto deseado y ajustar el ancho en consecuencia
							$altoNuevo = $altoDeseado;
							$anchoNuevo = $altoNuevo * $relacionAspecto;
						}
						
						$anchoMinimo = 50; // Tamaño mínimo deseado para el ancho
						$altoMinimo = 28;  // Tamaño mínimo deseado para el alto
						
						// Verificar si la imagen es más pequeña que el mínimo deseado
						if ($anchoNuevo < $anchoMinimo || $altoNuevo < $altoMinimo) {
							// Aumentar el tamaño de la imagen para alcanzar el tamaño mínimo
							$anchoNuevo = max($anchoNuevo, $anchoMinimo);
							$altoNuevo = max($altoNuevo, $altoMinimo);
						}
						
						// Calcular la posición X para centrar la imagen entre x=20 y x1=96
						$posicionX = (20 + $x1 - $anchoNuevo) / 2;
						$posicionY = 10;
						
						$pdf->Image($imagenPath, $posicionX, $posicionY, $anchoNuevo, $altoNuevo, $formato, '', '', true, 150, '', false, false, 0, false, false, false);
						
						
						$y=0;
						
						
						$pdf->Cell(80,7, '','', 0, 'L',0);
						
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						
						$pdf->SetFont('helvetica','',7);
						$y3=$pdf->GetY();
						$pdf->Cell(5,7, "R.U.C. : ", '', 1, 'L',0);
						$pdf->SetFont('helvetica','B',7);
						$pdf->SetY($y3);
						$pdf->SetX(112);
						
						$pdf->Cell(75,7, $ruc, '', 1, 'L',0);
						$pdf->Cell(80,7, '','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->SetTextColor(0, 0, 0  ); 
						$pdf->Cell(91,7, strtoupper($tipoc)." :", '', 1, 'L',1);
						$pdf->SetTextColor(0, 0, 0  ); 
						
		
						
						
						
						
					$lrazon= strlen($Ofact['razonSocial']);
					$yActual = $pdf->GetY();
					$pdf->SetY($yActual+1);
					
						  
					if($lrazon>54){
						    $pdf->setFillColor(255, 255, 255  );
						    $pdf->SetFont('helvetica','B',7);
						    $pdf->MultiCell(86, 7, ''.$razonSocial, 0, 'C', 1, 1, '', '', true);
						    $pdf->Cell(9, 7, '', '', 0, 'L',0);
						    $pdf->SetY($yActual-2);	
					        $pdf->SetX(105);
					        $y3= $pdf->GetY();
					        $pdf->Cell(10,14, "Nro: ", '', 1, 'L',0);
					        $pdf->SetFont('helvetica','B',7);
						    $pdf->SetY($y3);
						 	$pdf->SetX(110);
					       // $pdf->Cell(80,7, "Nro: ".$estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
					       $pdf->Cell(60,14, $estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
						}else{
						    $pdf->setFillColor(255, 255, 255  );
						    $pdf->SetFont('helvetica','B',7);
						    $pdf->MultiCell(86, 7, ''.$razonSocial, 0, 'C', 1, 1, '', '', true);
						    $pdf->Cell(9, 7, '', '', 0, 'L',0);
						    $pdf->SetY($yActual);	
					        $pdf->SetX(105);
					        $pdf->Cell(80,7, "Nro: ".$estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
						 }
		
					$lnc= strlen($Ofact['nombreComercial']);
					$yActual = $pdf->GetY();
					$pdf->SetY($yActual);
					
						if($lnc>54){
						        $pdf->setFillColor(255, 255, 255  );
						    	$pdf->MultiCell(86, 7, ''.$Ofact['nombreComercial'], 0, 'C', 1, 1, '', '', true);
						    	$pdf->Cell(9, 7, '', '', 0, 'L',0);
						    	$pdf->SetY($yActual-2);	
					            $pdf->SetX(104);
						}else{
						        $pdf->setFillColor(255, 255, 255  );
						    	$pdf->MultiCell(86, 7, ''.$Ofact['nombreComercial'], 0, 'C', 1, 1, '', '', true);
						    	$pdf->Cell(9, 7, '', '', 0, 'L',0);
						    	$pdf->SetY($yActual);	
					            $pdf->SetX(104);
						}
						

						$pdf->Cell(80,7, "NÚMERO DE AUTORIZACIÓN", '', 1, 'L',0);
						//$pdf->MultiCell(80,7, "Dir. Matriz: ".$dirMatriz,0, 'L', 0, 0, '', '', false);
						$pdf->Cell(80,7,"Email: ".$Ofact['email'],'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7,$numeroAutorizacion, '', 1, 'L',0);
						$pdf->Cell(80,7, "Dir. Matríz: ".substr($dirMatriz,0,45),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);

						

						$lbla=($claveAcceso!=$numeroAutorizacion)?"FECHA AUTORIZACIÓN :":"";
						
				
						
						$pdf->Cell(80,7,  'FECHA AUTORIZACIÓN :', '', 1, 'L',0);
						$pdf->Cell(80,7,"Teléfono: ".$telefono_empresa,'', 0, 'L',0);
						
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7,  $fechaAutorizacion, '', 1, 'L',0);
						$pdf->Cell(80,7,"Contribuyente Especial Nro: NO",'', 0, 'L',0);
						
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$ambiente =($ambiente=='PRODUCCION')?'PRODUCCIÓN':$ambiente;
						$pdf->Cell(80,7,  "AMBIENTE : ".$ambiente, '', 1, 'L',0);
					

						$pdf->Cell(80,7,  "OBLIGADO A LLEVAR CONTABILIDAD: ".($obligadoContabilidad),'', 0, 'L',0);
				      
						
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, "EMISIÓN : ".$tipoEmision, '', 1, 'L',0);
						$despuesEmision = $pdf->GetY();
						$yFinalCabecera = $pdf->GetY();

						if($Ofact['leyenda']!=''){
							$pdf->MultiCell(80,7,  $Ofact['leyenda'],'', 0, 'L',0);
							$yFinalCabecera = $pdf->GetY()+7;
							}else{
								
								$pdf->Cell(80,7, $Ofact['leyenda'],'', 0, 'L',0);   
							} 
							
						$pdf->SetXY(104, $despuesEmision);
						$pdf->Cell(80,7, "CLAVE DE ACCESO:", '', 1, 'L',0);
						$despuesClave =  $pdf->GetY();

						if($Ofact['leyenda2']=='1'){
						    $retencion='Resolución Nro. NAC-DNCRASC20-00000001 ';
						}else{
						      $retencion='';
						}
						
						
						
				$pdf->SetY($yFinalCabecera);
				if($retencion!=''){
				    $pdf->MultiCell(80, 7, ''.$retencion, '0', 'L', false);
					$yFinalCabecera = $pdf->GetY();
				}else{
				 $pdf->Cell(80,7, $retencion,'', 0, 'L',0);   
				}

	
						
				$pdf->SetXY(104, $despuesClave);
					if($sesion_id_empresa=='467'){
					    
					    $pdf->write1DBarcode($claveAcceso, 'C39E', '', '', '',16, 0.11, $style, 'N');
					    
					    
					}	else{
					    $pdf->write1DBarcode($claveAcceso, 'C39', '', '', '',14, 0.11, $style, 'N');
					}
					$despuesClave =  $pdf->GetY();

					$pdf->SetXY(15, $yFinalCabecera);
					if($Ofact['leyenda3']!=''){
						$pdf->MultiCell(80, 7, ''.$Ofact['leyenda3'], '0', 'L', false);
						$yFinalCabecera = $pdf->GetY();
					}else{
					 $pdf->Cell(80,7, $Ofact['leyenda3'],'', 0, 'L',0);   
					}

					$pdf->SetXY(15, $yFinalCabecera);
				if( trim($Ofact['leyenda4']) != ''){
				    $pdf->MultiCell(80, 7, ''.$Ofact['leyenda4'], '0', 'L', false);
					$yFinalCabecera = $pdf->GetY();
				}else{
				 $pdf->Cell(80,7, $Ofact['leyenda4'],'', 0, 'L',0);   
				}

				$yFinalCabecera2 = ($yFinalCabecera > $despuesClave)?$yFinalCabecera :$despuesClave ;
						

			
				$pdf->SetY($yFinalCabecera2+7);	

						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));

					   // $pdf->Cell(135, 7, "Razón Social/Nombres y Apellidos: ".substr($razonSocialComprador,0,80),'LT', 0, 'L',0);
					   $antesRazon = $pdf->GetY();
					   
					   $pdf->Line(15, $antesRazon-2, 196, $antesRazon-2);

					   $pdf->MultiCell(135, 7, "Razón Social/Nombres y Apellidos: " .$razonSocialComprador, 0, 'L', false, 1);
					   $pdf->Cell(135, 7,"Fecha Emisión: ".$fechaEmision,'LB', 0, 'L',0);
					    $despuesEmision = $pdf->GetY();
					 $pdf->SetY($antesRazon);
					 	 $pdf->SetX(150);
                    $pdf->MultiCell(46, 7, "RUC/Cl: ".$identificacionComprador,  0, 'L', false, 1);
                     $pdf->SetY($despuesEmision);
                        
                    $pdf->Line(15, $antesRazon-2, 15, $despuesEmision+7);
                     $pdf->Line(196, $antesRazon-2, 196, $despuesEmision+7);
                      $pdf->Line(15, $despuesEmision+7, 196, $despuesEmision+7);
                      
				// 		$pdf->Cell(46, 7, "" , 'BR', 1, 'L',0);
				// 		$pdf->Cell(5, 7, '', '', 0, 'L',0);
					$pdf->Cell(80,7, '', '', 1, 'L',0);
						$pdf->Cell(80,7, '', '', 1, 'L',0);
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						// $pdf->RoundedRect(15, 40, 86, 80, 3.50, '0000');
						$pdf->RoundedRect(15, 40, 86, $yFinalCabecera-40, 3.50, '0000');
						$pdf->RoundedRect(103, 20, 93, $despuesClave-20, 3.50, '0000');
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
	
					$pdf->SetTextColor(0, 0, 0  );
						$pdf->Cell(20, 7, 'Código', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232 ); 
						$pdf->Cell(10, 7, 'Cant', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232 ); 
						$pdf->Cell(55, 7, 'Descripción', 1, 0, 'C',1);
						$pdf->Cell(30, 7, 'Detalle Adicional', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232 ); 
						
						$pdf->MultiCell(18, 7, 'Precio Unitario', 1, 'C', 1, 0, '', '', true);
				// 		$pdf->Cell(11, 7, 'Precio Unitario', 1, 0, 'R',1);
						$pdf->setFillColor(232, 232, 232 ); 
						$pdf->Cell(13, 7, 'Desc', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232 ); 
						$pdf->MultiCell(13, 7, 'Precio Total', 1, 'C', 1, 0, '', '', true);
				// 		$pdf->Cell(13, 7, 'Precio Total', 1, 0, 'R',1);
						$pdf->setFillColor(232, 232, 232 ); 
						$pdf->Cell(23, 7, 'Subtotal', 1, 1, 'C',1);
						$pdf->SetTextColor(0, 0, 0  );

						$cdet=(mysql_query("select productos.id_producto as  Id,productos.codigo as Codigo,productos.producto AS Nombre,
						detalle_ventas.cantidad as Cantidad,detalle_ventas.v_unitario AS Preciounitario, 
						detalle_ventas.descuento as Total,detalle_ventas.detalle as detalle,'' as Nombreproducto from detalle_ventas,productos
						where productos.id_producto=detalle_ventas.id_servicio and detalle_ventas.id_venta=$factura"));
						
						//$Odet=mysql_fetch_array($conn,"select productos.Id,productos.Codigo,productos.Nombre,dfactura.Cantidad,dfactura.Preciounitario,dfactura.Total,dfactura.Nombreproducto from dfactura,productos where productos.Id=dfactura.Producto_id and dfactura.Factura_id=?",array($factura));
						$cm=0;
						while($Odet=mysql_fetch_array($cdet)){
						    
                   $pdf->startTransaction();
// get the number of lines
$lines = $pdf->MultiCell(20, 0, $Odet['Codigo'], 0, 'L', 0, 0, '', '', true, 0, false,true, 0);

$lines2 = $pdf->MultiCell(55, 0, $Odet['Nombre'], 0, 'L', 0, 0, '', '', true, 0, false,true, 0);

$lin3 = $pdf->MultiCell(30, 0, ''.$Odet['detalle'], 0, 'L', 0, 0, '', '', true,0,false,true,0);

// restore previous object
$pdf = $pdf->rollbackTransaction();

$lines3= ($lines>$lines2)?$lines:$lines2;
$lines3 = ($lines3>$lin3)?$lines3:$lin3;
$alto=$lines3*3.2;

						    	//15,15,55,40,35,21
						    $pdf->Cell(20, $alto, $Odet['Codigo'],  1, 0, 'L',0);//$pdf->Cell(22, 7, $dcod[$i],  1, 0, 'L',0);
							$pdf->Cell(10, $alto, $Odet['Cantidad'],  1, 0, 'R',0);//$pdf->Cell(22, 7, $dcant[$i],  1, 0, 'R',0);
							
							if($lnom<100){
								$pdf->setFillColor(255, 255, 255  );
								$pdf->MultiCell(55, $alto, ''.$Odet['Nombre'], 1, 'L', 1, 0, '', '', true);
							}
							

							
							$pdf->MultiCell(30, $alto, ''.$Odet['detalle'], 1, 'L', 1, 0, '', '', true);
							
							$pdf->Cell(18, $alto, number_format($Odet['Preciounitario'],6),  1, 0, 'R',0);
							$pdf->Cell(13, $alto, number_format($Odet['Total'],2),  1, 0, 'R',0);
							$pdf->Cell(13, $alto, number_format($Odet['Preciounitario']-$Odet['Total'],2),  1, 0, 'R',0);
							
							if($lnom>=100){
								$pdf->Cell(21, $alto, number_format(($Odet['Preciounitario']-$Odet['Total'])*$Odet['Cantidad'],2), 1, 0, 'R',0);
								$pdf->setFillColor(255, 255, 255  );
								$pdf->MultiCell(72, $alto, ''.$Odet['Nombre'], 1, 'L', 1, 1, '', '', true);
							}else
								$pdf->Cell(23, $alto, number_format(($Odet['Preciounitario']-$Odet['Total'])*$Odet['Cantidad'],2), 1, 1, 'R',0);
						}
						
		  		$pdf->Cell(181, 7,'', '', 1, 'R',0);
	$inicioFila = $pdf->GetX();
	$anchoGeneral=6;	
	
    $pdf->Cell(126, $anchoGeneral, 'Información Adicional','LRTB', 0,'L',0);
    	$numero_pagina_actual = $pdf->getPage();
    		$inicioInformacionAdicional = $pdf->GetY();
    	$pdf->Cell(1, $anchoGeneral,'', '', 1, 'R',0);
    	
    
	$inicioFila2 = $pdf->GetX();
	$fila2= $pdf->GetY();
    
    $sql1= "SELECT 
	codigo_lug as codigo_lug,
	ambiente as ambiente,
	tipoEmision as tipoEmision, 
	empresa.nombre as nombreComercial, 
    empresa.razonSocial as razonSocial, 
    empresa.ruc as ruc, 
    ClaveAcceso as claveAcceso,
    tipo_documento as codDoc,
    establecimientos.codigo as estab,
emision.codigo as ptoEmi,
numero_factura_venta as secuencial,
empresa.direccion as dirMatriz,
empresa.clave as clave,
empresa.FElectronica as firma,
ventas.fecha_venta as fechaEmision,
clientes.apellido,
clientes.nombre,
clientes.direccion,
clientes.cedula,
clientes.estado,
clientes.caracter_identificacion,
empresa.Ocontabilidad,
empresa.FElectronica,
ventas.sub0,
ventas.sub12,
ventas.sub_total,
ventas.descuento,
impuestos.iva,
clientes.telefono,
clientes.email,
empresa.autorizacion_sri,
ventas.descripcion,
establecimientos.direccion as dirSucursal,
empresa.leyenda as rimpe,
empresa.leyenda2 as retencion,
ventas.propina as propina,
ventas.total as totalventas,
ventas.Autorizacion as numAutorizacion,
ventas.FechaAutorizacion as fechaAutorizacion,
emision.SOCIO as socio,
ventas.Vendedor_id as vendedor
	, ventas.`tipo_inco_term`, ventas.`lugar_inco_term`, ventas.`pais_origen`, ventas.`puerto_embarque`, ventas.`puerto_destino`, ventas.`pais_destino`, ventas.`pais_adquisicion`, ventas.`numero_dae`, ventas.`numero_transporte`, ventas.`flete_internacional`, ventas.`seguro_internaiconal`, ventas.`gastos_aduaneros`, ventas.`gastos_transporte`

from ventas,emision,empresa,establecimientos,clientes,impuestos 
WHERE impuestos.id_iva=ventas.id_iva AND   clientes.id_cliente=ventas.id_cliente AND  id_venta='".$factura."' 
and emision.id=codigo_lug and empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun 
and emision.id=ventas.codigo_lug";  
$result1 = mysql_query($sql1);


while($row1 = mysql_fetch_array($result1)){
$clienteDireccion= $row1['direccion'];
$clienteTelefono =  $row1['telefono'];
$clienteEmail = $row1['email'];

$pais_destino = $row1['pais_destino'];
$numero_dae  =  $row1['numero_dae'];
$numero_transporte =  $row1['numero_transporte'];
}
				
// 						$filasInfoAdiciona=	count($nadic);
// 				for ($z = 0; $z < $filasInfoAdiciona; $z++) {
//     $arrayInfo[$z][0] = $nadic[$z];
//     $arrayInfo[$z][1] = $dadic[$z];
  
//       $pdf->startTransaction();
// $lines =  $pdf->MultiCell(94, $anchoGeneral, "\n" . $dadic[$z], 'LRTB', 'L', 0);
// $pdf = $pdf->rollbackTransaction();
//     $pdf->SetY($fila2);
//     $pdf->SetX($inicioFila);

//     $nombreInfo = ($nadic[$z] == 'DIRECCION') ? 'DIRECCIÓN' : $nadic[$z];
    
//     // Establecer la altura de la celda
//     $alturaCelda = $anchoGeneral;
//     $alt_actual =$lines*3.5;
//     // Celda izquierda
//     $pdf->Cell(32, $anchoGeneral, $nombreInfo . " :", 'LRTB', 0, 'L', 0);

//     // Celda derecha
//     	$pdf->Cell(94, $anchoGeneral,  substr($dadic[$z],0,60),  'LRTB', 1, 'L',0);
						
						
//     // $pdf->MultiCell(94, $anchoGeneral,  , 'LRTB', 'L', 0);

//     // Actualizar la posición de la fila
//     $fila2 = max($pdf->GetY() , $fila2);
// }

    $pdf->Cell(32, $anchoGeneral, "Dirección :",  'LRTB', 0, 'L',0);
	$pdf->Cell(94, $anchoGeneral,  substr($clienteDireccion,0,60),  'LRTB', 1, 'L',1);
	
	$pdf->Cell(32, $anchoGeneral, "Teléfono :" ,  'LRTB', 0, 'L',0);
	$pdf->Cell(94, $anchoGeneral, substr($clienteTelefono ,0,60),  'LRTB', 1, 'L',0);
	
	$pdf->Cell(32, $anchoGeneral, "Correo :",'LRTB' , 0, 'L',0);
	$pdf->Cell(94, $anchoGeneral, substr($clienteEmail,0,60),  'LRTB', 1, 'L',0);

   $sqlFormasPago="SELECT `id`, `id_forma`, `documento`, `id_factura`, `id_empresa`, `valor`, `tipo`, `porcentaje`, `fecha_registro`, `numero_retencion`, `autorizacion`, `intervalo_cuotas` FROM `cobrospagos` WHERE documento='0' AND `id_factura` = '".$factura."'  ORDER BY `cobrospagos`.`id` ASC LIMIT 1";
				$resultFormasPago = mysql_query($sqlFormasPago);
				$plazo=0;
				$tipo = 0;
				while($rowFP = mysql_fetch_array($resultFormasPago) ){
				    	$plazo=$rowFP['intervalo_cuotas'];
				    	$tipo =$rowFP['tipo'];
				}
				$plazo = trim($plazo)==''?0:$plazo;
				$a=array("SIN UTILIZACION DEL SISTEMA FINANCIERO"=>"1","20 OTROS CON UTILIZACION DEL SISTEMA FINANCIERO"=>"2","CON UTILIZACION DEL SISTEMA FINANCIERO"=>"3","CHEQUE"=>"4","DINERO ELECTRONICO"=>"17","TARJETA DE CREDITO"=>"19","20 OTROS CON UTILIZACION DEL SISTEMA FINANCIERO"=>"20");
				foreach($a as $b=>$c)
					if($c==$Ofact['FormaPago'])
						$formp=$b;
				$formp=($formp=="")?"20 s":$formp;

				$pdf->SetX($inicioFila);
				$pdf->Cell(32, $anchoGeneral, 'FORMA DE PAGO:',  'LRTB', 0, 'L',0);
					$pdf->SetFont('helvetica','B',6);
				$pdf->Cell(60, $anchoGeneral, $formp,  'LRTB', 0, 'L',0);
				$pdf->Cell(19, $anchoGeneral, '$ '.$importeTotal,  'LRTB', 0, 'L',0);
				$pdf->Cell(11, $anchoGeneral, 'PLAZO:',  'LRTB', 0, 'L',0);
				$pdf->Cell(4, $anchoGeneral, $plazo,  'LRTB', 1, 'L',0);
					$pdf->SetFont('helvetica','B',7);
				
				$pdf->Cell(32, $anchoGeneral, 'NOTA:',  'LRTB', 0, 'L',0);
		
			$pdf->MultiCell(94, $anchoGeneral, ''.$Ofact['descri'] ,1, 'L', 0, '1','', '', true);

			if($vendedor_id_tabla!=0 && $vendedor_id_tabla!=''){
	    $sqlVendedor = "SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email` FROM `vendedores` WHERE id_vendedor=$vendedor_id_tabla";
	    $resultVendedor = mysql_query($sqlVendedor); 
	    while($rowVen = mysql_fetch_array($resultVendedor) ){
	        $pdf->Cell(32, $anchoGeneral, 'Vendedor:',  'LRTB', 0, 'L',0);
	        	$pdf->Cell(94, $anchoGeneral,  $rowVen['nombre'].' '.$rowVen['apellidos'],  'LRTB', 1, 'L',0);
		    
	    }
	}
				if($socio!="0"){
			    $resultTrans=mysql_query( "select Nombres,Cedula,Placa,regimen,emision,est from transportista where Id='".$socio."' ");

  	         //   $resultTrans2=mysql_fetch_array($resultTrans);
  	            while ($resultTrans22 = mysql_fetch_array($resultTrans)) {
                            
                            $pdf->Cell(32, $anchoGeneral, 'Punto de emisión:',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['emision'], 'LRTB', 1, 'R',0); 
							$pdf->Cell(32, $anchoGeneral, 'Razón Social :',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['Nombres'], 'LRTB', 1, 'R',0); 	      
							$pdf->Cell(32, $anchoGeneral, 'Ruc :',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['Cedula'], 'LRTB', 1, 'R',0); 	 
							$pdf->Cell(32, $anchoGeneral, 'Régimen :',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['regimen'], 'LRTB', 1, 'R',0); 
							$pdf->Cell(32, $anchoGeneral, 'Placa :',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['Placa'], 'LRTB', 1, 'R',0); 	       

  	           
  	            }
			}
							
							
							
							
							//$fh = fopen('../facturas/'.$fffile.'.pdf', 'w') or die("Se produjo un error al crear el archivo");
							//fclose($fh);
				// 			echo '../facturas/'.$fffile.'.pdf', 'F';
				
				
						
	            $inicioFila=15;
				
				
				$sqlIA="SELECT `id_info_adicional`, `campo`, `descripcion`, `id_venta`, `id_empresa`,xml FROM `info_adicional` WHERE  id_venta=$factura ";
				$resultIA = mysql_query($sqlIA);
				while($rowIA = mysql_fetch_array($resultIA) ){
				   
    				
    			if($rowIA['campo']!='DIRECCION' && $rowIA['campo']!='TELEFONO' && $rowIA['campo']!='EMAIL'){
    			     
    				$pdf->SetX($inicioFila);
    			    	$pdf->Cell(32, $anchoGeneral, $rowIA['campo'].' :',  'LRTB', 0, 'L',0);
    				// $pdf->MultiCell(91, $alto2, $rowIA['descripcion'], 'LRTB', 1, 'L', false);
    				$pdf->Cell(94, $anchoGeneral, $rowIA['descripcion'],  'LRTB', 1, 'L',0);
    				// $fila2= $pdf->GetY();
    			}
    				
    
    			
				}
					$inicioFila2=141;
					
					$pdf->setPage($numero_pagina_actual );
		$pdf->SetY($inicioInformacionAdicional);
					$sqlDetalleV = "SELECT impuestos.id_iva, impuestos.codigo, impuestos.iva, detalle_ventas.`id_detalle_venta`, detalle_ventas.`idBodega`, detalle_ventas.`idBodegaInventario`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`, detalle_ventas.`descuento`, SUM(detalle_ventas.`v_total`) AS base_imponible, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`detalle`, detalle_ventas.`id_kardex`, detalle_ventas.`tipo_venta`, detalle_ventas.`id_empresa`,  detalle_ventas.`tarifa_iva`, SUM(detalle_ventas.`total_iva`) as suma_iva FROM `detalle_ventas` INNER JOIN impuestos ON impuestos.id_iva = detalle_ventas.tarifa_iva WHERE detalle_ventas.id_venta = '".$factura."'  GROUP BY impuestos.id_iva ";
					$resultDetVenta = mysql_query( $sqlDetalleV );
					 $array_ivas= array ();
					while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
						$subT12 = $rowDetVent['base_imponible'];

						
						$pdf->SetX($inicioFila2);
						$pdf->Cell(35, $anchoGeneral, 'Subtotal '.$rowDetVent['iva'].' % :',  'LRTB', 0, 'L',0);
								$pdf->Cell(21, $anchoGeneral, number_format($subT12,2), 'LRTB', 1, 'R',0);
								
					    $clave = $rowDetVent['tarifa_iva'] ;  
                        $array_ivas[$clave][0]= $rowDetVent['iva'];
                        $array_ivas[$clave][1] = floatval($rowDetVent['suma_iva']);
                    
                    
					}		
// 					$pdf->SetY($inicioInformacionAdicional);
// 					$pdf->SetX($inicioFila2);
// 		$pdf->Cell(35, $anchoGeneral, 'Subtotal 15% :',  'LRTB', 0, 'L',0);
// 							$pdf->Cell(21, $anchoGeneral, number_format($subtiva,2), 'LRTB', 1, 'R',0);
// 				// 			$filaSub12= $pdf->GetY();

// 				$pdf->SetX($inicioFila2);
// 				$pdf->Cell(35, $anchoGeneral, 'Subtotal 0% :',  'LRTB', 0, 'L',0);
// 				$pdf->Cell(21, $anchoGeneral,number_format($subtiva0,2), 'LRTB', 1, 'R',0);
				// $filaSub0= $pdf->GetY();

				$pdf->SetX($inicioFila2);
				$pdf->Cell(35, $anchoGeneral, 'Subtotal sin impuestos :',  'LRTB', 0, 'L',0);
				$pdf->Cell(21, $anchoGeneral,number_format($totalSinImpuestos,2), 'LRTB', 1, 'R',0);
				// $filaSubSinImp= $pdf->GetY();

            foreach ($array_ivas as $key => $value) {
                if($value[1]>0 ){
                    $pdf->SetX($inicioFila2);	
                	$pdf->Cell(35, $anchoGeneral,  utf8_decode("IVA ".$value[0]." %:" ),  'LRTB', 0, 'L',0);
                	$pdf->Cell(21, $anchoGeneral,number_format($value[1],2), 'LRTB', 1, 'R',0);
                }
            
            } 
				// $pdf->SetX($inicioFila2);
				// $pdf->Cell(35, $anchoGeneral, 'IVA :',  'LRTB', 0, 'L',0);
				// $pdf->Cell(21, $anchoGeneral, number_format($iva,2), 'LRTB', 1, 'R',0);
			

				$pdf->SetX($inicioFila2);
				$pdf->Cell(35, $anchoGeneral, 'Propina :',  'LRTB', 0, 'L',0);
				$pdf->Cell(21, $anchoGeneral, number_format($propina,2), 'LRTB', 1, 'R',0);

				$pdf->SetX($inicioFila2);
				$pdf->Cell(35, $anchoGeneral, 'Descuento :',  'LRTB', 0, 'L',0);
				$pdf->Cell(21, $anchoGeneral, number_format($totalDescuento,2), 'LRTB', 1, 'R',0);
			
				$pdf->SetX($inicioFila2);
							$pdf->Cell(35, $anchoGeneral, 'Total :',  'LRTB', 0, 'L',0);
							$pdf->Cell(21, $anchoGeneral, number_format($importeTotal+$adicionales,2), 'LRTB', 1, 'R',0);	
// 			$pdf->Output('estes.pdf', 'F');
							$pdf->Output('../facturas/'.$fffile.'.pdf', 'F');
							//echo 'facturas/'.$fffile.'.pdf';exit;
				
  break;
 			
 
 
   case "comprobanteRetencion":
		$tcomprobante="COMPROBANTE DE RETENCION"; 			
				$cimp=$bimp=$vimp=array();
						$dcod=$ddes=$dcant=$dprecio=$ddes=$dpreciot=array();
						$nadic=$dadic=array();	
						if(strpos($xml,"<razonSocial>")>0){
							$razonSocial= substr($xml,strpos($xml,"<razonSocial>")+strlen("<razonSocial>"),strpos($xml,"</razonSocial>")-strlen("<razonSocial>")-strpos($xml,"<razonSocial>"));
							$nombreComercial= substr($xml,strpos($xml,"<nombreComercial>")+strlen("<nombreComercial>"),strpos($xml,"</nombreComercial>")-strlen("<nombreComercial>")-strpos($xml,"<nombreComercial>"));
							$numeroAutorizacion='';
							if (strpos($xml, "<numeroAutorizacion>") !== false) {
							    	$numeroAutorizacion= substr($xml,strpos($xml,"<numeroAutorizacion>")+strlen("<numeroAutorizacion>"),strpos($xml,"</numeroAutorizacion>")-strlen("<numeroAutorizacion>")-strpos($xml,"<numeroAutorizacion>"));
							}
				// 		$fechaAutorizacion='';
				// 		if (strpos($xml, "<fechaAutorizacion>") !== false) {
						    $fechaAutorizacion=substr($xml,strpos($xml,"<fechaAutorizacion")+strlen("<fechaAutorizacion>"),strpos($xml,"</fechaAutorizacion>")-strlen("<fechaAutorizacion>")-strpos($xml,"<fechaAutorizacion"));
							$fechaAutorizacion=substr($fechaAutorizacion,strpos($fechaAutorizacion,">")+1,strlen($fechaAutorizacion));
				// 		}
						
							$ruc= (substr($xml,strpos($xml,"<ruc>")+strlen("<ruc>"),strpos($xml,"</ruc>")-strlen("<ruc>")-strpos($xml,"<ruc>")));
							$ambiente= substr($xml,strpos($xml,"<ambiente>")+strlen("<ambiente>"),strpos($xml,"</ambiente>")-strlen("<ambiente>")-strpos($xml,"<ambiente>"));
				// 			$ambiente= substr($xml,strpos($xml,"<ambiente>")+strlen("<ambiente>"),strpos($xml,"</ambiente>")-strlen("<ambiente>")-strpos($xml,"<ambiente>"));
							
							$ambiente=((int)trim($ambiente)==2)?"PRODUCCION":"PRUEBAS";
							$claveAcceso= substr($xml,strpos($xml,"<claveAcceso>")+strlen("<claveAcceso>"),strpos($xml,"</claveAcceso>")-strlen("<claveAcceso>")-strpos($xml,"<claveAcceso>"));
							$estab= substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
							$tipoEmision= substr($xml,strpos($xml,"<tipoEmision>")+strlen("<tipoEmision>"),strpos($xml,"</tipoEmision>")-strlen("<tipoEmision>")-strpos($xml,"<tipoEmision>"));
							$tipoEmision=($tipoEmision==1)?"NORMAL":"";
							$ptoEmi= substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
							$secuencial= substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));
							$dirMatriz= substr($xml,strpos($xml,"<dirMatriz>")+strlen("<dirMatriz>"),strpos($xml,"</dirMatriz>")-strlen("<dirMatriz>")-strpos($xml,"<dirMatriz>"));
							$fechaEmision= substr($xml,strpos($xml,"<fechaEmision>")+strlen("<fechaEmision>"),strpos($xml,"</fechaEmision>")-strlen("<fechaEmision>")-strpos($xml,"<fechaEmision>"));
							$fechaEmision= substr($fechaEmision,6,4)."-".substr($fechaEmision,3,2)."-".substr($fechaEmision,0,2);
							$dirEstablecimiento= substr($xml,strpos($xml,"<dirEstablecimiento>")+strlen("<dirEstablecimiento>"),strpos($xml,"</dirEstablecimiento>")-strlen("<dirEstablecimiento>")-strpos($xml,"<dirEstablecimiento>"));
							$obligadoContabilidad= substr($xml,strpos($xml,"<obligadoContabilidad>")+strlen("<obligadoContabilidad>"),strpos($xml,"</obligadoContabilidad>")-strlen("<obligadoContabilidad>")-strpos($xml,"<obligadoContabilidad>"));
						    
            				if(strpos($xml, "<agenteRetencion>")!="")
            				$agenteRetencion= substr($xml,strpos($xml,"<agenteRetencion>")+strlen("<agenteRetencion>"),strpos($xml,"</agenteRetencion>")-strlen("<agenteRetencion>")-strpos($xml,"<agenteRetencion>"));
            				if(strpos($xml, "<contribuyenteRimpe>")!="")
            				$contribuyenteRimpe= substr($xml,strpos($xml,"<contribuyenteRimpe>")+strlen("<contribuyenteRimpe>"),strpos($xml,"</contribuyenteRimpe>")-strlen("<contribuyenteRimpe>")-strpos($xml,"<contribuyenteRimpe>"));

				// 			$contribuyenteRimpe= substr($xml,strpos($xml,"<contribuyenteRimpe>")+strlen("<contribuyenteRimpe>"),strpos($xml,"</contribuyenteRimpe>")-strlen("<contribuyenteRimpe>")-strpos($xml,"<contribuyenteRimpe>"));
//  echo "rimpe==".$contribuyenteRimpe;
							$tipoIdentificacionSujetoRetenido= substr($xml,strpos($xml,"<tipoIdentificacionSujetoRetenido>")+strlen("<tipoIdentificacionSujetoRetenido>"),strpos($xml,"</tipoIdentificacionSujetoRetenido>")-strlen("<tipoIdentificacionSujetoRetenido>")-strpos($xml,"<tipoIdentificacionSujetoRetenido>"));
							$razonSocialSujetoRetenido= substr($xml,strpos($xml,"<razonSocialSujetoRetenido>")+strlen("<razonSocialSujetoRetenido>"),strpos($xml,"</razonSocialSujetoRetenido>")-strlen("<razonSocialSujetoRetenido>")-strpos($xml,"<razonSocialSujetoRetenido>"));
							$identificacionSujetoRetenido= substr($xml,strpos($xml,"<identificacionSujetoRetenido>")+strlen("<identificacionSujetoRetenido>"),strpos($xml,"</identificacionSujetoRetenido>")-strlen("<identificacionSujetoRetenido>")-strpos($xml,"<identificacionSujetoRetenido>"));
							$periodoFiscal= substr($xml,strpos($xml,"<periodoFiscal>")+strlen("<periodoFiscal>"),strpos($xml,"</periodoFiscal>")-strlen("<periodoFiscal>")-strpos($xml,"<periodoFiscal>"));
							$impuestos=substr($xml,strpos($xml,"<impuestos>"),strpos($xml,"</impuestos>")-strpos($xml,"<impuestos>"));
							$impuestos=substr($impuestos,12,strlen($impuestos));

							$cont=0;
							do{
								$cont++;
								//echo $impuestos." ";
								$dcod[]=substr($impuestos,strpos($impuestos,"<codigo>")+strlen("<codigo>"),strpos($impuestos,"</codigo>")-strlen("<codigo>")-strpos($impuestos,"<codigo>"));
								$dcodret[]=substr($impuestos,strpos($impuestos,"<codigoRetencion>")+strlen("<codigoRetencion>"),strpos($impuestos,"</codigoRetencion>")-strlen("<codigoRetencion>")-strpos($impuestos,"<codigoRetencion>"));
								$dbimp[]=substr($impuestos,strpos($impuestos,"<baseImponible>")+strlen("<baseImponible>"),strpos($impuestos,"</baseImponible>")-strlen("<baseImponible>")-strpos($impuestos,"<baseImponible>"));
								$dpret[]=substr($impuestos,strpos($impuestos,"<porcentajeRetener>")+strlen("<porcentajeRetener>"),strpos($impuestos,"</porcentajeRetener>")-strlen("<porcentajeRetener>")-strpos($impuestos,"<porcentajeRetener>"));
								$dvret[]=substr($impuestos,strpos($impuestos,"<valorRetenido>")+strlen("<valorRetenido>"),strpos($impuestos,"</valorRetenido>")-strlen("<valorRetenido>")-strpos($impuestos,"<valorRetenido>"));
								$dcsus[]=substr($impuestos,strpos($impuestos,"<codDocSustento>")+strlen("<codDocSustento>"),strpos($impuestos,"</codDocSustento>")-strlen("<codDocSustento>")-strpos($impuestos,"<codDocSustento>"));
								$dndsus[]=substr($impuestos,strpos($impuestos,"<numDocSustento>")+strlen("<numDocSustento>"),strpos($impuestos,"</numDocSustento>")-strlen("<numDocSustento>")-strpos($impuestos,"<numDocSustento>"));
								$dfesus[]=substr($impuestos,strpos($impuestos,"<fechaEmisionDocSustento>")+strlen("<fechaEmisionDocSustento>"),strpos($impuestos,"</fechaEmisionDocSustento>")-strlen("<fechaEmisionDocSustento>")-strpos($impuestos,"<fechaEmisionDocSustento>"));
								$impuestos=substr($impuestos,strpos($impuestos,"</impuesto>")+11,strlen($impuestos)-strpos($impuestos,"</impuesto>"));
								//substr($impuestos,strpos($impuestos,"</impuesto>"),strlen($impuestos)-strpos($impuestos,"</impuesto>"));
								
								
								if($cont>20)
									break;
							}while(strpos($impuestos,"<impuesto>")>0);
							
							$infoAdicional=substr($xml,strpos($xml,"<infoAdicional>")+strlen("<infoAdicional>"),strpos($xml,"</infoAdicional>")-+strlen("<infoAdicional>")-strpos($xml,"<infoAdicional>"));
							$dadic[]=substr($infoAdicional,strpos($infoAdicional,">")+1,strpos($infoAdicional,"</campoAdicional>")-1-strpos($infoAdicional,">"));
							$tmpad=substr($infoAdicional,strpos($infoAdicional,"<campoAdicional"),strpos($infoAdicional,"</campoAdicional>")-strpos($infoAdicional,"<campoAdicional"));
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
							$tmpad1=$tmpad=substr($infoAdicional,strpos($infoAdicional,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($infoAdicional)-strlen("</campoAdicional>")-strpos($infoAdicional,"</campoAdicional>"));
							$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));
							$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
	
			
						
						if($secuencial!=""){

						}
						$fffile=$tipoc."_".$ruc.$estab.$ptoEmi.$secuencial;
						$ffile=$fffile.".xml";
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
						if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
							require_once(dirname(__FILE__).'/lang/eng.php');
							$pdf->setLanguageArray($l);
						}
						$pdf->SetFont('helvetica', '', 7);
						$pdf->AddPage();
						$style = array(
							'position' => '',
							'align' => 'C',
							'stretch' => false,
							'fitwidth' => true,
							'cellfitalign' => '',
							'border' => false,
							'hpadding' => 'auto',
							'vpadding' => 'auto',
							'fgcolor' => array(0,0,0),
							'bgcolor' => false, //array(255,255,255),
							'text' => true,
							'font' => 'helvetica',
							'fontsize' => 7,
							'stretchtext' => 4
						);
						
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$style['position'] = 'R';
						
						$Ofact=mysql_fetch_array(mysql_query("SELECT empresa.imagen as imagen, empresa.telefono1
							from mcretencion, compras, empresa 
						where mcretencion.id=$factura and mcretencion.Factura_id=compras.id_compra and compras.id_empresa=empresa.id_empresa"));
						
						$telefono_empresa =$Ofact['telefono1'];


						$info = pathinfo($Ofact['imagen']);
						$formato = $info['extension'];
						

						$imagenPath = "archivos/" . $Ofact['imagen'];
						if (file_exists($imagenPath)) {
							list($anchoOriginal, $altoOriginal) = getimagesize($imagenPath);
						$x1 = 96;
						
						// Ancho y alto deseados
						$anchoDeseado = 86;
						$altoDeseado = 28;
						
						// Calcular la relación de aspecto
						$relacionAspecto = $anchoOriginal / $altoOriginal;
						
						// Verificar si el ancho de la imagen es mayor que el espacio disponible
						if ($anchoOriginal > $anchoDeseado) {
							// Redimensionar el ancho al ancho deseado y ajustar el alto en consecuencia
							$anchoNuevo = $anchoDeseado;
							$altoNuevo = $anchoNuevo / $relacionAspecto;
						} else {
							// Si el ancho de la imagen es menor o igual, usar el tamaño original
							$anchoNuevo = $anchoOriginal;
							$altoNuevo = $altoOriginal;
						}
						
						// Verificar si la altura de la imagen es mayor que el espacio disponible
						if ($altoNuevo > $altoDeseado) {
							// Redimensionar la altura al alto deseado y ajustar el ancho en consecuencia
							$altoNuevo = $altoDeseado;
							$anchoNuevo = $altoNuevo * $relacionAspecto;
						}
						
						$anchoMinimo = 50; // Tamaño mínimo deseado para el ancho
						$altoMinimo = 28;  // Tamaño mínimo deseado para el alto
						
						// Verificar si la imagen es más pequeña que el mínimo deseado
						if ($anchoNuevo < $anchoMinimo || $altoNuevo < $altoMinimo) {
							// Aumentar el tamaño de la imagen para alcanzar el tamaño mínimo
							$anchoNuevo = max($anchoNuevo, $anchoMinimo);
							$altoNuevo = max($altoNuevo, $altoMinimo);
						}
						
						// Calcular la posición X para centrar la imagen entre x=20 y x1=96
						$posicionX = (20 + $x1 - $anchoNuevo) / 2;
						$posicionY = 10;
						
						$pdf->Image($imagenPath, $posicionX, $posicionY, $anchoNuevo, $altoNuevo, $formato, '', '', true, 150, '', false, false, 0, false, false, false);
						} 
						
	
						$y=0;
						$pdf->Cell(80,7, '','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, "R.U.C. : ".$ruc, '', 1, 'L',0);
						
						$pdf->Cell(80,7, '','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->SetTextColor(0, 0, 0  ); 
						$tipoDocumento = (strtoupper($tipoc)=='COMPROBANTERETENCION')?'COMPROBANTE DE RETENCIÓN':strtoupper($tipoc);

						$pdf->Cell(91,7, $tipoDocumento." :", '', 1, 'L',1);
						$pdf->SetTextColor(0, 0, 0  ); 
						$pdf->MultiCell(80,7, $razonSocial,0, 'L', 0, 0, '', '', false);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						
						$pdf->Cell(80,7, "Nro: ".$estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
						//$$pdf->MultiCell(80,7, "",0, 'L', 0, 0, '', '', false);
						$pdf->Cell(80,7, "",'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, "NÚMERO DE AUTORIZACIÓN", '', 1, 'L',0);

						//$pdf->MultiCell(80,7, "Dir. Matriz: ".$dirMatriz,0, 'L', 0, 0, '', '', false);
						$pdf->Cell(80,7, "Dir. Matríz: ".substr($dirMatriz,0,45),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7,$numeroAutorizacion, '', 1, 'L',0);
						$despuesAutorizacion = $pdf->GetY();

						$pdf->SetX(104);
							$lbla="FECHA AUTORIZACIÓN :";
				// 		if($claveAcceso!=$numeroAutorizacion){
							
				// 			$fechaAutorizacion=$fechaAutorizacion;
				// 		}else{
						
				// 			$fechaAutorizacion='';
				// 		}
					
							$pdf->Cell(80,7,  $lbla, '', 1, 'L',0);
							$pdf->SetX(104);
							$pdf->Cell(80,7,  $fechaAutorizacion, '', 1, 'L',0);
							
						$ambiente =($ambiente=='PRODUCCION')?'PRODUCCIÓN':$ambiente;
						$pdf->SetX(104);
						$pdf->Cell(80,7,  "AMBIENTE : ".$ambiente, '', 1, 'L',0);
						$pdf->SetX(104);
						$pdf->Cell(80,7, "EMISIÓN : ".$tipoEmision, '', 1, 'L',0);
						$pdf->SetX(104);
						$pdf->Cell(80,7, "CLAVE DE ACCESO:", '', 1, 'L',0);
						$pdf->SetX(104);
						$pdf->write1DBarcode($claveAcceso, 'C39', '', '', '', 10, 0.1, $style, 'N');
						$yFinal2 = $pdf->GetY();
						$pdf->SetXY(15,$despuesAutorizacion);
						$pdf->Cell(80,7, "Dir. Sucursal: ".substr($dirEstablecimiento,0,43),'', 1, 'L',0);
						$pdf->Cell(80,7,"Teléfono: ".$telefono_empresa,'', 1, 'L',0);
						$pdf->Cell(80,7,"Contribuyente Especial Nro: ".$contribuyenteEspecial,'', 1, 'L',0);
						$pdf->Cell(80,7,  "OBLIGADO A LLEVAR CONTABILIDAD: ".($obligadoContabilidad),'', 1, 'L',0);
						
						if($agenteRetencion=='1'){
						    $agenteRetencion2='Resolución Nro. NAC-DNCRASC20-00000001';
							$pdf->Cell(80,7, $agenteRetencion2 ,'', 1, 'L',0);
						}else{
						    $agenteRetencion2='';
						}

                        if($contribuyenteRimpe!=''){
                            $pdf->Cell(80,7, $contribuyenteRimpe ,'', 1, 'L',0);
                        }

                        $yFinal = $pdf->GetY();
						$yFinal3= (  $yFinal >   $yFinal2)?  $yFinal :  $yFinal2;
						$pdf->SetY(  $yFinal3+7);
						
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$pdf->Cell(135, 7, "Razón Social/Nombres y Apellidos: ".substr(utf8_decode($razonSocialSujetoRetenido),0,50),'LT', 0, 'L',0);
						$pdf->Cell(46, 7, "RUC/Cl: ".$identificacionSujetoRetenido, 'TR', 1, 'L',0);
						$pdf->Cell(135, 7,"Fecha Emisión: ".$fechaEmision,'LB', 0, 'L',0);
						$pdf->Cell(46, 7, "" , 'BR', 1, 'L',0);
						
						$pdf->Cell(5, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, '', '', 1, 'L',0);
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$pdf->RoundedRect(15, 40, 86,  $yFinal-40, 3.50, '0000');
						$pdf->RoundedRect(103, 20, 93,  $yFinal2-20, 3.50, '0000');
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						
						$pdf->SetTextColor(0, 0, 0  );
						$pdf->Cell(22, 7, 'Comprobante', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->Cell(24, 7, 'Número', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->Cell(22, 7, 'Fecha Emisión', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->Cell(22, 7, 'Ejercicio Fiscal', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->Cell(22, 7, 'Base Imponible', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  ); 
						 
						$pdf->Cell(26, 7, 'Impuesto', 1, 0, 'C',1);
						$pdf->SetTextColor(0, 0, 0  );
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->Cell(20, 7, 'Porcentaje', 1, 0, 'C',1);
						$pdf->SetTextColor(0, 0, 0  );
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->Cell(22, 7, 'Valor Retenido', 1, 1, 'C',1);
						$pdf->SetTextColor(0, 0, 0  );
						$a=array("01"=>"Factura",
					 		"02"=>"Nota de venta",
							"03"=>"Liquidacion de Compras",
							"04"=>"Nota de Credito",
							"5"=>"Nota de Debito",
							"11"=>"Pasajes Emitidos por Empresas de Aviacion",
							"12"=>"Documentos Emitidos por Instituciones Financieras",
							"21"=>"Carta de Porte Aereo",
							"41"=>"Comprobante Emitido por reembolso",
							"43"=>"Liquidacion para explotacion y exploracion de Hidrocaarburos",
							"47"=>"Nota  de Credito por reembolso emitida por Intermendiario ",
							"48"=>"Nota de Debito por Reembolso emitida por Intermediario");
                          $suma_dvret = 0;
						for($i=0;$i<=count($dcod)-1;$i++){
						    
							$comprobante=$a[$dcsus[$i]];
							switch($dcod[$i]){
								case "1":
									$impuesto="Impuesto a la Renta";
								break;
								case "2":
									$impuesto="IVA ";
								break;
							}
							
							$pdf->Multicell(22, 7, $comprobante,  1, 0, 'L',0);
							$pdf->Cell(24, 7, $dndsus[$i],  1, 0, 'R',0);
							$pdf->Cell(22, 7, $dfesus[$i],  1, 0, 'L',0);
							$pdf->Cell(22, 7, $periodoFiscal,  1, 0, 'R',0);
							$pdf->Cell(22, 7, number_format($dbimp[$i],2), 1, 0, 'R',0);
							$pdf->Cell(26, 7, $impuesto,  1, 0, 'R',0);
							$pdf->Cell(20, 7, number_format($dpret[$i],2), 1, 0, 'R',0);
							$pdf->Cell(22, 7, number_format($dvret[$i],2), 1, 1, 'R',0);
							$suma_dvret += $dvret[$i];
							
						}
						
						
							$pdf->Cell(22, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(24, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(22, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(22, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(22, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(26, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(20, 7, 'Total Retenido',  'LRB', 0, 'L',0);
                            $pdf->Cell(22, 7, number_format($suma_dvret, 2), 'LRB', 1, 'R', 0);
							
							
							
							$pdf->Cell(32, 7, "Información Adicional",  '', 0, 'L',0);
							$pdf->Cell(12, 7, '',  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 0, 'L',0);
							$pdf->Cell(35, 7, '',  '', 0, 'L',0);
							$pdf->Cell(37, 7,"", '', 1, 'R',0);
							
							if($nadic[0] == 'DIRECCION'){
								$nombreInfo1 = 'DIRECCIÓN';
							}elseif($nadic[0] == 'TELEFONO'){
								$nombreInfo1 ='TELÉFONO';
							}else{
								$nombreInfo1 = $nadic[0];
							}

							$pdf->Cell(32, 7, $nombreInfo1." :",  '', 0, 'L',0);
							$pdf->Cell(12, 7, $dadic[0],  '', 0, 'L',0);
							$pdf->Cell(65, 7, '' ,  '', 0, 'L',0);
							$pdf->Cell(35, 7, '',  '', 0, 'L',0);
							$pdf->Cell(37, 7,"", '', 1, 'R',0);
							
							if($nadic[1] == 'DIRECCION'){
								$nombreInfo2 = 'DIRECCIÓN';
							}elseif($nadic[1] == 'TELEFONO'){
								$nombreInfo2 ='TELÉFONO';
							}else{
								$nombreInfo2 = $nadic[1];
							}
							$pdf->Cell(32, 7, $nombreInfo2." :",  '', 0, 'L',0);
							$pdf->Cell(12, 7, $dadic[1],  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 0, 'L',0);
							$pdf->Cell(35, 7, '',  '', 0, 'L',0);
							$pdf->Cell(37, 7, "", '', 1, 'R',0);
							$pdf->Cell(32, 7, $nadic[1]." :",  '', 0, 'L',0);
							$pdf->Cell(12, 7, $dadic[1],  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 0, 'L',0);
							$pdf->Cell(35, 7, '',  '', 0, 'L',0);
							$pdf->Cell(37, 7, "", '', 1, 'R',0);
							
							$pdf->Cell(32, 7, '',  '', 0, 'L',0);
							$pdf->Cell(12, 7, '',  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 0, 'L',0);
							$pdf->Cell(35, 7, '',  '', 0, 'L',0);
							$pdf->Cell(37, 7, "", '', 1, 'R',0);
							
							     //$pdf->Output('subidas/' . $fffile . '.pdf', 'F'); 
							$pdf->Output('../facturas/'.$fffile.'.pdf', 'F');//echo 'facturas/'.$fffile.'.pdf';exit;
				}
				
	break;
     case "notaCredito":
            $tcomprobante = "NOTA DE CREDITO";
            $cimp = $bimp = $vimp = array();
            $dcod = $ddes = $dcant = $dprecio = $ddes = $dpreciot = array();
            $nadic = $dadic = array();
            if (strpos($xml, "<razonSocial>") > 0) {
//                echo 'si correo';
                $razonSocial = substr($xml, strpos($xml, "<razonSocial>") + strlen("<razonSocial>"), strpos($xml, "</razonSocial>") - strlen("<razonSocial>") - strpos($xml, "<razonSocial>"));
                $nombreComercial = substr($xml, strpos($xml, "<nombreComercial>") + strlen("<nombreComercial>"), strpos($xml, "</nombreComercial>") - strlen("<nombreComercial>") - strpos($xml, "<nombreComercial>"));
                $numeroAutorizacion="";
				if(strpos($xml,"<numeroAutorizacion>")>0){
				     $numeroAutorizacion = substr($xml, strpos($xml, "<numeroAutorizacion>") + strlen("<numeroAutorizacion>"), strpos($xml, "</numeroAutorizacion>") - strlen("<numeroAutorizacion>") - strpos($xml, "<numeroAutorizacion>"));			    
				}
                 $fechaAutorizacion="";
				if(strpos($xml,"<fechaAutorizacion>")>0){
				    $fechaAutorizacion = substr($xml, strpos($xml, "<fechaAutorizacion") + strlen("<fechaAutorizacion>"), strpos($xml, "</fechaAutorizacion>") - strlen("<fechaAutorizacion>") - strpos($xml, "<fechaAutorizacion"));
                $fechaAutorizacion = substr($fechaAutorizacion, strpos($fechaAutorizacion, ">") + 1, strlen($fechaAutorizacion));
				}
                
                $ruc = (substr($xml, strpos($xml, "<ruc>") + strlen("<ruc>"), strpos($xml, "</ruc>") - strlen("<ruc>") - strpos($xml, "<ruc>")));
                $ambiente = substr($xml, strpos($xml, "<ambiente>") + strlen("<ambiente>"), strpos($xml, "</ambiente>") - strlen("<ambiente>") - strpos($xml, "<ambiente>"));
                $ambiente = ((int) trim($ambiente) == 2) ? "PRODUCCION" : "PRUEBAS";
                $claveAcceso = substr($xml, strpos($xml, "<claveAcceso>") + strlen("<claveAcceso>"), strpos($xml, "</claveAcceso>") - strlen("<claveAcceso>") - strpos($xml, "<claveAcceso>"));
                $estab = substr($xml, strpos($xml, "<estab>") + strlen("<estab>"), strpos($xml, "</estab>") - strlen("<estab>") - strpos($xml, "<estab>"));
                $tipoEmision = substr($xml, strpos($xml, "<tipoEmision>") + strlen("<tipoEmision>"), strpos($xml, "</tipoEmision>") - strlen("<tipoEmision>") - strpos($xml, "<tipoEmision>"));
                $tipoEmision = ($tipoEmision == 1) ? "NORMAL" : "";
                $ptoEmi = substr($xml, strpos($xml, "<ptoEmi>") + strlen("<ptoEmi>"), strpos($xml, "</ptoEmi>") - strlen("<ptoEmi>") - strpos($xml, "<ptoEmi>"));
                $secuencial = substr($xml, strpos($xml, "<secuencial>") + strlen("<secuencial>"), strpos($xml, "</secuencial>") - strlen("<secuencial>") - strpos($xml, "<secuencial>"));
                $dirMatriz = substr($xml, strpos($xml, "<dirMatriz>") + strlen("<dirMatriz>"), strpos($xml, "</dirMatriz>") - strlen("<dirMatriz>") - strpos($xml, "<dirMatriz>"));
                $fechaEmision = substr($xml, strpos($xml, "<fechaEmision>") + strlen("<fechaEmision>"), strpos($xml, "</fechaEmision>") - strlen("<fechaEmision>") - strpos($xml, "<fechaEmision>"));
                $fechaEmision = substr($fechaEmision, 6, 4) . "-" . substr($fechaEmision, 3, 2) . "-" . substr($fechaEmision, 0, 2);
                $dirEstablecimiento = substr($xml, strpos($xml, "<dirEstablecimiento>") + strlen("<dirEstablecimiento>"), strpos($xml, "</dirEstablecimiento>") - strlen("<dirEstablecimiento>") - strpos($xml, "<dirEstablecimiento>"));
                $obligadoContabilidad = substr($xml, strpos($xml, "<obligadoContabilidad>") + strlen("<obligadoContabilidad>"), strpos($xml, "</obligadoContabilidad>") - strlen("<obligadoContabilidad>") - strpos($xml, "<obligadoContabilidad>"));
                $codDocModificado = substr($xml, strpos($xml, "<codDocModificado>") + strlen("<codDocModificado>"), strpos($xml, "</codDocModificado>") - strlen("<codDocModificado>") - strpos($xml, "<codDocModificado>"));
                $numDocModificado = substr($xml, strpos($xml, "<numDocModificado>") + strlen("<numDocModificado>"), strpos($xml, "</numDocModificado>") - strlen("<numDocModificado>") - strpos($xml, "<numDocModificado>"));
                $fechaEmisionDocSustento = substr($xml, strpos($xml, "<fechaEmisionDocSustento>") + strlen("<fechaEmisionDocSustento>"), strpos($xml, "</fechaEmisionDocSustento>") - strlen("<fechaEmisionDocSustento>") - strpos($xml, "<fechaEmisionDocSustento>"));
//                echo 'si correo2';
                $razonSocialComprador = substr($xml, strpos($xml, "<razonSocialComprador>") + strlen("<razonSocialComprador>"), strpos($xml, "</razonSocialComprador>") - strlen("<razonSocialComprador>") - strpos($xml, "<razonSocialComprador>"));
                $identificacionComprador = substr($xml, strpos($xml, "<identificacionComprador>") + strlen("<identificacionComprador>"), strpos($xml, "</identificacionComprador>") - strlen("<identificacionComprador>") - strpos($xml, "<identificacionComprador>"));
                $totalSinImpuestos = substr($xml, strpos($xml, "<totalSinImpuestos>") + strlen("<totalSinImpuestos>"), strpos($xml, "</totalSinImpuestos>") - strlen("<totalSinImpuestos>") - strpos($xml, "<totalSinImpuestos>"));
                $totalDescuento = substr($xml, strpos($xml, "totalDescuento") + strlen("<totalDescuento>"), strpos($xml, "</totalDescuento>") - strlen("<totalDescuento>") - strpos($xml, "<totalDescuento>"));
                $motivo = substr($xml, strpos($xml, "<motivo>") + strlen("<motivo>"), strpos($xml, "</motivo>") - strlen("<motivo>") - strpos($xml, "<motivo>"));
                $contribuyenteEspecial = "";
                
                if (strpos($xml, "<contribuyenteEspecial>") > 0)
                    $contribuyenteEspecial = substr($xml, strpos($xml, "<contribuyenteEspecial>") + strlen("<contribuyenteEspecial>"), strpos($xml, "</contribuyenteEspecial>") - strlen("<contribuyenteEspecial>") - strpos($xml, "<contribuyenteEspecial>"));
                
                
				if(strpos($xml, "<agenteRetencion>")!="")
				$agenteRetencion= substr($xml,strpos($xml,"<agenteRetencion>")+strlen("<agenteRetencion>"),strpos($xml,"</agenteRetencion>")-strlen("<agenteRetencion>")-strpos($xml,"<agenteRetencion>"));
				if(strpos($xml, "<contribuyenteRimpe>")!="")
				$contribuyenteRimpe= substr($xml,strpos($xml,"<contribuyenteRimpe>")+strlen("<contribuyenteRimpe>"),strpos($xml,"</contribuyenteRimpe>")-strlen("<contribuyenteRimpe>")-strpos($xml,"<contribuyenteRimpe>"));

                
                $totalDescuento = substr($xml, strpos($xml, "<totalDescuento>") + strlen("<totalDescuento>"), strpos($xml, "</totalDescuento>") - strlen("<totalDescuento>") - strpos($xml, "<totalDescuento>"));
                $baseImponible = substr($xml, strpos($xml, "<baseImponible>") + strlen("<baseImponible>"), strpos($xml, "</baseImponible>") - strlen("<baseImponible>") - strpos($xml, "<baseImponible>"));
                $impuestos = substr($xml, strpos($xml, "<totalConImpuestos>"), strpos($xml, "</totalConImpuestos>") - strpos($xml, "<totalConImpuestos>"));
                $propina = substr($xml, strpos($xml, "<propina>") + strlen("<propina>"), strpos($xml, "</propina>") - strlen("<propina>") - strpos($xml, "<propina>"));
                $importeTotal = ($tipoc == "notaCredito") ? substr($xml, strpos($xml, "<valorModificacion>") + strlen("<valorModificacion>"), strpos($xml, "</valorModificacion>") - strlen("<valorModificacion>") - strpos($xml, "<valorModificacion>")) : substr($xml, strpos($xml, "<valorModificacion>") + strlen("<valorModificacion>"), strpos($xml, "</valorModificacion>") - strlen("<valorModificacion>") - strpos($xml, "<valorModificacion>"));
                $moneda = substr($xml, strpos($xml, "<moneda>") + strlen("<moneda>"), strpos($xml, "</moneda>") - strlen("<moneda>") - strpos($xml, "<moneda>"));
                $detalles = substr($xml, strpos($xml, "<detalles>") + strlen("<detalles>"), strpos($xml, "</detalles>") - strlen("<detalles>") - strpos($xml, "<detalles>"));
                // echo 'si correo3' . $impuestos;
                $infoAdicional = substr($xml, strpos($xml, "<infoAdicional>") + strlen("<infoAdicional>"), strpos($xml, "</infoAdicional>") - +strlen("<infoAdicional>") - strpos($xml, "<infoAdicional>"));
                
                // do {
                //     $cimp[] = substr($impuestos, strpos($impuestos, "<codigoPorcentaje>") + strlen("<codigoPorcentaje>"), strpos($impuestos, "</codigoPorcentaje>") - strlen("<codigoPorcentaje>") - strpos($impuestos, "<codigoPorcentaje>"));
                //     $bimp[] = substr($impuestos, strpos($impuestos, "<baseImponible>") + strlen("<baseImponible>"), strpos($impuestos, "</baseImponible>") - strlen("<baseImponible>") - strpos($impuestos, "<baseImponible>"));
                //     $vimp[] = substr($impuestos, strpos($impuestos, "<valor>") + strlen("<valor>"), strpos($impuestos, "</valor>") - strlen("<valor>") - strpos($impuestos, "<valor>"));
                //     $impuestos = substr($impuestos, strpos($impuestos, "</totalImpuesto>"), strlen($impuestos) - strpos($impuestos, "</totalImpuesto>"));
                // } while (strpos($impuestos, "<totalImpuesto>") > 0);
                		do{
								$cimp[]=substr($impuestos,strpos($impuestos,"<codigoPorcentaje>")+strlen("<codigoPorcentaje>"),strpos($impuestos,"</codigoPorcentaje>")-strlen("<codigoPorcentaje>")-strpos($impuestos,"<codigoPorcentaje>"));
								$bimp[]=substr($impuestos,strpos($impuestos,"<baseImponible>")+strlen("<baseImponible>"),strpos($impuestos,"</baseImponible>")-strlen("<baseImponible>")-strpos($impuestos,"<baseImponible>"));
								$vimp[]=substr($impuestos,strpos($impuestos,"<valor>")+strlen("<valor>"),strpos($impuestos,"</valor>")-strlen("<valor>")-strpos($impuestos,"<valor>"));
								$impuestos=substr($impuestos,strpos($impuestos,"</totalImpuesto>"),strlen($impuestos)-strpos($impuestos,"</totalImpuesto>"));
							
							    if($cont>3)
									break;
									$cont++;
							}while(strpos($impuestos,"<totalImpuesto>")>0);	
                $cont = 0;
                 $dadic[]=substr($infoAdicional,strpos($infoAdicional,">")+1,strpos($infoAdicional,"</campoAdicional>")-1-strpos($infoAdicional,">"));
							 $tmpad=substr($infoAdicional,strpos($infoAdicional,"<campoAdicional"),strpos($infoAdicional,"</campoAdicional>")-strpos($infoAdicional,"<campoAdicional"));
                           
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
                       
                     
						 $tmpad1=$tmpad=substr($infoAdicional,strpos($infoAdicional,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($infoAdicional)-strlen("</campoAdicional>")-strpos($infoAdicional,"</campoAdicional>"));
						 if( trim($tmpad1)!=''){
							$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));
							$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
						 }
							
                          
               $tmpad1=$tmpad=substr($tmpad1,strpos( $tmpad1,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($tmpad1)-strlen("</campoAdicional>")-strpos($tmpad1,"</campoAdicional>"));
			   if( trim($tmpad1)!=''){
				$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));

				$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
					$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
			   }
                    

						   //
                          $tmpad1=$tmpad=substr($tmpad1,strpos( $tmpad1,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($tmpad1)-strlen("</campoAdicional>")-strpos($tmpad1,"</campoAdicional>"));
                       if( trim($tmpad1)!=''){
				
						$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));
   
						$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
					   }
                         
							  
//

 $tmpad1=$tmpad=substr($tmpad1,strpos( $tmpad1,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($tmpad1)-strlen("</campoAdicional>")-strpos($tmpad1,"</campoAdicional>"));

      if( trim($tmpad1)!=''){
// 		echo 'A';
		$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));

		$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
		   $nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
	  }  

                // $dadic[] = substr($infoAdicional, strpos($infoAdicional, ">") + 1, strpos($infoAdicional, "</campoAdicional>") - 1 - strpos($infoAdicional, ">"));
                // $tmpad = substr($infoAdicional, strpos($infoAdicional, "<campoAdicional"), strpos($infoAdicional, "</campoAdicional>") - strpos($infoAdicional, "<campoAdicional"));
                // $nadic[] = substr($tmpad, strpos($tmpad, "nombre=") + 8, strpos($tmpad, ">") - 1 - strpos($tmpad, "nombre=") - 8);
                // $tmpad1 = $tmpad = substr($infoAdicional, strpos($infoAdicional, "</campoAdicional>") + strlen("</campoAdicional>"), strlen($infoAdicional) - strlen("</campoAdicional>") - strpos($infoAdicional, "</campoAdicional>"));
                // $dadic[] = substr($tmpad, strpos($tmpad, ">") + 1, strpos($tmpad, "</campoAdicional>") - 1 - strpos($tmpad, ">"));
                // $tmpad = substr($tmpad, strpos($tmpad, "<campoAdicional"), strpos($tmpad, "</campoAdicional>") - strpos($tmpad, "<campoAdicional"));
                // $nadic[] = substr($tmpad, strpos($tmpad, "nombre=") + 8, strpos($tmpad, ">") - 1 - strpos($tmpad, "nombre=") - 8);
            } else {
                $impuestos = substr($xml, strpos($xml, "totalConImpuestos") + strlen("totalConImpuestos") + 4, strpos($xml, "/totalConImpuestos") - strpos($xml, "totalConImpuestos") - (strlen("totalConImpuestos") + 8));
                do {
                    $cimp[] = substr($impuestos, strpos($impuestos, "codigoPorcentaje") + strlen("codigoPorcentaje") + 4, strpos($impuestos, "/codigoPorcentaje") - strpos($impuestos, "codigoPorcentaje") - (strlen("codigoPorcentaje") + 8));
                    $bimp[] = substr($impuestos, strpos($impuestos, "baseImponible") + strlen("baseImponible") + 4, strpos($impuestos, "/baseImponible") - strpos($impuestos, "baseImponible") - (strlen("baseImponible") + 8));
                    $vimp[] = substr($impuestos, strpos($impuestos, "valor") + strlen("valor") + 4, strpos($impuestos, "/valor") - strpos($impuestos, "valor") - (strlen("valor") + 8));
                    $impuestos = substr($impuestos, strpos($impuestos, "/totalImpuesto") + 4, strlen($impuestos) - strpos($impuestos, "/totalImpuesto") + 4);
                } while (strpos($impuestos, "totalImpuesto") > 0);
            }
//            echo 'si correo5';
            for ($j = 0; $j <= count($bimp) - 1; $j++) {
                if ($cimp[$j] == 0) {
                    $subtiva0 = $bimp[$j];
                    $iva0 = $vimp[$j];
                }
                if ($cimp[$j] == 2) {
                    $subtiva = $bimp[$j];
                    $iva = $vimp[$j];
                }
            }
            if ($secuencial != "") {
                //$Ofact=new CMySQL1($conn,"select mfactura.Cliente_id,clientes.Cedula,mfactura.Fecha ,clientes.Nombres,mfactura.FechaAutorizacion,FormaPago,clientes.Correoe from mfactura,clientes where clientes.Id=mfactura.Cliente_id and mfactura.Id=?",array($factura));
//                echo 'si correo1';
                $Ofact = mysql_fetch_array(mysql_query("SELECT  ventas.id_cliente as Cliente_id, clientes.cedula as Cedula, ventas.fecha_venta  as Fecha , concat(clientes.nombre,' ',clientes.apellido) as Nombres, ventas.FechaAutorizacion,'01' as FormaPago,clientes.email as Correoe from ventas, clientes  where clientes.id_cliente=ventas.id_cliente and ventas.id_venta=$factura"));
                $fechaAutorizacion = $Ofact['FechaAutorizacion'];
            }
            // echo 'si correo6';
            $fffile = $tipoc . "_" . $ruc . $estab . $ptoEmi . $secuencial;
//            	echo "arch.".$fffile;exit;
            $ffile = $fffile . ".xml";
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
            $pdf->SetFont('helvetica', '', 7);
            $pdf->AddPage();
            $style = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 7,
                'stretchtext' => 4
            );

            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $style['position'] = 'R';
            
            	$Ofact=mysql_fetch_array(mysql_query("SELECT empresa.imagen as imagen,ventas.id_empresa ,empresa.telefono1
							
							from empresa ,ventas
							
							where id_venta=$factura and empresa.id_empresa=ventas.id_empresa"));

		$telefono_empresa =$Ofact['telefono1'];

         $info = pathinfo($Ofact['imagen']);
						$formato = $info['extension'];
						

						$imagenPath = "archivos/" . $Ofact['imagen'];
						if (file_exists($imagenPath)) {
							list($anchoOriginal, $altoOriginal) = getimagesize($imagenPath);
						$x1 = 96;
						
						// Ancho y alto deseados
						$anchoDeseado = 86;
						$altoDeseado = 28;
						
						// Calcular la relación de aspecto
						$relacionAspecto = $anchoOriginal / $altoOriginal;
						
						// Verificar si el ancho de la imagen es mayor que el espacio disponible
						if ($anchoOriginal > $anchoDeseado) {
							// Redimensionar el ancho al ancho deseado y ajustar el alto en consecuencia
							$anchoNuevo = $anchoDeseado;
							$altoNuevo = $anchoNuevo / $relacionAspecto;
						} else {
							// Si el ancho de la imagen es menor o igual, usar el tamaño original
							$anchoNuevo = $anchoOriginal;
							$altoNuevo = $altoOriginal;
						}
						
						// Verificar si la altura de la imagen es mayor que el espacio disponible
						if ($altoNuevo > $altoDeseado) {
							// Redimensionar la altura al alto deseado y ajustar el ancho en consecuencia
							$altoNuevo = $altoDeseado;
							$anchoNuevo = $altoNuevo * $relacionAspecto;
						}
						
						$anchoMinimo = 50; // Tamaño mínimo deseado para el ancho
						$altoMinimo = 28;  // Tamaño mínimo deseado para el alto
						
						// Verificar si la imagen es más pequeña que el mínimo deseado
						if ($anchoNuevo < $anchoMinimo || $altoNuevo < $altoMinimo) {
							// Aumentar el tamaño de la imagen para alcanzar el tamaño mínimo
							$anchoNuevo = max($anchoNuevo, $anchoMinimo);
							$altoNuevo = max($altoNuevo, $altoMinimo);
						}
						
						// Calcular la posición X para centrar la imagen entre x=20 y x1=96
						$posicionX = (20 + $x1 - $anchoNuevo) / 2;
						$posicionY = 10;
						
						$pdf->Image($imagenPath, $posicionX, $posicionY, $anchoNuevo, $altoNuevo, $formato, '', '', true, 150, '', false, false, 0, false, false, false);
						} 


            $y = 0;
            $pdf->Cell(80, 7, '', '', 0, 'L', 0);
            $pdf->Cell(9, 7, '', '', 0, 'L', 0);
            $pdf->Cell(80, 7, "R.U.C. : " . $ruc, '', 1, 'L', 0);

            $pdf->Cell(80, 7, '', '', 0, 'L', 0);
            $pdf->Cell(9, 7, '', '', 0, 'L', 0);
			$pdf->setFillColor(232, 232, 232  ); 
            $pdf->SetTextColor(0, 0, 0);
			$tipoDocumento = (strtoupper($tipoc)=='NOTACREDITO')?'NOTA DE CRÉDITO':strtoupper($tipoc);
						
            $pdf->Cell(91, 7, $tipoDocumento . " :", '', 1, 'L', 1);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(80, 7, $razonSocial, 0, 'L', 0, 0, '', '', false);
            $pdf->Cell(9, 7, '', '', 0, 'L', 0);

            /* $pdf->Cell(80,7, "Nro: ".$estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
              //$$pdf->MultiCell(80,7, "",0, 'L', 0, 0, '', '', false);
              $pdf->Cell(80,7, "",'', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7, "NUMERO DE AUTORIZACION", '', 1, 'L',0);
              //$pdf->MultiCell(80,7, "Dir. Matriz: ".$dirMatriz,0, 'L', 0, 0, '', '', false);
              $pdf->Cell(80,7, "Dir. Matriz: ".substr($dirMatriz,0,45),'', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7,$numeroAutorizacion, '', 1, 'L',0);
              $pdf->Cell(80,7, "Dir. Sucursal: ".substr($dirEstablecimiento,0,43),'', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $lbla=($claveAcceso!=$numeroAutorizacion)?"FECHA AUTORIZACION :":"";
              $fechaAutorizacion=($claveAcceso!=$numeroAutorizacion)?$fechaAutorizacion:"";
              $pdf->Cell(80,7,  $lbla, '', 1, 'L',0);
              $pdf->Cell(80,7,"Contribuyente Especial Nro: ".$contribuyenteEspecial,'', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7,  $fechaAutorizacion, '', 1, 'L',0);
              $pdf->Cell(80,7,  "OBLIGADO A LLEVAR CONTABILIDAD: ".($obligadoContabilidad),'', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7,  "AMBIENTE : ".$ambiente, '', 1, 'L',0);
              $pdf->Cell(80,7, 'Agente De Retencion NAC-DNCRASC20-00000001','', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7, "EMISION : ".$tipoEmision, '', 1, 'L',0);
              $pdf->Cell(80,7, '','', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7, "CLAVE DE ACCESO:", '', 1, 'L',0);
              $pdf->write1DBarcode($claveAcceso, 'C39', '', '', '', 10, 0.1, $style, 'N');
              $pdf->Cell(80,7, '','', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7, '', '', 1, 'L',0);
              $pdf->Cell(80,7, '','', 0, 'L',0);
              $pdf->Cell(9, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7, '', '', 1, 'L',0);
              $pdf->Cell(80,7, '','', 0, 'L',0);
              $pdf->Cell(5, 7, '', '', 0, 'L',0);
              $pdf->Cell(80,7, '', '', 1, 'L',0); */
            $pdf->Cell(80, 7, "Nro: " . $estab . "-" . $ptoEmi . "-" . $secuencial, '', 1, 'L', 0);
            $lnc= strlen($Ofact['nombreComercial']);
					$yActual = $pdf->GetY();
					$pdf->SetY($yActual);
					
						if($lnc>54){
						        $pdf->setFillColor(255, 255, 255  );
						    	$pdf->MultiCell(86, 7, ''.$Ofact['nombreComercial'], 0, 'C', 1, 1, '', '', true);
						    	$pdf->Cell(9, 7, '', '', 0, 'L',0);
						    	$pdf->SetY($yActual-2);	
					            $pdf->SetX(104);
						}else{
						        $pdf->setFillColor(255, 255, 255  );
						    	$pdf->MultiCell(86, 7, ''.$Ofact['nombreComercial'], 0, 'C', 1, 1, '', '', true);
						    	$pdf->Cell(9, 7, '', '', 0, 'L',0);
						    	$pdf->SetY($yActual);	
					            $pdf->SetX(104);
						}
						
            //$$pdf->MultiCell(80,7, "",0, 'L', 0, 0, '', '', false);
            // $pdf->Cell(80, 7, "", '', 0, 'L', 0);
            // $pdf->Cell(9, 7, '', '', 0, 'L', 0);
            $pdf->Cell(80, 7, "NÚMERO DE AUTORIZACIÓN", '', 1, 'L', 0);
            //$pdf->MultiCell(80,7, "Dir. Matriz: ".$dirMatriz,0, 'L', 0, 0, '', '', false);
            $pdf->Cell(80, 7, "Dir. Matríz: " . substr($dirMatriz, 0, 45), '', 0, 'L', 0);
            $pdf->Cell(9, 7, '', '', 0, 'L', 0);
            $pdf->Cell(80, 7, $numeroAutorizacion, '', 1, 'L', 0);
            $pdf->Cell(80, 7, "Dir. Sucursal: " . substr($dirEstablecimiento, 0, 43), '', 0, 'L', 0);
            $pdf->Cell(9, 7, '', '', 0, 'L', 0);
            $lbla = ($claveAcceso != "") ? "FECHA AUTORIZACIÓN :" : "";
            $fechaAutorizacion = ($claveAcceso != "") ? $fechaAutorizacion : "";
            $pdf->Cell(80, 7, $lbla, '', 1, 'L', 0);
			$despuesAutorizacion= $pdf->GetY();
			$pdf->SetX(104);
            $pdf->Cell(80, 7, $fechaAutorizacion, '', 1, 'L', 0);
			$ambiente =($ambiente=='PRODUCCION')?'PRODUCCIÓN':$ambiente;
			$pdf->SetX(104);
			$pdf->Cell(80, 7, "AMBIENTE : " . $ambiente, '', 1, 'L', 0);
			$pdf->SetX(104);
			$pdf->Cell(80,7, "EMISIÓN : ".$tipoEmision, '', 1, 'L',0);
			$pdf->SetX(104);
			$pdf->Cell(80, 7, "CLAVE DE ACCESO:", '', 1, 'L', 0);
			$pdf->SetX(104);
			$pdf->write1DBarcode($claveAcceso, 'C39', '', '', '', 10, 0.1, $style, 'N');
			$yFinal2 = $pdf->GetY();
			$pdf->SetY($despuesAutorizacion);
			$pdf->SetX(15);
			$pdf->Cell(80,7,"Teléfono: ".$telefono_empresa,'', 1, 'L',0);
			$pdf->Cell(80, 7, "Contribuyente Especial Nro: " . $contribuyenteEspecial, '', 1, 'L', 0);
			$pdf->Cell(80, 7, "OBLIGADO A LLEVAR CONTABILIDAD: " . ($obligadoContabilidad), '', 1, 'L', 0);
			// $agenteRetencion=1;
						if($agenteRetencion=='1'){
						    $agenteRetencion2='Resolución Nro. NAC-DNCRASC20-00000001';
							$pdf->Cell(80,7, $agenteRetencion2 ,'', 1, 'L',0);
						}else{
						    $agenteRetencion2='';
						}
						// $contribuyenteRimpe='$contribuyenteRimpe';
			if($contribuyenteRimpe!=''){
				$pdf->Cell(80,7, $contribuyenteRimpe ,'', 1, 'L',0);
			}
           $yFinal1 = $pdf->GetY();

		   $yFinal3 = ( $yFinal1 > $yFinal2 )? $yFinal1 : $yFinal2 ;
		  
		 
		   $pdf->SetY( $yFinal3+7);


            $pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215, 215, 215)));
            $pdf->Cell(135, 7, "Razón Social/Nombres y Apellidos: " . substr($razonSocialComprador, 0, 50), '0', 0, 'L', 0);

            $pdf->Cell(46, 7, "RUC/CI: " . $identificacionComprador, 0, 0, 'L', 0);
            $pdf->Cell(1, 7, '', '', 0, 'L', 0);
            $pdf->Cell(1, 7, '', '', 1, 'L', 0);
//            $pdf->Cell(1, 7, '', '', 0, 'L', 0);
//            $pdf->Cell(1, 7, '', '', 0, 'L', 0);
//            $pdf->Cell(80, 7, '', '', 1, 'L', 0);
            $pdf->Cell(40, 7, "Fecha Emisión: " . $fechaEmision, '', 1, 'L', 0);
            $pdf->Line(15, 127, 195, 127);
            $pdf->Cell(120, 7, "Comprobante que se Modifica: ", 0, 0, 'L', 0);
            $pdf->Cell(80, 7, "FACTURA " . $numDocModificado, 0, 0, 'L', 0);
            $pdf->Cell(1, 7, '', '', 0, 'L', 0);
            $pdf->Cell(1, 7, '', '', 1, 'L', 0);
            $pdf->Cell(150, 7, "Fecha Emisión (Comprobante modificar): " . $fechaEmisionDocSustento, 0, 0, 'L', 0);
            $pdf->Cell(1, 7, '', '', 0, 'L', 0);
            $pdf->Cell(1, 7, '', '', 1, 'L', 0);
            $pdf->Cell(150, 7, "Razón de Modificación: " . $motivo, 0, 1, 'L', 0);
//            $pdf->Cell(5, 7, '', '', 0, 'L', 0);
//            $pdf->Cell(80, 7, '', '', 1, 'L', 0);
            $pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215, 215, 215)));
            $pdf->RoundedRect(15, 41, 86, $yFinal1-40, 3.50, '0000');
            $pdf->RoundedRect(103, 9, 93, $yFinal2-10, 3.50, '0000');
            $pdf->RoundedRect(15, 110, 180, 40, 3.50, '0000');
            $pdf->Ln(3);
			$pdf->setFillColor(232, 232, 232  ); 
            $pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215, 215, 215)));
          
            $pdf->Ln(3);
            $lnom = 80;
            
            $pdf->SetTextColor(0, 0, 0  );
						$pdf->Cell(20, 7, 'Código', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->Cell(10, 7, 'Cant', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  );  
						$pdf->Cell(55, 7, 'Descripción', 1, 0, 'C',1);
						$pdf->Cell(40, 7, 'Detalle Adicional', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  );  
						$pdf->Cell(35, 7, 'Precio', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  );  
						$pdf->Cell(21, 7, 'Subtotal', 1, 1, 'C',1);
						$pdf->SetTextColor(0, 0, 0  );
          
	$cdet=(mysql_query("select productos.id_producto as  Id,productos.codigo as Codigo,productos.producto AS Nombre,
						detalle_ventas.cantidad as Cantidad,detalle_ventas.v_unitario AS Preciounitario, 
						detalle_ventas.descuento as Total,detalle_ventas.detalle as detalle,'' as Nombreproducto from detalle_ventas,productos
						where productos.id_producto=detalle_ventas.id_servicio and detalle_ventas.id_venta=$factura"));
						
						//$Odet=mysql_fetch_array($conn,"select productos.Id,productos.Codigo,productos.Nombre,dfactura.Cantidad,dfactura.Preciounitario,dfactura.Total,dfactura.Nombreproducto from dfactura,productos where productos.Id=dfactura.Producto_id and dfactura.Factura_id=?",array($factura));
						$cm=0;
						while($Odet=mysql_fetch_array($cdet)){//for($i=0;$i<=count($dcod)-1;$i++){
						   // store current object
$pdf->startTransaction();
// get the number of lines
$linesDetalle = $pdf->MultiCell(35, 0, $Odet['detalle'], 0, 'L', 0, 0, '', '', true, 0, false,true, 0);
// restore previous object
$pdf = $pdf->rollbackTransaction();

$altodetalle=$linesDetalle*3.5;

$pdf->startTransaction();
// get the number of lines
$linesNombre = $pdf->MultiCell(35, 0,$Odet['Nombre'], 0, 'L', 0, 0, '', '', true, 0, false,true, 0);
// restore previous object
$pdf = $pdf->rollbackTransaction();

$altonombre=$linesNombre*3.5;

if($altodetalle>$altonombre){
    $alto =$altodetalle;
}else{
     $alto =$altonombre;
} 
						    	//15,15,55,40,35,21
						    $pdf->Cell(20, $alto, $Odet['Codigo'],  1, 0, 'L',0);//$pdf->Cell(22, 7, $dcod[$i],  1, 0, 'L',0);
							$pdf->Cell(10, $alto, $Odet['Cantidad'],  1, 0, 'R',0);//$pdf->Cell(22, 7, $dcant[$i],  1, 0, 'R',0);
							
							if($lnom<100){
								$pdf->setFillColor(255, 255, 255  );
								$pdf->MultiCell(55, $alto, ''.$Odet['Nombre'], 1, 'L', 1, 0, '', '', true);
							}
							
							$pdf->MultiCell(40, $alto, ''.$Odet['detalle'], 1, 'L', 1, 0, '', '', true);
							$pdf->Cell(35, $alto, number_format($Odet['Preciounitario'],2),  1, 0, 'R',0);
							if($lnom>=100){
								$pdf->Cell(21, $alto, number_format($Odet['Preciounitario']*$Odet['Cantidad'],2), 1, 0, 'R',0);
								$pdf->setFillColor(255, 255, 255  );
								$pdf->MultiCell(72, $alto, ''.$Odet['Nombre'], 1, 'L', 1, 1, '', '', true);
							}else
								$pdf->Cell(21, $alto, number_format($Odet['Preciounitario']*$Odet['Cantidad'],2), 1, 1, 'R',0);
						}
						

    $pdf->Cell(181, 7,'', '', 1, 'R',0);
//          INICIO COLUMNA 1 INFORMACION ADICIONAL
    $inicioFila = $pdf->GetX();
    $anchoGeneral=6;
    $pdf->Cell(126, $anchoGeneral, 'Información Adicional','LRTB', 0,'L',0);
    $numero_pagina_actual = $pdf->getPage();
    $inicioInformacionAdicional = $pdf->GetY();
    $pdf->Cell(1, $anchoGeneral,'', '', 1, 'R',0);
    
    $inicioFila2 = $pdf->GetX();
    $fila2= $pdf->GetY();   
    

            if($nadic[0] == 'DIRECCION'){
                $nombreInfo1 = 'DIRECCIÓN';
            }elseif($nadic[0] == 'TELEFONO'){
                $nombreInfo1 ='TELÉFONO';
            }else{
                $nombreInfo1 = $nadic[0];
            }
            
            $pdf->Cell(32, $anchoGeneral, $nombreInfo1 . " :",'LRTB' , 0, 'L',0);
            $pdf->Cell(94, $anchoGeneral, substr($dadic[0], 0, 40),  'LRTB', 1, 'L',0);
           

            if($nadic[1] == 'DIRECCION'){
                $nombreInfo2 = 'DIRECCIÓN';
            }elseif($nadic[1] == 'TELEFONO'){
                $nombreInfo2 ='TELÉFONO';
            }else{
                $nombreInfo2 = $nadic[1];
            }
            
            $pdf->Cell(32, $anchoGeneral,   $nombreInfo2 . " :",'LRTB' , 0, 'L',0);
            $pdf->Cell(94, $anchoGeneral, substr($dadic[1], 0, 40),  'LRTB', 1, 'L',0);
    
            
            $pdf->Cell(32, $anchoGeneral,  "CORREO :" ,'LRTB' , 0, 'L',0);
            $pdf->Cell(94, $anchoGeneral, $Ofact['Correoe'],  'LRTB', 1, 'L',0);
            

         
            
            
            $a = array("SIN UTILIZACIÓN DEL SISTEMA FINANCIERO" => "01", "OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO" => "02", "CON UTILIZACIÓN DEL SISTEMA FINANCIERO" => "03", "CHEQUE" => "04", "DINERO ELECTRÓNICO" => "17", "TARJETA DE CRÉDITO" => "19", "20 OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO" => "20");
            foreach ($a as $b => $c)
                if ($c == $Ofact['FormaPago'])
                    $formp = $b;
            $formp = ($formp == "") ? "OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO" : $formp;
            
            $pdf->SetX($inicioFila);
            $pdf->Cell(32, $anchoGeneral, 'FORMA DE PAGO;',  'LRTB', 0, 'L',0);
            $pdf->Cell(82, $anchoGeneral, $formp,  'LRTB', 0, 'L',0);
            $pdf->Cell(12, $anchoGeneral, $importeTotal,  'LRTB', 1, 'L',0);
            $pdf->Cell(32, $anchoGeneral, 'NOTA:',  'LRTB', 0, 'L',0);
            $pdf->MultiCell(94, $anchoGeneral, ''.$Ofact['descri'] ,1, 'L', 0, '1','', '', true);
           
            $inicioFila=15;
            // $sqlIA="SELECT `id_info_adicional`, `campo`, `descripcion`, `id_venta`, `id_empresa`,xml FROM `info_adicional` WHERE  id_venta=$factura ";
            // $resultIA = mysql_query($sqlIA);
            // while($rowIA = mysql_fetch_array($resultIA) ){
            //     if($rowIA['campo']!='DIRECCION' && $rowIA['campo']!='TELEFONO' && $rowIA['campo']!='EMAIL'){
            //         $pdf->SetX($inicioFila);
            //         $pdf->Cell(32, $anchoGeneral, $rowIA['campo'].' :',  'LRTB', 0, 'L',0);
            //         $pdf->Cell(94, $anchoGeneral, $rowIA['descripcion'],  'LRTB', 1, 'L',0);
            //     }
            // }
                
//          FIN COLUMNA 1 INFORMACION ADICIONAL 
            
            
//          INICIO COLUMNA 2 INFORMACION ADICIONAL
            $inicioFila2=141;
            $pdf->setPage($numero_pagina_actual );
            $pdf->SetY($inicioInformacionAdicional);
            
            $total_subtota_venta = 0;
            	$sqlDetalleV = "SELECT impuestos.id_iva, impuestos.codigo, impuestos.iva, detalle_ventas.`id_detalle_venta`, detalle_ventas.`idBodega`, detalle_ventas.`idBodegaInventario`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`, detalle_ventas.`descuento`, SUM(detalle_ventas.`v_total`) AS base_imponible, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`detalle`, detalle_ventas.`id_kardex`, detalle_ventas.`tipo_venta`, detalle_ventas.`id_empresa`,  detalle_ventas.`tarifa_iva`, SUM(detalle_ventas.`total_iva`) as suma_iva FROM `detalle_ventas` INNER JOIN impuestos ON impuestos.id_iva = detalle_ventas.tarifa_iva WHERE detalle_ventas.id_venta = '".$factura."'  GROUP BY impuestos.id_iva ";
					$resultDetVenta = mysql_query( $sqlDetalleV );
					 $array_ivas= array ();
					while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
						$subT12 = $rowDetVent['base_imponible'];
                    $total_subtota_venta +=$subT12;
						
						$pdf->SetX($inicioFila2);
						$pdf->Cell(35, $anchoGeneral, 'Subtotal '.$rowDetVent['iva'].' % :',  'LRTB', 0, 'L',0);
						$pdf->Cell(21, $anchoGeneral, number_format($subT12,2), 'LRTB', 1, 'R',0);
								
					    $clave = $rowDetVent['tarifa_iva'] ;  
                        $array_ivas[$clave][0]= $rowDetVent['iva'];
                        $array_ivas[$clave][1] = floatval($rowDetVent['suma_iva']);
                    
                    
					}
					$pdf->SetX($inicioFila2);
            $pdf->Cell(35, $anchoGeneral, 'Subtotal  :',  'LRTB', 0, 'L',0);
            $pdf->Cell(21, $anchoGeneral,number_format($total_subtota_venta, 2), 'LRTB', 1, 'R',0);
            // $pdf->SetX($inicioFila2);
            // $pdf->Cell(35, $anchoGeneral, 'Subtotal 0% :',  'LRTB', 0, 'L',0);
            // $pdf->Cell(21, $anchoGeneral,number_format($subtiva0, 2), 'LRTB', 1, 'R',0);
            
            foreach ($array_ivas as $key => $value) {
                if($value[1]>0 ){
                    $pdf->SetX($inicioFila2);	
                	$pdf->Cell(35, $anchoGeneral,  utf8_decode("IVA ".$value[0]." %:" ),  'LRTB', 0, 'L',0);
                	$pdf->Cell(21, $anchoGeneral,number_format($value[1],2), 'LRTB', 1, 'R',0);
                }
            
            } 
            
            // $pdf->SetX($inicioFila2);
            // $pdf->Cell(35, $anchoGeneral, 'IVA :',  'LRTB', 0, 'L',0);
            // $pdf->Cell(21, $anchoGeneral,number_format($iva, 2), 'LRTB', 1, 'R',0);
            $pdf->SetX($inicioFila2);
            $pdf->Cell(35, $anchoGeneral, 'Descuento :',  'LRTB', 0, 'L',0);
            $pdf->Cell(21, $anchoGeneral,number_format($totalDescuento, 2), 'LRTB', 1, 'R',0);
            
    
            $pdf->SetX($inicioFila2);
            $pdf->Cell(35, $anchoGeneral, 'Total :',  'LRTB', 0, 'L',0);
            $pdf->Cell(21, $anchoGeneral, number_format($importeTotal,2), 'LRTB', 1, 'R',0);       
            // $pdf->Output('archivos/' . $fffile . '.pdf', 'F');
            $pdf->Output('../facturas/' . $fffile . '.pdf', 'F');
            // echo 'facturas/'.$fffile.'.pdf';exit;

            break;
case "guiaRemision":
    
		          		                $tcomprobante="GUIA DE REMISION";
		
					    $cimp=$bimp=$vimp=array();
						$dcod=$ddes=$dcant=$dprecio=$ddes=$dpreciot=array();
						$nadic=$dadic=array();	
						
						
						$result11=mysql_query( "SELECT codigo_lug as codigo_lug,ambiente as ambiente,tipoEmision as tipoEmision, empresa.nombre as nombreComercial, 
empresa.razonSocial as razonSocial, empresa.ruc as ruc, ClaveAcceso as claveAcceso,tipo_documento as codDoc,establecimientos.codigo as estab,
emision.codigo as ptoEmi,numero_factura_venta as secuencial,empresa.direccion as dirMatriz,empresa.clave as clave,empresa.FElectronica as firma,
ventas.fecha_venta as fechaEmision,clientes.apellido as apellidoCliente,clientes.nombre as nombreCliente,clientes.direccion,clientes.cedula as cedulaCliente,clientes.estado,clientes.caracter_identificacion,
empresa.Ocontabilidad,empresa.FElectronica,ventas.sub0,ventas.sub12,ventas.descuento,impuestos.iva,clientes.telefono,clientes.email,
empresa.autorizacion_sri,ventas.descripcion,establecimientos.direccion as dirSucursal,empresa.leyenda as rimpe,empresa.leyenda2 as retencion,
ventas.Autorizacion as autorizacion,ventas.FechaAutorizacion as fechaAutorizacion,ventas.Vendedor_id as vendedor,ventas.fechaInicio as fechaInicio,
ventas.FechaFin as FechaFin,ventas.MotivoTraslado  as motivo,ventas.RetIva as RetIva,
ventas.DirDestino as destino,empresa.imagen as imagen,ventas.descripcion as descripcion,empresa.telefono1 

from ventas,emision,empresa,establecimientos,clientes,impuestos 
WHERE impuestos.id_iva=ventas.id_iva AND   clientes.id_cliente=ventas.id_cliente AND  id_venta='$factura' 
and emision.id=codigo_lug and empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun and emision.id=ventas.codigo_lug			");



				// 		$row11=mysql_fetch_array($result11);
				// $result111= mysql_query($result11);
				// $row11[];
						 while($row11=mysql_fetch_array($result11)){
						
						$vendedorId= $row11['vendedor'];
						$Ocontabilidad =$row11['Ocontabilidad'];
						$facafectar =$row11['RetIva'];
						$telefono_empresa =$row11['telefono1'];
						
						$result12=mysql_query( "select detalle_ventas.cantidad,productos.codigo,productos.producto,
						detalle_ventas.detalle as detalleV from detalle_ventas,productos where 
						productos.id_producto=detalle_ventas.id_servicio and id_venta=$factura");
// 	echo 
// 						"select detalle_ventas.cantidad,productos.codigo,productos.producto,
// 						detalle_ventas.detalle as detalleV from detalle_ventas,productos where 
// 						productos.id_producto=detalle_ventas.id_servicio and id_venta=$factura";
						
						$fffile=$tipoc."_".$row11['ruc'].$row11['estab'].$row11['ptoEmi'].ceros($row11['secuencial']);
						$ffile=$fffile.".xml";
						
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
						if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
							require_once(dirname(__FILE__).'/lang/eng.php');
							$pdf->setLanguageArray($l);
						}
						$pdf->SetFont('helvetica', '', 7);
						$pdf->AddPage();
						$style = array(
							'position' => '',
							'align' => 'C',
							'stretch' => false,
							'fitwidth' => true,
							'cellfitalign' => '',
							'border' => false,
							'hpadding' => 'auto',
							'vpadding' => 'auto',
							'fgcolor' => array(0,0,0),
							'bgcolor' => false, //array(255,255,255),
							'text' => true,
							'font' => 'helvetica',
							'fontsize' => 7,
							'stretchtext' => 4
						);
						
						$ambiente_id=$row['ambiente'];
						
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$style['position'] = 'R';
						
						$info = pathinfo($row11['imagen']);
						$formato = $info['extension'];
						

						$imagenPath = "archivos/" . $row11['imagen'];
						if (file_exists($imagenPath)) {
							list($anchoOriginal, $altoOriginal) = getimagesize($imagenPath);
						$x1 = 96;
						
						// Ancho y alto deseados
						$anchoDeseado = 86;
						$altoDeseado = 28;
						
						// Calcular la relación de aspecto
						$relacionAspecto = $anchoOriginal / $altoOriginal;
						
						// Verificar si el ancho de la imagen es mayor que el espacio disponible
						if ($anchoOriginal > $anchoDeseado) {
							// Redimensionar el ancho al ancho deseado y ajustar el alto en consecuencia
							$anchoNuevo = $anchoDeseado;
							$altoNuevo = $anchoNuevo / $relacionAspecto;
						} else {
							// Si el ancho de la imagen es menor o igual, usar el tamaño original
							$anchoNuevo = $anchoOriginal;
							$altoNuevo = $altoOriginal;
						}
						
						// Verificar si la altura de la imagen es mayor que el espacio disponible
						if ($altoNuevo > $altoDeseado) {
							// Redimensionar la altura al alto deseado y ajustar el ancho en consecuencia
							$altoNuevo = $altoDeseado;
							$anchoNuevo = $altoNuevo * $relacionAspecto;
						}
						
						$anchoMinimo = 50; // Tamaño mínimo deseado para el ancho
						$altoMinimo = 28;  // Tamaño mínimo deseado para el alto
						
						// Verificar si la imagen es más pequeña que el mínimo deseado
						if ($anchoNuevo < $anchoMinimo || $altoNuevo < $altoMinimo) {
							// Aumentar el tamaño de la imagen para alcanzar el tamaño mínimo
							$anchoNuevo = max($anchoNuevo, $anchoMinimo);
							$altoNuevo = max($altoNuevo, $altoMinimo);
						}
						
						// Calcular la posición X para centrar la imagen entre x=20 y x1=96
						$posicionX = (20 + $x1 - $anchoNuevo) / 2;
						$posicionY = 10;
						
						$pdf->Image($imagenPath, $posicionX, $posicionY, $anchoNuevo, $altoNuevo, $formato, '', '', true, 150, '', false, false, 0, false, false, false);
						} 
						

					
						$y=0;
						$pdf->Cell(80,7, '','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, "R.U.C. : ".$row11['ruc'], '', 1, 'L',0);
						
						$pdf->Cell(80,7, '','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->SetTextColor(0, 0, 0  ); 
						$tipoDocumento = (strtoupper($tipoc)=='GUIAREMISION')?'GUIA DE REMISIÓN':strtoupper($tipoc);
						$pdf->Cell(91,7,	$tipoDocumento." :", '', 1, 'L',1);
						$pdf->SetTextColor(0, 0, 0  ); 
						$pdf->MultiCell(80,7, $row11['razonSocial'],0, 'L', 0, 0, '', '', false);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$ambiente=($ambiente_id==1)?"PRUEBAS":"PRODUCCIÓN";

						$pdf->Cell(80,7, "Nro: ".$row11['estab']."-".$row11['ptoEmi']."-".ceros($row11['secuencial']), '', 1, 'L',0);
						//$$pdf->MultiCell(80,7, "",0, 'L', 0, 0, '', '', false);
						$pdf->Cell(80,7, "",'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, "NÚMERO DE AUTORIZACIÓN :", '', 1, 'L',0);
					
						$pdf->Cell(80,7, "Dir. Matríz: ".substr($row11['dirMatriz'],0,45),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7,$row11['autorizacion'], '', 1, 'L',0);
						$debajoAutorizacionY = $pdf->GetY();
						$pdf->Cell(80,7, "Dir. Sucursal: ".substr($row11['dirSucursal'],0,43),'', 1, 'L',0);
						$pdf->Cell(80,7,"Teléfono: ".$telefono_empresa,'', 1, 'L',0);
						$pdf->Cell(80,7,"Contribuyente Especial Nro: ".$contribuyenteEspecial,'', 1, 'L',0);
						$obc=(	$Ocontabilidad ==1)?"SI":"NO";
						$pdf->Cell(80,7,  "OBLIGADO A LLEVAR CONTABILIDAD: $obc ",'', 1, 'L',0);
					

						if($Ofact['leyenda']!=''){
        				    $pdf->MultiCell(80, 7, ''.$Ofact['leyenda'], '0', 'L', false,1);
        				}

						if($Ofact['leyenda2']=='1'){
						    $retencion='Agente de Retencion según resolución Nro. NAC-DNCRASC20-00000001 ';
						    $pdf->Cell(80,7, $retencion, '', 1, 'L',0);
						}else if ($Ofact['leyenda2']=='2'){
						      $retencion='Resolución Calificación Agentes de Retención NAC- GTRRIOC22-00000001';
						      $pdf->Cell(80,7, $retencion, '', 1, 'L',0);
						}
						$debajoCuadroIzquierdo = $pdf->GetY();


						$pdf->SetY($debajoAutorizacionY );
						$pdf->SetX(104 );
					
						if($row11['claveAcceso']!= $row11['autorizacion']){
							$lbla = "FECHA AUTORIZACIÓN :";
							$fechaAutorizacion = $row11['fechaAutorizacion'];
							$pdf->Cell(80,7,  $lbla, '', 1, 'L',0);
							$pdf->SetX(104 );
							$pdf->Cell(80,7,  $fechaAutorizacion, '', 1, 'L',0);
						}
						$ambiente =($ambiente=='PRODUCCION')?'PRODUCCIÓN':$ambiente;
						$pdf->SetX(104 );
						$pdf->Cell(80,7,  "AMBIENTE : ".$ambiente, '', 1, 'L',0);
						$pdf->SetX(104 );
						$pdf->Cell(80,7, "EMISIÓN : NORMAL", '', 1, 'L',0);
						$pdf->SetX(104 );
						$pdf->Cell(80,7, "CLAVE DE ACCESO:", '', 1, 'L',0);
						$pdf->SetX(104 );
						$pdf->write1DBarcode($row11['claveAcceso'], 'C39', '', '', '', 10, 0.1, $style, 'N');
						$debajoCuadroDerecho = $pdf->GetY();

						$debajoCuadros = ($debajoCuadroIzquierdo >$debajoCuadroDerecho )?$debajoCuadroIzquierdo:$debajoCuadroDerecho;
			
						$pdf->SetY($debajoCuadros+7);
				
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$pdf->Cell(135, 7, "Razón Social/Nombres y Apellidos: ".substr($row11['nombreCliente']." ".$row11['apellidoCliente'],0,50),'LT', 0, 'L',0);
						$pdf->Cell(46, 7, "RUC/Cl: ".$row11['cedulaCliente'], 'TR', 1, 'L',0);
						$pdf->Cell(135, 7,"Fecha Emisión: ".$row11['fechaEmision'],'LB', 0, 'L',0);
						$pdf->Cell(46, 7, "" , 'BR', 1, 'L',0);
						
					

    					$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$result13=mysql_query( "select * from transportista where Id=$vendedorId" );
						 while($row13=mysql_fetch_array($result13)){
						     
    					
    						$pdf->Cell(100, 7, "Transportista/Nombres y Apellidos: ".substr($row13['Nombres'],0,50),'LT', 0, 'L',0);
    						$pdf->Cell(40, 7, "RUC/Cl Transpor: ".$row13['Cedula'], 'LT', 0, 'L',0);
    						$pdf->Cell(41, 7, "Placa:".$row13['Placa'] , 'TR', '1', 'L',0);
						
						 }
						 
			if($facafectar!="0"){
    			 $resultFactura=mysql_query( "
    			 
    			  SELECT *,establecimientos.codigo as estab,emision.codigo as ptoEmi
                        from ventas,emision,empresa,establecimientos
                            WHERE           ventas.id_venta='$facafectar' and emision.id=codigo_lug and 
                        empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun 
    			 
    		");   
    			 //echo "SELECT * FROM ventas WHERE ventas.id_venta='".$facafectar."'";
    			 
    			 
    			 
    			 while ($rowFactura = mysql_fetch_array($resultFactura)) {
    			     		$a=array("Factura"=>1);
						foreach($a as $k=>$v)
							if($rowFactura['tipo_documento']==$v)
								$documento=$k;	
								
							$pdf->Cell(40, 7,"Comprobante de Venta:".$documento, 'LT', '0', 'L',0);	
							$pdf->Cell(41, 7,"Número: ".$rowFactura['estab']."-".$rowFactura['ptoEmi']."-".ceros($rowFactura['numero_factura_venta']), 'LT', 0, 'L',0);

							$pdf->Cell(100, 7,"Autorización:".$rowFactura['Autorizacion'],'LTR', 1, 'L',0);
    						
    			 }
			 }			 
						 
						 
						
					
						
						
						
						
						
						
		$a=array("Venta"=>1,"Compra"=>2,"Transformacion"=>3,"Consignacion"=>4,"Traslado entre Establecimiento Misma Empresa"=>5,"Traslado por emisor itinerante de comprobante de venta"=>6,"Devolucion"=>7,"Importacion"=>8,"Exportacion"=>9,"Otros"=>10);
						foreach($a as $k=>$v)
							if($row11['motivo']==$v)
								$motivo=$k;				
						
						
						$pdf->Cell(60, 7,"Motivo Tras: ".$motivo,'LTR', 0, 'L',0);
				// 		$pdf->Cell(46, 7, "" , 'BR', 1, 'L',0);
						
						$pdf->Cell(60, 7,"Fecha inicio: ".$row11['fechaInicio'],'TBB', 0, 'L',0);
						$pdf->Cell(61, 7, "Fecha fin:".$row11['FechaFin'] , 'TBR', 1, 'L',0);
						
					    $destino = $row11['destino'];  
			            $direccionEstablecimiento = $row11['dirSucursal'];
			            $ruta = $direccionEstablecimiento."-".$destino;
			
						$pdf->Cell(181, 7,"Destino: ".$row11['destino'],'LTR', 1, 'L',0);
						
						$pdf->Cell(135, 7,"Ruta: ".$ruta,'LTR', 0, 'L',0);
						$pdf->Cell(46, 7, "" , 'BR', 1, 'L',0);
				// 		$pdf->Cell(180, 7, "Ruta: ".$ruta, 'LB', 0, 'L',0);
				

			
// 						$pdf->Cell(91, 7,"Destino: ".$row11['destino'],'LB', 0, 'L',0);
// 						$pdf->Cell(90, 7,"Ruta: ".$ruta,'LB', 0, 'L',0);
						
						
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$pdf->RoundedRect(15, 40, 86, $debajoCuadroIzquierdo-40, 3.50, '0000');
						$pdf->RoundedRect(103, 20, 93, $debajoCuadroDerecho-20, 3.50, '0000');
					
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->SetTextColor(0, 0, 0  );
						
						
						$pdf->Cell(20, 7, 'Código', 1, 0, 'C',1);
						$pdf->Cell(10, 7, 'Cant', 1, 0, 'C',1);
						$pdf->Cell(85, 7, 'Descripción', 1, 0, 'C',1);
						$pdf->Cell(67, 7, 'Detalle Adicional', 1, 1, 'C',1);
						    
				// 		do{
				 while ($row121 = mysql_fetch_array($result12)) {//for($i=0;$i<=count($dcod)-1;$i++){
				 
				        $detalleV = $row121['detalleV'];
				        
				        $pdf->setFillColor(255, 255, 255 ); 
				        $pdf->SetTextColor(0, 0, 0  ); 
				        
				        $pdf->Cell(20, 7,  $row121['codigo'], 1, 0, 'L',1);
					
						$pdf->Cell(10, 7, $row121['cantidad'], 1, 0, 'L',1);
					
						$pdf->Cell(85, 7, $row121['producto'], 1,0, 'L',1);
						
						$pdf->MultiCell(67, 7, $detalleV, 1,1, 'L',1);
				    

				 }
							
							
				// 		}while($Odet->GetRow());
						
							$pdf->Cell(23, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(23, 7, '',  'LRB', 0, 'L',0);
							
							$pdf->Cell(135, 7,'', 'LRB', 1, 'R',0);
							
							$pdf->Cell(22, 7, '',  '', 0, 'L',0);
							
							$pdf->Cell(22, 7, '',  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 1, 'R',0);
							
							
							$pdf->Cell(22, 7, "Información Adicional",  '', 0, 'L',0);
							$pdf->Cell(22, 7, '',  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 1, 'R',0);
							
							
							$pdf->Cell(22, 7, "Dirección :",  '', 0, 'L',0);
							$pdf->Cell(22, 7, $row11['direccion'],  '', 0, 'L',0);
							$pdf->Cell(65, 7, '' ,  '', 1, 'R',0);
							
							
							$pdf->Cell(22, 7, "Correo :",  '', 0, 'L',0);
							$pdf->Cell(22, 7, $row11['email'],  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 1, 'R',0);
							
							$pdf->Cell(22, 7, "Observación :",  '', 0, 'L',0);
							$pdf->Cell(22, 7, $row11['descripcion'],  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 1, 'R',0);
							
							$pdf->Cell(22, 7, '',  '', 0, 'L',0);
							$pdf->Cell(22, 7, '',  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 1, 'R',0);
							
							
							$pdf->Output('../facturas/'.$fffile.'.pdf', 'F');//echo 'facturas/'.$fffile.'.pdf';exit;
							
						 }
							
	break;
	
	case "liquidacionCompra":
	   // echo "LIQUIDACION DE COMPRA";
						$tcomprobante="LIQUIDACION DE COMPRA";
					
						$cimp=$bimp=$vimp=array();
						$dcod=$ddes=$dcant=$dprecio=$ddes=$dpreciot=array();
						$nadic=$dadic=array();	
						if(strpos($xml,"<razonSocial>")>0){
							$razonSocial= substr($xml,strpos($xml,"<razonSocial>")+strlen("<razonSocial>"),strpos($xml,"</razonSocial>")-strlen("<razonSocial>")-strpos($xml,"<razonSocial>"));
							$nombreComercial= substr($xml,strpos($xml,"<nombreComercial>")+strlen("<nombreComercial>"),strpos($xml,"</nombreComercial>")-strlen("<nombreComercial>")-strpos($xml,"<nombreComercial>"));
							$numeroAutorizacion= substr($xml,strpos($xml,"<numeroAutorizacion>")+strlen("<numeroAutorizacion>"),strpos($xml,"</numeroAutorizacion>")-strlen("<numeroAutorizacion>")-strpos($xml,"<numeroAutorizacion>"));
							$fechaAutorizacion=substr($xml,strpos($xml,"<fechaAutorizacion")+strlen("<fechaAutorizacion>"),strpos($xml,"</fechaAutorizacion>")-strlen("<fechaAutorizacion>")-strpos($xml,"<fechaAutorizacion"));
							$fechaAutorizacion=substr($fechaAutorizacion,strpos($fechaAutorizacion,">")+1,strlen($fechaAutorizacion));
							$ruc= (substr($xml,strpos($xml,"<ruc>")+strlen("<ruc>"),strpos($xml,"</ruc>")-strlen("<ruc>")-strpos($xml,"<ruc>")));
							$ambiente= substr($xml,strpos($xml,"<ambiente>")+strlen("<ambiente>"),strpos($xml,"</ambiente>")-strlen("<ambiente>")-strpos($xml,"<ambiente>"));
							$ambiente=((int)trim($ambiente)==2)?"PRODUCCION":"PRUEBAS";
							$claveAcceso= substr($xml,strpos($xml,"<claveAcceso>")+strlen("<claveAcceso>"),strpos($xml,"</claveAcceso>")-strlen("<claveAcceso>")-strpos($xml,"<claveAcceso>"));
							$estab= substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
							$tipoEmision= substr($xml,strpos($xml,"<tipoEmision>")+strlen("<tipoEmision>"),strpos($xml,"</tipoEmision>")-strlen("<tipoEmision>")-strpos($xml,"<tipoEmision>"));
							$tipoEmision=($tipoEmision==1)?"NORMAL":"";
							$ptoEmi= substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
							$secuencial= substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));
							$dirMatriz= substr($xml,strpos($xml,"<dirMatriz>")+strlen("<dirMatriz>"),strpos($xml,"</dirMatriz>")-strlen("<dirMatriz>")-strpos($xml,"<dirMatriz>"));
							$fechaEmision= substr($xml,strpos($xml,"<fechaEmision>")+strlen("<fechaEmision>"),strpos($xml,"</fechaEmision>")-strlen("<fechaEmision>")-strpos($xml,"<fechaEmision>"));
							$fechaEmision= substr($fechaEmision,6,4)."-".substr($fechaEmision,3,2)."-".substr($fechaEmision,0,2);
							$dirEstablecimiento= substr($xml,strpos($xml,"<dirEstablecimiento>")+strlen("<dirEstablecimiento>"),strpos($xml,"</dirEstablecimiento>")-strlen("<dirEstablecimiento>")-strpos($xml,"<dirEstablecimiento>"));
							$obligadoContabilidad= substr($xml,strpos($xml,"<obligadoContabilidad>")+strlen("<obligadoContabilidad>"),strpos($xml,"</obligadoContabilidad>")-strlen("<obligadoContabilidad>")-strpos($xml,"<obligadoContabilidad>"));
							$razonSocialComprador= substr($xml,strpos($xml,"<razonSocialProveedor>")+strlen("<razonSocialProveedor>"),strpos($xml,"</razonSocialProveedor>")-strlen("<razonSocialProveedor>")-strpos($xml,"<razonSocialProveedor>"));
							$identificacionComprador= substr($xml,strpos($xml,"<identificacionProveedor>")+strlen("<identificacionProveedor>"),strpos($xml,"</identificacionProveedor>")-strlen("<identificacionProveedor>")-strpos($xml,"<identificacionProveedor>"));
							$totalSinImpuestos= substr($xml,strpos($xml,"<totalSinImpuestos>")+strlen("<totalSinImpuestos>"),strpos($xml,"</totalSinImpuestos>")-strlen("<totalSinImpuestos>")-strpos($xml,"<totalSinImpuestos>"));
							$totalDescuento= substr($xml,strpos($xml,"totalDescuento")+strlen("<totalDescuento>"),strpos($xml,"</totalDescuento>")-strlen("<totalDescuento>")-strpos($xml,"<totalDescuento>"));
				
				$contribuyenteEspecial="";
				if(strpos($xml,"<contribuyenteEspecial>")>0)
				$contribuyenteEspecial= substr($xml,strpos($xml,"<contribuyenteEspecial>")+strlen("<contribuyenteEspecial>"),strpos($xml,"</contribuyenteEspecial>")-strlen("<contribuyenteEspecial>")-strpos($xml,"<contribuyenteEspecial>"));
				if(strpos($xml, "<agenteRetencion>")!="")
				$agenteRetencion= substr($xml,strpos($xml,"<agenteRetencion>")+strlen("<agenteRetencion>"),strpos($xml,"</agenteRetencion>")-strlen("<agenteRetencion>")-strpos($xml,"<agenteRetencion>"));
				if(strpos($xml, "<contribuyenteRimpe>")!="")
				$contribuyenteRimpe= substr($xml,strpos($xml,"<contribuyenteRimpe>")+strlen("<contribuyenteRimpe>"),strpos($xml,"</contribuyenteRimpe>")-strlen("<contribuyenteRimpe>")-strpos($xml,"<contribuyenteRimpe>"));

							
							$totalDescuento= substr($xml,strpos($xml,"<totalDescuento>")+strlen("<totalDescuento>"),strpos($xml,"</totalDescuento>")-strlen("<totalDescuento>")-strpos($xml,"<totalDescuento>"));
							$baseImponible= substr($xml,strpos($xml,"<baseImponible>")+strlen("<baseImponible>"),strpos($xml,"</baseImponible>")-strlen("<baseImponible>")-strpos($xml,"<baseImponible>"));
							$impuestos=substr($xml,strpos($xml,"<totalConImpuestos>"),strpos($xml,"</totalConImpuestos>")-strpos($xml,"<totalConImpuestos>"));
							$propina=substr($xml,strpos($xml,"<propina>")+strlen("<propina>"),strpos($xml,"</propina>")-strlen("<propina>")-strpos($xml,"<propina>"));
							$importeTotal=substr($xml,strpos($xml,"<importeTotal>")+strlen("<importeTotal>"),strpos($xml,"</importeTotal>")-strlen("<importeTotal>")-strpos($xml,"<importeTotal>"));
							$moneda=substr($xml,strpos($xml,"<moneda>")+strlen("<moneda>"),strpos($xml,"</moneda>")-strlen("<moneda>")-strpos($xml,"<moneda>"));
							$detalles=substr($xml,strpos($xml,"<detalles>")+strlen("<detalles>"),strpos($xml,"</detalles>")-strlen("<detalles>")-strpos($xml,"<detalles>"));
												
							$infoAdicional=substr($xml,strpos($xml,"<infoAdicional>")+strlen("<infoAdicional>"),strpos($xml,"</infoAdicional>")-+strlen("<infoAdicional>")-strpos($xml,"<infoAdicional>"));
							$cont=0;
							do{
								$cimp[]=substr($impuestos,strpos($impuestos,"<codigoPorcentaje>")+strlen("<codigoPorcentaje>"),strpos($impuestos,"</codigoPorcentaje>")-strlen("<codigoPorcentaje>")-strpos($impuestos,"<codigoPorcentaje>"));
								$bimp[]=substr($impuestos,strpos($impuestos,"<baseImponible>")+strlen("<baseImponible>"),strpos($impuestos,"</baseImponible>")-strlen("<baseImponible>")-strpos($impuestos,"<baseImponible>"));
								$vimp[]=substr($impuestos,strpos($impuestos,"<valor>")+strlen("<valor>"),strpos($impuestos,"</valor>")-strlen("<valor>")-strpos($impuestos,"<valor>"));
								$impuestos=substr($impuestos,strpos($impuestos,"</totalImpuesto>"),strlen($impuestos)-strpos($impuestos,"</totalImpuesto>"));
								if($cont>20)
									break;
								$cont++;
							}while(strpos($impuestos,"<totalImpuesto>")>0);	
							
							$cont=0;
							//echo $detalles;
							/*do{
								$cont++;
								$dcod[]=substr($detalles,strpos($detalles,"<codigoPrincipal>")+strlen("<codigoPrincipal>"),strpos($detalles,"</codigoPrincipal>")-strlen("<codigoPrincipal>")-strpos($detalles,"<codigoPrincipal>"));
								$ddes[]=substr($detalles,strpos($detalles,"<descripcion>")+strlen("<descripcion>"),strpos($detalles,"</descripcion>")-strlen("<descripcion>")-strpos($detalles,"<descripcion>"));
								$dcant[]=substr($detalles,strpos($detalles,"<cantidad>")+strlen("<cantidad>"),strpos($detalles,"</cantidad>")-strlen("<cantidad>")-strpos($detalles,"<cantidad>"));
								$dprecio[]=substr($detalles,strpos($detalles,"<precioUnitario>")+strlen("<precioUnitario>"),strpos($detalles,"</precioUnitario>")-strlen("<precioUnitario>")-strpos($detalles,"<precioUnitario>"));
								$ddes[]=substr($detalles,strpos($detalles,"<descuento>")+strlen("<descuento>"),strpos($detalles,"</descuento>")-strlen("<descuento>")-strpos($detalles,"<descuento>"));
								$dpreciot[]=substr($detalles,strpos($detalles,"<precioTotalSinImpuesto>")+strlen("<precioTotalSinImpuesto>"),strpos($detalles,"</precioTotalSinImpuesto>")-strlen("<precioTotalSinImpuesto>")-strpos($detalles,"<precioTotalSinImpuesto>"));
								echo " ".strpos($detalles,"</detalle>"). "de .".strlen($detalles); exit;
								$detalles=substr($detalles,strpos($detalles,"</detalle>"),strlen($detalles)-strpos($detalles,"</detalle>"));
								if($cont>100)
									break;
							}while(strpos($detalles,"<detalle>")>0);	*/
							$dadic[]=substr($infoAdicional,strpos($infoAdicional,">")+1,strpos($infoAdicional,"</campoAdicional>")-1-strpos($infoAdicional,">"));
							$tmpad=substr($infoAdicional,strpos($infoAdicional,"<campoAdicional"),strpos($infoAdicional,"</campoAdicional>")-strpos($infoAdicional,"<campoAdicional"));
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
							$tmpad1=$tmpad=substr($infoAdicional,strpos($infoAdicional,"</campoAdicional>")+strlen("</campoAdicional>"),strlen($infoAdicional)-strlen("</campoAdicional>")-strpos($infoAdicional,"</campoAdicional>"));
							$dadic[]=substr($tmpad,strpos($tmpad,">")+1,strpos($tmpad,"</campoAdicional>")-1-strpos($tmpad,">"));
							$tmpad=substr($tmpad,strpos($tmpad,"<campoAdicional"),strpos($tmpad,"</campoAdicional>")-strpos($tmpad,"<campoAdicional"));
							$nadic[]=substr($tmpad,strpos($tmpad,"nombre=")+8,strpos($tmpad,">")-1-strpos($tmpad,"nombre=")-8);
							
						}else{			
							$impuestos=substr($xml,strpos($xml,"totalConImpuestos")+strlen("totalConImpuestos")+4,strpos($xml,"/totalConImpuestos")-strpos($xml,"totalConImpuestos")-(strlen("totalConImpuestos")+8));
							$cont=0;
							do{
								$cimp[]=substr($impuestos,strpos($impuestos,"codigoPorcentaje")+strlen("codigoPorcentaje")+4,strpos($impuestos,"/codigoPorcentaje")-strpos($impuestos,"codigoPorcentaje")-(strlen("codigoPorcentaje")+8));
								$bimp[]=substr($impuestos,strpos($impuestos,"baseImponible")+strlen("baseImponible")+4,strpos($impuestos,"/baseImponible")-strpos($impuestos,"baseImponible")-(strlen("baseImponible")+8));
								$vimp[]=substr($impuestos,strpos($impuestos,"valor")+strlen("valor")+4,strpos($impuestos,"/valor")-strpos($impuestos,"valor")-(strlen("valor")+8));
								$impuestos=substr($impuestos,strpos($impuestos,"/totalImpuesto")+4,strlen($impuestos)-strpos($impuestos,"/totalImpuesto")+4);
								if($cont>20)
									break;
								$cont++;
							}while(strpos($impuestos,"totalImpuesto")>0);
						}
						
						
						for($j=0;$j<=count($bimp)-1;$j++){
								if($cimp[$j]==0){
									$subtiva0=$bimp[$j];
									$iva0=$vimp[$j];
								}
								if($cimp[$j]==2){
									$subtiva=$bimp[$j];
									$iva=$vimp[$j];
								}	
								
						}	
						
						if($secuencial!=""){
						    
					$resultCompras=mysql_query( "select * from compras,proveedores,empresa where id_compra=$factura 
					and proveedores.id_proveedor=compras.id_proveedor and empresa.id_empresa=compras.id_empresa");
	                   $Ofact=mysql_fetch_array($resultCompras);
	   //                         echo "select * from compras,proveedores,empresa where id_compra=$factura 
				// 	and proveedores.id_proveedor=compras.id_proveedor and empresa.id_empresa=compras.id_empresa";

						}
					
						$fffile=$tipoc."_".$ruc.$estab.$ptoEmi.$secuencial;
						
				// 		echo $tipoc."_".$ruc.$estab.$ptoEmi.$secuencial;
						
						$ffile=$fffile.".xml";
					
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
						if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
							require_once(dirname(__FILE__).'/lang/eng.php');
							$pdf->setLanguageArray($l);
						}
						$pdf->SetFont('helvetica', '', 7);
						$pdf->AddPage();
						$style = array(
							'position' => '',
							'align' => 'C',
							'stretch' => false,
							'fitwidth' => true,
							'cellfitalign' => '',
							'border' => false,
							'hpadding' => 'auto',
							'vpadding' => 'auto',
							'fgcolor' => array(0,0,0),
							'bgcolor' => false, //array(255,255,255),
							'text' => true,
							'font' => 'helvetica',
							'fontsize' => 7,
							'stretchtext' => 4
						);
						
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$style['position'] = 'R';
						
						
							// $logo = 'Contaweb_logo.jpg';
						// $logo = 'CONTRICAPSAS.jpg';
						// $logo = 'CORP JJGASOCIADOS LOGO.jpg';
						// $logo = 'el pigual.jpg';
						// $logo = 'elikec-logoo.jpg';
						// $logo = 'FINA.jpg';
						// $logo = 'el pigual.jpg';
						// $logo = 'FINCA SAN JACINTO.png';
						// $logo = 'FINAL.jpg';
						// $logo = 'FLORES DEL ALBA.jpg';
						// $logo = 'IMG-3903.jpg';
						// $logo = 'imagen.jpg';
						// $logo = 'logotipo colori.png';

						// $Ofact['imagen'] = $logo;

						$info = pathinfo($Ofact['imagen']);
						$formato = $info['extension'];
						

						$imagenPath = "archivos/" . $Ofact['imagen'];
						if (file_exists($imagenPath)) {
							list($anchoOriginal, $altoOriginal) = getimagesize($imagenPath);
						$x1 = 96;
						
						// Ancho y alto deseados
						$anchoDeseado = 86;
						$altoDeseado = 28;
						
						// Calcular la relación de aspecto
						$relacionAspecto = $anchoOriginal / $altoOriginal;
						
						// Verificar si el ancho de la imagen es mayor que el espacio disponible
						if ($anchoOriginal > $anchoDeseado) {
							// Redimensionar el ancho al ancho deseado y ajustar el alto en consecuencia
							$anchoNuevo = $anchoDeseado;
							$altoNuevo = $anchoNuevo / $relacionAspecto;
						} else {
							// Si el ancho de la imagen es menor o igual, usar el tamaño original
							$anchoNuevo = $anchoOriginal;
							$altoNuevo = $altoOriginal;
						}
						
						// Verificar si la altura de la imagen es mayor que el espacio disponible
						if ($altoNuevo > $altoDeseado) {
							// Redimensionar la altura al alto deseado y ajustar el ancho en consecuencia
							$altoNuevo = $altoDeseado;
							$anchoNuevo = $altoNuevo * $relacionAspecto;
						}
						
						$anchoMinimo = 50; // Tamaño mínimo deseado para el ancho
						$altoMinimo = 28;  // Tamaño mínimo deseado para el alto
						
						// Verificar si la imagen es más pequeña que el mínimo deseado
						if ($anchoNuevo < $anchoMinimo || $altoNuevo < $altoMinimo) {
							// Aumentar el tamaño de la imagen para alcanzar el tamaño mínimo
							$anchoNuevo = max($anchoNuevo, $anchoMinimo);
							$altoNuevo = max($altoNuevo, $altoMinimo);
						}
						
						// Calcular la posición X para centrar la imagen entre x=20 y x1=96
						$posicionX = (20 + $x1 - $anchoNuevo) / 2;
						$posicionY = 10;
						
						$pdf->Image($imagenPath, $posicionX, $posicionY, $anchoNuevo, $altoNuevo, $formato, '', '', true, 150, '', false, false, 0, false, false, false);
						} 
						$y=0;
						$pdf->Cell(80,7, '','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, "R.U.C. : ".$ruc, '', 1, 'L',0);
						
						$pdf->Cell(80,7, '','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->SetTextColor(0, 0, 0  ); 
						$tipoDocumento = (strtoupper($tipoc)=='LIQUIDACIONCOMPRA')?'LIQUIDACIÓN DE COMPRA':strtoupper($tipoc);
						$pdf->Cell(91,7, $tipoDocumento." :", '', 1, 'L',1);
						$pdf->SetTextColor(0, 0, 0  ); 
						$pdf->MultiCell(80,7, $razonSocial,0, 'L', 0, 0, '', '', false);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						
						$pdf->Cell(80,7, "Nro: ".$estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
						//$$pdf->MultiCell(80,7, "",0, 'L', 0, 0, '', '', false);
						$pdf->Cell(80,7, "",'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, "NÚMERO DE AUTORIZACIÓN:", '', 1, 'L',0);
						//$pdf->MultiCell(80,7, "Dir. Matriz: ".$dirMatriz,0, 'L', 0, 0, '', '', false);
						$pdf->Cell(80,7, "Dir. Matríz: ".substr($dirMatriz,0,45),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7,$numeroAutorizacion, '', 1, 'L',0);
						$pdf->Cell(80,7, "Dir. Sucursal: ".substr($dirEstablecimiento,0,43),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$lbla="FECHA AUTORIZACIÓN :";
						$fechaAutorizacion=($claveAcceso!=$numeroAutorizacion)?$fechaAutorizacion:"";
						$pdf->Cell(80,7,  $lbla, '', 1, 'L',0);
						$pdf->Cell(80,7,"Contribuyente Especial Nro: ".$contribuyenteEspecial,'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7,  $fechaAutorizacion, '', 1, 'L',0);
						$pdf->Cell(80,7,  "OBLIGADO A LLEVAR CONTABILIDAD: ".($obligadoContabilidad),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$ambiente =($ambiente=='PRODUCCION')?'PRODUCCIÓN':$ambiente;
						$pdf->Cell(80,7,  "AMBIENTE : ".$ambiente, '', 1, 'L',0);
						$despuesIzquierda = $pdf->GetY();
						$pdf->SetX(104);
						$pdf->Cell(80,7, "EMISIÓN : ".$tipoEmision, '', 1, 'L',0);
						$pdf->SetX(104);
						$pdf->Cell(80,7, "CLAVE DE ACCESO:", '', 1, 'L',0);
						$pdf->SetX(104);
						$pdf->write1DBarcode($claveAcceso, 'C39', '', '', '', 10, 0.1, $style, 'N');
						$despuesDerecha = $pdf->GetY();

						$pdf->SetY($despuesIzquierda);
						if($agenteRetencion=='1'){
						    $agenteRetencion2='Resolución Nro. NAC-DNCRASC20-00000001';
							$pdf->Cell(80,7, $agenteRetencion2 ,'', 1, 'L',0);
						}
						
                        if($contribuyenteRimpe!=''){
                            $pdf->Cell(80,7, $contribuyenteRimpe ,'', 1, 'L',0);
                        }

						$despuesIzquierda = $pdf->GetY();
					
						$finalCabecera = ($despuesIzquierda>$despuesDerecha )?$despuesIzquierda:$despuesDerecha;
						$pdf->SetY($finalCabecera+7);
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$pdf->Cell(135, 7, "Razón Social/Nombres y Apellidos: ".substr($razonSocialComprador,0,50),'LT', 0, 'L',0);
						$pdf->Cell(46, 7, "RUC/Cl: ".$identificacionComprador, 'TR', 1, 'L',0);
						$pdf->Cell(135, 7,"Fecha Emisión: ".$fechaEmision,'LB', 0, 'L',0);
						$pdf->Cell(46, 7, "Guia Remisión:" , 'BR', 1, 'L',0);
						
						$pdf->Cell(5, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, '', '', 1, 'L',0);
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$pdf->RoundedRect(15, 40, 86, $despuesIzquierda-40, 3.50, '0000');
						$pdf->RoundedRect(103, 20, 93, $despuesDerecha-20, 3.50, '0000');
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						  
						  
						$resultProducto=mysql_query( "select max(length(productos.producto)) as lnom  from detalle_compras,productos
						where detalle_compras.id_compra=$factura and detalle_compras.id_producto=productos.id_producto");
						
				// 		echo "select max(length(productos.producto)) as lnom  from detalle_compras,productos
				// 		where detalle_compras.id_compra=$factura and detalle_compras.id_producto=productos.id_producto";
						
				// 		echo "select max(length(productos.producto)) as lnom  from detalle_compras,productos
				// 		where detalle_compras.id_compra=$factura and detalle_compras.id_producto=productos.id_producto";
	                    $Odet=mysql_fetch_array($resultProducto);
						
						
				// 		$Odet=new CMySQL("select max(length(productos.Nombre)) as lnom from dcompras,productos where productos.Id=dcompras.Producto_id and dcompras.Factura_id=?",array($factura));
						$lnom=$Odet['lnom'];
						
						
						$pdf->SetTextColor(0, 0, 0  );
						$pdf->Cell(22, 7, 'Código', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  ); 
						$pdf->Cell(22, 7, 'Cantidad', 1, 0, 'C',1);
						if($lnom<100){
							$pdf->setFillColor(232, 232, 232  ); 
							$pdf->Cell(65, 7, 'Descripción', 1, 0, 'C',1);
						}
						$pdf->setFillColor(232, 232, 232  );  
						$pdf->Cell(35, 7, 'Precio', 1, 0, 'C',1);
						$pdf->setFillColor(232, 232, 232  );  
						
						if($lnom>=100){
							$pdf->Cell(30, 7, 'Subtotal', 1, 0, 'C',1);
							$pdf->setFillColor(232, 232, 232  );  
							$pdf->Cell(72, 7, 'Descripción', 1, 1, 'C',1);
						}else
							$pdf->Cell(37, 7, 'Subtotal', 1, 1, 'C',1);
						
						$pdf->SetTextColor(0, 0, 0  );
						
				// 		$Odet->SetQuery("select productos.Id,productos.Codigo,productos.Nombre,dcompras.Cantidad,dcompras.Preciounitario,dcompras.Total,dcompras.Nombreproducto 
				// 		from dcompras,productos where productos.Id=dcompras.Producto_id and dcompras.Factura_id=?",array($factura));
						
						
						$resultProductoDetalle=mysql_query( "select productos.id_producto,productos.codigo,productos.producto,detalle_compras.cantidad,detalle_compras.valor_unitario,
						detalle_compras.valor_total 
						from detalle_compras,productos where productos.id_producto=detalle_compras.id_producto and detalle_compras.id_compra=$factura ");
				// 		$Odet=mysql_fetch_array($resultProductoDetalle);
						
						
						$cm=0;
					while ($Odet = mysql_fetch_array($resultProductoDetalle)) {
							if($vclase[$Odet['id_producto']]!="")$cm++;
   							 $nom=($vclase[$Odet['id_producto']]!="" and $cm==1)?substr($Odet['producto']." ".$vclase[$Odet['id_producto']]." ".$meses[$vmes[$Odet['id_producto']]-1]."/". $vanio[$Odet['id_producto']],0,80):$Odet['producto']." ".$Odet['producto'];
							$pdf->Cell(22, 7, $Odet['codigo'],  1, 0, 'L',0);//$pdf->Cell(22, 7, $dcod[$i],  1, 0, 'L',0);
							$pdf->Cell(22, 7, $Odet['cantidad'],  1, 0, 'R',0);//$pdf->Cell(22, 7, $dcant[$i],  1, 0, 'R',0);
							if($lnom<100){
								$pdf->setFillColor(255, 255, 255  );
								$pdf->MultiCell(65, 7, ''.$nom, 1, 'L', 1, 0, '', '', true);
							}
							
							//$pdf->Cell(65, 7, $nom,  1, 0, 'L',0);//$pdf->Cell(65, 7, $ddes[$i],  1, 0, 'L',0);
							$pdf->Cell(35, 7, number_format($Odet['valor_unitario'],2),  1, 0, 'R',0);//$pdf->Cell(35, 7, number_format($dprecio[$i],2),  1, 0, 'R',0);
							if($lnom>=100){
								$pdf->Cell(30, 7, number_format($Odet['valor_unitario']*$Odet['cantidad'],2), 1, 0, 'R',0);
								$pdf->setFillColor(255, 255, 255  );
								$pdf->MultiCell(72, 7, ''.$nom, 1, 'L', 1, 1, '', '', true);
							}else
								$pdf->Cell(37, 7, number_format($Odet['valor_unitario']*$Odet['cantidad'],2), 1, 1, 'R',0);
						};
						
						
							$pdf->Cell(22, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(22, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(65, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(35, 7, '',  'LRB', 0, 'L',0);
							$pdf->Cell(37, 7,'', 'LRB', 1, 'R',0);
							
							$pdf->Cell(22, 7, '',  '', 0, 'L',0);
							$pdf->Cell(22, 7, '',  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 0, 'L',0);
							$pdf->Cell(35, 7, 'Subtotal  :',  'LRTB', 0, 'L',0);
							$pdf->Cell(37, 7, number_format($subtiva,2), 'LRTB', 1, 'R',0);
							
							$pdf->Cell(22, 7, "Información Adicional",  '', 0, 'L',0);
							$pdf->Cell(22, 7, '',  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 0, 'L',0);
							$pdf->Cell(35, 7, 'Subtotal 0% :',  'LRTB', 0, 'L',0);
							$pdf->Cell(37, 7,number_format($subtiva0,2), 'LR', 1, 'R',0);
							
							if($nadic[0] == 'DIRECCION'){
								$nombreInfo1 = 'DIRECCIÓN';
							}elseif($nadic[0] == 'TELEFONO'){
								$nombreInfo1 ='TELÉFONO';
							}else{
								$nombreInfo1 = $nadic[0];
							}
							$pdf->Cell(22, 7, $nombreInfo1." :",  '', 0, 'L',0);
							$pdf->Cell(22, 7, substr($dadic[0],0,40),  '', 0, 'L',0);
							$pdf->Cell(65, 7, '' ,  '', 0, 'L',0);
							$pdf->Cell(35, 7, 'IVA :',  'LRTB', 0, 'L',0);
							$pdf->Cell(37, 7, number_format($iva,2), 'LRTB', 1, 'R',0);
							
							if($nadic[1] == 'DIRECCION'){
								$nombreInfo2 = 'DIRECCIÓN';
							}elseif($nadic[1] == 'TELEFONO'){
								$nombreInfo2 ='TELÉFONO';
							}else{
								$nombreInfo2 = $nadic[1];
							}

							$pdf->Cell(22, 7, $nombreInfo2." :",  '', 0, 'L',0);
							$pdf->Cell(22, 7, substr($dadic[1],0,40),  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 0, 'L',0);
							$pdf->Cell(35, 7, 'Descuento :',  'LRTB', 0, 'L',0);
							$pdf->Cell(37, 7, number_format($totalDescuento,2), 'LRTB', 1, 'R',0);
							$a=array("SIN UTILIZACIÓN DEL SISTEMA FINANCIERO"=>"01","SIN UTILIZACIÓN DEL SISTEMA FINANCIERO"=>"02","CON UTILIZACIÓN DEL SISTEMA FINANCIERO"=>"03","CHEQUE"=>"04","DINERO ELECTRÓNICO"=>"17","TARJETA DE CRÉDITO"=>"19","OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO"=>"20");
							foreach($a as $b=>$c)
								if($c==$Ofact['FormaPago'])
									$formp=$b;
							$pdf->Cell(22, 7, 'FORMA DE PAGO: ',  '', 0, 'L',0);
							$pdf->Cell(22, 7, $formp,  '', 0, 'L',0);
							$pdf->Cell(65, 7, '',  '', 0, 'L',0);
							$pdf->Cell(35, 7, 'Total :',  'LRTB', 0, 'L',0);
							$pdf->Cell(37, 7, number_format($importeTotal,2), 'LRTB', 1, 'R',0);
						
						
							$pdf->Output('../facturas/'.$fffile.'.pdf', 'F');//echo 'facturas/'.$fffile.'.pdf';exit;				
  break;
  
	
            
	
}		

	
	if($correo!=""){
        




    	$mail = new PHPMailer();
    	$mail->CharSet = 'UTF-8';
    	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    	
    	$sqlMail="Select * From empresa where id_empresa='".$sesion_id_empresa."' " ;
		$resultMail=mysql_query($sqlMail);
		
		while($rowMail=mysql_fetch_array($resultMail))//permite ir de fila en fila de la tabla
		{
			$Razon=$rowMail['razonSocial'];
			
		}
				
	if($tcomprobante=="FACTURA" || $tcomprobante=="GUIA DE REMISION" || $tcomprobante=="NOTA DE CREDITO"){
        
    		
		
    	$sqlMailDocumento="Select * From ventas where id_venta='".$factura."' " ;
		$resultMailDocumento=mysql_query($sqlMailDocumento);
		while($rowMailDocumento=mysql_fetch_array($resultMailDocumento))//permite ir de fila en fila de la tabla
		{
		    $codEstablecimiento=$rowMailDocumento['codigo_pun'];
		    $codPunto=$rowMailDocumento['codigo_lug'];
			$secuencial=ceros($rowMailDocumento['numero_factura_venta']);
			$fechaVenta=$rowMailDocumento['fecha_venta'];
			$fid_cliente=$rowMailDocumento['id_cliente'];
			
		}
		
		 $fcliente="Select * From clientes where id_cliente='".$fid_cliente."' " ;
		$fclienteResult=mysql_query($fcliente);
		while($clienteResult=mysql_fetch_array($fclienteResult))//permite ir de fila en fila de la tabla
		{
		    $fclientenombre=$clienteResult['nombre'];
		    $fapelldocliente=$clienteResult['apellido'];
		    $nombreClienteFactura=$fclientenombre.' '.$fapelldocliente;
		}
		
	    $sqlMailEstablecimiento="Select * From establecimientos where id='".$codEstablecimiento."' " ;
		$resultMailEsta=mysql_query($sqlMailEstablecimiento);
		while($rowMailEstablecimiento=mysql_fetch_array($resultMailEsta))//permite ir de fila en fila de la tabla
		{
		    $estab=$rowMailEstablecimiento['codigo'];
		}
		
		$sqlMailEmision="Select * From emision where id='".$codPunto."' " ;
		$resultMailEmision=mysql_query($sqlMailEmision);
		while($rowMailEmision=mysql_fetch_array($resultMailEmision))//permite ir de fila en fila de la tabla
		{
		    $ptoEmi=$rowMailEmision['codigo'];
		}


	}else if($tcomprobante=="COMPROBANTE DE RETENCION" || $tcomprobante=="LIQUIDACION DE COMPRA"){
	    
	    $sqlMailDocumentoRetencion="SELECT `id_compra`, `fecha_compra`, `total`, `sub_total`, `subTotal0`, `subTotal12`, `subTotalInvenarios`, `descuento`, `exentoIVA`, `noObjetoIVA`, `montoIce`, `id_iva`, `id_proveedor`, `numero_factura_compra`, `id_empresa`, `numSerie`, `txtEmision`, `txtNum`, `autorizacion`, `caducidad`, `TipoComprobante`, `codSustento`, `xml`, `Retfuente`, `Observaciones`, `Observaciones2`, `FechaAutorizacion`, `ClaveAcceso`, `subTotalProveeduria`, `subtotalServicios`, `ats`, `anulado`, `id_usuario`, `id_establecimiento` FROM `compras` WHERE  id_compra='".$factura."' " ;
		$resultMailDocumento=mysql_query($sqlMailDocumentoRetencion);
		while($rowMailDocumento=mysql_fetch_array($resultMailDocumento))//permite ir de fila en fila de la tabla
		{
		    $estab=$rowMailDocumento['numSerie'];
		    $ptoEmi=$rowMailDocumento['txtEmision'];
			$secuencial=ceros($rowMailDocumento['numero_factura_compra']);
			$fechaVenta=$rowMailDocumento['fecha_compra'];
			$fid_cliente=$rowMailDocumento['id_proveedor'];
			
		}
     
	    
        $fProveedores="SELECT `id_proveedor`, `rbCaracterIdentificacion`, `parteRel`, `nombre_comercial`, `nombre`, `direccion`, `ruc` FROM `proveedores` WHERE id_proveedor='".$fid_cliente."' " ;
		$fproveedorResult=mysql_query($fProveedores);
		while($proveedorResult=mysql_fetch_array($fproveedorResult))//permite ir de fila en fila de la tabla
		{
		    $nombreClienteFactura=$proveedorResult['nombre_comercial'];
		}

       
        
	   $sqlMcretencion="SELECT `Id`, `Factura_id`, `Numero`, `Fecha`, `TipoC`, `Autorizacion`, `Total`, `Total1`, `FechaAutorizacion`, `Retfuente`, `ClaveAcceso`, `Observaciones`, `Observaciones2`, `Serie`, `anulado` FROM `mcretencion` WHERE Factura_id='".$factura."' AND TipoC='1' ";
	   $resultRetencion = mysql_query($sqlMcretencion);
	   while($rowMc = mysql_fetch_array($resultRetencion) ){
	       $serie = $rowMc['Serie'];
	   }

        $partes = explode("-", $serie);
        $estab = $partes[0];
        $ptoEmi = $partes[1];
	
	    
	    
	}
    
		
		

    	$server_name = $_SERVER['SERVER_NAME'];

        if (substr($server_name, 0, 4) === 'www.') {
            $server_name = substr($server_name, 4);
        }
					    	
					    	
        $mail->SetFrom('facturas@'.$server_name,$sesion_empresa_nombre );
		$mail->AddReplyTo('facturas@'.$server_name,$sesion_empresa_nombre);
		$address = $correo;
		$mail->AddAddress($address, $Ofact["Nombres"]);
// 		$mail->Subject = utf8_decode("Notificación de Comprobante Electrónico ".strtoupper($tipoc)." :").$estab."-".$ptoEmi."-".$secuencial;
		$mail->Subject = "Notificación de Comprobante Electrónico ";
		$message = file_get_contents("../email_template.html");
		
		//$mail->isHTML(true);  // Establecer el formato de correo electr?nico en
		
// 			$body="<p>&nbsp;</p>";
		$body.="<p>Su comprobante correspondiente a la fecha:".$fechaVenta." </p>";
		$body.="Se adjunta la información correspondiente a su {$tcomprobante}  cualquier inquietud favor remitirse a la dirección electrónica de la institución que emitió este comprobante electrónico.</p>";
		$body.="<p> Este correo ha sido generado automáticamente, por favor no responda al mismo.  </p>";

				
					$message = str_replace('{{message}}', $body, $message);
					
					$mail->isHTML(true); 
			
		
				$message = str_replace('{{first_name}}', "Estimado(a)", $message);
					
					$message = str_replace('{{message}}', $body, $message);
					$message = str_replace('{{factura}}', $estab."-".$ptoEmi."-".$secuencial, $message);
					$message = str_replace('{{comprobante}}', $tcomprobante, $message);
					$message = str_replace('{{empresa}}',$sesion_empresa_nombre, $message);
					//$message = str_replace('{{logo}}', $_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos(substr($_SERVER['PHP_SELF'],1,strlen($_SERVER['PHP_SELF'])),"/")+1)."/files/".$_COOKIE['Logo'], $message);
					$body="<p>&nbsp;</p>";
							$body.="<p>Su comprobante correspondiente a la fecha:".$fechaEmision." </p>";
							$body.="<p> Estimado(a) : ".$Ofact["Nombres"]."<br><br>";
							$body.="Se adjunta la información correspondiente a su $tcomprobante ".$estab."-".$ptoEmi."-".$secuencial."<br>";
							$body.="cualquier inquietud favor remitirse a la dirección electrónica de la institución que emitió este 
							comprobante electrónico.</p>";
							$body.="<p> Este correo ha sido generado automáticamente, por favor no responda al mismo.  </p>
					<p>Saludos Cordiales</p>";
				// 			$body.='<p>Consulte el Historial de sus facturas en nuestro sitio <a href="'.$_COOKIE['Web'].'" title="Consulta de Facturas" target="new">Mis Facturas</a></p>
				// 	<p>&nbsp;</p>
				// 	<p>&nbsp;</p>';
							//$mail->MsgHTML($body);
							$mail->MsgHTML($message);
							
							$mail->AddAttachment("../facturas/".$fffile.'.pdf');     
							
							$mail->AddAttachment($archivo); 
							
							
							//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
							//echo $address;
							if(!$mail->Send()) {

								 $Err[]=$mail->ErrorInfo;
								//echo $mail->ErrorInfo;
							}else {
							     $Err[]= "Correo se envio correctamente";
								$nf++;
							}
						}else{

						 $Err[]="No Tiene Registrado Un Correo";
						}
                $conn=null;		
                return $Err[0];
                }

	
function genXml($id){
    
	$result=mysql_query( "SELECT codigo_lug as codigo_lug,ambiente as ambiente,tipoEmision as tipoEmision, empresa.nombre as nombreComercial, 
empresa.razonSocial as razonSocial, empresa.ruc as ruc, ClaveAcceso as claveAcceso,tipo_documento as codDoc,establecimientos.codigo as estab,
emision.codigo as ptoEmi,numero_factura_venta as secuencial,empresa.direccion as dirMatriz,empresa.clave as clave,empresa.FElectronica as firma,
ventas.fecha_venta as fechaEmision,clientes.apellido,clientes.nombre,clientes.direccion,clientes.cedula,clientes.estado,clientes.caracter_identificacion,
empresa.Ocontabilidad,empresa.FElectronica,ventas.sub0,ventas.sub12,ventas.sub_total,ventas.descuento,impuestos.iva,impuestos.codigo as codigoImpuesto,
clientes.telefono,clientes.email,
empresa.autorizacion_sri,ventas.descripcion,establecimientos.direccion as dirSucursal,empresa.leyenda as rimpe,empresa.leyenda2 as retencion,
ventas.propina as propina,ventas.total as totalventas,emision.SOCIO as socio,ventas.Vendedor_id as vendedor,empresa.id_empresa as idEmpresa,
ventas.tipo_inco_term, ventas.lugar_inco_term, ventas.pais_origen AS origenPais, ventas.puerto_embarque ,ventas.puerto_destino as puertopaisdestino , ventas.pais_destino as destinoPais, 
ventas.pais_adquisicion as adquisicionPais, 
ventas.numero_dae,ventas.numero_transporte, ventas.flete_internacional, ventas.seguro_internaiconal, ventas.gastos_aduaneros, ventas.gastos_transporte


from ventas,emision,empresa,establecimientos,clientes,impuestos 
WHERE impuestos.id_iva=ventas.id_iva AND   clientes.id_cliente=ventas.id_cliente AND  id_venta='".$id."' 
and emision.id=codigo_lug and empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun 
and emision.id=ventas.codigo_lug");   


 	$row=mysql_fetch_array($result);
	$estado="";
	$ambiente="PRUEBAS"; //1 Pruebas 2 Producción   texto
	$ambiente_id=$row['ambiente'];//1 Pruebas 2 Producción   numero
	$tipoemision="1";//1 Emisión Normal  2 Emisión por Indisponibilidad del Sistema
	$numeroAutorizacion="";
	$fechaAutorizacion="";
	$codDoc="0".$row['codDoc'];//01 FACTURA  04 NOTA DE CRÉDITO 05 NOTA DE DÉBITO 06 GUÍA DE REMISIÓN 07  COMPROBANTE DE RETENCIÓN
	$claveAcceso=generarClave($id,$codDoc,$row['ruc'],$row['ambiente'],$row['estab'].$row['ptoEmi'],ceros($row['secuencial']),substr(date("d/m/Y",strtotime($row['fechaEmision'])),0,2).substr(date("d/m/Y",strtotime($row['fechaEmision'])),3,2).substr(date("d/m/Y",strtotime($row['fechaEmision'])),6,4), $tipoemision);

	$tipoIdentificacionComprador=$row['caracter_identificacion'];  // RUC  04  CEDULA 05  PASAPORTE 06 VENTA A CONSUMIDOR FINAL 07 IDENTIFICACION DELEXTERIOR* 08  PLACA 09
	//header( "content-type: application/xml; charset=encoding='UTF-8' standalone='true'" );
	// "Create" the document.
	
	
	$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$s .= "<factura id=\"comprobante\" version=\"1.1.0\">\n";		
			$s .= "<infoTributaria>\n";
				$s .= "<ambiente>".$ambiente_id."</ambiente>\n";
				$s .= "<tipoEmision>".$tipoemision."</tipoEmision>\n";
				$s .= "<razonSocial>".eliminar_tildes(utf8_decode(substr($row['razonSocial'], 0,300))) ."</razonSocial>\n";
				$s .= "<nombreComercial>".eliminar_tildes(substr($row['nombreComercial'], 0,300))."</nombreComercial>\n";
				$s .= "<ruc>".substr($row['ruc'],0,13)."</ruc>\n";
				$s .= "<claveAcceso>".substr($claveAcceso,0,49)."</claveAcceso>\n";
				$s .= "<codDoc>".substr($codDoc,0,2)."</codDoc>\n";
				$s .= "<estab>".substr($row['estab'],0,3)."</estab>\n";
				$s .= "<ptoEmi>".substr($row['ptoEmi'],0,3)."</ptoEmi>\n";
				$s .= "<secuencial>".substr(ceros($row['secuencial']),0,9)."</secuencial>\n";
				$s .= "<dirMatriz>".eliminar_tildes(utf8_decode(substr($row['dirMatriz'],0,300)))."</dirMatriz>\n";
				
				if($row['retencion']=="1")
				$s .= "<agenteRetencion>".(substr($row['retencion'],0,299))."</agenteRetencion>\n";
				
				
				if($row['rimpe']!="")
				$s .= "<contribuyenteRimpe>".(substr($row['rimpe'],0,299))."</contribuyenteRimpe>\n";
				
				
			$s .= "</infoTributaria>\n";
			$s .= "<infoFactura>\n";
				$s .= "<fechaEmision>".substr(date("d/m/Y",strtotime($row['fechaEmision'])),0,10)."</fechaEmision>\n";
				$s .= "<dirEstablecimiento>".eliminar_tildes(utf8_decode(substr($row['dirSucursal'],0,300)))."</dirEstablecimiento>\n";
				if($contr != '')
				$s .= "<contribuyenteEspecial>".substr($contr,0,13)."</contribuyenteEspecial>\n";
				$obc=($row['Ocontabilidad']==1)?"SI":"NO";
				$s .= "<obligadoContabilidad>".$obc."</obligadoContabilidad>\n";
				
				
				
				$s .= "<tipoIdentificacionComprador>".substr($tipoIdentificacionComprador,0,2)."</tipoIdentificacionComprador>\n";				
				$s .= "<razonSocialComprador>".eliminar_tildes(utf8_decode(substr($row['nombre']." ".$row['apellido'],0,300)))."</razonSocialComprador>\n";
                $s .= "<identificacionComprador>" . substr(trim($row['cedula']), 0, 20) . "</identificacionComprador>\n";
			
	
				$s .= "<totalSinImpuestos>".number_format($row['sub_total'], 2, '.', '')."</totalSinImpuestos>\n";
				
				$s .= "<totalDescuento>".$row['descuento']."</totalDescuento>\n";
                $s .= "<totalConImpuestos>\n";
                
     $sqlDetalleV = "SELECT impuestos.id_iva, impuestos.codigo, impuestos.iva, detalle_ventas.`id_detalle_venta`, detalle_ventas.`idBodega`, 
                detalle_ventas.`idBodegaInventario`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`, detalle_ventas.`descuento`, 
                SUM(detalle_ventas.`v_total`) AS base_imponibleTarifas, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`detalle`,
                detalle_ventas.`id_kardex`, 
                detalle_ventas.`tipo_venta`, detalle_ventas.`id_empresa`,  detalle_ventas.`tarifa_iva`, SUM(detalle_ventas.`total_iva`) as suma_iva 
                FROM `detalle_ventas` INNER JOIN impuestos ON impuestos.id_iva = detalle_ventas.tarifa_iva WHERE detalle_ventas.id_venta = '".$id."' 
                GROUP BY impuestos.id_iva ";
				$resultDetVenta = mysql_query( $sqlDetalleV );
				while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
				    
					$codigoPorcentaje=$rowDetVent['codigo'];

					$s .= "<totalImpuesto>\n";
                    $s .= "<codigo>2</codigo>\n";
                    $s .= "<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>\n";
                    $s .= "<descuentoAdicional>0.00</descuentoAdicional>\n";
                    $s .= "<baseImponible>".number_format($rowDetVent['base_imponibleTarifas'], 2, '.', '')."</baseImponible>\n";
                    // $s .= "<tarifa>".number_format($row['iva'], 0)."</tarifa>\n";
                    $s .= "<valor>".number_format($rowDetVent['base_imponibleTarifas']*($rowDetVent['iva']/100), 2, '.', '')."</valor>\n";
                    $s .= "</totalImpuesto>\n";
				}
                
                $s .= "</totalConImpuestos>\n";
                
				$s .= "<propina>".number_format($row['propina'] , 3, '.', '')."</propina>\n";
			
		
				
			
			 //   $s .= "<importeTotal>".number_format(($row['sub12']-(($row['sub12']*$row['descuento'])/100))*(1+$row['iva']/100)+$row['sub0']+$row['propina'], 2, '.', '')."</importeTotal>\n";
			    $s .= "<importeTotal>".number_format($row['totalventas'], 3, '.', '')."</importeTotal>\n";

			    $s .= "<moneda>DOLAR</moneda>\n";
			    $s .= "<pagos>\n";
				$s .= "<pago>\n";
				$sqlFormasPago="SELECT `id`, `id_forma`, `documento`, `id_factura`, `id_empresa`, `valor`, `tipo`, `porcentaje`, `fecha_registro`, `numero_retencion`, `autorizacion`, `intervalo_cuotas` FROM `cobrospagos` WHERE documento='0' AND `id_factura` = '".$id."'  ORDER BY `cobrospagos`.`id` ASC LIMIT 1";
				$resultFormasPago = mysql_query($sqlFormasPago);
				$plazo=0;
				$tipo = 0;
				while($rowFP = mysql_fetch_array($resultFormasPago) ){
				    	$plazo=$rowFP['intervalo_cuotas'];
				    	$tipo =$rowFP['tipo'];
				}
				$plazo = trim($plazo)==''?0:$plazo;
			
				
					$fp=($row['FormaPago']=="01")?"01":"20";
				// 	$fp=($plazo=="0")?"01":"20";
		            $s .= "<formaPago>".$fp."</formaPago>\n";
		  //    $s .= "<total>".number_format(($row['sub12']-(($row['sub12']*$row['descuento'])/100))*(1+$row['iva']/100)+$row['sub0'], 2, '.', '')."</total>\n";//-(($row['subt0']+$row['subt12'])*$row['Descuento'])/100
					
					$s .= "<total>".number_format($row['totalventas'], 3, '.', '')."</total>\n";//-(($row['subt0']+$row['subt12'])*$row['Descuento'])/100

				// 	if($row['Plazo']!=0){
		            $s .= "<plazo>$plazo</plazo>\n";
		            $s .= "<unidadTiempo>Dias</unidadTiempo>\n";
				// 	}
		        	$s .= "</pago>\n";	
		        $s .= "</pagos>\n";
		        
			$s .= "</infoFactura>\n";
	
					$s .= "<detalles>\n";
				 //$result1=mysql_query( "SELECT detalle_ventas.cantidad,detalle_ventas.descuento,detalle_ventas.v_unitario,detalle_ventas.detalle, productos.codigo,productos.producto,productos.iva ,detalle_ventas.v_tota	FROM detalle_ventas, productos WHERE detalle_ventas.id_servicio= productos.id_producto AND detalle_ventas.id_venta='".$id."'");   
                 $result1=mysql_query( "SELECT detalle_ventas.cantidad,detalle_ventas.descuento,detalle_ventas.v_unitario,detalle_ventas.detalle,
				 productos.codigo,productos.producto,productos.iva ,detalle_ventas.v_total, detalle_ventas.v_total,  detalle_ventas.total_iva as ivatotal, 
				 impuestos.codigo as codigo_iva, impuestos.id_iva, impuestos.iva
				FROM detalle_ventas, productos  ,impuestos  
				WHERE detalle_ventas.id_servicio= productos.id_producto AND  impuestos.id_iva = detalle_ventas.tarifa_iva AND detalle_ventas.id_venta='".$id."'");   

 				while($det=mysql_fetch_array($result1)){
					$ivaproducto=$det['iva'];
					
					$s .= "<detalle>\n";
				    $s .= "<codigoPrincipal>".substr($det['codigo'],0,25)."</codigoPrincipal>\n";
				    $s .= "<descripcion>".eliminar_tildes(utf8_decode(substr($det['producto'],0,300)))."</descripcion>\n";
				    $s .= "<cantidad>".$det['cantidad']."</cantidad>\n";
				    $s .= "<precioUnitario>".number_format($det['v_unitario'], 6, '.', '')."</precioUnitario>\n";
				    $s .= "<descuento>".number_format($det['descuento']*$det['cantidad'], 2, '.', '')."</descuento>\n";
				    $s .= "<precioTotalSinImpuesto>".number_format($det['v_total'], 2, '.', '')."</precioTotalSinImpuesto>\n";-//$det['cantidad']*$det['v_unitario']*($det['descuento']=="")?0:$det['descuento']/100
				    $detalleAdicional = $det['detalle'];
				    if($detalleAdicional!=""){
				    $s .= "<detallesAdicionales>\n";
				    $s .= "<detAdicional nombre=\"detalle\" valor=\"".$det['detalle']."\" ></detAdicional>\n";
				    $s .= "</detallesAdicionales>\n";
				    }
				    $s .= "<impuestos>\n";		
				    
				   // $resultimpuestos=mysql_query( "select * from impuestos where id_empresa='".$row['idEmpresa']."' ");
                  //  $Oimpuesto=mysql_fetch_array($resultimpuestos);
					
					if($det['iva']>0  && $tipoIdentificacionComprador!='08'){
                      
                          $codigoPorcentaje=$det['codigo_iva'];
                        $Iva =$det['iva'];
                        // $codigoPorcentaje=$row['codigoImpuesto'];
                       // $Iva = $Oimpuesto['iva'];
                    }else{
                        // Si las condiciones iniciales no se cumplen, establecemos tanto el codigoPorcentaje como el Iva a '0'
                        $codigoPorcentaje = '0';
                        $Iva = '0';
                    }
                       	 
				     
   	                $s .= "<impuesto>\n";
					$s .= "<codigo>2</codigo>\n";
				    $s .= "<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>\n";
				    $s .= "<tarifa>$Iva</tarifa>\n";
				    $s .= "<baseImponible>".number_format($det['v_total'], 2, '.', '')."</baseImponible>\n";//-$det['cantidad']*$det['v_unitario']*($det['descuento']=="")?0:$det['descuento']/100
				    
				//$s .= "<valor>".number_format((($det['cantidad']*($det['v_unitario']-$det['descuento'])))*($Iva/100), 2, '.', '')."</valor>\n";//-$det['cantidad']*$det['v_unitario']*($det['descuento']=="")?0:$det['descuento']/100
				   	
				   	$s .= "<valor>".number_format($det['ivatotal'], 2, '.', '')."</valor>\n";
				   	
				   	//-$det['cantidad']*$det['v_unitario']*($det['descuento']=="")?0:$det['descuento']/100

				    $s .= "</impuesto>\n";	
				
				    $s .= "</impuestos>\n";
				    $s .= "</detalle>\n";	
				}
			
		  	$s .= "</detalles>\n";		
			$s .= "<infoAdicional>\n";	

				$s .= "<campoAdicional nombre=\"DIRECCION\">".eliminar_tildes(utf8_decode(substr($row['direccion'],0,299)))."</campoAdicional>\n";									

				if($row['telefono']!=""){
				$s .= "<campoAdicional nombre=\"TELEFONO\">".(substr($row['telefono'],0,299))."</campoAdicional>\n";	    
				}
				
				if($row['email']!=""){
				$s .= "<campoAdicional nombre=\"EMAIL\">".(substr($row['email'],0,299))."</campoAdicional>\n";    
				}
				
				if($notifica!=""){
				    $s .= "<campoAdicional nombre=\"MENSAJE\">".eliminar_tildes(utf8_decode(substr($notifica,0,299)))."</campoAdicional>\n";
				}
				
				if($row['descripcion']!=""){
				    $s .= "<campoAdicional nombre=\"DETALLE\">".eliminar_tildes(utf8_decode(substr($row['descripcion'],0,299)))."</campoAdicional>\n";
				}
			
            	 $sqlIA="SELECT `id_info_adicional`, `campo`, `descripcion`, `id_venta`, `id_empresa`,xml FROM `info_adicional` WHERE  id_venta=$id ";
				$resultIA = mysql_query($sqlIA);
				while($rowIA = mysql_fetch_array($resultIA) ){
				   
        			if($rowIA['campo']!='DIRECCION' && $rowIA['campo']!='TELEFONO' && $rowIA['campo']!='EMAIL'&& $rowIA['campo']!='MENSAJE'&& $rowIA['campo']!='DETALLE'){
        			    	$s .= "<campoAdicional nombre=\"".$rowIA['campo']."\">".(substr($rowIA['descripcion'],0,299))."</campoAdicional>\n";	
        			}
    			
				}
				
			if($row['socio']!="0"){

			    $resultTrans=mysql_query( "select Nombres,Cedula,Placa,regimen,emision,est from transportista where Id='".$row['socio']."' ");

  	            while ($resultTrans22 = mysql_fetch_array($resultTrans)) {
  	                    
          	            $s .= "<campoAdicional nombre=\"NOMBRES\">".eliminar_tildes(utf8_decode(substr($resultTrans22['Nombres'],0,299)))."</campoAdicional>\n";	
          	            $s .= "<campoAdicional nombre=\"RUC\">".$resultTrans22['Cedula']."</campoAdicional>\n";	
          	            $s .= "<campoAdicional nombre=\"PLACA\">".$resultTrans22['Placa']."</campoAdicional>\n";
          	            
          	            if (!empty(trim($resultTrans22['regimen']))) {
                            $s .= "<campoAdicional nombre=\"REGIMEN\">" . $resultTrans22['regimen'] . "</campoAdicional>\n";
                        }

                        if (!empty(trim($resultTrans22['emision']))) {
                           $s .= "<campoAdicional nombre=\"PUNTO\">".$resultTrans22['emision']."</campoAdicional>\n";
                        }
          	           
  	            }
			}
				
				
			$s .= "</infoAdicional>";	
		$s .="\n</factura>";
		
      
   

		// $consulta= generarFirma( $s,$claveAcceso,'factura',$row['clave'],$row['firma'],$ambiente_id,$row['autorizacion_sri']);
		
		
        
		$doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($s); // xml  
		$doc->save("../xmls/generados/factura_".$row['ruc'].$row['estab'].$row['ptoEmi'].ceros($row['secuencial']).".xml"); 
exit;
		consultaSRI($consulta,$ambiente_id,$claveAcceso,'factura',$id);	
		$conn=null;

}
genXml(419706);
function genXmlRet($factura){
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_usuario = $_SESSION['sesion_id_usuario'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_empresa_imagen = $_SESSION['sesion_empresa_imagen'];
    $sesion_empresa_razonSocial =$_SESSION['sesion_empresa_razonSocial'];
    $sesion_empresa_direccion =$_SESSION['sesion_empresa_direccion'];
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
    $emision_ambiente = $_SESSION["emision_ambiente"];
    
    $emision_codigo = $_SESSION["emision_codigo"];
	$establecimiento_codigo = $_SESSION["establecimiento_codigo"];
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    
    $Ocontabilidad = $_SESSION["Ocontabilidad"];
    
    $empresa_contribuyente=$_SESSION['empresa_contribuyente'] ;
    
    
    
    $result1=mysql_query( "select * from mcretencion where Id='".$factura."' and anulado='0' ");
    // echo "select * from mcretencion where Id='".$factura."' "."</br>";;
 	$OMaster=mysql_fetch_array($result1);
 	
//	$OMaster=new CMySQL1($conn,"select * from mcretencion where Id=?",array($factura));
// 	echo $OMaster['Id']."</br>";
	
	$result2=(mysql_query("SELECT dcretencion.CodImp as Codigo, dcretencion.TipoImp, dcretencion.BaseImp, dcretencion.Porcentaje FROM  
	dcretencion WHERE dcretencion.Retencion_id=".$OMaster['Id'] ) );
	
	
//  	echo "SELECT dcretencion.CodImp as Codigo, dcretencion.TipoImp, dcretencion.BaseImp, dcretencion.Porcentaje FROM  
// 	dcretencion WHERE dcretencion.Retencion_id=".$OMaster['Id'] ;
	
	$OComp=mysql_fetch_array(mysql_query("select * from compras where id_compra=".$OMaster['Factura_id']));
// 	echo "select * from compras where id_compra=".$OMaster['Factura_id'];
	
	$OEmp=mysql_fetch_array(mysql_query("select * from empresa where id_empresa=".$OComp['id_empresa']));
// 	echo "select * from empresa where id_empresa=".$OComp['id_empresa'];

	$OCli=mysql_fetch_array(mysql_query("select * from proveedores where id_proveedor=".$OComp['id_proveedor']));
	//echo $dom->saveXML();
	$estado="";
	$ambiente="PRUEBAS"; //1 Pruebas 2 Producciظ├ق╛n   texto
	$ambiente_id=$emision_ambiente;//1 Pruebas 2 Producciظ├ق╛n   numero
	$tipoemision='1';//1 Emisiظ├ق╛n Normal  2 Emisiظ├ق╛n por Indisponibilidad del Sistema
	$numeroAutorizacion="";
	$fechaAutorizacion="";
	$codDoc="07";//01 FACTURA  04 NOTA DE CRظ├أ╩DITO 05 NOTA DE Dظ├أ╩BITO 06 GUظ├ق╛ûA DE REMISIظ├أ═N 07  COMPROBANTE DE RETENCIظ├أ═N
	$claveAcceso=generarClave($id,$codDoc,$sesion_empresa_ruc,$ambiente_id,$establecimiento_codigo.$emision_codigo,ceros($OMaster['Numero']),substr(date("d/m/Y",strtotime($OMaster['Fecha'])),0,2).substr(date("d/m/Y",strtotime($OMaster['Fecha'])),3,2).substr(date("d/m/Y",strtotime($OMaster['Fecha'])),6,4), $tipoemision);
	
// 	echo "</br> CLAVE DE ACCESO ==>".$id."<br> 1 ==>".$codDoc."<br> 2 ==>".$sesion_empresa_ruc."<br> 3 ==>".$ambiente_id."<br> 4 ==>".$establecimiento_codigo.$emision_codigo."<br> 5 ==>".ceros($OMaster['Numero'])."<br> A ==>".substr(date("d/m/Y",strtotime($OMaster['Fecha'])),0,2).substr(date("d/m/Y",strtotime($OMaster['Fecha'])),3,2).substr(date("d/m/Y",strtotime($OMaster['Fecha'])),6,4)."<br> 6 ==>". $tipoemision."</br>";
	
	$tipoIdentificacionComprador=$OCli['rbCaracterIdentificacion'];  // RUC  04  CEDULA 05  PASAPORTE 06 VENTA A CONSUMIDOR FINAL 07 IDENTIFICACION DELEXTERIOR* 08  PLACA 09
	//header( "content-type: application/xml; charset=ISO-8859-15" );
	// "Create" the document.
	
	$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$s .= "<comprobanteRetencion id=\"comprobante\" version=\"1.0.0\">\n";
		$s.="<infoTributaria>\n";
			$s.="<ambiente>".$ambiente_id."</ambiente>\n";
			$s.="<tipoEmision>".$tipoemision."</tipoEmision>\n";
			$s.="<razonSocial>".eliminar_tildes(utf8_decode($sesion_empresa_razonSocial))."</razonSocial>\n";
			$s.="<nombreComercial>".eliminar_tildes(utf8_decode($sesion_empresa_nombre))."</nombreComercial>\n";
			$s.="<ruc>".$sesion_empresa_ruc."</ruc>\n";
			$s.="<claveAcceso>".$claveAcceso."</claveAcceso>\n";
			$s.="<codDoc>".$codDoc."</codDoc>\n";
			$s.="<estab>".substr($OMaster['Serie'],0,3)."</estab>\n";
			$s.="<ptoEmi>".substr($OMaster['Serie'],4,3)."</ptoEmi>\n";
			$s.="<secuencial>".ceros($OMaster['Numero'])."</secuencial>\n";
			$s.="<dirMatriz>".eliminar_tildes(utf8_decode($sesion_empresa_direccion))."</dirMatriz>\n";
			
            	if($OEmp['leyenda2']=="1")
				$s .= "<agenteRetencion>".(substr($OEmp['leyenda2'],0,299))."</agenteRetencion>\n";
				
				
				if($OEmp['leyenda']!="")
				$s .= "<contribuyenteRimpe>".(substr($OEmp['leyenda'],0,299))."</contribuyenteRimpe>\n";
            
            
		$s.="</infoTributaria>\n";
		$s.="<infoCompRetencion>\n";
			$s.="<fechaEmision>".date("d/m/Y",strtotime($OMaster['Fecha']))."</fechaEmision>\n";
			$s.= "<dirEstablecimiento>".eliminar_tildes(utf8_decode(substr($sesion_empresa_direccion,0,300)))."</dirEstablecimiento>\n";
			if($empresa_contribuyente!="")
			$s.="<contribuyenteEspecial>".$empresa_contribuyente."</contribuyenteEspecial>\n";
			$obc=(	$Ocontabilidad ==1)?"SI":"NO";
			$s.="<obligadoContabilidad>".$obc."</obligadoContabilidad>\n";
			$s.="<tipoIdentificacionSujetoRetenido>".$tipoIdentificacionComprador."</tipoIdentificacionSujetoRetenido>\n";
			$s.="<razonSocialSujetoRetenido>".eliminar_tildes(utf8_decode($OCli['nombre']))."</razonSocialSujetoRetenido>\n";
			$s.="<identificacionSujetoRetenido>".$OCli['ruc']."</identificacionSujetoRetenido>\n";
			$s.="<periodoFiscal>".substr($OMaster['Fecha'],5,2)."/".substr($OMaster['Fecha'],0,4)."</periodoFiscal>\n";
	$s.="</infoCompRetencion>\n";
	$s.="<impuestos>\n";
	 while($ODetalle=mysql_fetch_array($result2)){
	     
		$s.="<impuesto>\n";
			$s.="<codigo>".$ODetalle['TipoImp']."</codigo>\n";
			$s.="<codigoRetencion>".$ODetalle['Codigo']."</codigoRetencion>\n";
			$s.="<baseImponible>".number_format($ODetalle['BaseImp'],2, '.', '')."</baseImponible>\n";
			$s.="<porcentajeRetener>".number_format($ODetalle['Porcentaje'],2, '.', '')."</porcentajeRetener>\n";
			$s.="<valorRetenido>".number_format($ODetalle['BaseImp']*$ODetalle['Porcentaje']/100,2, '.', '')."</valorRetenido>\n";
			$s.="<codDocSustento>"."0".$OComp['TipoComprobante']."</codDocSustento>\n";
		  //  $ndocsus=($OComp['TipoComprobante']==3)?str_replace("-","",$OComp['Nretencion']).ceros($OComp['numSerie']):$OComp['txtEmision'].$OComp['txtEmision'].ceros($OComp['txtNum']);
		
			$s.="<numDocSustento>".$OComp['numSerie'].$OComp['txtEmision'].ceros($OComp['txtNum'])."</numDocSustento>\n";
			//$s.="<numDocSustento>".substr(str_replace("-","",$OComp->Row['Factref']),0,15)."</numDocSustento>\n";
			$s.="<fechaEmisionDocSustento>".date("d/m/Y",strtotime($OMaster['Fecha']))."</fechaEmisionDocSustento>\n";
		$s.="</impuesto>\n";
		
	}
	$s.="</impuestos>\n";
	$s .= "<infoAdicional>\n";	
	            //$s .= "<campoAdicional nombre=\"Agente de Retencion\">No. Resolucion: 1</campoAdicional>\n";
				$s .= "<campoAdicional nombre=\"DIRECCION\">".''.eliminar_tildes(utf8_decode(substr($OCli['direccion'],0,299)))."</campoAdicional>\n";
				if($OCli['email']!="")										
				$s .= "<campoAdicional nombre=\"EMAIL\">".''.(substr($OCli['email'],0,299))."</campoAdicional>\n";
			$s .= "</infoAdicional>";	
	$s.="</comprobanteRetencion>";
	echo $s;
	
	$resultClave=mysql_fetch_array(mysql_query("SELECT clave,FElectronica,ruc,autorizacion_sri FROM  empresa WHERE id_empresa='".$sesion_id_empresa."'"));
        
       // echo "SELECT clave,FElectronica,ruc,autorizacion_sri FROM  empresa WHERE id_empresa='".$sesion_id_empresa."' ";

	$consulta= generarFirma($s,$claveAcceso,'comprobanteRetencion',$resultClave['clave'],$resultClave['FElectronica'],$ambiente_id,$resultClave['autorizacion_sri']);
//	echo "consulta"."</br>".$consulta."</br>";
	
		$doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($consulta); // xml  
		$doc->save("../xmls/generados/comprobanteRetencion_".$sesion_empresa_ruc.$establecimiento_codigo.$emision_codigo.ceros($OMaster['Numero']).".xml"); 
// 		 echo $doc;
		 
        // $respuesta=consultaSRI($consulta,$ambiente_id,$claveAcceso,'comprobanteRetencion',$factura);
        
        consultaSRI($consulta,$ambiente_id,$claveAcceso,'comprobanteRetencion',$factura);
        // print_r($respuesta);

	//$xml->save("xmls/generados/R_".$_COOKIE['Ruc'].$_COOKIE['Establecimiento'].$_COOKIE['PuntoEmision'].ceros($OMaster->Row['Numero']).".xml");
	$conn=null;
}

//	SendMail('../xmls/autorizados/comprobanteRetencion_1001570389001001001000000369.xml','macarena-marcela@hotmail.com','137','comprobanteRetencion');

function genXmlnc($id) {    

    //echo $dom->saveXML();
    $result = mysql_query("SELECT codigo_lug as codigo_lug,ambiente as ambiente,tipoEmision as tipoEmision, empresa.nombre as nombreComercial, 
empresa.razonSocial as razonSocial, empresa.ruc as ruc, ClaveAcceso as claveAcceso,tipo_documento as codDoc,establecimientos.codigo as estab,
emision.codigo as ptoEmi,numero_factura_venta as secuencial,empresa.direccion as dirMatriz,empresa.clave as clave,empresa.FElectronica as firma,
ventas.fecha_venta as fechaEmision,clientes.apellido,clientes.nombre,clientes.direccion,clientes.cedula,clientes.estado,clientes.caracter_identificacion,
empresa.Ocontabilidad,ventas.sub0 as sub0,ventas.sub12 as sub12,ventas.descuento,impuestos.iva,clientes.telefono,clientes.email,
empresa.autorizacion_sri,ventas.MotivoNota,ventas.descripcion,Retiva,empresa.leyenda as leyenda, empresa.leyenda2 as leyenda2,
empresa.id_empresa as idEmpresa,ventas.sub_total as subTotal,
ventas.valorModificacion as valorModificacion,ventas.total as ventasguiatotal
from ventas,emision,empresa,establecimientos,clientes,impuestos 
WHERE impuestos.id_iva=ventas.id_iva AND   clientes.id_cliente=ventas.id_cliente AND  id_venta='" . $id . "' and emision.id=codigo_lug 
and empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun and ventas.tipo_documento='4' and emision.id=ventas.codigo_lug");

    $row = mysql_fetch_array($result);
    
    
    
    
    
    $estado = "";
    $ambiente = "PRUEBAS"; //1 Pruebas 2 Producción   texto
    $ambiente_id = $row['ambiente']; //1 Pruebas 2 Producción   numero
    $tipoemision = "1"; //1 Emisión Normal  2 Emisión por Indisponibilidad del Sistema
    $numeroAutorizacion = "";
    $fechaAutorizacion = "";
    $codDoc = "0".$row['codDoc']; //01 FACTURA  04 NOTA DE CRÉDITO 05 NOTA DE DÉBITO 06 GUÍA DE REMISIÓN 07  COMPROBANTE DE RETENCIÓN
    $claveAcceso = generarClave($id, $codDoc, $row['ruc'], $row['ambiente'], $row['estab'] . $row['ptoEmi'], ceros($row['secuencial']), substr(date("d/m/Y", strtotime($row['fechaEmision'])), 0, 2) . substr(date("d/m/Y", strtotime($row['fechaEmision'])), 3, 2) . substr(date("d/m/Y", strtotime($row['fechaEmision'])), 6, 4), $tipoemision);

    $tipoIdentificacionComprador = $row['caracter_identificacion'];  // RUC  04  CEDULA 05  PASAPORTE 06 VENTA A CONSUMIDOR FINAL 07 IDENTIFICACION DELEXTERIOR* 08  PLACA 09

    
    
    $s = "";
    $s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    
    $s .= "<notaCredito id=\"comprobante\" version=\"1.1.0\">\n";
    
    $s .= "<infoTributaria>\n";
    $s .= "<ambiente>" . $ambiente_id . "</ambiente>\n";
    $s .= "<tipoEmision>" . $tipoemision . "</tipoEmision>\n";
    $s .= "<razonSocial>" . eliminar_tildes(utf8_decode(substr($row['razonSocial'], 0, 300))) . "</razonSocial>\n";
    $s .= "<nombreComercial>" . eliminar_tildes(substr($row['nombreComercial'], 0, 300)) . "</nombreComercial>\n";
    $s .= "<ruc>" . substr($row['ruc'], 0, 13) . "</ruc>\n";
    
    $s .= "<claveAcceso>" . substr($claveAcceso, 0, 49) . "</claveAcceso>\n";
    $s .= "<codDoc>" . substr($codDoc, 0, 2) . "</codDoc>\n";
    $s .= "<estab>" . substr($row['estab'], 0, 3) . "</estab>\n";
    $s .= "<ptoEmi>" . substr($row['ptoEmi'], 0, 3) . "</ptoEmi>\n";
    $s .= "<secuencial>" . substr(ceros($row['secuencial']), 0, 9) . "</secuencial>\n";
    $s .= "<dirMatriz>".eliminar_tildes(utf8_decode(substr($row['dirMatriz'], 0, 300)))."</dirMatriz>\n";
                
    if($row['leyenda2']=="1")
	$s .= "<agenteRetencion>".(substr($row['leyenda2'],0,299))."</agenteRetencion>\n";
	
	
	if($row['leyenda']!="")
	$s .= "<contribuyenteRimpe>".(substr($row['leyenda'],0,299))."</contribuyenteRimpe>\n";
				
    $s .= "</infoTributaria>\n";
    
    
    $s .= "<infoNotaCredito>\n";
    	$s.="<fechaEmision>".date("d/m/Y",strtotime($row['fechaEmision']))."</fechaEmision>\n";
    	
        // $s .= "<fechaEmision>".substr(date("d/m/Y", strtotime($row['fechaEmision'])), 0, 10)."</fechaEmision>\n";
        $s .= "<dirEstablecimiento>" . eliminar_tildes(utf8_decode(substr($row['dirMatriz'], 0, 300))) . "</dirEstablecimiento>\n";
        $s .= "<tipoIdentificacionComprador>" . substr($tipoIdentificacionComprador, 0, 2) . "</tipoIdentificacionComprador>\n";
        $s .= "<razonSocialComprador>" . eliminar_tildes(utf8_decode(substr($row['nombre'] . " " . $row['apellido'], 0, 300))) . "</razonSocialComprador>\n";
        $s .= "<identificacionComprador>" . substr($row['cedula'], 0, 20) . "</identificacionComprador>\n";
        // if($nroContribuyente != '')
        // $s .= "<contribuyenteEspecial>".substr($nroContribuyente,0,13)."</contribuyenteEspecial>\n";
        $obc = ($row['Ocontabilidad'] == 1) ? "SI" : "NO";
        $s .= "<obligadoContabilidad>" . $obc . "</obligadoContabilidad>\n";
    //	$s .= "<rise>"."</rise>\n";
   
    
    
        // $pizza = $row['Retiva'];
        // $pieces = explode(".", $pizza);
        $id_fv =  $row['Retiva'];


        if($id_fv!='0'){
        
            $result1 = mysql_query("SELECT establecimientos.codigo as estab,emision.codigo as ptoEmi,numero_factura_venta as secuencial,fecha_venta
            as fechaDocumento,ventas.total as totalVentaModificada
                        from ventas,emision,empresa,establecimientos,impuestos 
                        WHERE impuestos.id_iva=ventas.id_iva  AND  id_venta='$id_fv' and emision.id=codigo_lug and 
                        empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun and 
                        ventas.tipo_documento='1' and emision.id=ventas.codigo_lug");
                        
                        
                        
            $row1 = mysql_fetch_array($result1);
            
                $s .= "<codDocModificado>" . substr('01', 0, 2) . "</codDocModificado>\n";
                $secuencialfac = $row1['estab']. "-" .$row1['ptoEmi']. "-" . substr(ceros($row1['secuencial']), 0, 9);
                $s .= "<numDocModificado>" . $secuencialfac . "</numDocModificado>\n";
                $s .= "<fechaEmisionDocSustento>" . substr(date("d/m/Y", strtotime($row1['fechaDocumento'])), 0, 10) . "</fechaEmisionDocSustento>\n";
            }
        
        
            $descuento = 0;
            $totalSinImpuestos = 0;
            $total = 0;
        
            $sinivaCERO = $row['sub0'];
            $valorsuma = $sinivaCERO + $row['sub12'];
        
            $resultimpuestos=mysql_query( "select * from impuestos where id_empresa='".$row['idEmpresa']."' ");
            $Oimpuesto=mysql_fetch_array($resultimpuestos);
        
            $s .= "<totalSinImpuestos>" . number_format($row['subTotal'], 2, '.', '') . "</totalSinImpuestos>\n";
            
   
            
        
                //  if ($row1['totalVentaModificada'] != "0.00" || $row1['totalVentaModificada'] != "") {
                
         $s .= "<valorModificacion>" . number_format($row['ventasguiatotal'], 2, '.', '') . "</valorModificacion>\n";
// }
                                    
                                    
        $totalSinImpuestos = $row['sub12'];
        $tarifa = $row['sub12'];
        $iva = $row['iva'];
        $descuento = $row['descuento'];
        
        $total = number_format(($row['sub12'] - (($row['sub12'] * $row['descuento']) / 100)) * (1 + $row['iva'] / 100) + $row['sub0'], 2, '.', '');

        $s .= "<moneda>" . "DOLAR" . "</moneda>\n";

        $s .= "<totalConImpuestos>\n";
    $sqlDetalleV = "SELECT impuestos.id_iva, impuestos.codigo, impuestos.iva, detalle_ventas.`id_detalle_venta`, detalle_ventas.`idBodega`, 
                detalle_ventas.`idBodegaInventario`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`, detalle_ventas.`descuento`, 
                SUM(detalle_ventas.`v_total`) AS base_imponibleTarifas, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`detalle`,
                detalle_ventas.`id_kardex`, 
                detalle_ventas.`tipo_venta`, detalle_ventas.`id_empresa`,  detalle_ventas.`tarifa_iva`, SUM(detalle_ventas.`total_iva`) as suma_iva 
                FROM `detalle_ventas` INNER JOIN impuestos ON impuestos.id_iva = detalle_ventas.tarifa_iva WHERE detalle_ventas.id_venta = '".$id."' 
                GROUP BY impuestos.id_iva ";
				$resultDetVenta = mysql_query( $sqlDetalleV );
				while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
				    
					$codigoPorcentaje=$rowDetVent['codigo'];

					$s .= "<totalImpuesto>\n";
                    $s .= "<codigo>2</codigo>\n";
                    $s .= "<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>\n";
                  //  $s .= "<descuentoAdicional>0.00</descuentoAdicional>\n";
                    $s .= "<baseImponible>".number_format($rowDetVent['base_imponibleTarifas'], 2, '.', '')."</baseImponible>\n";
                    // $s .= "<tarifa>".number_format($row['iva'], 0)."</tarifa>\n";
                    $s .= "<valor>".number_format($rowDetVent['base_imponibleTarifas']*($rowDetVent['iva']/100), 2, '.', '')."</valor>\n";
                    $s .= "</totalImpuesto>\n";
				}
        $s .= "</totalConImpuestos>\n";


                                
        $motivo = $row['MotivoNota'];
        $fechaEmision = substr(date("d/m/Y", strtotime($row['fechaEmision'])), 0, 10);
        $s .= "<motivo>NC generada por: $motivo, FECHA/HORA: $fechaEmision</motivo>\n";
        $s .= "</infoNotaCredito>\n";
                        
        $s .= "<detalles>\n";
        // $result1 = mysql_query("SELECT detalle_ventas.cantidad,detalle_ventas.descuento,detalle_ventas.v_unitario,productos.codigo,productos.producto,productos.iva,  
        // detalle_ventas.detalle as detalle
        // FROM detalle_ventas, productos WHERE detalle_ventas.id_servicio= productos.id_producto AND detalle_ventas.id_venta='" . $id . "'");
        $result1=mysql_query( "SELECT detalle_ventas.cantidad,detalle_ventas.descuento,detalle_ventas.v_unitario,detalle_ventas.detalle,
				 productos.codigo,productos.producto,productos.iva ,detalle_ventas.v_total, detalle_ventas.v_total,  impuestos.codigo as codigo_iva, impuestos.id_iva, impuestos.iva
				FROM detalle_ventas, productos  ,impuestos  WHERE detalle_ventas.id_servicio= productos.id_producto AND  impuestos.id_iva = detalle_ventas.tarifa_iva AND detalle_ventas.id_venta='".$id."'");  
        while ($det = mysql_fetch_array($result1)) {
                  
                    $s .= "<detalle>\n";
                    $s .= "<codigoInterno>" . substr($det['codigo'], 0, 25) . "</codigoInterno>\n";
                        $s .= "<codigoAdicional>" . substr($det['codigo'], 0, 25) . "</codigoAdicional>\n";
                    $s .= "<descripcion>" . eliminar_tildes(utf8_decode(substr($det['producto'], 0, 300))) . "</descripcion>\n";
                    $s .= "<cantidad>" . $det['cantidad'] . "</cantidad>\n";
                    $s .= "<precioUnitario>" . number_format($det['v_unitario'], 2, '.', '') . "</precioUnitario>\n";
                    $s .= "<descuento>" . number_format($det['cantidad'] * $det['v_unitario'] * ($det['descuento'] == "") ? 0 : $det['descuento'] / 100, 2, '.', '') . "</descuento>\n";
                    $s .= "<precioTotalSinImpuesto>" . number_format(($det['cantidad'] * $det['v_unitario']), 2, '.', '') . "</precioTotalSinImpuesto>\n";
                    $detalleAdicional = $det['detalle'];
				    if($detalleAdicional!=""){
				    $s .= "<detallesAdicionales>\n";
				    $s .= "<detAdicional nombre=\"detalle\" valor=\"".$det['detalle']."\" ></detAdicional>\n";
				    $s .= "</detallesAdicionales>\n";
				    }
                                                
                $s .= "<impuestos>\n";
        if($det['iva']>0  && $tipoIdentificacionComprador!='08'){
                        
                          $codigoPorcentaje=$det['codigo_iva'];
                        $Iva =$det['iva'];
                       
                    }else{
                        
                        $codigoPorcentaje = '0';
                        $Iva = '0';
                    }
              $s .= "<impuesto>\n";
					$s .= "<codigo>2</codigo>\n";
				    $s .= "<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>\n";
				    $s .= "<tarifa>$Iva</tarifa>\n";
				    $s .= "<baseImponible>".number_format($det['v_total'], 2, '.', '')."</baseImponible>\n";//-$det['cantidad']*$det['v_unitario']*($det['descuento']=="")?0:$det['descuento']/100
				    $s .= "<valor>".number_format((($det['cantidad']*$det['v_unitario']-$det['descuento']))*($Iva/100), 2, '.', '')."</valor>\n";//-$det['cantidad']*$det['v_unitario']*($det['descuento']=="")?0:$det['descuento']/100
				    $s .= "</impuesto>\n";	
                    $s .= "</impuestos>\n";
				    $s .= "</detalle>\n";   
            }
            $s .= "</detalles>\n";

                $s .= "<infoAdicional>\n";
                //$s .= "<campoAdicional nombre=\"Agente de Retencion\">No. Resolucion: 1</campoAdicional>\n";
                if (trim($row['direccion']) != "")
                    $s .= "<campoAdicional nombre=\"DIRECCION\">" . eliminar_tildes(utf8_decode(substr($row['direccion'], 0, 299))) . "</campoAdicional>\n";
                if (trim($row['telefono']) != "")
                    $s .= "<campoAdicional nombre=\"TELEFONO\">" . (substr($row['telefono'], 0, 299)) . "</campoAdicional>\n";
                if (trim($row['email']) != "")
                    $s .= "<campoAdicional nombre=\"EMAIL\">" . (substr($row['email'], 0, 299)) . "</campoAdicional>\n";
                if (trim($notifica) != "")
                    $s .= "<campoAdicional nombre=\"MENSAJE\">" . eliminar_tildes(utf8_decode(substr($notifica, 0, 299))) . "</campoAdicional>\n";
                if (trim($row['descripcion']) != "")
                    $s .= "<campoAdicional nombre=\"DESCRIPCION\">" . eliminar_tildes(utf8_decode(substr($row['descripcion'], 0, 299))) . "</campoAdicional>\n";
                        
                $sqlIA="SELECT `id_info_adicional`, `campo`, `descripcion`, `id_venta`, `id_empresa`,xml FROM `info_adicional` WHERE  id_venta=$id ";
				$resultIA = mysql_query($sqlIA);
				while($rowIA = mysql_fetch_array($resultIA) ){
				   
        			if($rowIA['campo']!='DIRECCION' && $rowIA['campo']!='TELEFONO' && $rowIA['campo']!='EMAIL'&& $rowIA['campo']!='MENSAJE'&& $rowIA['campo']!='DETALLE'){
        			     if (trim($rowIA['campo']) != "")
        			    	$s .= "<campoAdicional nombre=\"".$rowIA['campo']."\">".(substr($rowIA['descripcion'],0,299))."</campoAdicional>\n";	
        			}
    			
				}
				
        $s .= "</infoAdicional>";
    $s .= "\n</notaCredito>";

// echo $s;

    $consulta = generarFirma($s, $claveAcceso, 'notaCredito', $row['clave'], $row['firma'], $ambiente_id, $row['autorizacion_sri']);

    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadXML($consulta); 
    $doc->save("../xmls/generados/notaCredito_".$row['ruc'].$row['estab'].$row['ptoEmi'].ceros($row['secuencial']).".xml");

    consultaSRI($consulta, $ambiente_id, $claveAcceso, 'notaCredito', $id);

    $conn = null;

    
}






function generarXMLGUIA($id) {
    
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_usuario = $_SESSION['sesion_id_usuario'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_empresa_imagen = $_SESSION['sesion_empresa_imagen'];
    $sesion_empresa_razonSocial =$_SESSION['sesion_empresa_razonSocial'];
    $sesion_empresa_direccion =$_SESSION['sesion_empresa_direccion'];
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
    $emision_ambiente = $_SESSION["emision_ambiente"];
    
    $emision_codigo = $_SESSION["emision_codigo"];
	$establecimiento_codigo = $_SESSION["establecimiento_codigo"];
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    
    $Ocontabilidad = $_SESSION["Ocontabilidad"];
    
    $empresa_contribuyente=$_SESSION['empresa_contribuyente'] ;



	$result=mysql_query( "SELECT codigo_lug as codigo_lug,ambiente as ambiente,tipoEmision as tipoEmision, empresa.nombre as nombreComercial, 
empresa.razonSocial as razonSocial, empresa.ruc as ruc, ClaveAcceso as claveAcceso,tipo_documento as codDoc,establecimientos.codigo as estab,
emision.codigo as ptoEmi,numero_factura_venta as secuencial,empresa.direccion as dirMatriz,empresa.clave as clave,empresa.FElectronica as firma,
ventas.fecha_venta as fechaEmision,clientes.apellido,clientes.nombre,clientes.direccion,clientes.cedula,clientes.estado,clientes.caracter_identificacion,
empresa.Ocontabilidad,empresa.FElectronica,ventas.sub0,ventas.sub12,ventas.descuento,impuestos.iva,clientes.telefono,clientes.email,
empresa.autorizacion_sri,ventas.descripcion,empresa.leyenda as rimpe,empresa.leyenda2 as retencion,
ventas.Vendedor_id as transportista,ventas.fechaInicio as fechaInicio,ventas.FechaFin as FechaFin,ventas.MotivoTraslado  as motivo,
ventas.DirDestino as destino,ventas.RetIva as RetIva,ventas.descripcion as obser,ventas.Retiva,ventas.DirOrigen as dirSucursal


from ventas,emision,empresa,establecimientos,clientes,impuestos 
WHERE impuestos.id_iva=ventas.id_iva AND   clientes.id_cliente=ventas.id_cliente AND  id_venta='".$id."' 
and emision.id=codigo_lug and empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun and emision.id=ventas.codigo_lug");   


	while ($row = mysql_fetch_array($result)) {
		$ruc = $row['ruc'];                             
		$fechaEmision = $row['fechaEmision'];
        $ambiente_id = $row['ambiente']; 
         
        $tipoemision="1";
         
        $codDoc="06";//01 FACTURA  04 NOTA DE CRظ├أ╩DITO 05 NOTA DE Dظ├أ╩BITO 06 GUظ├ق╛ûA DE REMISIظ├أ═N 07  COMPROBANTE DE RETENCIظ├أ═N
    	$claveAcceso=generarClave($id,$codDoc,$row['ruc'],$ambiente_id,$row['estab'].$row['ptoEmi'],ceros($row['secuencial']),substr(date("d/m/Y",strtotime($row['fechaEmision'])),0,2).substr(date("d/m/Y",strtotime($row['fechaEmision'])),3,2).substr(date("d/m/Y",strtotime($row['fechaEmision'])),6,4), $tipoemision);
        // echo  $row['fechaEmision']."</br>";
    // echo $claveAcceso."</br>";
                    
// 			$claveAcceso = $row['claveAcceso'];
			$razonSocial = $row['razonSocial'];
			$nombreComercial = $row['nombreComercial'];
			$direcionMatriz = $row['dirMatriz'];
			$direccionEstablecimiento = $row['dirSucursal'];
			
			$transportista = $row['transportista'];

			$identificacion = $row['cedula'];
			$nombreCliente= $row['nombre']." ".$row['apellido'];
			
            $cliente = $nombreCliente;
			$direcion = $row['direccion'];
			$telefono = $row['telefono'];
			$email = $row['email'];			
			$secuencial =  $row['estab']."-".$row['ptoEmi']."-".$row['secuencial'];	
            
            $secuencialresult  =  ceros($row['secuencial']);      
            
            $facafectar = $row['Retiva'];      
                   
			$establecimiento=$row['estab'];
			$puntoEmision = $row['ptoEmi'];
			$fechaAut = $row[30];
			$rimpe = $row['rimpe'];
			$destino = $row['destino'];  
			$direccionEstablecimiento = $row['dirSucursal'];
			
			$observacion= $row['obser'];
			
			$ruta = $direccionEstablecimiento."-".$destino;
			
			$contabilidad = $row['Ocontabilidad'];
			
			            $fecha_ini = $row['fechaInicio'];
                        $date = new DateTime($fecha_ini);
                        $fecha_ini= $date->format('d/m/Y');    
                        $fecha_fin = $row['FechaFin'];
                        $date = new DateTime($fecha_fin);
                        $fecha_fin= $date->format('d/m/Y');

                        
                        
            	$a=array("Venta"=>1,"Compra"=>2,"Transformacion"=>3,"Consignacion"=>4,"Traslado entre Establecimiento Misma Empresa"=>5,"Traslado por emisor itinerante de comprobante de venta"=>6,"Devolucion"=>7,"Importacion"=>8,"Exportacion"=>9,"Otros"=>10);
						foreach($a as $k=>$v)
							if($row['motivo']==$v)
								$motivot=$k;	            
                        

		}
        
      $consultaTrans = mysql_query("select * from transportista where  transportista.id= '$transportista' ");
        // echo "select * from transportista where  transportista.id= '$transportista' ";
		while ($row = mysql_fetch_array($consultaTrans)) {
                          $tipo_docu = $row['Tipo'];
                          $razontransp = $row['Nombres'];
                          $identificacion_trans=$row['Cedula'];
                          $placa=$row['Placa'];
                    }

      	$s = "";
		$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$s .= "<guiaRemision id=\"comprobante\" version=\"1.1.0\">\n";		
			$s .= "<infoTributaria>\n";
				

				$s .= "<ambiente>".$ambiente_id."</ambiente>\n";
				$s .= "<tipoEmision>".$tipoemision."</tipoEmision>\n";
				$s .= "<razonSocial>".eliminar_tildes(substr($razonSocial, 0,300) )."</razonSocial>\n";
				$s .= "<nombreComercial>".eliminar_tildes(substr($nombreComercial, 0,300))."</nombreComercial>\n";
				$s .= "<ruc>".substr($ruc,0,13)."</ruc>\n";
				$s .= "<claveAcceso>".substr($claveAcceso,0,49)."</claveAcceso>\n";
				$s .= "<codDoc>".substr($codDoc,0,2)."</codDoc>\n";
				$s .= "<estab>".substr($establecimiento,0,3)."</estab>\n";
				$s .= "<ptoEmi>".substr($puntoEmision,0,3)."</ptoEmi>\n";
				$s .= "<secuencial>".substr($secuencialresult,0,9)."</secuencial>\n";
				$s .= "<dirMatriz>".eliminar_tildes(substr($direcionMatriz,0,300))."</dirMatriz>\n";
				
				
				if($row['retencion']=="1")
				$s .= "<agenteRetencion>".(substr($row['retencion'],0,299))." </agenteRetencion>\n";
				
				if($rimpe!="")
				$s .= "<contribuyenteRimpe>".(substr($rimpe,0,299))."</contribuyenteRimpe>\n";
			
				
			$s .= "</infoTributaria>\n";
			$s .= "<infoGuiaRemision>\n";				
				$s .= "<dirEstablecimiento>".substr($direccionEstablecimiento,0,300)."</dirEstablecimiento>\n";				
				$s .= "<dirPartida>".$direccionEstablecimiento."</dirPartida>\n";
				$s .= "<razonSocialTransportista>".$razontransp."</razonSocialTransportista>\n";				
				$s .= "<tipoIdentificacionTransportista>".$tipo_docu."</tipoIdentificacionTransportista>\n";
				$s .= "<rucTransportista>".substr($identificacion_trans,0,20)."</rucTransportista>\n";
				
				if($contr != '')
				$s .= "<contribuyenteEspecial>".substr($contr,0,13)."</contribuyenteEspecial>\n";
				// $s .= "<rise>0</rise>\n";
				
				$obc=($contabilidad==1)?"SI":"NO";
				$s .= "<obligadoContabilidad>".$obc."</obligadoContabilidad>\n";
				// $s .= "<contribuyenteEspecial>0</contribuyenteEspecial>\n";
				
				$s .= "<fechaIniTransporte>".substr($fecha_ini,0,300)."</fechaIniTransporte>\n";
                $s .= "<fechaFinTransporte>".substr($fecha_fin,0,300)."</fechaFinTransporte>\n";
                $s .= "<placa>".substr($placa,0,300)."</placa>\n";
                
                
                

			$s .= "</infoGuiaRemision>\n";
			$s .= "<destinatarios>\n";
                $s .= "<destinatario>\n";                       
                $s .= "<identificacionDestinatario>".$identificacion."</identificacionDestinatario>\n";				
				$s .= "<razonSocialDestinatario>".$cliente."</razonSocialDestinatario>\n";
				$s .= "<dirDestinatario>".substr($destino,0,20)."</dirDestinatario>\n";
				$s .= "<motivoTraslado>".substr($motivot,0,300)."</motivoTraslado>\n";

				$s .= "<ruta>".substr($ruta,0,300)."</ruta>\n";
				
			if($facafectar!="0"){
    			 $resultFactura=mysql_query( "
    			 
    			 SELECT *,establecimientos.codigo as estab,emision.codigo as ptoEmi
                        from ventas,emision,empresa,establecimientos
                            WHERE           ventas.id_venta='$facafectar' and emision.id=codigo_lug and 
                        empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun 
    			 
    			 ");   
    			 while ($rowFactura = mysql_fetch_array($resultFactura)) {
    			    $s .= "<codDocSustento>0".$rowFactura['tipo_documento']."</codDocSustento>\n";
    				$s .= "<numDocSustento>".$rowFactura['estab']."-".$rowFactura['ptoEmi']."-".ceros($rowFactura['numero_factura_venta'])."</numDocSustento>\n";
    				$s .= "<numAutDocSustento>".$rowFactura['Autorizacion']."</numAutDocSustento>\n";
    				
    			 }
			 }

				
				
                $s .= "<detalles>\n";					
                                                 
			 $result1=mysql_query( "SELECT detalle_ventas.cantidad,detalle_ventas.descuento,
			 detalle_ventas.v_unitario,productos.codigo,productos.producto,productos.iva, detalle_ventas.detalle as detalleV
				FROM detalle_ventas, productos WHERE 
				detalle_ventas.id_servicio= productos.id_producto AND detalle_ventas.id_venta='".$id."'");   
				
				
				// echo "SELECT detalle_ventas.cantidad,detalle_ventas.descuento,
			 //detalle_ventas.v_unitario,productos.codigo,productos.producto,productos.iva, detalle_ventas.detalle as detalleV
				// FROM detalle_ventas, productos WHERE 
				// detalle_ventas.id_servicio= productos.id_producto AND detalle_ventas.id_venta='".$id."'";
				while ($row2 = mysql_fetch_array($result1)) {
				    
				    $detalleV = $row2['detalleV'];
				    
                    $s .= "<detalle>\n";
				    $s .= "<codigoInterno>".$row2['codigo']."</codigoInterno>\n";
				    $s .= "<descripcion>".$row2['producto']."</descripcion>\n";
				    $s .= "<cantidad>".$row2['cantidad']."</cantidad>\n";
				    
				    if($detalleV!=''){
				        $s .= "<detallesAdicionales>";
				        $s .= "<detAdicional nombre='DETALLE' valor='".$detalleV."' />";
				        $s .= "</detallesAdicionales>\n";
				    }
				    
                    // $s .= "<contador>".$cont."</contador>\n";
				    $s .= "</detalle>\n";
                    //   $cont++;         
				}
                //   echo "===>".$cont."</br>";      
		  	$s .= "</detalles>\n";                               
            $s .= "</destinatario>\n";	
		  	$s .= "</destinatarios>\n";			
		  //	$s .= "<infoAdicional>\n";
    //             if ($observacion!= "")
    //                 $s .= "<campoAdicional nombre=\"DESCRIPCION\">" . eliminar_tildes(utf8_decode(substr($observacion, 0, 299))) . "</campoAdicional>\n";
    //             $s .= "</infoAdicional>";
		  	
		  	
		$s .="\n</guiaRemision>";
		
	$resultClave=mysql_fetch_array(mysql_query("SELECT clave,FElectronica,ruc,autorizacion_sri FROM  empresa WHERE id_empresa='".$sesion_id_empresa."'"));
	
		$consulta= generarFirma( $s,$claveAcceso,'guiaRemision',$resultClave['clave'],$resultClave['FElectronica'],$emision_ambiente,$resultClave['autorizacion_sri']);
	   //echo "===>".$s."</br>";  
		$doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($consulta); // xml  
		$doc->save("../xmls/generados/guia_".$ruc.$secuencial.".xml"); 
		consultaSRI($consulta,$ambiente_id,$claveAcceso,'guiaRemision',$id);
// 		echo $consulta.$ambiente_id.$claveAcceso.'guiaRemision'.$id."<=====";
// 	print_r($result_sri);
		$conn=null;
		
		
		
	}
	
function genXmlLiquidacion($factura){
// 	$conn=getDatabaseConnection();
	    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_usuario = $_SESSION['sesion_id_usuario'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_empresa_imagen = $_SESSION['sesion_empresa_imagen'];
    $sesion_empresa_razonSocial =$_SESSION['sesion_empresa_razonSocial'];
    $sesion_empresa_direccion =$_SESSION['sesion_empresa_direccion'];
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
    $emision_ambiente = $_SESSION["emision_ambiente"];
    
    $emision_codigo = $_SESSION["emision_codigo"];
	$establecimiento_codigo = $_SESSION["establecimiento_codigo"];
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    
    $Ocontabilidad = $_SESSION["Ocontabilidad"];
    
    $empresa_contribuyente=$_SESSION['empresa_contribuyente'] ;
	
	$resultCompras=mysql_query( "select * from compras where id_compra=$factura");
	$OMaster=mysql_fetch_array($resultCompras);
// 	echo "select * from compras where id_compra=$factura";
	
	$resultproveedor=mysql_query( "select * from proveedores where id_proveedor='".$OMaster['id_proveedor']."' ");
	$OCli=mysql_fetch_array($resultproveedor);
	
	$resultimpuestos=mysql_query( "select * from impuestos where id_empresa='".$OMaster['id_empresa']."' ");
	$Oimpuesto=mysql_fetch_array($resultimpuestos);
// 	echo "select * from proveedores where id_proveedor='".$OMaster['id_proveedor']."' ";
    
    $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$OEmpresa=mysql_fetch_array($resultEmpresa);

    
    
	$estado="";
	$ambiente="PRUEBAS"; //1 Pruebas 2 Producciظ├ق╛n   texto
	
	$ambiente_id=$emision_ambiente;//1 Pruebas 2 Producciظ├ق╛n   numero
	$tipoemision="1";//1 Emisiظ├ق╛n Normal  2 Emisiظ├ق╛n por Indisponibilidad del Sistema
	$numeroAutorizacion="";
	$fechaAutorizacion="";
	
	$codDoc="0".$OMaster['TipoComprobante'];//01 FACTURA  04 NOTA DE CRظ├أ╩DITO 05 NOTA DE Dظ├أ╩BITO 06 GUظ├ق╛ûA DE REMISIظ├أ═N 07  COMPROBANTE DE RETENCIظ├أ═N
	
	

	
	$claveAcceso=generarClave($factura,$codDoc,$sesion_empresa_ruc,$ambiente_id,$establecimiento_codigo.$emision_codigo,ceros($OMaster['txtNum']),substr(date("d/m/Y",strtotime($OMaster['fecha_compra'])),0,2).substr(date("d/m/Y",strtotime($OMaster['fecha_compra'])),3,2).substr(date("d/m/Y",strtotime($OMaster['fecha_compra'])),6,4), $tipoemision);

	$tipoIdentificacionComprador=$OCli['rbCaracterIdentificacion'];  // RUC  04  CEDULA 05  PASAPORTE 06 VENTA A CONSUMIDOR FINAL 07 IDENTIFICACION DELEXTERIOR* 08  PLACA 09
	//header( "content-type: application/xml; charset=encoding='UTF-8' standalone='true'" );
	// "Create" the document.
	$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$s .= "<liquidacionCompra id=\"comprobante\" version=\"1.1.0\">\n";		
			$s .= "<infoTributaria>\n";
				$s .= "<ambiente>".$ambiente_id."</ambiente>\n";
				$s .= "<tipoEmision>".$tipoemision."</tipoEmision>\n";
				$s.="<razonSocial>".eliminar_tildes(utf8_decode($sesion_empresa_razonSocial))."</razonSocial>\n";
				$s.="<nombreComercial>".eliminar_tildes(utf8_decode($sesion_empresa_nombre))."</nombreComercial>\n";
				$s.="<ruc>".$sesion_empresa_ruc."</ruc>\n";
				$s.="<claveAcceso>".$claveAcceso."</claveAcceso>\n";
				$s.="<codDoc>".$codDoc."</codDoc>";
				$s.="<estab>".$establecimiento_codigo."</estab>\n";
				$s.="<ptoEmi>".$emision_codigo."</ptoEmi>\n";
				$s.="<secuencial>".ceros($OMaster['txtNum'])."</secuencial>\n";
				$s.="<dirMatriz>".eliminar_tildes(utf8_decode($sesion_empresa_direccion))."</dirMatriz>\n";
				
				if($OEmpresa['leyenda2']=="1")
				$s .= "<agenteRetencion>".(substr($OEmpresa['leyenda2'],0,299))."</agenteRetencion>\n";
				
				
				if($OEmpresa['leyenda']!="")
				$s .= "<contribuyenteRimpe>".(substr($OEmpresa['leyenda'],0,299))."</contribuyenteRimpe>\n";
				
			$s .= "</infoTributaria>\n";
			$s .= "<infoLiquidacionCompra>\n";
				$s .= "<fechaEmision>".substr(date("d/m/Y",strtotime($OMaster['fecha_compra'])),0,10)."</fechaEmision>\n";
				$s .= "<dirEstablecimiento>".eliminar_tildes(utf8_decode(substr($sesion_empresa_direccion,0,400)))."</dirEstablecimiento>\n";

				$obc=($Ocontabilidad==1)?"SI":"NO";
				$s.="<obligadoContabilidad>".$obc."</obligadoContabilidad>\n";
			
				$s .= "<tipoIdentificacionProveedor>".substr($tipoIdentificacionComprador,0,2)."</tipoIdentificacionProveedor>\n";				
				$s .= "<razonSocialProveedor>".eliminar_tildes(utf8_decode(substr($OCli['nombre'],0,300)))."</razonSocialProveedor>\n";
				$s .= "<identificacionProveedor>".substr($OCli['ruc'],0,20)."</identificacionProveedor>\n";
				$s .= "<direccionProveedor>".eliminar_tildes(utf8_decode(substr($OCli['direccion'],0,300)))."</direccionProveedor>\n";
				
				$s .= "<totalSinImpuestos>".number_format($OMaster['sub_total'], 2, '.', '')."</totalSinImpuestos>\n";
				$s .= "<totalDescuento>".number_format($OMaster['descuento'], 2, '.', '')."</totalDescuento>\n";
				
				$s .= "<totalConImpuestos>\n";
				
                 $sqlDetalleC = "SELECT
                    impuestos.id_iva,
                    impuestos.codigo,
                    impuestos.iva,
                    detalle_compras.id_detalle_compra,
                 	detalle_compras.idBodega,
                	detalle_compras.idBodegaInventario,
                    detalle_compras.cantidad,
                    detalle_compras.valor_unitario,
                    detalle_compras.des,
                    SUM(detalle_compras.valor_total) AS base_imponibleTarifas,
                    detalle_compras.id_compra,
                    detalle_compras.id_producto,
                    SUM(detalle_compras.total_iva) AS suma_iva
                FROM
                    detalle_compras
                INNER JOIN impuestos ON impuestos.id_iva = detalle_compras.iva 
                WHERE
                	detalle_compras.id_compra='".$factura."'
                GROUP BY
                    impuestos.id_iva; ";
				$resultDetCompra= mysql_query( $sqlDetalleC );
					while($rowDetCompr = mysql_fetch_array($resultDetCompra) ){
				    
					$codigoPorcentaje=$rowDetCompr['codigo'];

					$s .= "<totalImpuesto>\n";
                    $s .= "<codigo>2</codigo>\n";
                    $s .= "<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>\n";
                    $s .= "<descuentoAdicional>0.00</descuentoAdicional>\n";
                    $s .= "<baseImponible>".number_format($rowDetCompr['base_imponibleTarifas'], 2, '.', '')."</baseImponible>\n";
                    // $s .= "<tarifa>".number_format($rowDetCompr['iva'], 0)."</tarifa>\n";
                    $s .= "<valor>".number_format($rowDetCompr['base_imponibleTarifas']*($rowDetCompr['iva']/100), 2, '.', '')."</valor>\n";
                    $s .= "</totalImpuesto>\n";
				}
			    $s .= "</totalConImpuestos>\n";


			    $s .= "<importeTotal>".number_format(($OMaster['total']), 2, '.', '')."</importeTotal>\n";


			    $s .= "<moneda>DOLAR</moneda>\n";
			    $s .= "<pagos>\n";
				$s .= "<pago>\n";
					$fp=($OMaster->Row['FormaPago']=="02" or $OMaster->Row['FormaPago']=="03" or $OMaster->Row['FormaPago']=="04")?"01":$OMaster->Row['FormaPago'];
					$fp=($fp=="")?"01":$fp;
		            $s .= "<formaPago>".$fp."</formaPago>\n";
		            $s .= "<total>".number_format(($OMaster['total']), 2, '.', '')."</total>\n";
				// 	if($OMaster->Row['Plazo']!=0){
		            $s .= "<plazo>0</plazo>\n";
		            $s .= "<unidadTiempo>DIAS</unidadTiempo>\n";
				// 	}
		        	$s .= "</pago>\n";	
		        $s .= "</pagos>\n";
		        
			$s .= "</infoLiquidacionCompra>\n";
	
			$s .= "<detalles>\n";
	                $resultdetalle=mysql_query( "SELECT
                    detalle_compras.cantidad,
                    detalle_compras.des,
                    detalle_compras.valor_unitario,
                    detalle_compras.valor_total,
                    productos.codigo,
                    productos.producto,
                    productos.iva as producto_iva,
                    detalle_compras.total_iva AS ivatotal,
                    impuestos.codigo AS codigo_iva,
                    impuestos.id_iva,
                    impuestos.iva
                FROM
                    detalle_compras,
                    productos,
                    impuestos
                WHERE
                    detalle_compras.id_producto = productos.id_producto AND impuestos.id_iva = detalle_compras.iva AND detalle_compras.id_compra =$factura");
                    
                //     echo "SELECT
                //     detalle_compras.cantidad,
                //     detalle_compras.des,
                //     detalle_compras.valor_unitario,
                //     detalle_compras.valor_total,
                //     productos.codigo,
                //     productos.producto,
                //     productos.iva as producto_iva,
                //     detalle_compras.total_iva AS ivatotal,
                //     impuestos.codigo AS codigo_iva,
                //     impuestos.id_iva,
                //     impuestos.iva
                // FROM
                //     detalle_compras,
                //     productos,
                //     impuestos
                // WHERE
                //     detalle_compras.id_producto = productos.id_producto AND impuestos.id_iva = detalle_compras.iva AND detalle_compras.id_compra =$factura";
	                
				    while ($ODetalle = mysql_fetch_array($resultdetalle)) {
				        
				    $totalDescuento=$ODetalle['des']*$ODetalle['cantidad'];
				    // echo "DESCUENTO==>".$totalDescuento;
				    $resultProducto=mysql_query( "select * from productos where id_producto='".$ODetalle['id_producto']."' ");
    	            $OProd=mysql_fetch_array($resultProducto);
    	            
				    // echo "select * from productos where id_producto='".$ODetalle['id_producto']."' ";
				    
					$s .= "<detalle>\n";
				    $s .= "<codigoPrincipal>".$ODetalle['codigo']."</codigoPrincipal>\n";
				    $s .= "<descripcion>".eliminar_tildes(utf8_decode(substr($ODetalle['producto'],0,300)))."</descripcion>\n";
				    $s .= "<cantidad>".$ODetalle['cantidad']."</cantidad>\n";
				    $s .= "<precioUnitario>".number_format($ODetalle['valor_unitario'], 2, '.', '')."</precioUnitario>\n";
				    $s .= "<descuento>".number_format($totalDescuento, 2, '.', '')."</descuento>\n";
				    $s .= "<precioTotalSinImpuesto>".number_format(($ODetalle['cantidad']*$ODetalle['valor_unitario'])-$totalDescuento, 2, '.', '')."</precioTotalSinImpuesto>\n";
				    $s .= "<impuestos>\n";				    				   
			    	$s .= "<impuesto>\n";
				    $s .= "<codigo>2</codigo>\n";
				    
	                if($ODetalle['iva']>0  ){
                        $codigoPorcentaje=$ODetalle['codigo_iva'];
                        $Iva =$ODetalle['iva'];
                    }else{
                        $codigoPorcentaje = '0';
                        $Iva = '0';
                    }
		
				    $s .= "<codigoPorcentaje>$codigoPorcentaje</codigoPorcentaje>\n";
				
				    $s .= "<tarifa>$Iva</tarifa>\n";
				    $s .= "<baseImponible>".number_format(($ODetalle['cantidad']*$ODetalle['valor_unitario'])-($ODetalle['des']*$ODetalle['cantidad']), 2, '.', '')."</baseImponible>\n";
				    $s .= "<valor>".number_format( (($ODetalle['cantidad']*$ODetalle['valor_unitario'])-$ODetalle['des'])*($Iva/100), 2, '.', '')."</valor>\n";
				    $s .= "</impuesto>\n";					    			    			    
				    $s .= "</impuestos>\n";
				    $s .= "</detalle>\n";
				
				}
		  	$s .= "</detalles>\n";	
		  	
			
			$s .= "<infoAdicional>\n";	
			     //$s .= "<campoAdicional nombre=\"Agente de Retencion\">No. Resolucion: 1</campoAdicional>\n";
				$s .= "<campoAdicional nombre=\"DIRECCION\">".eliminar_tildes(utf8_decode(substr($OCli['direccion'],0,299)))."</campoAdicional>\n";	
				if($OCli['telefono']!="")
				$s .= "<campoAdicional nombre=\"TELEFONO\">".$OCli['telefono']."</campoAdicional>\n";	
				if($OCli['email']!="")
				$s .= "<campoAdicional nombre=\"EMAIL\">".$OCli['email']."</campoAdicional>\n";
				if($notifica!="")
				$s .= "<campoAdicional nombre=\"MENSAJE\">".eliminar_tildes(utf8_decode(substr($notifica,0,299)))."</campoAdicional>\n";
			$s .= "</infoAdicional>";	
			
		$s .="\n</liquidacionCompra>";
		
	    $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$OMaster['id_empresa']."'");
	    $rowEmpresa=mysql_fetch_array($resultEmpresa);

	    $consulta= generarFirma( $s,$claveAcceso,'liquidacionCompra',$rowEmpresa['clave'],$rowEmpresa['FElectronica'],$emision_ambiente,$rowEmpresa['autorizacion_sri']);

		$doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($consulta); // xml  
        
        $doc->save("../xmls/generados/liquidacionCompra_".$sesion_empresa_ruc."-".$establecimiento_codigo.$emision_codigo.ceros($OMaster['numero_factura_compra']).".xml"); 


		consultaSRI($consulta,$ambiente_id,$claveAcceso,'liquidacionCompra',$factura);	

		$conn=null;
	}
	

function genXmlRet20($factura){
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_usuario = $_SESSION['sesion_id_usuario'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_empresa_imagen = $_SESSION['sesion_empresa_imagen'];
    $sesion_empresa_razonSocial =$_SESSION['sesion_empresa_razonSocial'];
    $sesion_empresa_direccion =$_SESSION['sesion_empresa_direccion'];
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
    $emision_ambiente = $_SESSION["emision_ambiente"];
    
    $emision_codigo = $_SESSION["emision_codigo"];
	$establecimiento_codigo = $_SESSION["establecimiento_codigo"];
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    $Ocontabilidad = $_SESSION["Ocontabilidad"];
    $empresa_contribuyente=$_SESSION['empresa_contribuyente'] ;

    $result1=mysql_query( "select * from mcretencion where Id='".$factura."' and anulado='0' ");
 	$OMaster=mysql_fetch_array($result1);

	$result2=(mysql_query("SELECT dcretencion.CodImp as Codigo, dcretencion.TipoImp, dcretencion.BaseImp, dcretencion.Porcentaje FROM  
	dcretencion WHERE dcretencion.Retencion_id=".$OMaster['Id'] ) );
	
	$OComp=mysql_fetch_array(mysql_query("select * from compras where id_compra=".$OMaster['Factura_id']));
	
	
	$OEmp=mysql_fetch_array(mysql_query("select * from empresa where id_empresa=".$OComp['id_empresa']));
    
	$OCli=mysql_fetch_array(mysql_query("select * from proveedores where id_proveedor=".$OComp['id_proveedor']));
  

	$estado="";
	$ambiente="PRUEBAS"; //1 Pruebas 2 Producciظ├ق╛n   texto
	$ambiente_id=$emision_ambiente;//1 Pruebas 2 Producciظ├ق╛n   numero
	$tipoemision='1';//1 Emisiظ├ق╛n Normal  2 Emisiظ├ق╛n por Indisponibilidad del Sistema
	$numeroAutorizacion="";
	$fechaAutorizacion="";
	$codDoc="07";//01 FACTURA  04 NOTA DE CRظ├أ╩DITO 05 NOTA DE Dظ├أ╩BITO 06 GUظ├ق╛ûA DE REMISIظ├أ═N 07  COMPROBANTE DE RETENCIظ├أ═N
	$claveAcceso=generarClave($id,$codDoc,$sesion_empresa_ruc,$ambiente_id,$establecimiento_codigo.$emision_codigo,ceros($OMaster['Numero']),substr(date("d/m/Y",strtotime($OMaster['Fecha'])),0,2).substr(date("d/m/Y",strtotime($OMaster['Fecha'])),3,2).substr(date("d/m/Y",strtotime($OMaster['Fecha'])),6,4), $tipoemision);
	
// 	echo "</br> CLAVE DE ACCESO ==>".$id."<br> 1 ==>".$codDoc."<br> 2 ==>".$sesion_empresa_ruc."<br> 3 ==>".$ambiente_id."<br> 4 ==>".$establecimiento_codigo.$emision_codigo."<br> 5 ==>".ceros($OMaster['Numero'])."<br> A ==>".substr(date("d/m/Y",strtotime($OMaster['Fecha'])),0,2).substr(date("d/m/Y",strtotime($OMaster['Fecha'])),3,2).substr(date("d/m/Y",strtotime($OMaster['Fecha'])),6,4)."<br> 6 ==>". $tipoemision."</br>";
	
	$tipoIdentificacionComprador=$OCli['rbCaracterIdentificacion'];  // RUC  04  CEDULA 05  PASAPORTE 06 VENTA A CONSUMIDOR FINAL 07 IDENTIFICACION DELEXTERIOR* 08  PLACA 09
	//header( "content-type: application/xml; charset=ISO-8859-15" );
	// "Create" the document.
	
	$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$s .= "<comprobanteRetencion id=\"comprobante\" version=\"1.0.0\">\n";
		$s.="<infoTributaria>\n";
			$s.="<ambiente>".$ambiente_id."</ambiente>\n";
			$s.="<tipoEmision>".$tipoemision."</tipoEmision>\n";
			$s.="<razonSocial>".eliminar_tildes(utf8_decode($sesion_empresa_razonSocial))."</razonSocial>\n";
			$s.="<nombreComercial>".eliminar_tildes(utf8_decode($sesion_empresa_nombre))."</nombreComercial>\n";
			$s.="<ruc>".$sesion_empresa_ruc."</ruc>\n";
			$s.="<claveAcceso>".$claveAcceso."</claveAcceso>\n";
			$s.="<codDoc>".$codDoc."</codDoc>\n";
			$s.="<estab>".substr($OMaster['Serie'],0,3)."</estab>\n";
			$s.="<ptoEmi>".substr($OMaster['Serie'],4,3)."</ptoEmi>\n";
			$s.="<secuencial>".ceros($OMaster['Numero'])."</secuencial>\n";
			$s.="<dirMatriz>".eliminar_tildes(utf8_decode($sesion_empresa_direccion))."</dirMatriz>\n";
			
    //         	if($OEmp['leyenda2']=="1")
				// $s .= "<agenteRetencion>".(substr($OEmp['leyenda2'],0,299))."</agenteRetencion>\n";
				
				
				if($OEmp['leyenda']!="")
				$s .= "<contribuyenteRimpe>".(substr($OEmp['leyenda'],0,299))."</contribuyenteRimpe>\n";
            
            
		$s.="</infoTributaria>\n";
		$s.="<infoCompRetencion>\n";
			$s.="<fechaEmision>".date("d/m/Y",strtotime($OMaster['Fecha']))."</fechaEmision>\n";
			$s.= "<dirEstablecimiento>".eliminar_tildes(utf8_decode(substr($sesion_empresa_direccion,0,300)))."</dirEstablecimiento>\n";
			if($empresa_contribuyente!="")
			$s.="<contribuyenteEspecial>".$empresa_contribuyente."</contribuyenteEspecial>\n";
			$obc=(	$Ocontabilidad ==1)?"SI":"NO";
			$s.="<obligadoContabilidad>".$obc."</obligadoContabilidad>\n";
			$s.="<tipoIdentificacionSujetoRetenido>".$tipoIdentificacionComprador."</tipoIdentificacionSujetoRetenido>\n";
			$s.="<tipoSujetoRetenido>".'0'.$OCli['id_tipo_proveedor']."</tipoSujetoRetenido>\n";
			$parteRel = ($OCli['parteRel']=='SI')?$OCli['parteRel']:'NO';
			$s.="<parteRel>".$parteRel."</parteRel>\n";
			$s.="<razonSocialSujetoRetenido>".eliminar_tildes(utf8_decode($OCli['nombre']))."</razonSocialSujetoRetenido>\n";
			$s.="<identificacionSujetoRetenido>".$OCli['ruc']."</identificacionSujetoRetenido>\n";
			$s.="<periodoFiscal>".substr($OMaster['Fecha'],5,2)."/".substr($OMaster['Fecha'],0,4)."</periodoFiscal>\n";
	$s.="</infoCompRetencion>\n";
	$s.="<docsSustento>\n";
    	$s.="<docSustento>\n";
    	$s.="<codSustento>"."0".$OComp['codSustento']."</codSustento>\n";
    	$s.="<codDocSustento>"."0".$OComp['TipoComprobante']."</codDocSustento>\n";
    	$s.="<numDocSustento>".$OComp['numSerie'].$OComp['txtEmision'].ceros($OComp['txtNum'])."</numDocSustento>\n";
    	$s.="<fechaEmisionDocSustento>".date("d/m/Y",strtotime($OMaster['Fecha']))."</fechaEmisionDocSustento>\n";
    // 	$s.="<fechaRegistroContable>".$ODetalle['TipoImp']."</fechaRegistroContable>\n";
    // 	$s.="<numAutDocSustento>".$ODetalle['TipoImp']."</numAutDocSustento>\n";
        $pagoLocExt = ($OComp['paisResidencia']=='593')?01:01;

    	$s.="<pagoLocExt>".$pagoLocExt."</pagoLocExt>\n";
    	if($pagoLocExt=='02'){
    	    $s.="<tipoRegi>".$ODetalle['TipoImp']."</tipoRegi>\n";
    	    $s.="<paisEfecPago>".$ODetalle['TipoImp']."</paisEfecPago>\n";
    	    $s.="<aplicConvDobTrib>".$ODetalle['TipoImp']."</aplicConvDobTrib>\n";
    	    $s.="<pagExtSujRetNorLeg>".$ODetalle['TipoImp']."</pagExtSujRetNorLeg>\n";
    	    $s.="<pagoRegFis>".$ODetalle['TipoImp']."</pagoRegFis>\n";
    	}
    	if($OComp['TipoComprobante']=="41"){
    
    	 
    	    $s.="<totalComprobantesReembolso>".$OComp['total']."</totalComprobantesReembolso>\n";
    	    $s.="<totalBaseImponibleReembolso>".$OComp['sub_total']."</totalBaseImponibleReembolso>\n";
    	    $s.="<totalImpuestoReembolso>".floatval($OComp['total'])-floatval($OComp['sub_total'])."</totalImpuestoReembolso>\n";
    	}

    	
    	$s.="<totalSinImpuestos>".$OComp['sub_total']."</totalSinImpuestos>\n";
    	$s.="<importeTotal>".$OComp['total']."</importeTotal>\n";
   
    
  $s.="<impuestosDocSustento>\n";
  if($OComp['subTotal0']>0){
    $s.="<impuestoDocSustento>\n";
    $s.="<codImpuestoDocSustento>2</codImpuestoDocSustento>\n";
    $s.="<codigoPorcentaje>0</codigoPorcentaje>\n";
    $s.="<baseImponible>".$OComp['subTotal0']."</baseImponible>\n";
    $s.="<tarifa>0</tarifa>\n";
    $s.="<valorImpuesto>0</valorImpuesto>\n";
    $s.="</impuestoDocSustento>\n"; 
  }
 
   if($OComp['subTotal12']>0){
        
         if($OComp['id_iva']=='12'){
             $tarifaIva = 2;
        }else if($OComp['id_iva']=='14'){
             $tarifaIva = 3;
        }
        
            $s.="<impuestoDocSustento>\n";
            	$s.="<codImpuestoDocSustento>2</codImpuestoDocSustento>\n";
            	$s.="<codigoPorcentaje>".$tarifaIva."</codigoPorcentaje>\n";
            	$s.="<baseImponible>".$OComp['subTotal12']."</baseImponible>\n";
            	$s.="<tarifa>".$OComp['id_iva']."</tarifa>\n";
            	$s.="<valorImpuesto>".floatval($OComp['subTotal12'])*($OComp['id_iva']/100)."</valorImpuesto>\n";
            $s.="</impuestoDocSustento>\n";
        
    }
    
  $s.="</impuestosDocSustento>\n";
	$s.="<retenciones>\n";

	 while($ODetalleRet=mysql_fetch_array($result2)){
	     
		$s.="<retencion>\n";
			$s.="<codigo>".$ODetalleRet['TipoImp']."</codigo>\n";
			$s.="<codigoRetencion>".$ODetalleRet['Codigo']."</codigoRetencion>\n";
			$s.="<baseImponible>".number_format($ODetalleRet['BaseImp'],2, '.', '')."</baseImponible>\n";
			$s.="<porcentajeRetener>".number_format($ODetalleRet['Porcentaje'],2, '.', '')."</porcentajeRetener>\n";
			$s.="<valorRetenido>".number_format($ODetalleRet['BaseImp']*$ODetalleRet['Porcentaje']/100,2, '.', '')."</valorRetenido>\n";
		$s.="</retencion>\n";
		
	}
	$s.="</retenciones>\n";
	
	if($OComp['TipoComprobante']=="41"){
	    $s.="<reembolsos>\n";
	    
    	    $s.="<reembolsoDetalle>\n";
    	    $oReembolso=mysql_fetch_array(mysql_query("SELECT `id_reembolso`, `tipo_identificacion_proveedor_reembolso`, `identificacion_proveedor_reembolso`, `cod_pais_proveedor_reembolso`, `tipo_proveedor_reembolso`, `cod_doc_reembolso`, `estab_doc_reembolso`, `pto_emi_doc_reembolso`, `secuencial_doc_reembolso`, `fecha_emision_doc_reembolso`, `numero_autorizacion_doc_reembolso`, `id_compra`, `id_venta` FROM `reembolsos_gastos` WHERE id_compra=".$OMaster['Factura_id']));
    	    $s .= "<tipoIdentificacionProveedorReembolso>".$oReembolso['tipo_identificacion_proveedor_reembolso']."</tipoIdentificacionProveedorReembolso>\n";
    	    $s .= "<identificacionProveedorReembolso>".$oReembolso['identificacion_proveedor_reembolso']."</identificacionProveedorReembolso>\n";
    	    $s .= "<codPaisPagoProveedorReembolso>".$oReembolso['cod_pais_proveedor_reembolso']."</codPaisPagoProveedorReembolso>\n";
    	    $s .= "<codDocReembolso>".$oReembolso['cod_doc_reembolso']."</codDocReembolso>\n";
    	    $s .= "<estabDocReembolso>".$oReembolso['estab_doc_reembolso']."</estabDocReembolso>\n";
    	    $s .= "<ptoEmiDocReembolso>".$oReembolso['pto_emi_doc_reembolso']."</ptoEmiDocReembolso>\n";
    	    $s .= "<secuencialDocReembolso>".$oReembolso['secuencial_doc_reembolso']."</secuencialDocReembolso>\n";
    	    $s .= "<fechaEmisionDocReembolso>".$oReembolso['fecha_emision_doc_reembolso']."</fechaEmisionDocReembolso>\n";
    	    $s .= "<numeroAutorizacionDocReemb>".$oReembolso['numero_autorizacion_doc_reembolso']."</numeroAutorizacionDocReemb>\n";
    	    
    	      $s.="<detalleImpuestos>\n";
  if($OComp['subTotal0']>0){
    $s.="<detalleImpuesto>\n";
    $s.="<codImpuestoDocSustento>2</codImpuestoDocSustento>\n";
    $s.="<codigoPorcentaje>0</codigoPorcentaje>\n";
    $s.="<baseImponible>".$OComp['subTotal0']."</baseImponible>\n";
    $s.="<tarifa>0</tarifa>\n";
    $s.="<valorImpuesto>0</valorImpuesto>\n";
    $s.="</detalleImpuesto>\n"; 
  }
 
   if($OComp['subTotal12']>0){
        
         if($OComp['id_iva']=='12'){
             $tarifaIva = 2;
        }else if($OComp['id_iva']=='14'){
             $tarifaIva = 3;
        }
        
            $s.="<detalleImpuesto>\n";
            	$s.="<codImpuestoDocSustento>2</codImpuestoDocSustento>\n";
            	$s.="<codigoPorcentaje>".$tarifaIva."</codigoPorcentaje>\n";
            	$s.="<baseImponible>".$OComp['subTotal12']."</baseImponible>\n";
            	$s.="<tarifa>".$OComp['id_iva']."</tarifa>\n";
            	$s.="<valorImpuesto>".floatval($OComp['subTotal12'])*($OComp['id_iva']/100)."</valorImpuesto>\n";
            $s.="</detalleImpuesto>\n";
        
    }
    
  $s.="</detalleImpuestos>\n";
    	    $s.="</reembolsoDetalle>\n";
	    $s.="</reembolsos>\n";
		    $s.="<codigo>".$ODetalle['TipoImp']."</codigo>\n";
	}
	 $s.="</docSustento>\n";
	$s.="</docsSustento>\n";
	
	$s .= "<pagos>\n";
	$s .= "<pago>\n";
	$fp=($row['FormaPago']=="01")?"01":"20";
	$s .= "<formaPago>".$fp."</formaPago>\n";
	$s .= "<total>".number_format($OComp['total'], 3, '.', '')."</total>\n";
	$s .= "</pago>\n";
	$s .= "</pagos>\n";
	
	$s .= "<infoAdicional>\n";	
	            //$s .= "<campoAdicional nombre=\"Agente de Retencion\">No. Resolucion: 1</campoAdicional>\n";
				$s .= "<campoAdicional nombre=\"DIRECCION\">".' '.eliminar_tildes(utf8_decode(substr($OCli['direccion'],0,299)))."</campoAdicional>\n";
				if($OCli['email']!="")										
				$s .= "<campoAdicional nombre=\"EMAIL\">".' '.(substr($OCli['email'],0,299))."</campoAdicional>\n";
			$s .= "</infoAdicional>";	
	$s.="</comprobanteRetencion>";
	echo $s;
	exit;
	$resultClave=mysql_fetch_array(mysql_query("SELECT clave,FElectronica,ruc,autorizacion_sri FROM  empresa WHERE id_empresa='".$sesion_id_empresa."'"));
        
       // echo "SELECT clave,FElectronica,ruc,autorizacion_sri FROM  empresa WHERE id_empresa='".$sesion_id_empresa."' ";

	$consulta= generarFirma($s,$claveAcceso,'comprobanteRetencion',$resultClave['clave'],$resultClave['FElectronica'],$ambiente_id,$resultClave['autorizacion_sri']);
//	echo "consulta"."</br>".$consulta."</br>";
	
		$doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($consulta); // xml  
		$doc->save("../xmls/generados/comprobanteRetencion_".$sesion_empresa_ruc.$establecimiento_codigo.$emision_codigo.ceros($OMaster['Numero']).".xml"); 
		 //echo $doc;
		 
        // $respuesta=consultaSRI($consulta,$ambiente_id,$claveAcceso,'comprobanteRetencion',$factura);
        
        consultaSRI($consulta,$ambiente_id,$claveAcceso,'comprobanteRetencion',$factura);
        // print_r($respuesta);

	//$xml->save("xmls/generados/R_".$_COOKIE['Ruc'].$_COOKIE['Establecimiento'].$_COOKIE['PuntoEmision'].ceros($OMaster->Row['Numero']).".xml");
	$conn=null;
}


?>