
<?php 
session_start();
$empresa   = $_SESSION['id_empresa'];
$fecha_ini = $rutas[1];
$fecha_fin = $rutas[2];

$query_doc = $connect->query("SELECT 
td,
nomdoc,
sum(valor_exportacion) as valor_exportacion, 
sum(op_gravadas) as op_gravadas, 
sum(op_exoneradas) as op_exoneradas, 
sum(op_inafectas) as op_inafectas,
sum(isc) as isc,
sum(igv) as igv,
sum(icbper) as icbper,
sum(oth) as oth,
sum(total) as total FROM vw_tbl_ventas_sunat WHERE fecha_emision BETWEEN '$fecha_ini' and '$fecha_fin' AND  td<>'99' GROUP BY td");
//$query_doc->execute();
$resultado_doc = $query_doc->fetchAll(PDO::FETCH_OBJ);

$query_totales = ("SELECT 
sum(if(td='07',-valor_exportacion,valor_exportacion)) as valor_exportacion,
sum(if(td='07',-op_gravadas,op_gravadas)) as op_gravadas,
sum(if(td='07',-op_exoneradas,op_exoneradas)) as op_exoneradas,
sum(if(td='07',-op_inafectas,op_inafectas)) as op_inafectas,
sum(if(td='07',-isc,isc)) as isc,
sum(if(td='07',-igv,igv)) as igv,
sum(if(td='07',-icbper,icbper)) as icbper,
sum(if(td='07',-oth,oth)) as oth,
sum(if(td='07',-total,total)) as total
FROM vw_tbl_ventas_sunat WHERE  fecha_emision BETWEEN '$fecha_ini' and '$fecha_fin' AND td<>'99'");
//$resultado_totales = $query_totales->fetch(PDO::FETCH_OBJ);

$resultado_totales=$connect->prepare($query_totales);
$resultado_totales->execute();
$row_totales = $resultado_totales->fetch(PDO::FETCH_ASSOC);
//print_r($row_totales);exit();



require_once 'assets/dompdf/lib/html5lib/Parser.php';
require_once 'assets/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'assets/dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'assets/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

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

        table {
           border-collapse: collapse;
         }

         .table-bordered {
  border: 1px solid #fff;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
  page-break-before:auto;
}

.table-bordered thead th,
.table-bordered thead td {
  border-bottom-width: 2px;
}

        .border
        {
          border: 1px solid #000;
          border-spacing: 0;
          
           padding: 0.3em;
        }

        .border1, th
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

        hr {
  box-sizing: content-box;
  height: 0;
  overflow: visible;
}

.bg-dark {
  background-color: #343a40 !important;
}   

  </style>
</head>
<body>

    <div class="container-fluid">
      
     
    
            <h2 class="text-center">REGISTRO DE VENTAS DEL '.$fecha_ini.' AL '.$fecha_fin.'</h2>
            <hr>
             <table class="table table-bordered">
                      <thead>
                              <tr>                          
                                <th>F. Emision</th>
                                <th>F. Vencimiento</th>
                                <th>TD</th>
                                <th>Serie</th>
                                <th>Numero</th>
                                <th>Doc</th>
                                <th>N. DOC</th>
                                <th>Valor Export</th>
                                <th>Op. Gravada</th>
                                <th>Op. Exonerada</th>
                                <th>Op. Inafecta</th>
                                <th>ISC</th>
                                <th>IGV</th>
                                <th>ICBPER</th>
                                <th>Otros</th>
                                <th>Total</th>
                                <th>T. ref</th>
                                <th>Ser. ref</th>
                                <th>Num. ref</th>
                              </tr>
                      </thead>
                      <tbody>';
                        foreach ($resultado_doc as $doc) 
                        {
                           $tpdoc = $doc->td;
                           $ndoc = $doc->nomdoc;

                           if($doc->td=='07')
                              {
                                 $tvalor_exportacion = -($doc->valor_exportacion);
                                 $top_gravadas       = -($doc->op_gravadas);
                                 $top_exoneradas     = -($doc->op_exoneradas);
                                 $top_inafectas      = -($doc->op_inafectas);
                                 $tisc               = -($doc->isc);
                                 $tigv               = -($doc->igv);
                                 $ticbper            = -($doc->icbper);
                                 $toth               = -($doc->oth);
                                 $ttotal             = -($doc->total);
                              }
                              else
                              {
                                 $tvalor_exportacion = ($doc->valor_exportacion);
                                 $top_gravadas       = ($doc->op_gravadas);
                                 $top_exoneradas     = ($doc->op_exoneradas);
                                 $top_inafectas      = ($doc->op_inafectas);
                                 $tisc               = ($doc->isc);
                                 $tigv               = ($doc->igv);
                                 $ticbper            = ($doc->icbper);
                                 $toth               = ($doc->oth);
                                 $ttotal             = ($doc->total);
                              }
                           $query_tdoc = $connect->query("SELECT * FROM vw_tbl_ventas_sunat WHERE fecha_emision BETWEEN '$fecha_ini' and '$fecha_fin' AND  td='$tpdoc' ");
                           //$query_doc->execute();
                           $resultado_tdoc = $query_tdoc->fetchAll(PDO::FETCH_OBJ); 

                           foreach($resultado_tdoc as $tdoc)
                           {
                              if($tdoc->td=='07')
                              {
                                 $valor_exportacion = -($tdoc->valor_exportacion);
                                 $op_gravadas       = -($tdoc->op_gravadas);
                                 $op_exoneradas     = -($tdoc->op_exoneradas);
                                 $op_inafectas      = -($tdoc->op_inafectas);
                                 $isc               = -($tdoc->isc);
                                 $igv               = -($tdoc->igv);
                                 $icbper            = -($tdoc->icbper);
                                 $oth               = -($tdoc->oth);
                                 $total             = -($tdoc->total);
                              }
                              else
                              {
                                 $valor_exportacion = ($tdoc->valor_exportacion);
                                 $op_gravadas       = ($tdoc->op_gravadas);
                                 $op_exoneradas     = ($tdoc->op_exoneradas);
                                 $op_inafectas      = ($tdoc->op_inafectas);
                                 $isc               = ($tdoc->isc);
                                 $igv               = ($tdoc->igv);
                                 $icbper            = ($tdoc->icbper);
                                 $oth               = ($tdoc->oth);
                                 $total             = ($tdoc->total);
                              }
                        
                                   $output.='
                                   <tr>
                                   <th class="border">'.$tdoc->fecha_emision.'</th>
                                   <th class="border">'.$tdoc->fecha_vencimiento.'</th>
                                   <th class="border">'.$tdoc->td.'</th>
                                   <th class="border">'.$tdoc->serie.'</th>
                                   <th class="border">'.$tdoc->numero.'</th>
                                   <th class="border">'.$tdoc->doc.'</th>
                                   <th  class="border">'.$tdoc->num_cli.'</th>
                                   <th class="border text-right">'.number_format($valor_exportacion,2).'</th>
                                   <th class="border text-right">'.number_format($op_gravadas,2).'</th>
                                   <th class="border text-right">'.number_format($op_exoneradas,2).'</th>
                                   <th class="border text-right">'.number_format($op_inafectas,2).'</th>
                                   <th class="border text-right">'.number_format($isc,2).'</th>
                                   <th class="border text-right">'.number_format($igv,2).'</th>
                                   <th class="border text-right">'.number_format($icbper,2).'</th>
                                   <th class="border text-right">'.number_format($oth,2).'</th>
                                   <th class="border text-right">'.number_format($total,2).'</th>
                                   <th class="border">'.$tdoc->tipo_ref.'</th>
                                   <th class="border">'.$tdoc->serie_ref.'</th>
                                   <th  class="border">'.$tdoc->correlativo_ref.'</th>
                                 </tr>';
                           } 

                           $output .='
                           <tr class="bg-dark" style="color:white">
                              <td colspan="7">TOTAL '.$ndoc.'</td>
                                   <th class="border text-right">'.number_format($tvalor_exportacion,2).'</th>
                                   <th class="border text-right">'.number_format($top_gravadas,2).'</th>
                                   <th class="border text-right">'.number_format($top_exoneradas,2).'</th>
                                   <th class="border text-right">'.number_format($top_inafectas,2).'</th>
                                   <th class="border text-right">'.number_format($tisc,2).'</th>
                                   <th class="border text-right">'.number_format($tigv,2).'</th>
                                   <th class="border text-right">'.number_format($ticbper,2).'</th>
                                   <th class="border text-right">'.number_format($toth,2).'</th>
                                   <th class="border text-right">'.number_format($ttotal,2).'</th>
                              <td colspan="3"></td>
                           </tr>
                           ';
                        }

                        $output.='
                      </tbody>
                      <tfoot>
                        <tr>
                              <td colspan="7">TOTAL GENERAL</td>
                                   <th class="border text-right">'.number_format($row_totales['valor_exportacion'],2).'</th>
                                   <th class="border text-right">'.number_format($row_totales['op_gravadas'],2).'</th>
                                   <th class="border text-right">'.number_format($row_totales['op_exoneradas'],2).'</th>
                                   <th class="border text-right">'.number_format($row_totales['op_inafectas'],2).'</th>
                                   <th class="border text-right">'.number_format($row_totales['isc'],2).'</th>
                                   <th class="border text-right">'.number_format($row_totales['igv'],2).'</th>
                                   <th class="border text-right">'.number_format($row_totales['icbper'],2).'</th>
                                   <th class="border text-right">'.number_format($row_totales['oth'],2).'</th>
                                   <th class="border text-right">'.number_format($row_totales['total'],2).'</th>
                              <td colspan="3"></td>
                           </tr>

                      </tfoot>
              </table>        
   
    </div>
  </body>

</html>';

//echo $output; exit();
$invoiceFileName = 'VENTAS DEL '.$fecha_ini.' AL '.$fecha_fin.'.pdf';
$dompdf = new DOMPDF();
$dompdf->set_paper('A4','landscape');//$dompdf->set_paper("A4", "landscape"/portrait); //esta es otra forma de ponerlo horizontal
$dompdf->load_html($output);
$dompdf->render();
$pdf = $dompdf->output();
$dompdf->stream($invoiceFileName, array("Attachment" => true));
file_put_contents($rutaGuardado.$invoiceFileName, $pdf);
