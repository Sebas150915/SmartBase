<?php
session_start();
ob_start();
date_default_timezone_set("America/Lima");

require_once 'assets/dompdf/lib/html5lib/Parser.php';
require_once 'assets/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'assets/dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'assets/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

$empresa   = $_SESSION['id_empresa'];
$local     = $_SESSION["almacen"]; 
$idusuario = $_POST['idusuario'];
$fechaz    = $_POST['fechaz'];

$query_empresa = $connect->prepare("SELECT * FROM tbl_empresas WHERE id_empresa = $empresa");
$query_empresa->execute();
$row_empresa=$query_empresa->fetch(PDO::FETCH_ASSOC);

$query_local = $connect->prepare("SELECT * FROM tbl_almacen WHERE empresa = $empresa and id = $local");
$query_local->execute();
$row_local=$query_local->fetch(PDO::FETCH_ASSOC);

$query_user = $connect->prepare("SELECT * FROM tbl_usuarios WHERE id_usuario = $idusuario");
$query_user->execute();
$row_user=$query_user->fetch(PDO::FETCH_ASSOC);

$query_z = $connect->query("SELECT * FROM vw_tbl_venta_z WHERE id_usuario = $idusuario and fecha_emision = '$fechaz'");
$query_z->execute();
$row_z=$query_z->fetchAll(PDO::FETCH_OBJ);

$query_fdp = $connect->query("SELECT * FROM vw_tbl_venta_pago_tipo WHERE id_usuario = $idusuario and fecha_pago = '$fechaz'");
$query_fdp->execute();
$row_fdp=$query_fdp->fetchAll(PDO::FETCH_OBJ);

$query_pr = "SELECT fecha,nombre,sum(cantidad) as cantidad,sum(cantidad*precio_unitario) as total FROM vw_tbl_venta_producto WHERE vendedor = $idusuario and fecha = '$fechaz' GROUP BY fecha,nombre,vendedor";
$query_prod = $connect->query($query_pr);
//echo $query_pr;exit();
$query_prod->execute();
$row_prod=$query_prod->fetchAll(PDO::FETCH_OBJ);
//print_r($row_prod);exit();


$query_cre = "SELECT SUM(total) as tot_cre FROM tbl_venta_cab WHERE idempresa=$empresa AND condicion_venta ='2' AND vendedor = $idusuario and fecha_emision = '$fechaz'";
$resultado_cre = $connect->prepare($query_cre);
$resultado_cre->execute();
$row_cre = $resultado_cre->fetch(PDO::FETCH_ASSOC);


include 'assets/ajax/numeros.php';
$texto=convertir($numero);
//file_put_contents($rutaGuardado.$fileName, $fileData);

$invoiceFileName = 'Reportez.pdf';
$rutaGuardado = 'sunat/pdf/';

$height = 550;//mm
$width = 78;
$ancho = ($width/25.4)*72;
$largo =($height/25.4)*72;
$colspan = 5;

$output='';

$output.='
<html>
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montagu+Slab:opsz,wght@16..144,100..700&display=swap" rel="stylesheet">
  
  <style>
  
       body
        {
           font-size: 18px;
         font-family: "Montagu Slab", serif;
  font-optical-sizing: auto;
  font-weight: 700;
  font-style: bold;
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
<td colspan="'.$colspan.'" align="center">'.$row_empresa["razon_social"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">'.$row_empresa["ruc"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">'.$row_empresa["direccion"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">'.$row_local["nombre"].'</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>
';

$output.='

<tr>
<td class="text-left" colspan="3" >FECHA EMISION</td>
<td class="text-left" colspan="2">'.$fechaz .'</td>
</tr>
<tr>
<td class="text-left" colspan="3" >HORA</td>
<td class="text-left" colspan="2">'.date('h:i:s').'</td>
</tr>
<tr>
<td class="text-left" colspan="3" >VENDEDOR(A)</td>
<td class="text-left" colspan="2">'.$row_user['nombre_personal'].' '.$row_user['apellido_personal'] .'</td>
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
$total =0;
foreach ($row_z as $user) {

$output.='
<tr>
<th class="text-left border3" colspan="'.$colspan.'"> TIPO  :'.$user->tipocomp.'</th>
</tr>
<tr>
<th class="text-left border3" colspan="'.$colspan.'"> SERIE :'.$user->serie.'</th>
</tr>
<tr>
<th class="text-left border3">DESDE:'.$user->desde.'</th>
</tr>
<tr>
<th class="text-left border3">HASTA:'.$user->hasta.'</th>
</tr>
<tr>
<th class="text-left border3">VALOR VENTA:</th>
<th class="text-right border3">'.$user->op_gravadas.'</th>
</tr>
<tr>
<th class="text-left border3">IGV:</th>
<th class="text-right border3">'.$user->igv.'</th>
</tr>
<tr>
<th class="text-left border3">TOTAL:</th>
<th class="text-right border3">'.$user->total.'</th>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>

';
$total = $total + $user->total;

} 

$output.='
<tr>
<th class="text-left border3" colspan="2">TOTAL POR DOCUMENTO:</th>
<th class="text-right border3" style="font-size: 20px">'.number_format($total,2,'.',',').'</th>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">RESUMEN DE VENTAS POR TIPO DE PAGO</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="left"></td>
<td colspan="'.$colspan.'" align="right"></td>
</tr>
<tr>
<th colspan="2">DESCRIPCION</th>
<th>IMPORTE</th>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>';
$totalf = 0;
$vueltof =0;
foreach ($row_fdp as $fdp1) 
{
$vueltof = ($fdp1->vuelto)+($vueltof);
}

 //echo $vueltof;exit();
foreach ($row_fdp as $fdp) 
{
    //echo $vueltof;exit();
    if($fdp->tipo_pago == 'EFECTIVO')
    {
       $importe_p = $fdp->importe_pago - $vueltof; 
      
    }
    else
    {
        $importe_p = $fdp->importe_pago; 
    }
    //echo  $importe_p ;exit();
$output.='
<tr>
<th class="text-left border3" colspan="2">'.$fdp->tipo_pago.'</th>

<th class="text-right border3" colspan="1">'.number_format($importe_p,2,'.',',').'</th>
</tr>

';
$totalf = $totalf + $importe_p;


} 

$output.='
<tr>
<th class="text-left border3" colspan="2">CREDITO</th>

<th class="text-right border3" colspan="1">'.$row_cre['total_cre'].'</th>
</tr>

';

$output.='
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>
<tr>
<th class="text-left border3">TOTAL POR TIPO DE PAGO:</th>
<th class="text-right border3" style="font-size: 20px">'.number_format($totalf,2,'.',',').'</th>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center">DETALLE DE PRODUCTOS</td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"></td>
</tr>
<tr>
<td colspan="'.$colspan.'" align="center"></td>
</tr>
<tr>
<td align="left">PRODUCTO</td>
<td align="left">CANT.</td>
<td align="right">TOTAL</td>
</tr>';
$totalpro = 0;
foreach ($row_prod as $pro) 
{
$output.='<tr>
<th class="text-left border3" >'.$pro->nombre.'</th>

<th class="text-right border3">'.$pro->cantidad.'</th>
<th class="text-right border3">'.number_format($pro->total,2,'.',',').'</th>
</tr>'; 
$totalpro = $totalpro + $pro->total;
}
$output.='
<tr>
<td colspan="'.$colspan.'" align="center"><hr></td>
</tr>
<tr>
<th class="text-left border3" colspan="2">TOTAL POR PRODUCTOS:</th>
<th class="text-center border3" style="font-size: 20px">'.number_format($totalpro,2,'.',',').'</th>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr>
<th colspan="'.$colspan.'" align="center"><hr></th>
</tr>
</tbody>
</table>';



$output.='
</body>


</table>';
//echo $output; exit();

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


