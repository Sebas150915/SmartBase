<?php 

$empresa = $_SESSION["id_empresa"];

$query_data = "SELECT 
s.id as id_serie,
s.id_td as id_td,
d.nombre as nombre,
s.serie as serie,
s.correlativo as correlativo,  
s.estado as estado 
FROM tbl_series as s
LEFT JOIN tbl_tipo_documento as d
ON  s.id_doc = d.cod   WHERE id_empresa='$empresa'";
$resultado_data=$connect->prepare($query_data);
$resultado_data->execute();
$num_reg_data=$resultado_data->rowCount();

$query_tipo_doc = "SELECT * FROM tbl_tipo_documento WHERE fe='1' ";
$resultado_tipo_doc=$connect->prepare($query_tipo_doc);
$resultado_tipo_doc->execute(); 

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
                  <h2 class="h5 page-title">Series </h2>
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
                             <h2><button type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fe fe-plus-circle"></i> Nuevo</button></h2>
                          </div>
                         <div class="card-body">
                          
                          <table id="dataTable-1" class="table table-bordered table-hover table-striped datatables dataTable no-footer">
                          <thead class="bg-dark" style="color: white">
                        <tr>
                          <th width="10%">Acciones</th>
                          <th>Id</th>
                          <th>T. Doc.</th>
                          <th>Descripcion</th>
                          <th>Serie</th>
                          <th>Correlativo</th>
                          <th>Estado</th>
                       
                        </tr>
                      </thead>
                        <tbody>
                        <?php foreach($resultado_data as $serie ){ ?>
                          <tr>
                            <td>
                              <button class="btn btn-warning rounded-circle" onclick="ediserie()"><i class="fe fe-edit"></i></button>
                              <button class="btn btn-danger rounded-circle" onclick="delserie()"><i class="fe fe-trash-2"></i></button></td>
                            <td><?= $serie['id_serie'] ?></td>
                            <td><?= $serie['id_td'] ?></td>
                            <td><?= $serie['nombre'] ?></td>
                            <td><?= $serie['serie'] ?></td>
                            <td><?= $serie['correlativo'] ?></td>
                            <td><?php $e = $serie['estado'];
                               
                               if($e=='1')
                               {
                                 $e = 'Activo';
                                 $c = 'success';
                               }
                               else
                               {
                                $e = 'Inactivo';
                                $c = 'danger';
                               }
                               
                             ?><button class="btn btn-<?=$c?>"><?=$e?></button></td>

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
  <?php include 'views/modules/modals/serie_nuevo.php' ?>
      <?php include 'views/modules/modals/serie_editar.php' ?>
      <?php include 'views/modules/modals/serie_delete.php' ?>
    <?php include 'views/template/pie.php' ?>
    <script defer="" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/imask"></script>
     <!-- <script type="text/javascript" src="Assets/vendors/inputMask/inputmask.js" charset="utf-8"></script>-->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.8/jquery.inputmask.bundle.min.js"></script>


  




<script src="assets/js/marcas.js"></script>
      <script src="assets/js/funciones_marca.js"></script>

  </body>
</html>