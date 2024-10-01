<?php

function enviarCorreo($id_empresa, $nombre,$ruc,$cod_empresa,$dominio){
    
    // Varios destinatarios
   
    $para  = 'edu@alexsys.ec' . ', '; // atención a la coma
    
    $para .= 'loremuvel1@gmail.com';
    

    $mail = "Nueva cuenta distribuidor";
    //Titulo
    $titulo = "Nueva cuenta distribuidor ";

    // mensaje
    $mensaje = '
    
    <html>
    
    <head><meta charset="gb18030">
    
      <title>Nueva Cuenta Educativo Alexsys</title>
      
    </head>
    
    <body>
      <p>Nueva Cuenta Educativo Alexsys</p>
      <table border="1">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>RUC</th>
          <th>Codigo de ingreso</th>
          <th>Dominio</th>
        </tr>
        <tr>
          <td>'.$id_empresa.'</td>
          <td>'.$nombre.'</td>
          <td>'.$ruc.'</td>
          <td>'.$cod_empresa.'</td>
          <td>'.$dominio.'</td>
          
        </tr>

      </table>
      <br>
      <br>
     <div> Click en el siguiente link para Activar la Cuenta '.$nombre.' </div>
         <br>
         <a href="http://contaweb.ec/activarEmpresa.php?id='.$id_empresa.'"> Activar</a>
    </body>
    </html>
    ';

    //cabecera
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    //dirección del remitente
    $headers .= "From: ALEXSYS \r\n";

    //direcci�n de respuesta, si queremos que sea distinta que la del remitente
    $headers .= "Reply-To: servittec_ecuador@yahoo.com\r\n";

    //direcciones que recibir�n copia oculta
    $headers .= "Bcc: edu@alexsys.ec\r\n";

    //Enviamos el mensaje a tu_dirección_email
    
    $bool = mail($para,$titulo,$mensaje,$headers);

    if($bool){
        return "Mensaje enviado";
    }else{
        return "Mensaje no enviado";
    }
 
}



?>
