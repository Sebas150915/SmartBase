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
$query_pago = $connect->prepare("SELECT * FROM vw_tbl_venta_pago WHERE id_venta='$factura'");
$query_pago->execute();
$resultado_pago=$query_pago->fetchAll(PDO::FETCH_OBJ);


 ?>


<html> 
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
   
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Arialbold:wght@200&display=swap" rel="stylesheet">
   <style>

      * {
    font-size: 12px;
    font-family: 'Times New Roman';
}



td,
th,
tr,
table {
    
    border-collapse: collapse;
}

td.producto,
th.producto {
    width: 75px;
    max-width: 75px;
}

td.cantidad,
th.cantidad {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}


th.decripcion {

    width: 82mm;
    max-width: 82mm;
    word-break: break-all;
}

th.decripcion1 {
    width: 40mm;
    max-width: 40mm;
    word-break: break-all;
}

td.precio,
th.precio {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}

.centrado {
    text-align: center;
    align-content: center;
}

.zona_impresion {
    width: 90mm;
    max-width: 90mm;
}


.zona_impresion{
   position: absolute;
   box-sizing: border-box;
width: 87mm;
   -webkit-box-shadow: 7px 6px 21px -2px rgba(0,0,0,0.58);
-moz-box-shadow: 7px 6px 21px -2px rgba(0,0,0,0.58);
box-shadow: 7px 6px 21px -2px rgba(0,0,0,0.58);
}



   </style>
</head>
    <!--onload="window.print();"-->
  <body onload="window.print();">   


   <div class="zona_impresion">
   <!--codigo imprimir-->
   <br>
   <table>
<thead>
   <tr>
      <td align="center">
         <img src="<?=media()?>/images/<?=$row_empresa['logo']?>" alt="" width="60%">
      </td>
   </tr>
   <tr>
         <td align="center" style="font-size: 17px ;font-family: 'Arialbold', sans-serif; font-weight:bold;">
            <!--mostramos los datos de la empresa en el doc HTML-->
            .::<strong> <?php echo $row_empresa['razon_social']?></strong>::.<br>
            
         </td>
         
      </tr>
       <?php if(empty($row_empresa['logo'])){ ?>
      <tr>
         <td align="center" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:700">
            <!--mostramos los datos de la empresa en el doc HTML-->
            .::<strong> <?php echo $row_empresa['razon_social']?></strong>::.<br>
            
         </td>
         
      </tr>
        <tr>
          <td colspan="4">==============================================</td>
        
      </tr>

   <?php }?>
     
      <tr>
          <td align="left" style="font-size: 12px; font-family: 'Arialbold', sans-serif; font-weight:600">
            DIRECCION:<?php echo $row_empresa['direccion'] ; ?><br>
            SUCURSAL :<?php echo $_SESSION["sucursal"]   ; ?><br>
            CELULAR  :<?php echo $row_empresa['celular'] ; ?><br>
          
           
         </td>
      </tr>
       <tr>
          <td colspan="4">==============================================</td>
        
      </tr>

<tr>
   <td align="center"  style="font-size: 14px; font-family: 'Arialbold', sans-serif; font-weight:bold;">RUC:  <?php echo $row_empresa['ruc'] ; ?></td>
</tr>
 <tr>
          <td colspan="4">==============================================</td>
        
</tr>

<tr>
   <td align="center" style="font-size: 14px; font-family: 'Arialbold', sans-serif; font-weight:600"><strong class="text-bold" style="font-size: 15px; font-family: 'Arialbold', sans-serif; font-weight:600"><?php
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
   <td>Motivo: ". $row_cabecera['cod_motivo']." | ". $row_cabecera['des_motivo']."</td>
  
</tr>
<tr>
   <td>Documento: ". $row_cabecera['tipocomp_ref']."-".$row_cabecera['serie_ref']."-".$row_cabecera['correlativo_ref']."</td>
</tr>
";
} ?>

<tr>
          <td colspan="4">==============================================</td>
        
</tr>

      <tr> 
         <td align="center"></td>
      </tr>
            <tr>
         <td align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">
            Fecha Emision : <?= $row_cabecera['fecha_emision'] ?> <br>
            Hora          : <?= $row_cabecera['hora_emision'] ?> <br>
         </td>
      </tr>
 

<tr>
   <td align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Cliente: <?=$row_cabecera['nombre_persona']  ?> <br></td>
</tr>
<tr>
   <td align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Obs: <?=$row_cabecera['obs']  ?> <br></td>
</tr>
<tr>
   <td align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Nro Documento: <?=$row_cabecera['num_doc'] ?> <br></td>
</tr>
<tr>
   <td align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Direccion: <?=$row_cabecera['direccion_persona'] ?> <br></td>
</tr>

<tr>
   <td align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">condicion: <?=$row_cabecera['det_condicion'] ?> <br></td>
</tr>

<?php if($row_cabecera['orden_compra'] != ''){ ?>
   <tr>
   <td align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Doc Ref: <?=$row_cabecera['orden_compra'] ?> <br></td>
</tr>
<?php } ?>

<tr>
          <td colspan="4">==============================================</td>
        
</tr>
</thead>
</table>

   <table width="100%">
      <thead>
        <tr>           
            <th class="descripcion" align="left" colspan="5"style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Descripcion</th>
         </tr>
         <tr>           
            <th align="left"style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Cantidad</th>
            <th align="center" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">UNI</th>
          
            <th align="center" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Precio</th>
            <th align="right" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Total</th>
         </tr>
<tr>
          <td colspan="4">==============================================</td>
        
</tr>
      </thead>
      <?php if($row_empresa['venta_por_mayor']==='SI') {?>
      <tbody style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">
             <?php  foreach($resultado_detalle as $row_detalle)
               { ?>
                 
                  <tr><td align="left" colspan="4"style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?=strtoupper($row_detalle->descripcion) ?></td></tr>
                  <tr><td align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= number_format(strtoupper($row_detalle->cantidad_factor),2).'/'.strtoupper($row_detalle->cantidad_unitario) ?></td>
                      <td align="center" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= strtoupper($row_detalle->unidadf).'/'.strtoupper($row_detalle->unidadu) ?></td>
               
               <td align="center" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= number_format($row_detalle->precio_unitario*$row_detalle->factor,2).'/'.number_format($row_detalle->precio_unitario,2)  ?></td>
               <td align="right" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= number_format($row_detalle->valor_unitario*$row_detalle->cantidad,2) ?></td>
              
                  </tr>
                 
                <?php } ?>
      </tbody>
   <?php } 
   else{?>
      <tbody>
             <?php  foreach($resultado_detalle as $row_detalle)
               { ?>
                 
                  <tr>  
                <td class="description" align="left" colspan="4" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?=strtoupper($row_detalle->descripcion) ?>
                </td>
                  </tr>
                  <tr>
               <td width="20%" class="cantidad" align="left"  style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= strtoupper($row_detalle->cantidad) ?></td>

               <td width="20%" class="unidad" align="center" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= strtoupper($row_detalle->unidadu) ?></td>
               
               <td class="precio" align="center" class="text-bold"  style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= number_format($row_detalle->precio_unitario,2) ?></td>
               <td class="total" align="right"  style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= number_format($row_detalle->precio_unitario*$row_detalle->cantidad,2) ?></td>

                  </tr>
                  <?php 
                    $id_producto = $row_detalle->codigo;
                    $query_pro = "SELECT * FROM vw_tbl_recetas WHERE id_producto=$id_producto";
                    $resultado_pro=$connect->prepare($query_pro);
                    $resultado_pro->execute();
                    $row_pro = $resultado_pro->fetch(PDO::FETCH_ASSOC);
                    $num_reg_pro=$resultado_pro->rowCount();
                    
                    if($num_reg_pro>0)
                    { ?>
                     <tr>
                         <td class="description" align="left" colspan="4" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?=$row_pro['nombre_insumo'].' '.$row_pro['cantidad']?></td>
                         
                     </tr> 
                   <?php  }
                  ?>

                <?php } ?>
      </tbody>
   <?php } ?>
   </table>
    <table width="100%">
      <thead>
      <tr>
      <td colspan="4">==============================================</td>

      </tr> 
      <tr>
             <td colspan="2" class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Op. Gravada</td>
             <td colspan="2" align="right"  class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= $row_cabecera['op_gravadas'] ?></td>
          </tr>
          <tr>
             <td colspan="2"  class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Op. Exonerada</td>
             <td colspan="2" align="right"   class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= $row_cabecera['op_exoneradas'] ?></td>
          </tr>
          <tr>
             <td colspan="2"  class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Op. Inafecta</td>
             <td colspan="2" align="right"   class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= $row_cabecera['op_inafectas'] ?></td>
          </tr>
          <tr>
             <td colspan="2"  class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">SUB TOTAL</td>
             <td colspan="2" align="right"   class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= number_format($row_cabecera['op_gravadas'] + $row_cabecera['op_exoneradas'] + $row_cabecera['op_inafectas'],2) ?></td>
          </tr>
          <tr>
             <td colspan="2"   class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">I.G.V.</td>
             <td colspan="2" align="right"   class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= $row_cabecera['igv'] ?></td>
          </tr>
          <?php if($row_cabecera['redondeo']>0){ ?>
          
          <tr>
       <td colspan="2"   class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Redondeo</td>
       <td colspan="2" align="right" class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= $row_cabecera['redondeo'] ?></td>
    </tr>
          
          <?php } ?>
          
          <tr>
       <td colspan="2"   class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">TOTAL</td>
       <td colspan="2" align="right" class="descripcion1" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= $row_cabecera['total'] ?></td>
    </tr>
      <tr>
      <td colspan="4">==============================================</td>

      </tr>           
 </thead>
    </table>
 <table>
  <thead>
   
   <tr>
      <td colspan="4" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">
         SON : <?=$texto?>
      </td>
   </tr>
    
   <tr>
      <td colspan="4" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">
         VENDEDOR : <?=$row_cabecera["nom_usuario"]?>
      </td>
   </tr>
   
<tr>
      <td colspan="4">==============================================</td>

      </tr> 
    
    
    
      <?php $fpago=0;
         foreach($resultado_pago as $row_pago){ ?>
      <tr>
         <td  style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">
         <?=$row_pago->nombre;?></td>
         <td align="right"  style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"> <?= $row_pago->importe_pago;?></td>
        </tr>
      <?php $fpago = $fpago + $row_pago->importe_pago;  } 
          
      ?>   
 <?php if($row_cabecera['tipocomp']<>'07') {?>
   <tr> 
      <td  style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">VUELTO : </td>
      <td align="right"  style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?= number_format(($row_cabecera['total'] - $fpago)*(-1),2) ?>
   </td> 
 
  </tr>
         <tr>
      <td colspan="4">==============================================</td>

      </tr> 
      <?php } ?>
    <tr>
      <td align="center">
         <img src="<?=base_url()?>/sunat/<?= $row_empresa['ruc']?>/qr/<?= $row_empresa['ruc'].'-'.$row_cabecera['tipocomp'].'-'.$row_cabecera['serie'].'-'.$row_cabecera['correlativo'].'.png' ?>" alt="" width="20%">
      </td>
   </tr>

 
</thead>

 </table>
 <table>
    <tr>
       <td colspan="4" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">
            <?php 

            if($row_cabecera['tipocomp']=='03')
            {
                    if($row_empresa['envio_resumen']=='SI')
                  {
                     $mensaje ='REPRESENTACION IMPRESA DE '. $doc;
                     $mensaje1 ='';
                  } 
                  else if($row_empresa['envio_resumen']=='NO')
                  {
                     $mensaje =  $row_cabecera['femensajesunat'];
                     $mensaje1 ='REPRESENTACION IMPRESA DE '. $doc;
                  }

            }
            else if($row_cabecera['tipocomp']=='99' )
            {
                $mensaje = 'REPRESENTACION IMPRESA DE '.' '. $doc;

            }
            else if($row_cabecera['tipocomp']=='01' || $row_cabecera['tipocomp']=='07' || $row_cabecera['tipocomp']=='08')
            {
               if($row_empresa['envio_automatico']=='SI')
               {
                  $mensaje = $row_cabecera['femensajesunat'];
                    $mensaje2 = 'REPRESENTACION IMPRESA DE '.' '. $doc;
                  $mensaje1 ='';
               }
               else
               {
                  $mensaje ='REPRESENTACION IMPRESA DE '.' '. $doc;
                  $mensaje1 ='';
               }
            }

            ?>
                 <?=$mensaje?>
                 
                  <?=$mensaje2?>
       </td>
       
    </tr>
    <tr>
        <td colspan="4" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">
           <?=$mensaje1?>
       </td>
    </tr>
    <tr>
   <td style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Hash: <?=$row_cabecera['hash']?> </td>
    </tr>
    <tr>
      <td colspan="4" align="left" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600"><?=nl2br($row_empresa['comentario']) ?></td>
   </tr>
   <tr>
   <td colspan="4" align="center" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Gracias por su compra</td>

   </tr>
   <tr>
        <td  colspan="4" align="center" style="font-size: 13px; font-family: 'Arialbold', sans-serif; font-weight:600">Vuelva Pronto</td>
   </tr>
 </table>

    </div>
   </body>

   </html>