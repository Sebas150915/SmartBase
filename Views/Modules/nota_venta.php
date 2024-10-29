<?php 

$empresa = $_SESSION["id_empresa"];
$vendedor = $_SESSION["id"];

if(!empty($_POST))
{
    $fecha_ini = $_POST['f_ini'];
    $fecha_fin = $_POST['f_fin'];
    $query_venta = "SELECT * FROM tbl_venta_cab AS c LEFT JOIN tbl_contribuyente as x
    on c.idcliente = x.id_persona WHERE idempresa = $empresa AND vendedor=$vendedor AND tipocomp ='99' AND fecha_emision BETWEEN '$fecha_ini' AND '$fecha_fin'";
    $resultado_venta=$connect->prepare($query_venta);
    $resultado_venta->execute();
    $num_reg_venta=$resultado_venta->rowCount();

}
else
{
    $hoy = date('Y-m-d');
    $fecha_ini = $hoy;
    $fecha_fin = $hoy;
    $query_venta = "SELECT * FROM tbl_venta_cab AS c LEFT JOIN tbl_contribuyente as x
    on c.idcliente = x.id_persona WHERE idempresa = $empresa AND vendedor=$vendedor AND tipocomp ='99'AND fecha_emision='$hoy'";
    $resultado_venta=$connect->prepare($query_venta);
    $resultado_venta->execute();
    $num_reg_venta=$resultado_venta->rowCount();
}



$query_empresa = "SELECT * FROM tbl_empresas WHERE id_empresa = $empresa";
$resultado_empresa = $connect->prepare($query_empresa);
$resultado_empresa->execute();
$row_empresa = $resultado_empresa->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
  <head>
       <?php include 'views/template/head.php' ?>
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
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row align-items-center mb-2">
                <div class="col">
                  <h2 class="h5 page-title">Nota de Venta / Recibo </h2>
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
                      <h2><a href="nueva_nota_venta" type="button" class="btn btn-danger"><i class="fa fa-plus-circle"></i> Nota de Venta/ Recibo </a></h2>

                    <hr style="border-top: 1px solid #d5dde6;">
                    <form method="POST" class="form-inline"><label for=""> Fecha Inicial :</label><input type="date" class="form-control mr-3" name="f_ini" id="f_ini" value="<?=$fecha_ini?>"><label for=""> Fecha Final :</label><input type="date" class="form-control mr-3" name="f_fin" value="<?=$fecha_fin?>" id="f_fin"> <button class="btn btn-success" type="submit">Procesar</button></form>
                          </div>
                         <div class="card-body">
                          
                         <table id="datatable-ventas" class="table table-striped table-bordered  nowrap" cellspacing="0" width="100%">
                          <thead class="bg-dark" style="color: white">
                              <tr>
                                <th>Acciones</th>
                                <th>Id</th>
                                <th>Fecha</th>
                                <th>T. Cpe</th>
                                <th>N. Cpe</th>
                                <th>RUC/DNI</th>
                                <th>Cliente</th>
                                <th>Op. Gravada</th>
                                <th>Op. Exonerada</th>
                                <th>Op. Inafecta</th>
                                <th>IGV</th>
                                <th>Total</th>
                                <th>R-imp</th>
                                 <th>R-enviar</th>
                                <th>XML</th>
                                <th>CDR</th>
                                <th>PDF</th>
                                <th>Codigo</th>
                                <th>Estado</th>
                                
                              </tr>
                      </thead>
                      <tbody>
                        <?php foreach($resultado_venta as $ventas ){ ?>
                          <tr>
                            <td><a class="btn btn-warning rounded-circle" href="<?=base_url()?>/editar_nota_venta/<?= $ventas['id'] ?>"><i class="fe fe-edit"></i></a></td>
                            <td><?= $ventas['id'] ?></td>
                            <td><?= $ventas['fecha_emision'] ?></td>
                            <td><?= $ventas['tipocomp'] ?></td>
                            <td><?= $ventas['serie'].'-'.$ventas['correlativo'] ?></td>
                            <td><?= $ventas['codcliente'] ?></td>
                            <td><?= $ventas['nombre_persona'] ?></td>
                            <td align="right"><?= $ventas['op_gravadas'] ?></td>
                            <td align="right"><?= $ventas['op_exoneradas'] ?></td>
                            <td align="right"><?= $ventas['op_inafectas'] ?></td>
                            <td align="right"><?= $ventas['igv'] ?></td>
                            <td align="right"><?= $ventas['total'] ?></td>
                            <td align="center"><a target="_blank" href="<?=base_url()?>/ticket_factura/<?=$ventas['id']?>" class="btn btn-secondary rounded-circle"><i class="fe fe-printer"></i></a></td>
                            <td align="center"><a href="#" class="btn btn-danger rounded-circle" onclick="openModalEnvia()"><i class="fe fe-send"></i></a></td>

                            <td><a href="<?=base_url()?>/sunat/xml/<?=$row_empresa['ruc'].'-'.$ventas['tipocomp'].'-'.$ventas['serie'].'-'.$ventas['correlativo'].'.ZIP'?>" class="btn btn-primary rounded-circle"><i class="fe fe-book"></i></a></td>

                            <td><?php if($ventas['feestado']==1){$x = ''; } else{$x='not-active';}?>
                              <a href="<?=base_url()?>/sunat/cdr/<?= 'R-'. $row_empresa['ruc'].'-'.$ventas['tipocomp'].'-'.$ventas['serie'].'-'.$ventas['correlativo'].'.ZIP'?>" class="btn btn-secondary rounded-circle <?=$x?>"  ><i class="fe fe-book"></i></a>
                            </td>

                            <td><a href="factura_pdf/<?= $ventas['id'] ?>" class="btn btn-danger rounded-circle"><i class="fe fe-book"></i></a></td>
                            <td><?=$ventas['fecodigoerror']?></td>
                            <td><?php $e = $ventas['feestado'];
                            if($e == 1)
                            {
                              $e ='fe fe-check';
                              $c = 'success';
                            }
                            else
                            {
                              $e ='fe fe-x-circle';
                              $c = 'danger';

                            }?> 
                            <button class="btn btn-<?=$c?> rounded-circle"><i class="<?=$e?>"></i></button></td>
                            
                          </tr>
                        <?php } ?>                     
                      </tbody>
                        </table>
                         </div>
                        </div>
                      </div>
                      
                    </div> 

              
              
              
             
            </div> <!-- /.col -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
       
        
      </main> <!-- main -->
    </div> <!-- .wrapper -->
   <?php include 'views/modules/modals/envia_venta.php' ?>
    <?php include 'views/template/pie.php' ?>
    <script defer="" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/imask"></script>
     <!-- <script type="text/javascript" src="Assets/vendors/inputMask/inputmask.js" charset="utf-8"></script>-->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.8/jquery.inputmask.bundle.min.js"></script>


<script src="assets/js/funciones_ventas.js"></script>

  </body>
</html>