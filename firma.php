<?php    

include 'include/FirmaElectronica.php';
include 'include/nusoap.php'; 
include '../phpseclib/Crypt/RSA.php';
include '../phpseclib/File/X509.php';
include '../phpseclib/Math/BigInteger.php';

   function generarFirma($xmlDoc,$clave,$tipoDocumento,$pass,$token,$ambiente,$tfirma) {   
  		 // echo "SIGN===>". $xmlDoc = ''."==1===>". $clave ."==2===>".$tipoDocumento."==3===>".$pass."==4===>".$clave."==5==".$token."==6==>";exit;
  	
        $firma = new FirmaElectronica($config = [],$pass,$token,$tfirma);  

        $result = $firma->signXML($xmlDoc,'', null, false,$tipoDocumento,$clave);
  
  	 //   echo "==>".$xmlDoc."==1==".$clave."==2==".$tipoDocumento."==3==".$pass."==4==".$token."==5==".$ambiente."==6==".$tfirma;
        return $result;
		
    } 

    function generarFirmaLote($xmlDoc,$clave,$tipoDocumento,$pass,$token,$ambiente) {        
        $firma = new FirmaElectronica($config =[],$pass,$token);
        $result = $firma->signXML($xmlDoc,'', null, false,$tipoDocumento,$clave);
        
        return $result;
    }   

    function webService($result,$ambiente,$clave,$xmlDoc,$tipoDocumento,$pass,$token,$lote) {   
		
// 		echo $result,$ambiente,$clave,$xmlDoc,$tipoDocumento,$pass,$token,$lote;
		
        if($ambiente == 1) {
            $url = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
            $slAutorWs = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
        } else {
            $url = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
            $slAutorWs = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
        }
        $xml = base64_encode($result);      
        $client = new nusoap_Client($url,true);                        
        $resp = $client->call('validarComprobante',array("xml"=> "$xml"));
        // echo "==>".$resp;
        return $resp;
        
    }

    function consultarComprobante($ambiente,$clave) { 
		
        if($ambiente == 1) {            
            $slAutorWs = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
        } else {            
            $slAutorWs = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
        }            
        $olClient = new SoapClient($slAutorWs, array('encoding'=>'UTF-8'));
        $olResp = $olClient->autorizacionComprobante(array('claveAccesoComprobante'=> $clave));
     	//print_r($olClient);exit;
        return $olResp;  
    }
    
    function generarClave($id,$tipoComprobante,$ruc,$ambiente,$serie,$numeroDocumento,$fecha, $tipoEmision){      
      $secuencia = '765432';      
     // $ceros = 9;      
     // $temp = '';
      //$tam = $ceros - strlen($numeroDocumento);

      //for ($i = 0; $i < $tam; $i++) {                 
       // $temp = $temp .'0';        
      //}
     // $numeroDocumento = $temp .''. $numeroDocumento;  
      
      
      //$fechaT = explode('/', $fecha);    
      //$fecha = $fechaT[0].''.$fechaT[1].''.$fechaT[2];            

      $clave = $fecha.''.$tipoComprobante.''.$ruc.''.$ambiente.''.$serie.''.$numeroDocumento.''.$fecha.''.$tipoEmision;      
      $tamSecuencia = strlen($secuencia);      
      $ban = 0;
      $inc = 0;
      $sum = 0;
      for ($i = 0; $i < strlen($clave); $i++) { 
        $sum = $sum  + ($clave[$i] * $secuencia[$ban + $inc]);        
        //echo $sum.'<br/>';
        $inc++;
        if($inc >= $tamSecuencia){
          $inc = 0;
        }
      }              
      $resp = $sum % 11;
      $resp = 11 - $resp;      
      if($resp == 10){
        $resp = 1;
      }else{
        if($resp == 11){
          $resp = 0;
        }  
      }
      
      $clave = $clave.$resp; 
     
      return $clave;      
    } 
?>