<?php 

$empresa = $_SESSION["id_empresa"];
$vendedor = $_SESSION["id"];

if(!empty($_POST))
{
    $fecha_ini = $_POST['f_ini'];
    $fecha_fin = $_POST['f_fin'];
    $query_venta = "SELECT * FROM vw_tbl_compra_cab WHERE fecha_emision BETWEEN  '$fecha_ini' AND '$fecha_fin' AND idempresa = $empresa";
//echo $query_venta;
$resultado_venta=$connect->prepare($query_venta);
$resultado_venta->execute();
$num_reg_venta=$resultado_venta->rowCount();

}
else
{
    $hoy = date('Y-m-d');
    $fecha_ini = $hoy;
    $fecha_fin = $hoy;
    $query_venta = "SELECT * FROM vw_tbl_compra_cab  WHERE fecha_emision='$hoy' AND idempresa = $empresa";
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
                  <h2 class="h5 page-title">Compras </h2>
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
                      <h2><a href="nueva_compra" type="button" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Nueva Compra</a>

                        <a href="nueva_compra_farmacia" type="button" class="btn btn-secondary"><i class="fas fa-plus-circle"></i> Nueva Compra Farmacia</a>

                      <a href="nueva_nota_credito_compra" type="button" class="btn btn-danger"><i class="fas fa-plus-circle"></i> Nota de Credito</a></h2>
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
                          <th>Cliente</th>
                          <th>Op. Gravada</th>
                          <th>Op. Exonerada</th>
                          <th>Op. Inafecta</th>
                          <th>IGV</th>
                          <th>Total</th>
                          <th>Re-imp.</th>
                          <th>Detraccion</th>
                          <th>PDF</th>
                          <th>Estado</th>
                                
                              </tr>
                      </thead>
                      <tbody>
                        <?php foreach($resultado_venta as $ventas ){ ?>
                          <tr>
                            <td><a href="<?=base_url()?>/editar_compra/<?= $ventas['id'] ?>" class="btn btn-warning rounded-circle"><i class="fas fa-edit"></i></a></td>
                            <td><?= $ventas['id'] ?></td>
                            <td><?= $ventas['fecha_emision'] ?></td>
                            <td><?= $ventas['tipocomp'] ?></td>
                            <td><?= $ventas['serie'].'-'.$ventas['correlativo'] ?></td>
                            <td><?= $ventas['nombre_persona'] ?></td>
                            <td align="right"><?= number_format($ventas['op_gravadas'],2,'.',',') ?></td>
                            <td align="right"><?= number_format($ventas['op_exoneradas'],2,'.',',') ?></td>
                            <td align="right"><?= number_format($ventas['op_inafectas'],2,'.',',') ?></td>
                            <td align="right"><?= number_format($ventas['igv'],2,'.',',') ?></td>
                            <td align="right"><?= number_format($ventas['total'],2,'.',',') ?></td>
                            <td align="center"><a target="_blank" href="<?=base_url()?>/ticket_factura_compra/<?=$ventas['id']?>" class="btn btn-secondary rounded-circle"><i class="fe fe-printer"></i></a></td>  
                            <td align="center"><button onclick="modalDt()" class="btn btn-primary rounded-circle" type="button">DT</button></td>
                            <td><a href="factura_pdf_compra/<?= $ventas['id'] ?>" class="btn btn-danger rounded-circle"><i class="fas fa-file-pdf"></i></a></td>
                            <td><?php $e = $ventas['feestado'];
                            if($e == 1)
                            {
                              $e ='ACEPTADO';
                              $c = 'success';
                            }
                            else
                            {
                              $e ='NO ACEPTADO';
                              $c = 'danger';

                            }?> 
                            <span class="badge badge-pill badge-<?=$c?>"><?=$e?></span></td>
                            
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



<!-- ----------------------------Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form name="frm-dt" id="frm-dt">
        <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Datos Detraccion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="add_dt">
        <input type="hidden" name="id_com" id="id_com" value=""><!--id de compra dt--->
        <input type="hidden" name="id_ven" id="id_ven" value=""><!--id de compra cab--->
       <div class="row">
         <div class="col-12">
          <label for="">Fecha Detraccion</label>
          <input type="date" class="form-control" name="fec_det" id="fec_det" required>
        </div>
        <div class="col-12">
          <label for="">Numero Detraccion</label>
          <input type="text" class="form-control" name="num_det" id="num_det" required>
        </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>

   <?php include 'views/modules/modals/envia_venta.php' ?>
    <?php include 'views/template/pie.php' ?>
    <script defer="" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/imask"></script>
     <!-- <script type="text/javascript" src="Assets/vendors/inputMask/inputmask.js" charset="utf-8"></script>-->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.8/jquery.inputmask.bundle.min.js"></script>


<script src="assets/js/funciones_ventas.js"></script>
<script src="assets/js/funciones_compras.js"></script>
<script src="assets/js/compras.js"></script>

  </body>
</html>