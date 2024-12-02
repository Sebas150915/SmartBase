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
          font-family: Aptos;
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

      footer {
position: fixed;
bottom: 100px;
left: 0px;
right: 0px;
height: 50px;

/** Extra personal styles **/

text-align: center;
line-height: 20px;
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
            <th width="25%">
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
              <table">
                <thead>
                <tr>
                  <th align="left" class="text-bold" colspan="2">DATOS DE INICIO DEL TRASLADO</th>
                </tr>
                <tr>
                    
                    <th align="left">Fecha de Emision :</th>
                    <th align="left">'.$row_cabecera['fecha_emision'].'</th>
                    <th align="left">Motivo de traslado :</th>
                    <th align="left">'.$row_cabecera['motivo_traslado'].'</th>
                    <th align="left">Peso :</th>
                    <th align="left">'.$row_cabecera['peso'].'</th>
                </tr>
                <tr>
                     <th align="left">Fecha de inicio de traslado:</th>
                     <th align="left">'.$row_cabecera['fecha_traslado'].'</th>
                     <th align="left">Modalidad de Transporte:</th>
                     <th align="left">'.$row_cabecera['descripcion'].'</th>
                     <th align="left">DAM:</th>
                     <th align="left">'.$row_cabecera['dam'].'</th>
                </tr>
                <tr>
                   <th align="left">Documento Relacionado:</th>
                   <th align="left">'.$row_cabecera[''].'</th>
                   <th align="left">Contenedor:</th>
                   <th align="left">'.$row_cabecera['cont'].'</th>
                   <th align="left">Precinto:</th>
                   <th align="left">'.$row_cabecera['prec'].'</th>
                </tr>
                  
                </thead>
              </table>
              <hr>
            </td>
          </tr>
          <tr><td></td></tr>
          <tr>
            <td colspan="3">
              <table>
                <thead>
                  <tr>
                    <th  align="left" class="text-bold" colspan="2">DATOS DEL DESTINATARIO</th>
                  
                  </tr>
                  <tr>
                    <th align="left">Documento de Identidad</th>
                     <th class=" text-left">'.$row_cabecera['nro_doc_cliente'].'</th>
                  
                    <th align="left">Razon Social</th>
                     <th class=" text-left">'.$row_cabecera['razon_social_cliente'].'</th>
                  </tr>
                  
                </thead>
              </table>
              <hr>
            </td>
          </tr>
          <tr><td></td></tr>
          <tr>
            <td colspan="3">
              <table>
                <thead>
                <tr>
                   <th  align="left" class="text-bold" colspan="2">DATOS DEL PUNTO DE PARTIDA Y LLEGADA</th>
                </tr>
                <tr>
                <th >Dirección del punto de partida:</th>
                <th align="left">'.$row_cabecera['direccion_partida'].'</th>
                </tr>
                <tr>
                <th >Dirección del punto de llegada:</th>
                <th align="left">'.$row_cabecera['direccion_destino'].'</th>
                </tr>
                  <tr><td></td></tr>
                </thead>
              </table>
              <hr>
            </td>
          </tr>
          <tr><td></td></tr>
          <tr>
            <td colspan="3">
              <table>
                <thead>
                <tr>
                   <th  align="left" class="text-bold" colspan="2">DATOS DEL VEHICULO Y TRANSPORTISTA</th>
                </tr>
                <tr>
                <th >Conductor:</th>
                <th align="left">'.$row_cabecera['nombre_chofer'].'</th>
                 <th>DNI:</th>
                <th align="left">'.$row_cabecera['nro_doc_chofer'].'</th>
                 <th>Licencia:</th>
                <th align="left">'.$row_cabecera['licencia'].'</th>
                </tr>
                <tr>
                <th>Marca:</th>
                <th align="left">'.$row_cabecera['marca_vehiculo'].'</th>
                <th >Placa:</th>
                <th align="left">'.$row_cabecera['placa_vehiculo'].'</th>
                <th>Carreta:</th>
                <th align="left">'.$row_cabecera['carreta'].'</th>
                </tr>
                  <tr><td></td></tr>
                </thead>
              </table>
              <hr>
            </td>
          </tr>
          <tr><td></td></tr>';


          $output.='
          <tr>
            <td colspan="3">
              <table >
                <thead>
                  <tr>
                     <th width="5%" class="border1 text-center border5" >#</th>
                    <th width="10%" class="border1 text-center" >CODIGO</th>
                    <th  class="text-center border1">DESCRIPCION</th>
                   
                    <th width="8%"  class="text-center border1">CANT.</th>
                    <th width="8%"  class="text-center border1">UNID.</th>
                    <th width="8%"  class="text-center border1">PESO</th>
                  </tr>
                </thead>
                <tbody>';
                  foreach ($resultado_detalle as $detalle) {
                  
                  $output.='
                     <tr>
                       <th class="text-center">'.$detalle->item.'</th>
                       <th class="text-center">'.$detalle->codigoproducto.'</th>
                       <th class="text-center">'.$detalle->nombreproducto.'</th>
                      
                       <th class="text-center">'.$detalle->cantidad.'</th>
                       <th  class="text-center">'.$detalle->unidadmedida.'</th>
                       <th  class="text-center">'.number_format($detalle->peso,2).'</th>
                     </tr>';
                  } 

          $output.='
                </tbody>
              </table>
            </td>
          </tr>
       
        
          </tr>

                <tr>
                <th >OBSERVACIÓN:</th>
                <th >'.$row_cabecera['obs'].'</th>
                </tr>
                
        </tbody>


      </table>
    </div>
    <footer>
    <hr>
    <table width="100%">
                <tr>
                  <th width="90%">
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
                  <th>
                    <table width="100%">
                      <tr>
                        <td class="border3 border"><img src="'.base_url().'/sunat/'.$row_empresa['ruc'].'/qr/'.$row_empresa['ruc'].'-'.$row_cabecera['tipocomp'].'-'.$row_cabecera['serie'].'-'.$row_cabecera['correlativo'].'.png" alt="" width="150px"></td>
                      </tr>
                    </table>
                  </th>
                  
                </tr>
                
              </table>
    
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


