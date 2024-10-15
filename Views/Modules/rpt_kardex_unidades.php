
<?php 
//session_start();
$empresa = $_SESSION["id_empresa"];
$usuariov = $_SESSION["id"];
$boton  = 'disabled';


$query_alm = "SELECT * FROM tbl_almacen WHERE empresa = $empresa";
$resultado_alm=$connect->prepare($query_alm);
$resultado_alm->execute(); 
$num_reg_alm=$resultado_alm->rowCount();


if(!empty($_POST))
{
$fecha_ini = $_POST['fecha_ini'];
$fecha_fin = $_POST['fecha_fin'];
$almacen   = $_POST['almacen'];
$boton  = '';

if($almacen == '%')
{
   $query_pro = "SELECT codigo_producto as codpro, nombre_producto as nompro, empresa
from vw_tbl_alm WHERE empresa = $empresa GROUP BY codigo_producto,nombre_producto,empresa";

//echo $query_pro; exit();
$resultado_pro = $connect->prepare($query_pro); 
$resultado_pro->execute(); 
}
else{
 $query_pro = "SELECT codigo_producto as codpro, nombre_producto as nompro, empresa, local
from vw_tbl_alm WHERE empresa = $empresa AND local = $almacen GROUP BY codigo_producto,nombre_producto,empresa,local";
$resultado_pro = $connect->prepare($query_pro); 
$resultado_pro->execute();   
}




}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <?php include 'views/template/head.php' ?>
        <style>
          .not-active { 
            pointer-events: none; 
            cursor: default; 
        } 
        </style>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
  </head>

 <body class="horizontal dark  ">
    <div class="wrapper">
      <?php
       if($_SESSION['perfil']=='1')
       {
       include 'views/template/nav.php';
       }
       else
       {
       include 'views/template/nav_ventas.php';
       } ?>
      <main role="main" class="main-content">      
        

        <!-- page content -->
        <div class="container-fluid">
          <div class="row justify-content-center">
            

            

            <div class="col-12">
               <div class="row align-items-center mb-2">
                <div class="col">
                  <h2 class="h5 page-title">Reporte de salidas de almacen </h2>
                </div>
                <div class="col-auto">
                  <form class="form-inline">
                    <div class="form-group d-none d-lg-inline">
                      <label for="reportrange" class="sr-only">Date Ranges</label>
                      <div id="reportrange" class="px-2 py-2 text-muted">
                        <span class="small"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="button" class="btn btn-sm"><span class="fe fe-refresh-ccw fe-16 text-muted"></span></button>
                      <button type="button" class="btn btn-sm mr-2"><span class="fe fe-filter fe-16 text-muted"></span></button>
                    </div>
                  </form>
                </div>
              </div>
              <hr>
              <div class="row my-4">
                      <div class="col-md-12">
                        <div class="card shadow">
                          <div class="card-header">

                          <form class="form-inline" method="POST">
                                <div class="form-group mr-3 ml-3">
                                  <label for="ex3" class="col-form-label"> Fecha Inicio: </label>
                                  <input type="date" id="fecha_ini" name="fecha_ini" class="form-control" value="<?=$fecha_ini?>">
                                </div>
                                <div class="form-group mr-3 ml-3">
                                  <label for="ex4" class="col-form-label"> Fecha Fin: </label>
                                  <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?=$fecha_fin?>">
                                </div>
                                 <div class="form-group mr-3 ml-3">
                                <label for="">Almacen:</label>
                                <select class="form-control select2" name="almacen" id="almacen">
                                
                                <option value="%">--TODOS--</option>
                                
                                <?php 
                                while($row_alm = $resultado_alm->fetch(PDO::FETCH_ASSOC) )
                                {?>
                                <option value="<?= $row_alm['id'] ?>"><?=$row_alm['nombre']?></option>;
                                <?php  } ?>
                                </select>
                                
                                </div>
                                
                                
                                <div class="form-group">
                                  <button type="submit" class="btn btn-success mx-1">Procesar</button>
                                  
                                </div>
                          </form>
                        </div>

                  <div class="card-body">

                    <table id="examplex1" class="table table-striped table-bordered  nowrap" cellspacing="0" width="100%">
                      <thead class="bg-dark" style="color: white">
                          
                          
                          <tr>
                             <th rowspan="2">Producto</th>
                            <th colspan="4">DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO INTERNO O SIMILAR</th>
                            <th rowspan="2">Tipo Operacion</th>
                            <th rowspan="2">Entradas</th>
                            <th rowspan="2">Salidas</th>
                            <th rowspan="2">Saldo Final</th>
                            
                          </tr>
                          <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Serie</th>
                            <th>Número</th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php foreach($resultado_pro as $pro)
                        {
                           $prod = $pro['nompro']; 
                           $codp = $pro['codpro']; 

                         if($almacen == '%')
                         {
                          $query_data = "SELECT * FROM vw_tbl_alm WHERE fecha BETWEEN '$fecha_ini' AND '$fecha_fin' AND empresa = $empresa AND codigo_producto = $codp  ORDER BY nombre_producto,fecha,tipo_movimiento";
                         }
                         else
                         {
                             $query_data = "SELECT * FROM vw_tbl_alm WHERE fecha BETWEEN '$fecha_ini' AND '$fecha_fin' AND empresa = $empresa AND local = $almacen AND codigo_producto = $codp  ORDER BY nombre_producto,fecha,tipo_movimiento";
                         }
                         
                         //echo $query_data;exit(); 
                          $resultado_data=$connect->prepare($query_data);
                          $resultado_data->execute();
                          $num_reg_data=$resultado_data->rowCount();

                          $saldo_pro = 0;
                              foreach($resultado_data as $data) 
                              {
                                if($data['tipo_movimiento']=='1')
                                {
                                $tipo_mov = '01';
                                $saldo_pro = $saldo_pro + $data['cantidad_entrada'];
                                }
                                else
                                {
                                $tipo_mov = '02';
                                $saldo_pro =  $saldo_pro-$data['cantidad_salida'];
                                } 
                                

                                ?>
                              <tr>
                                <td><?=$data['nombre_producto'].'-'.$data['codigo_producto']?></td>
                                <td><?=$data['fecha']?></td>
                                <td><?=$data['tipo_doc']?></td>
                                <td><?=$data['serie_doc']?></td>
                                <td><?=$data['num_doc']?></td>
                                <td><?=$tipo_mov?></td>
                                <td><?=$data['cantidad_entrada']?></td>
                                <td><?=$data['cantidad_salida']?></td>
                                <td><?=$saldo_pro?></td>
                              </tr>
                        <?php } } ?>
                        
                      </tbody>
                      
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
      
      </div>
    </div>
     
      <?php include 'views/template/pie.php' ?>


  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
      <script>
    $(document).ready(function() {
    $('#examplex1').DataTable( {
        //responsive: true,
         "scrollX": true,
         paging: false,

        order: [[0, 'asc'],[1, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5',footer: true, title: 'Registro de ventas'},
            { extend: 'csvHtml5',  footer: true },
            { extend: 'pdfHtml5',  footer: true }
                ],
        rowGroup: {
            dataSrc: 0
        }
    } );
} );
  </script>  

      
  </body>
</html>
