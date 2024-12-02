<?php
//session_start();
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

$query_empresa = $connect->prepare("SELECT * FROM tbl_empresas WHERE id_empresa = $empresa");
$query_empresa->execute();
$row_empresa=$query_empresa->fetch(PDO::FETCH_ASSOC);



$query_cabecera = $connect->prepare("SELECT * FROM vw_tbl_gre_cab as c
LEFT JOIN tbl_contribuyente as p
ON c.codcliente = p.num_doc WHERE id=$factura");
$query_cabecera->execute();
$row_cabecera=$query_cabecera->fetch(PDO::FETCH_ASSOC);

//DETALLE
$query_detalle = $connect->query("SELECT * FROM vw_tbl_gre_det WHERE idguia=$factura ORDER BY item");
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
      else if($row_cabecera['tipocomp']=='09')
      {
         $doc = 'GUIA DE REMISION ELECTRONICA';
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

$invoiceFileName = $row_empresa['ruc'].'-09'.$row_cabecera['serie'].'-'.$row_cabecera['correlativo'].'.pdf';
$rutaGuardado = 'sunat/pdf/';


$output='';

$output.='
<html>
<head>

  
  <style>
       body
        {
          background: white;
          font-size: 14px;
          font-family: tahoma;
        }
        table
        {
          width: 100%;
          
        }
        th,tr{
          padding:0.5em;
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

         .border4
        {
          border-right: 1px solid #000;
           padding: 0.3em;
          border-spacing: 0;
        }
        .border5
        {
          border-right: 3px solid #FFF;
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

      



  </style>
</head>
<body>

    <div class="container-fluid">
      <table>
        <thead>
          <tr>
            <th width="30%"><img src="'.base_url().'/assets/images/'.$row_empresa['logo'].'" alt="" width="200px"></th>
            <th>
              <table border="0" class="text-left">
                <thead>
                  <tr>
                    <th>'.$row_empresa['razon_social'].'</th>
                  </tr>
                  <tr>
                    <th>Direccion : '.$row_empresa['direccion'].'</th>
                  </tr>
                  <tr>
                    <th>correo</th>
                  </tr>
                </thead>
              </table>
            </th>
            <th width="20%">
              <table class="border1 border2 border3 border4">
                <thead>
                  <tr>
                    <th class="text-center border1">'.$row_empresa['ruc'].'</th>
                  </tr>
                  <tr>
                    <th class="text-center border1">'.$doc.'</th>
                  </tr>
                  <tr>
                    <th class="text-center">'.$row_cabecera['serie'].'-'.$row_cabecera['correlativo'].'</th>
                  </tr>
                </thead>
              </table>
            </th>
          </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="3">
              <table class="border1 border2 border3 border4">
                <thead>
                  <tr>
                    <th  class="border5"  style="background:black;color:white">FECHA DE EMISION</th>
                    <th  class="border5" style="background:black;color:white">FECHA TRASLADO</th>
                    <th  style="background:black;color:white">DOC. REFERE</th>
                  </tr>
                  <tr>
                    <th width="" class="border4 text-center">'.$row_cabecera['fecha_emision'].'</th>
                    <th width="" class="border4 text-center">'.$row_cabecera['fecha_traslado'].'</th>
                     <th class=" text-left"></th>
                  </tr>
                  
                </thead>
              </table>
            </td>
          </tr>
          <tr><td></td></tr>
          <tr>
            <td colspan="3">
              <table class="border1 border2 border3 border4">
                <thead>
                  <tr>
                    <th width="50%" class="border5"  style="background:black;color:white">PUNTO DE PARTIDA</th>
                    <th  style="background:black;color:white">PUNTO DE LLEGADA</th>
                  </tr>
                  <tr>
                    <th width="50%" class="border4 text-left">'.$row_cabecera['direccion_partida'].'</th>
                     <th class=" text-left">'.$row_cabecera['direccion_destino'].'</th>
                  </tr>
                  
                </thead>
              </table>
            </td>
          </tr>
          <tr><td></td></tr>
          <tr>
            <td colspan="3">
              <table class="border1 border2 border3 border4">
                <thead>
                  <tr>
                    <th width="50%" class="border5"  style="background:black;color:white">REMITENTE</th>
                    <th  style="background:black;color:white">DESTINATARIO</th>
                  </tr>
                  <tr>
                    <th width="50%" class="border4 text-left">'.
                      $row_cabecera['razon_social'].'<br />'.
                       'RUC      '.$row_cabecera['ruc_empresa'].'<br /></th>
                     <th class=" text-left">'.$row_cabecera['razon_social_cliente'].'<br />'.
                       'RUC      '.$row_cabecera['nro_doc_cliente'].'<br /></th>
                  </tr>
                  <tr><td></td></tr>
                </thead>
              </table>
            </td>
          </tr>
          <tr><td></td></tr>';


          $output.='
          <tr>
            <td colspan="3">
              <table class="border1 border2 border3 border4">
                <thead>
                  <tr>
                     <th width="5%" class="text-center border5" style="background:black;color:white">#</th>
                    <th width="10%" class="border1 text-center border5" style="background:black;color:white">CODIGO</th>
                    <th  class="text-center border5" style="background:black;color:white">DESCRIPCION</th>
                   
                    <th width="8%"  class="text-center border5" style="background:black;color:white">CANT.</th>
                    <th width="8%"  class="text-center border5" style="background:black;color:white">UNID.</th>
                    <th width="8%"  class="text-center" style="background:black;color:white">PESO</th>
                  </tr>
                </thead>
                <tbody>';
                  foreach ($resultado_detalle as $detalle) {
                  
                  $output.='
                     <tr>
                       <th class="text-right">'.$detalle->item.'</th>
                       <th class="text-left border3">'.$detalle->codigoproducto.'</th>
                       <th class="text-left border3">'.$detalle->nombreproducto.'</th>
                      
                       <th class="text-right border3">'.$detalle->cantidad.'</th>
                       <th  class="text-center border3">'.$detalle->unidadmedida.'</th>
                       <th  class="text-right border3">'.number_format($detalle->peso,2).'</th>
                     </tr>';
                  } 

          $output.='
                </tbody>
              </table>
            </td>
          </tr>
          <tr><td></td></tr>
          <tr>
            <td colspan="3">
              <table class="border1 border2 border3 border4">
                <thead>
                  <tr>
                    <th width="50%" class="border5"  style="background:black;color:white">TRANSPORTISTA</th>
                    <th  style="background:black;color:white">DATOS DEL VEHICULO</th>
                  </tr>
                  <tr>
                    <th width="50%" class="border4 text-left"><br />'.
                    '<table border="0">
                    <tr>
                    <td width="20%">Empresa</td>
                    <td class"text-left">'.$row_cabecera['razon_social_transporte'].'</td>
                    </tr>
                    <tr>
                    <td>RUC</td>
                    <td class"text-left">'.$row_cabecera['nro_documento_transporte'].'</td>
                    </tr>
                    <tr>
                    <td>Conductor</td>
                    <td class"text-left">'.$row_cabecera['nombre_chofer'].'</td>
                    </tr>
                    <tr>
                    <td>DNI</td>
                    <td class"text-left">'.$row_cabecera['nro_doc_chofer'].'</td>
                    </tr>
                    <tr>
                    <td>licencia</td>
                    <td class"text-left">'.$row_cabecera['licencia'].'</td>
                    </tr>
                    </table>
                    </th>
                     <th class="text-left"><br />
                     <table border="0">
                    <tr>
                    <td width="20%">Marca</td>
                    <td class"text-left">'.$row_cabecera['marca_vehiculo'].'</td>
                    </tr>
                    <tr>
                    <td>Placa</td>
                    <td class"text-left">'.$row_cabecera['placa_vehiculo'].'</td>
                    </tr>
                    
                    
                    
                    </table>
                       </th>
                  </tr>
                  
                </thead>
              </table>
            </td>
          </tr>
          <tr><td></td></tr>
          <tr>
            <td colspan="3">
              <table class="border1 border2 border3 border4">
                <thead>
                  <tr>
                    <th width="50%" class="text-left"  style="background:black;color:white">MOTIVO DE TRANSPORTE</th>
                    
                  </tr>
                  <tr>
                    <th width="50%" class="text-left">'.$row_cabecera['motivo_traslado'].'</th>
                    
                  </tr>
                  
                </thead>
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
                          <td>
                            Representación Impresa de la '.$doc .'  '.$row_cabecera['femensajesunat'].'
                          </td>
                        </tr>
                        <tr>
                          <td>Hash: '.$row_cabecera['hash'].' </td>
                        </tr>
                      </thead>
                    </table>
                  </th>
                  <th width="20%">
                    <table width="100%">
                      <tr>
                        <td><img src="'.base_url().'/sunat/'.$row_empresa['ruc'].'/qr/'.$row_empresa['ruc'].'-'.$row_cabecera['tipocomp'].'-'.$row_cabecera['serie'].'-'.$row_cabecera['correlativo'].'.png" alt="" width="150px"></td>
                      </tr>
                    </table>
                  </th>
                  
                </tr>
                
                
              </table>
            </td>
          </tr>
        </tbody>
        
      </table>
    </div>
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



 ?>


