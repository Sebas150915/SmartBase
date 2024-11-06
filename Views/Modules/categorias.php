<?php 
$empresa = $_SESSION["id_empresa"];
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
                  <h2 class="h5 page-title">Categorias </h2>
                </div>
                <div class="col-auto">
                 
                </div>
              </div>
              <hr>
              

              <div class="row my-4">
                      <div class="col-md-12">
                        <div class="card shadow">
                          <div class="card-header">
                             <h2><button type="button" class="btn btn-dark" data-toggle="modal" data-target="#ModalCategoria"><i class="fe fe-plus-circle"></i> Nuevo</button></h2>
                          </div>
                         <div class="card-body">
                          
                          <table id="dataTable-1" class="table table-bordered table-hover table-striped datatables dataTable no-footer">
                          <thead class="bg-dark" style="color: white">
                        <tr>
                          <th width="10%">Acciones</th>
                          <th width="8%">Id</th>
                          <th>Nombre</th>
                          <th width="8%">Cuenta Compra</th>
                          <th width="8%">Cuenta Venta</th>
                          <th width="16%">Estado</th>
                          
                        </tr>
                      </thead>
                        <tbody>
                                           
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
<?php include 'views/modules/modals/categorias.php' ?>
    <?php include 'views/template/pie.php' ?>
      
     
      <script src="assets/js/categoria.js?v=4"></script>
      <script src="assets/js/funciones_categoria.js?v=4"></script>
<script>
  cargarCategoria();
</script>
  </body>
</html>