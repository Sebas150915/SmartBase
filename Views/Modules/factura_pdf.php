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

$color_bg = '#073385';
$color_tx = '#ffffff';


$factura=$rutas[1];
$empresa = $_SESSION['id_empresa'];

$query_empresa = $connect->prepare("SELECT * FROM tbl_empresas WHERE id_empresa = $empresa");
$query_empresa->execute();
$row_empresa=$query_empresa->fetch(PDO::FETCH_ASSOC);



$query_cabecera = $connect->prepare("SELECT * FROM vw_tbl_venta_cab as c
LEFT JOIN tbl_contribuyente as p
ON c.idcliente = p.id_persona WHERE id=$factura");
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
      else if($row_cabecera['tipocomp']=='10')
      {
         $doc = 'RECIBO DE ARRENDAMIENTO';
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


 if($row_cabecera['tipocomp_ref']=='01')
      {
         $docref = 'FACTURA ELECTRONICA';
      }
      else if($row_cabecera['tipocomp_ref']=='03')
      {
         $docref = 'BOLETA DE VENTA ELECTRONICA';
      }
      
/*$query_pago = $connect->prepare("SELECT * FROM tbl_venta_pag as p LEFT JOIN tbl_forma_pago AS f
ON p.fdp = f.id_fdp WHERE id_venta='$factura'");
$query_pago->execute();
$resultado_pago = $query_pago->fetchAll(PDO::FETCH_OBJ);*/
$numero = $row_cabecera['total'];
include 'assets/ajax/numeros.php';
$texto=convertir($numero);
//file_put_contents($rutaGuardado.$fileName, $fileData);

$invoiceFileName = $row_empresa['ruc'].'-'.$row_cabecera['tipocomp'].'-'.$row_cabecera['serie'].'-'.$row_cabecera['correlativo'];
$rutaGuardado = 'sunat/'.$row_empresa['ruc'].'/pdf/';


$output='';

$output.='
<html>
<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  
  <style>
       body
        {
          background: white;
          font-size: 16px;
         
            font-family: "Montserrat", sans-serif;

        }
        table
        {
          width: 100%;
          
        }
        th,tr{
          padding:0.5em;
        }
        tr
        {
            background-color = '.$color_bg.';
        }
        .border
        {
          border: 1px solid #000;
          border-spacing: 0;
          border-radius: 50%;
           padding: 0.3em;
        }

        .border1
        {
          border-bottom: 1px solid #000;
           padding: 0.3em;
          border-spacing: 0;
        }
         .border2
        {
          border-top: 1px solid #000;
          border-spacing: 0;
           padding: 0.3em;
        }
         .border3
        {
          border-left: 1px solid #000;
           padding: 0.3em;
          border-spacing: 0;
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

      footer {
position: fixed;
bottom: 0cm;
left: 0cm;
right: 0cm;
height: 2cm;


}



  </style>

</head>
<body>

    <div class="container-fluid">
      <table>
        <thead>
          <tr>
            <th width="30%"><img src="'.base_url().'/assets/images/'.$row_empresa["logo"].'" alt="" width="300px"></th>
            <th>
              <table border="0">
                <thead border="0">
                  <tr border="0">
                    <th border="0" style="font-size:24px; font-weight:bold">'.$row_empresa["razon_social"].'</th>
                  </tr>
                  <tr>
                    <th>'.$row_empresa["direccion"].'</th>
                  </tr>
                 
                </thead>
              </table>
            </th>
            <th width="25%">
              <table class="border">
                <thead>
                  <tr>
                    <th class="text-center border1">'.$row_empresa["ruc"].'</th>
                  </tr>
                  <tr>
                    <th class="text-center">'.$doc.'</th>
                  </tr>
                  <tr>
                    <th class="text-center border2">'.$row_cabecera["serie"].'-'.$row_cabecera["correlativo"].'</th>
                  </tr>
                </thead>
              </table>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="3">
              <table class="border">
                <thead>
                  <tr>
                    <th width="15%" class="border1">Cliente</th>
                    <th class="text-left border1 border3">'.$row_cabecera["nombre_persona"].'</th>
                  </tr>
                  <tr>
                    <th width="15%" class="">RUC</th>
                     <th class="text-left border3">'.$row_cabecera["num_doc"].'</th>
                  </tr>
                  <tr>
                    <th width="15%" class="border2">Direccion</th>
                     <th class="text-left border2 border3">'.$row_cabecera["direccion_persona"].'</th>
                  </tr>
                </thead>
              </table>
            </td>
          </tr>';

          if($row_cabecera['tipocomp']=='07'|| $row_cabecera['tipocomp']=='08')
          {
            $output.='<tr>
            <td colspan="3">
              <table class="border">
                <thead>
                  <tr>
                    <th width="15%" class="border1">Motivo</th>
                    <th class="text-left border1 border3">'.$row_cabecera["cod_motivo"].' | '.$row_cabecera["des_motivo"].'</th>
                  </tr>
                  <tr>
                    <th width="15%" class="">Tipo Doc Ref</th>
                     <th class="text-left border3">'.$docref.'</th>
                  </tr>
                  <tr>
                    <th width="15%" class="border2">Numero Ref</th>
                     <th class="text-left border2 border3">'.$row_cabecera["serie_ref"].'-'.$row_cabecera["correlativo_ref"].'</th>
                  </tr>
                </thead>
              </table>
            </td>
          </tr>';

          }

          $output.='<tr>
            <td colspan="3">
              <table class="table table-bordered border">
                <thead>
                  <tr>
                    <th class="border1 text-center" width="20%">FECHA EMISION</th>
                    <th class="border1 text-center border3" width="20%">FECHA DE VENCIMIENTO</th>
                    <th class="border1 text-center border3" width="20%">CONDICION DE VENTA</th>
                    <th class="border1 text-center border3" width="20%">ORDEN DE COMPRA</th>
                    <th class="border1 text-center border3" width="20%">MONEDA</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center">'.$row_cabecera["fecha_emision"].'</td>
                    <td class="text-center border3">'.$row_cabecera["fecha_vencimiento"].'</td>
                    <td class="text-center border3">'.$condicion.'</td>
                    <td class="text-center border3">'.$row_cabecera["orden_compra"].'</td>
                    <td class="text-center border3">'.$mon.'</td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <table class="border">
                <thead>
                  <tr>
                     <th width="8%" class="border1 text-center ">Cantidad</th>
                    <th class="border1 text-left border3">Descripcion</th>
                    <th width="5%" class="border1 text-center border3">U.M.</th>
                   
                    <th width="8%"  class="border1 text-center border3">P.U.</th>
                 
                    <th width="8%"  class="border1 text-center border3">Total</th>
                  </tr>
                </thead>
                <tbody>';
                  foreach ($resultado_detalle as $detalle) {
                  
                  $output.='
                     <tr>
                       <th class="text-right">'.$detalle->cantidad_factor.'/'.$detalle->cantidad_unitario.'</th>
                       <th class="text-left border3">'.$detalle->descripcion.'</th>
                       <th class="text-center border3">'.$detalle->unidad.'</th>
                      
                       <th class="text-right border3">'.number_format($detalle->valor_unitario,2).'</th>
                     
                       <th  class="text-right border3">'.number_format($detalle->importe_total,2).'</th>
                     </tr>';
                  } 

          $output.='
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <table width="100%">
                <tr>
                  <th width="50%">
                    <table width="100%">
                      <thead>
                        <tr>
                          <td>SON: '.$texto.'</td>
                        </tr>
                        <tr>
                          <td>
                            La '.$doc .'  '.$row_cabecera["femensajesunat"].'
                          </td>
                        </tr>
                        <tr>
                          <td>Hash: '.$row_cabecera["hash"].' </td>
                        </tr>
                      </thead>
                    </table>
                  </th>
                  <th width="20%">
                    <table width="100%">
                      <tr>
                        <td><img src="'.base_url().'/sunat/'.$row_empresa["ruc"].'/qr/'.$row_empresa["ruc"].'-'.$row_cabecera["tipocomp"].'-'.$row_cabecera["serie"].'-'.$row_cabecera["correlativo"].'.png" alt="" width="150px"></td>
                      </tr>
                    </table>
                  </th>
                  <th width="30%">
                    <table class="border">
                      <tr>
                        <th class="text-right">Op. Gravadas</th>
                        <th class="text-right border3">'.number_format($row_cabecera["op_gravadas"],2).'</th>
                      </tr>
                      <tr>
                        <th class="border2 text-right">Op. Exonerdas</th>
                        <th class="border2 border3 text-right">'.number_format($row_cabecera["op_exoneradas"],2).'</th>
                      </tr>
                      <tr>
                        <th class="border2 text-right">Op. Inafectas</th>
                        <th class="border2 border3 text-right">'.number_format($row_cabecera["op_inafectas"],2).'</th>
                      </tr>
                      <tr>
                        <th class="border2 text-right">I.G.V.</th>
                        <th class="border2 border3 text-right">'.number_format($row_cabecera["igv"],2).'</th>
                      </tr>
                      <tr>
                        <th class="border2 text-right">Total</th>
                        <th class="border2 border3 text-right">'.number_format($row_cabecera["total"],2).'</th>
                      </tr>
                    </table>
                  </th>
                </tr>
                <tr>
                          <td>Observacion: '.$row_cabecera["obs"].' </td>
                        </tr>
                
              </table>
            </td>
          </tr>
          
        </tbody>
        
      </table>';
        if($row_cabecera['imp_det']>0)
        {
          $output .='
          OPERACIÓN ES SUJETA AL SISTEMA DE PAGO DE OBLIGACIONES 
TRIBUTARIAS CON EL GOBIERNO CENTRAL D.I. N°940
          <table class="border" width="200px">
                      
                        <tr>
                           <th cth class="border1 text-center"">IMPORTE DETRACCION</th>
                           <th class="border1 text-center border3">SALDO FACTURA</th>
                           <th class="border1 text-center border3">PORCENTAJE DETRACCION</th>
                           <th class="border1 text-center border3">CODIGO DETRACCION</th>
                        </tr>
                        <tr>
                            <th class="text-center">'.$row_cabecera['imp_det'].'</th>
                           <th class="text-center border3">'. (intval ($row_cabecera['total']) -  intval ($row_cabecera['imp_det'])).'</th>
                          <th class="text-center border3">'.$row_cabecera['por_det'].'</th>
                          <th class="text-center border3">'.$row_cabecera['cod_det'].'</th>
                        </tr>

                    </table>';

        }

    $output .='</div>
    
    
<footer>
Powered by SmartBase 
</footer>
  </body>

</html>';

//echo $output; exit();

$dompdf = new DOMPDF();
$dompdf->set_paper('A4','portrait');
$dompdf->load_html($output);
$dompdf->render();
$font = $dompdf->getFontMetrics()->getFont("Arial", "bold");
$pdf = $dompdf->output();
header('Content-Type: application/pdf');
header("Content-Disposition: inline; filename=".$invoiceFileName.".pdf");
echo $pdf;
//$dompdf->stream($invoiceFileName, array("Attachment" => true));
//file_put_contents($rutaGuardado.$invoiceFileName, $pdf);



 ?>


