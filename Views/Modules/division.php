<?php 
$empresa = $_SESSION["id_empresa"];

$query_data = "SELECT * FROM tbl_division WHERE empresa='$empresa'";
$resultado_data=$connect->prepare($query_data);
$resultado_data->execute();
$num_reg_data=$resultado_data->rowCount();

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
                  <h2 class="h5 page-title">División</h2>
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
                             <h2><button type="button" class="btn btn-dark" data-toggle="modal" data-target="#ModalDivision"><i class="fe fe-plus-circle"></i>Nuevo<i class="fe fe-users"></i></button></h2>
                          </div>
                         <div class="card-body">
                          
                          <table id="dataTable-1" class="table table-bordered table-hover table-striped datatables dataTable no-footer">
                          <thead class="bg-dark" style="color: white">
                        <tr>
                          <th width="10%">Acciones</th>
                          <th width="8%">Id</th>
                          <th>Nombre</th>
                          <th>Estado</th>
                          
                        </tr>
                      </thead>
                        <tbody>
                        <?php foreach($resultado_data as $usuario ){ ?>
                          <tr>
                            <td>
                              <button class="btn btn-warning rounded-circle" onclick="openModalEdit()"><i class="fe fe-edit"></i></button>
                              <button class="btn btn-danger rounded-circle" onclick="openModalDel()"><i class="fe fe-trash-2"></i></button></td>
                            <td><?= $usuario['id'] ?></td>
                            <td><?= $usuario['nombre'] ?></td>
                           
                            <td><?php $e = $usuario['estado'];
                            if($e == '1')
                            {
                              $e ='Activo';
                              $c = 'success';
                            }
                            else
                            {
                              $e ='Inactivo';
                              $c = 'danger';

                            }?> 
                            <button class="btn btn-<?=$c?> btn-xs"><?=$e?></button></td>
                            
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
<?php include 'views/modules/modals/division.php' ?>
    <?php include 'views/template/pie.php' ?>
      
      <script src="../assets/js/division.js?v=1"></script>
      <script src="../assets/js/funciones_division.js?v=1"></script>

  </body>
</html>