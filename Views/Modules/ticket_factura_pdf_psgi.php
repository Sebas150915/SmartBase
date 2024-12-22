<?php
session_start();
ob_start();
//date_default_timezone_set("America/Lima");

require_once 'assets/dompdf/lib/html5lib/Parser.php';
require_once 'assets/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'assets/dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'assets/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;


$factura=$rutas[1];
$empresa = $_SESSION['id_empresa'];

 
$query_pago = $connect->prepare("SELECT * FROM vw_tbl_venta_pago WHERE id_venta='$factura'");
$query_pago->execute();
$resultado_pago=$query_pago->fetchAll(PDO::FETCH_OBJ);

print_r($resultado_pago);
$query_cabecera = $connect->prepare("SELECT * FROM vw_tbl_venta_cab as c
LEFT JOIN tbl_contribuyente as p
ON c.codcliente = p.num_doc WHERE id=$factura");
$query_cabecera->execute();
$row_cabecera=$query_cabecera->fetch(PDO::FETCH_ASSOC);

//DETALLE
$query_detalle = $connect->query("SELECT * FROM vw_tbl_venta_det WHERE idventa=$factura ORDER BY item");
$resultado_detalle = $query_detalle->fetchAll(PDO::FETCH_OBJ);

//var_dump($resultado_detalle);exit();
//$resultado_detalle = $query_detalle->fetchAll(PDO::FETCH_OBJ);

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
  if($row_cabecera['condicion_venta']=='1')
  {
    $condicion = 'CONTADO';
  }
  else
  {
    $condicion = 'CREDITO';
  }

 if($row_cabecera['codmoneda']=='PEN')
  {
    $mon = 'SOLES';
}
else
{
  $mon='DOLARES';
} 


/*$query_pago = $connect->prepare("SELECT * FROM tbl_venta_pag as p LEFT JOIN tbl_forma_pago AS f
ON p.fdp = f.id_fdp WHERE id_venta='$factura'");
$query_pago->execute();
$resultado_pago = $query_pago->fetchAll(PDO::FETCH_OBJ);*/
$numero = $row_cabecera['total'];
include 'assets/ajax/numeros.php';
$texto=convertir($numero);
//file_put_contents($rutaGuardado.$fileName, $fileData);

$invoiceFileName = $row_empresa['ruc'].'-'.$row_cabecera['tipocomp'].'-'.$row_cabecera['serie'].'-'.$row_cabecera['correlativo'].'.pdf';
$rutaGuardado = 'sunat/pdf/';

$height = 250;//mm
$width = 72;
$ancho = ($width/25.4)*72;
$largo =($height/25.4)*72;
$colspan = 5;

$output='';

$output.='
<html>
<head>

  
  <style>
       body
        {
          background: white;
          font-size: 15px;
         
          font-family: Verdana;
        }
        html {
	margin: 50pt 15pt;
}
        table
        {
          width: 100%;
          
        }
        th,tr{
          padding:0.05em;
        }

 

        .text-center 
        {
          text-align: center !important;
        }     
        .text-left 
        {
          text-align: left !important;
        }

        .text-right
        {
          text-align: right !important;
        }  

      



  </style>
</head>

<body>
<table>
<tbody>
<tr>
 <td colspan="'.$colspan.'" class="text-center">
 <img src="'.base_url().'/assets/images/'.$row_empresa["logo"].'" alt="" width="200px">
 </td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">'.$row_empresa["razon_social"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">'.$row_empresa["direccion"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>

<tr>
<td colspan="'.$colspan.'" align="center">'.$row_empresa["ruc"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">'.$doc.'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">'.$row_cabecera["serie"].'-'.$row_cabecera["correlativo"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>
<tr>
<td colspan="1">Cliente</td>
<td colspan="4" class="text-left">'.$row_cabecera["nombre_persona"].'</td>
</tr>
<tr>
<td colspan="1">Nro. Doc.</td>
<td colspan="4" class="text-left">'.$row_cabecera["num_doc"].'</td>
</tr>
<tr>
<td colspan="1">División</td>
<td colspan="4" class="text-left">'.$row_cabecera["division"].'</td>
</tr>

<tr>
<td colspan="1">Zona</td>
<td colspan="4" class="text-left">'.$row_cabecera["zona"].'</td>
</tr>

<tr>
<td colspan="1">Modalidad de Contribución:</td>
<td colspan="4" class="text-left">'.$row_cabecera["division"].'</td>
</tr>

<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>';
if($row_cabecera['tipocomp']=='07'|| $row_cabecera['tipocomp']=='08')
{
$output.='<tr>
<td colspan="1">Motivo</td>
<td colspan="4" class="text-left">'.$row_cabecera["cod_motivo"].' | '.$row_cabecera["des_motivo"].'</td>
</tr>
<tr>
<td wcolspan="1">Tipo Doc Ref</td>
<td colspan="4" class="text-left">'.$row_cabecera["nom_cod_motivo"].'</td>
</tr>
<tr>
<td colspan="1">Numero Ref</td>
<td colspan="4" class="text-left">'.$row_cabecera["serie_ref"].'-'.$row_cabecera["correlativo_ref"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>
';
}

$output.='

<tr>
<td class="text-left" colspan="3" >FECHA EMISION</td>
<td class="text-left" colspan="2">'.$row_cabecera["fecha_emision"].'</td>
</tr>
<tr>
<td class="text-left" colspan="3">FECHA DE VENCIMIENTO</td>
<td class="text-left" colspan="2">'.$row_cabecera["fecha_vencimiento"].'</td>
</tr>

<tr>
<td class="text-left" colspan="3">MONEDA</td>
<td class="text-left" colspan="2">'.$mon.'</td>
</tr>
        



<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>

</tbody>
</table>';

$output.=' 
<table>
<tbody> ';
$output.='
<tr>
<td colspan="">
<table class="border" >

<tbody>';
foreach ($resultado_detalle as $detalle) {

$output.='
<tr>
<th class="text-left border3" >'.$detalle->descripcion.'</th>
</tr>';
} 

$output.='
</tbody>
</table>
</td>
</tr>
<tr>
<td>

<table class="border">


<tr>
<th class="border2 text-right">La suma de:</th>
<th class="border2 border3 text-right">'.number_format($row_cabecera["total"],2).'</th>
</tr>

</table>

</td>
</tr>
<tr>
<td align="center"><hr></td>
</tr>
<tr>
<td><strong style="font-weight: bold;">SON: '.$texto.'</strong></td>
</tr>
<tr>
<td align="center"><hr></td>
</tr>
';

 $fpago=0;
         foreach($resultado_pago as $row_pago){ 
      $output .='<tr>
         <td  style="font-size: 13px; font-family: "Arialbold", sans-serif; font-weight:600">'.
         $row_pago->nombre .'</td>
         <td align="right"  style="font-size: 13px; font-family: "Arialbold", sans-serif; font-weight:600">'. $row_pago->importe_pago .'</td>
        </tr>';
        $fpago = $fpago + $row_pago->importe_pago;
        }; 
$output .='<tr>
<td align="center"><hr></td>
</tr>

<tr>
<td>
La '.$doc .'  '.$row_cabecera["femensajesunat"].'
</td>
</tr>

<tr>
<td>Hash: '.$row_cabecera["hash"].' </td>
</tr>
<tr>
<td></td>
</tr>
<tr>
<td align="center"><img src="'.base_url().'/sunat/'.$row_empresa["ruc"].'/qr/'.$row_empresa["ruc"].'-'.$row_cabecera["tipocomp"].'-'.$row_cabecera["serie"].'-'.$row_cabecera["correlativo"].'.png" alt="" width="150px"></td>
</tr>




</tbody>

</table>
</div>
</body>

</html>';

/*echo $output; exit();*/

$paper_format = array(0,0,$ancho,$largo);//ticket
$dompdf = new DOMPDF();
$dompdf->set_paper($paper_format,'portrait');
//$dompdf->set_paper(array(0,0,500,1000));
$dompdf->load_html($output);
$dompdf->render();
//$font = $dompdf->getFontMetrics()->getFont("Courier", "bold");
$dompdf->set_option('defaultFont', 'Courier');
$pdf = $dompdf->output();
header('Content-Type: application/pdf');
header("Content-Disposition: inline; filename=Registro_".date("d-m-Y")."_".time().".pdf");
echo $pdf;
//$dompdf->stream($invoiceFileName, array("Attachment" => true));
//file_put_contents($rutaGuardado.$invoiceFileName, $pdf);



 ?>


