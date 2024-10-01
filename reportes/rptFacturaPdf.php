<?php
     require_once "../conexion2.php";
     $id = $_GET["id"];
     
    $sql="SELECT empresa.nombre FROM empresa WHERE id_empresa=$id " ;
            	$result=mysqli_query($conexion,$sql);
				while($ver=mysqli_fetch_row($result)){ 

	$datos=$ver[0]; 
			        
	echo "<div style='border:1px solid #ccc;padding:10px; font-size:20px;margin-left:5px;margin-right:5px'><center>".$ver[0]."</center></div>";	
    }
?>


<html lang="es-ES">
<head>
    <meta charset="utf-8">
<title>Factura</title>

      <style type="text/css">
   .bg-light {
    background-color: #f8f9fa!important;
}
.bg-info {
    background-color: #0dcaf0!important;
}
.w100 {
    width: 100% !important;
}
.border{
    border: 1px solid rgba(39,41,43,0.1);
}
.border-b{
    border-bottom: 1px solid rgba(39,41,43,0.1);
}
.mt-2{
    margin-top: 2% !important; 
}
.p-5{
    padding-top: 5px !important; 
    padding-bottom: 5px !important; 
}
    
        </style>
</head>

<body style="padding:2%"> 


<?php 	{
    $sql="SELECT id, nombreComercial,razonSocial,factura.direccion,factura.ruc, numFactura, numAutorizacion,nombreCliente, rucCliente, 
    direccionCliente, telefonoCliente, fechaEmision, guiaRemision, cantidad1, descripcion1, valorUnitario1, valorTotal1,cantidad2,descripcion2, 
    valorUnitario2, valorTotal2, cantidad3, descripcion3, valorUnitario3, valorTotal3, cantidad4, descripcion4, valorUnitario4, 
    valorTotal4, firmaAutorizada, recibiConforme, subtotal, descuento, subtotal2, IVA0, IVA12, valorTotal,factura.id_empresa
    from factura where factura.id_empresa= $id";
	}
			
			
			$result=mysqli_query($conexion,$sql);
			while($ver=mysqli_fetch_row($result)){ 
		     	    $datos=$ver[0]."||".
						   $ver[1]."||".
						   $ver[2]."||".
						   $ver[3]."||".
						   $ver[4]."||".
						   $ver[5]."||".
						   $ver[6]."||".
						   $ver[7]."||".
						   $ver[8]."||".
						   $ver[9]."||".
						   $ver[10]."||".
						   $ver[11]."||".
						   $ver[12]."||".
						   $ver[13]."||".
						   $ver[14]."||".
						   $ver[15]."||".
						   $ver[16]."||".
						   $ver[17]."||".
						   $ver[18]."||".
						   $ver[19]."||".
						   $ver[20]."||".
						   $ver[21]."||".
						   $ver[22]."||".
						   $ver[23]."||".
						   $ver[24]."||".
						   $ver[25]."||".
						   $ver[26]."||".
						   $ver[27]."||".
						   $ver[28]."||".
						   $ver[29]."||".
						   $ver[30]."||".
						   $ver[31]."||".
						   $ver[32]."||".
						   $ver[33]."||".
						   $ver[34]."||".
						   $ver[35]."||".
						   $ver[36]."||".
						   $ver[37]."||".
						   $ver[38];
			 ?>




<table class="border w100 mt-2 bg-light">
    <tr>
        <td style="width:60%" class="p-5" ><center><font class="3"> <?php echo $ver[1]?> </center></td> 
        <td style="width:40%" class="border p-5" ><center> Num. Factura &nbsp; &nbsp;<font class="2"><?php echo $ver[5]?> </center></td>
    </tr>
    <tr>
        <td style="width:60%" class="p-5"><center>  <?php echo $ver[2]?> </center></td> 
        <td style="width:40%" class="border p-5"><center> R.U.C &nbsp; &nbsp;<font class="2"><?php echo $ver[4]?> </center></td>
    </tr>
    <tr>
        <td style="width:60%" class=" p-5" ><center>  <?php echo $ver[3]?> </center></td>
        <td style="width:40%" class="border p-5"><center> Aut: &nbsp; &nbsp;<font class="2"><?php echo $ver[6]?> </center></td>
    </tr>
</table>

<table class="tabla border w100" >
    <tbody >
       <tr class="border">
        <td style="width:15%" > Cliente </td>
        <td style="width:15%" ><font  class="border-b"><?php echo $ver[7]?> </td>
        <td style="width:15%" > R.U.C: &nbsp;&nbsp;&nbsp; </td>
        <td style="width:15%" ><font  class="border-b"><?php echo $ver[8]?> </td>
        <td style="width:15%" > Direcci&oacute;n: </td>
        <td style="width:15%"><font class="border-b"><?php echo $ver[10]?> </td>
      </tr>
       <tr class="border">
        <td style="width:15%"> Tel&eacute;fono </td>
        <td style="width:15%"><font class="border-b"><?php echo $ver[9]?> </td>
         <td style="width:15%"> Fecha de emisi&oacute;n: </td>
         <td style="width:15%"><font class="border-b"><?php echo $ver[11]?> </td>
        <td style="width:15%"> Gu&iacute;a Remisi&oacute;n </td>
        <td style="width:15%"><font class="border-b"><?php echo $ver[12]?> </td>
      </tr>
    </tbody >
    
</table>
<table class=" w100">
    
    <thead  >
      <tr class="bg-light">
        <th style="width:25%" class="p-5"><center><font class="2">Cantidad </center></th>
        <th style="width:50%" class="border" colspan="2"><center><font class="2">Descripcion </center></th>
        <th style="width:25%" class="border"><center><font class="2">Valor Unitario </center></th>
        <th style="width:25%" class="border"><center><font class="2">Valor Total </center></th>
      </tr>
    </thead>
    <tbody>
      <tr  class="border">
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[13]?> </center></td>
        <td style="width:50%" class="border p-5"  colspan="2"><center><font class="2"><?php echo $ver[14]?> </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[15]?> </center></td>
        <td style="width:25%" class="border p-5" ><center><font class="2"><?php echo $ver[16]?> </center></td>
      </tr>
      <tr class="border">
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[17]?> </center></td>
        <td style="width:50%" class="border p-5"   colspan="2"><center><font class="2"><?php echo $ver[18]?> </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[19]?> </center></td>
        <td style="width:25%" class="border p-5"   ><center><font class="2"><?php echo $ver[20]?> </center></td>
      </tr>
      <tr class="border">
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[21]?> </center></td>
        <td style="width:50%" class="border p-5"   colspan="2"><center><font class="2"><?php echo $ver[22]?> </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[23]?> </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[24]?> </center></td>
      </tr>
      <tr class="border">
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[25]?> </center></td>
        <td style="width:50%" class="border p-5"   colspan="2"><center><font class="2"><?php echo $ver[26]?> </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[27]?> </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[28]?> </center></td>
      </tr>
      <tr>
        <td style="width:25%" ></td>
        <td style="width:50%"  colspan="2"></td>
        <td style="width:25%" class="border p-5"  ><center> Subtotal </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[31]?> </center></td>
      </tr>
      <tr>
        <td style="width:25%" ></td>
        <td style="width:50%"  colspan="2"></td>
        <td style="width:25%" class="border p-5"  ><center> Descuento </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[32]?> </center></td>
      </tr>
      <tr>
        <td style="width:25%" ></td>
        <td style="width:50%"  colspan="2"></td>
        <td style="width:25%" class="border p-5"  ><center> Subtotal </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[33]?> </center></td>
      </tr>
      <tr>
        <td style="width:25%" ></td>
        <td style="width:50%"  colspan="2"></td>
        <td style="width:25%" class="border p-5"  ><center> IVA O% </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[34]?> </center></td>
      </tr>
       <tr>
        <td style="width:25%" ><center><font class="2"><?php echo $ver[29]?> </center></td>
        <td style="width:50%"  colspan="2"><center><font class="2"><?php echo $ver[30]?> </center></td>
        <td style="width:25%" class="border p-5"  ><center> IVA 12% </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[35]?> </center></td>
      </tr>
       <tr>
        <td style="width:25%" ><center> Firma Autorizada </center></td>
        <td style="width:50%"  colspan="2"><center> Firma Recibido </center></td>
        <td style="width:25%" class="border p-5"  ><center> Total: </center></td>
        <td style="width:25%" class="border p-5"  ><center><font class="2"><?php echo $ver[36]?> </center></td>
      </tr>
      </tbody>   
    </table>  
    

</div>

 <?php 	} ?>
</body>	

</html>


