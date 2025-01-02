<?php 

$empresa = $_SESSION["id_empresa"];
$vendedor = $_SESSION["id"];

if(!empty($_POST))
{
    $fecha_ini = $_POST['f_ini'];
    $fecha_fin = $_POST['f_fin'];
    $query_venta = "SELECT * FROM vw_tbl_ret_cab WHERE fecha_emision BETWEEN  '$fecha_ini' AND '$fecha_fin' AND idempresa = $empresa";
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
    $query_venta = "SELECT * FROM vw_tbl_ret_cab  WHERE fecha_emision='$hoy' AND idempresa = $empresa";
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
                      <h2><a href="nueva_retencion" type="button" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Nueva Retencion</a>
                     </h2>
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
                          <th>Retenido</th>
                          <th>TOTAL</th>
                          <th>Opciones</th>

                          <th>Estado</th>
                                
                              </tr>
                      </thead>
                      <tbody>
                        <?php foreach($resultado_venta as $ventas ){ ?>
                          <tr>
                            <td><a href="#" class="btn btn-danger rounded-circle"><i class="fas fa-trash"></i></a></td>
                            <td><?= $ventas['id'] ?></td>
                            <td><?= $ventas['fecha_emision'] ?></td>
                            <td><?= $ventas['tipocomp'] ?></td>
                            <td><?= $ventas['serie'].'-'.$ventas['correlativo'] ?></td>
                            <td><?= $ventas['nombre_persona'] ?></td>
                            <td align="right"><?= number_format($ventas['PERCIBIDO'],2,'.',',') ?></td>
                            <td align="right"><?= number_format($ventas['TOTAL'],2,'.',',') ?></td>
                            
                            <td align="center">
                           
                                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                <a target="_blank" href="<?=base_url()?>/retencion_pdf1/<?=$ventas['id']?>" class="btn btn-danger"><i class="fe fe-printer"></i></a>
                                <a href="<?=base_url()?>/sunat/<?=$row_empresa['ruc']?>/xml/<?=$row_empresa['ruc'].'-'.$ventas['tipocomp'].'-'.$ventas['serie'].'-'.str_pad($ventas['correlativo'],8,"0",STR_PAD_LEFT).'.ZIP'?>" class="btn btn-success"><i class="far fa-file-excel"></i></a>
                                <?php if($ventas['estado']==1){$x = ''; } else{$x='not-active';}?>
                              <a href="<?=base_url()?>/sunat/<?=$row_empresa['ruc']?>/cdr/<?= 'R-'. $row_empresa['ruc'].'-'.$ventas['tipocomp'].'-'.$ventas['serie'].'-'.str_pad($ventas['correlativo'],8,"0",STR_PAD_LEFT).'.ZIP'?>" class="btn btn-primary  <?=$x?>"  ><i class="far fa-file-code"></i>
                              </a>
                                <button type="button" class="btn btn-secondary" onclick="openModalEdit()">
                                Revertir
                                </button>                        
                                                          
                            
                                </div>
                                
                                </div>
                      







                             </td>  
                            
                            
                            <td><?php $e = $ventas['estado'];
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





   <?php include 'views/modules/modals/envia_venta.php' ?>



    <?php include 'views/template/pie.php' ?>
    <script defer="" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/imask"></script>
     <!-- <script type="text/javascript" src="Assets/vendors/inputMask/inputmask.js" charset="utf-8"></script>-->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.8/jquery.inputmask.bundle.min.js"></script>
<script src="assets/js/funciones_retenciones.js?v=4"></script>
<script src="assets/js/retenciones.js?v=4"></script>

<script src="assets/js/funciones_ventas.js"></script>
<script src="assets/js/funciones_compras.js"></script>


   <!-- Modal BAJA DE RETENCIONES-->
<div class="modal fade" id="bajaModal" tabindex="-1" aria-labelledby="bajaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="frmbajaretencion" id="frmbajaretencion">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="bajaModalLabel">Confirmar Baja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action"  value="revertir_retencion">
                <input type="hidden" name="idretencion" id="idretencion" value="">
                <input type="hidden" name="fecharet" id="fecharet" value="">
                <input type="hidden" name="empresa" id="empresa" value="<?=$empresa?>">
                ¿Está seguro de que desea dar de baja este comprobante de retención?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" id="confirmarBaja">Dar de Baja</button>
            </div>
          </form>
        </div>
    </div>
</div>

  </body>
</html>