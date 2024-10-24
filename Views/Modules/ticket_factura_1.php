<?php 

//session_start();

require_once 'assets/dompdf/lib/html5lib/Parser.php';
require_once 'assets/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'assets/dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'assets/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

$factura=$rutas[1];
$empresa = $_SESSION['id_empresa'];

$query_empresa = $connect->prepare("SELECT * FROM tbl_empresas WHERE id_empresa = $empresa");
$query_empresa->execute();
$row_empresa=$query_empresa->fetch(PDO::FETCH_ASSOC);



$query_cabecera = $connect->prepare("SELECT * FROM vw_tbl_venta_cab WHERE id='$factura'");
$query_cabecera->execute();
$row_cabecera=$query_cabecera->fetch(PDO::FETCH_ASSOC);


//DETALLE
$query_detalle = $connect->prepare("SELECT * FROM vw_tbl_venta_det WHERE idventa='$factura'");
$query_detalle->execute();
$resultado_detalle = $query_detalle->fetchAll(PDO::FETCH_OBJ);

$numero = $row_cabecera['total'];

include 'assets/ajax/numeros.php';
$texto=convertir($numero);


//pagos
$query_pago = $connect->prepare("SELECT * FROM tbl_venta_pago WHERE id_venta='$factura'");
$query_pago->execute();
$resultado_pago=$query_pago->fetchAll(PDO::FETCH_OBJ);


 ?>


<html> 
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
   <!--link rel="stylesheet" href="../public/css/ticket.css"-->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 

</head>
    <!--onload="window.print();"-->
  <body onload="window.print();">   


   <div class="zona_impresion">
   <!--codigo imprimir-->
   <br>
   <table border="0" align="center" width="200px" style="font-size: 45px; font-family: 'Roboto', sans-serif; font-weight:500">
<thead>
   <tr>
      <td align="center">
         <img src="<?=media()?>/images/<?=$row_empresa['logo']?>" alt="" width="20%">
      </td>
   </tr>
   <tr>
         <td align="center" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:500">
            <!--mostramos los datos de la empresa en el doc HTML-->
            .::<strong> <?php echo $row_empresa['razon_social']?></strong>::.<br>
            
         </td>
         
      </tr>
       <?php if(empty($row_empresa['logo'])){ ?>
      <tr>
         <td align="center" style="font-size: 45px; font-family: 'Roboto', sans-serif; font-weight:500">
            <!--mostramos los datos de la empresa en el doc HTML-->
            .::<strong> <?php echo $row_empresa['razon_social']?></strong>::.<br>
            
         </td>
         
      </tr>

   <?php }?>
      <tr>
          <td colspan="5">===================================================</td>
        
      </tr>
      <tr>
          <td align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">
            DIRECCION:<?php echo $row_empresa['direccion'] ; ?><br>
            CELULAR  :<?php echo $row_empresa['celular'] ; ?><br>
          
           
         </td>
      </tr>
<tr>
    <td colspan="5">===================================================</td>
</tr>
<tr>
   <td align="center"  style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">RUC:  <?php echo $row_empresa['ruc'] ; ?></td>
</tr>

<tr>
    <td colspan="5">===================================================</td>
</tr>
<tr>
   <td align="center" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><strong class="text-bold" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?php
    if($row_cabecera['tipocomp']=='01')
      {
         $doc = 'FACTURA ELECTRONICA';
      }
      else if($row_cabecera['tipocomp']=='03')
      {
         $doc = 'BOLETA DE VENTA ELECTRONICA';
      }
      else if($row_cabecera['tipocomp']=='07')
      {
         $doc = 'NOTA DE CREDITO ELECTRONICA';
      }
      else if($row_cabecera['tipocomp']=='08')
      {
         $doc = 'NOTA DE DEBITO ELECTRONICA';
      }
      else if($row_cabecera['tipocomp']=='99')
      {
         $doc = 'NOTA DE VENTA ELECTRONICA';
      }
      echo $doc;
   ?></strong> <br>
   <?=$row_cabecera['serie'] .'-'. $row_cabecera['correlativo'] ?></td>
</tr>


<?php if($row_cabecera['tipocomp'] == '07' || $row_cabecera['tipocomp']=='08'){
   echo "
   <tr>
   <td colspan='5'>===================================================</td>
</tr>
   <tr>
   <td>Motivo: ". $row_cabecera['cod_motivo']." | ". $row_cabecera['des_motivo']."</td>
  
</tr>
<tr>
   <td>Documento: ". $row_cabecera['tipocomp_ref']."-".$row_cabecera['serie_ref']."-".$row_cabecera['correlativo_ref']."</td>
</tr>
";
} ?>


<tr>
   <td colspan="5">===================================================</td>
</tr>
      <tr> 
         <td align="center"></td>
      </tr>
            <tr>
         <td align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">
            Fecha Emision : <?= $row_cabecera['fecha_emision'] ?> <br>
            Hora          : <?= $row_cabecera['hora_emision'] ?> <br>
         </td>
      </tr>
 
<tr>
    <td colspan="5">===================================================</td>
</tr>
<tr>
   <td align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Cliente: <?=$row_cabecera['nombre_persona']  ?> <br></td>
</tr>
<tr>
   <td align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Obs: <?=$row_cabecera['obs']  ?> <br></td>
</tr>
<tr>
   <td align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Nro Documento: <?=$row_cabecera['num_doc'] ?> <br></td>
</tr>
<tr>
   <td align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Direccion: <?=$row_cabecera['direccion_persona'] ?> <br></td>
</tr>
<tr>
   <td colspan="5">===================================================</td>
</tr>
<tr>
   <td align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">condicion: <?=$row_cabecera['det_condicion'] ?> <br></td>
</tr>
<tr>
   <td colspan="5">===================================================</td>
</tr>
</thead>
   </table>
   <table border="0" align="center" width="200px" style="font-size: 45px; font-family: 'Roboto', sans-serif; font-weight:500">
      <thead>
        <tr>           
            <th align="left" colspan="5"style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Descripcion</th>
         </tr>
         <tr>           
            <th align="left"style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Cantidad</th>
            <th align="center">UNI</th>
          
            <th align="center" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Precio</th>
            <th align="right" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Total</th>
         </tr>
 <tr>
   <td colspan="5">===================================================</td>
   </tr>
      </thead>
      <?php if($row_empresa['venta_por_mayor']==='SI') {?>
      <tbody style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">
             <?php  foreach($resultado_detalle as $row_detalle)
               { ?>
                 
                  <tr><td align="left" colspan="4"style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?=strtoupper($row_detalle->descripcion) ?></td></tr>
                  <tr><td align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= strtoupper($row_detalle->cantidad_factor).'/'.strtoupper($row_detalle->cantidad_unitario) ?></td>
                      <td align="center" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= strtoupper($row_detalle->unidadf).'/'.strtoupper($row_detalle->unidadu) ?></td>
               
               <td align="center" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= number_format($row_detalle->precio_unitario*$row_detalle->factor,2).'/'.number_format($row_detalle->precio_unitario,2)  ?></td>
               <td align="right" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= number_format($row_detalle->importe_total,2) ?></td>
              
                  </tr>
                   <tr>
                  <td colspan="5">----------------------------------------------------------------------------</td>
                  </tr>
                <?php } ?>
      </tbody>
   <?php } 
   else{?>
      <tbody>
             <?php  foreach($resultado_detalle as $row_detalle)
               { ?>
                 
                  <tr>  
                        <td align="left" colspan="4" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?=strtoupper($row_detalle->descripcion) ?></td>
                  </tr>
                  <tr>
               <td align="left"  style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= strtoupper($row_detalle->cantidad_unitario) ?></td>
               <td align="center" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= strtoupper($row_detalle->unidadu) ?></td>
               
               <td align="center"  style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= number_format($row_detalle->precio_unitario,2) ?></td>
               <td align="right"  style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= number_format($row_detalle->importe_total,2) ?></td>

                  </tr>
                  <tr>
                  <td colspan="5">----------------------------------------------------------------------------</td>
                  </tr> 

                <?php } ?>
      </tbody>
   <?php } ?>
   </table>
    <table border="0" align="center" width="200px" style="font-size: 45px; font-family: 'Roboto', sans-serif; font-weight:500">
      <thead>
          <tr>
         <td colspan="5">===================================================</td>
         </tr>
       <tr>
             <td>Op. Gravada</td>
             <td align="right"><?= $row_cabecera['op_gravadas'] ?></td>
          </tr>
          <tr>
             <td>Op. Exonerada</td>
             <td align="right"><?= $row_cabecera['op_exoneradas'] ?></td>
          </tr>
          <tr>
             <td>Op. Inafecta</td>
             <td align="right"><?= $row_cabecera['op_inafectas'] ?></td>
          </tr>
          <tr>
             <td  style="font-size: 80px; font-family: 'tahoma'">SUB TOTAL</td>
             <td align="right"><?= number_format($row_cabecera['op_gravadas'] + $row_cabecera['op_exoneradas'] + $row_cabecera['op_inafectas'],2) ?></td>
          </tr>
          <tr>
             <td  style="font-size: 80px; font-family: 'tahoma'">I.G.V.</td>
             <td align="right"><?= $row_cabecera['igv'] ?></td>
          </tr>
          <tr>
       <td style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">TOTAL</td>
       <td align="right" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= $row_cabecera['total'] ?></td>
    </tr>
          
 </thead>
    </table>
 <table border="0" align="center" width="200px" style="font-size: 45px; font-family: 'Roboto', sans-serif; font-weight:500">
  <thead>
   <tr>
   <td colspan="4">===================================================</td>
   </tr>
   <tr>
      <td colspan="4" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">
         SON : <?=$texto?>
      </td>
   </tr>
    <tr>
   <td colspan="4">===================================================</td>
   </tr>
   <tr>
      <td colspan="4" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">
         VENDEDOR : <?=$_SESSION["nombre"]?>
      </td>
      <td colspan="4" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">
         TELEFONO : <?=$_SESSION["telefono"]?>
      </td>
   </tr>

    <tr>
   <td colspan="4">===================================================</td>
   </tr>
    
    <tr>
      <?php $fpago=0;
         foreach($resultado_pago as $row_pago){ ?>
         <td  style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?php 
         if($row_pago->fdp==1)
         { $f = 'EFECTIVO';  }
         else{$f = 'VISA'; } ?>
         <?=$f;?></td>
         <td align="right"  style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"> <?= $row_pago->importe_pago;?></td>
      <?php $fpago = $fpago + $row_pago->importe_pago;  } 
          
      ?>   
   </tr>
   <tr> 
      <td  style="font-size: 80px; font-family: 'tahoma'">VUELTO : </td>
      <td align="right"  style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?= number_format(($row_cabecera['total'] - $fpago)*(-1),2) ?>
   </td> 
  </tr>
   <tr>
   <td colspan="4">===================================================</td>
   </tr>
    <tr>
      <td align="center">
         <img src="<?=base_url()?>/sunat/<?= $row_empresa['ruc']?>/qr/<?= $row_empresa['ruc'].'-'.$row_cabecera['tipocomp'].'-'.$row_cabecera['serie'].'-'.$row_cabecera['correlativo'].'.png' ?>" alt="" width="50%">
      </td>
   </tr>

    <tr>
   <td colspan="4">===================================================</td>
</thead>

 </table>
 <table border="0" align="center" width="200px" style="font-size: 45px; font-family: 'Roboto', sans-serif; font-weight:500">
    <tr>
       <td colspan="4" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">
            <?php 

            if($row_cabecera['tipocomp']=='03')
            {
                    if($row_empresa['envio_resumen']=='SI')
                  {
                     $mensaje ='REPRESENTACION IMPRESA DE '. $doc;
                  } 
                  else if($row_empresa['envio_resumen']=='NO')
                  {
                     $mensaje = $doc .'  '.$row_cabecera['femensajesunat'];
                  }

            }
            else if($row_cabecera['tipocomp']=='99')
            {
                $mensaje = 'REPRESENTACION IMPRESA DE '.' '. $doc;

            }
            else if($row_cabecera['tipocomp']=='01' || $row_cabecera['tipocomp']=='07' || $row_cabecera['tipocomp']=='08')
            {
               if($row_empresa['envio_automatico']=='SI')
               {
                  $mensaje = $doc .'  '.$row_cabecera['femensajesunat'];
               }
               else
               {
                  $mensaje ='REPRESENTACION IMPRESA DE '.' '. $doc;
               }
            }

            ?>
                 <?=$mensaje?>
       </td>
    </tr>
    <tr>
          <td style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Hash: <?=$row_cabecera['hash']?> </td>
    </tr>
 </table>
 <table border="0" align="center" width="200px" style="font-size: 45px; font-family: 'Roboto', sans-serif; font-weight:500">
       <tr>
   <td colspan="4">===================================================</td>
   </tr>
    <tr>
      <td colspan="4" align="left" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600"><?=nl2br($row_empresa['comentario']) ?></td>
   </tr>
       <tr>
   <td colspan="4" align="center" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Gracias por su compra</td>

   </tr>
   <tr>
        <td colspan="4" align="center" style="font-size: 60px; font-family: 'Roboto', sans-serif; font-weight:600">Vuelva Pronto</td>
   </tr>
  
 </table>
    </div>
   </body>

   </html>