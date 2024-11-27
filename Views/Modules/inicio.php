<?php 
       $hoy = date('Y-m-d');
        
        $mes = date('m');
       

        $sql_almacenes="SELECT * FROM tbl_almacen WHERE empresa = $_SESSION[id_empresa]";
        $resultado_almacenes=$connect->prepare($sql_almacenes);
        $resultado_almacenes->execute();
        $num_reg_almacenes=$resultado_almacenes->rowCount();

        $sql_productos="SELECT * FROM tbl_productos WHERE empresa = $_SESSION[id_empresa]";
        $resultado_productos=$connect->prepare($sql_productos);
        $resultado_productos->execute();
        $num_reg_productos=$resultado_productos->rowCount();


        $sql_contribuyente="SELECT * FROM tbl_contribuyente WHERE empresa = $_SESSION[id_empresa]";
        $resultado_contribuyente=$connect->prepare($sql_contribuyente);
        $resultado_contribuyente->execute();
        $num_reg_contribuyente=$resultado_contribuyente->rowCount();

        
        $sql_facturas="SELECT * FROM tbl_venta_cab where tipocomp ='01' AND fecha_emision='$hoy' AND idempresa = $_SESSION[id_empresa]";
        $resultado_facturas=$connect->prepare($sql_facturas);
        $resultado_facturas->execute();
        $num_reg_facturas=$resultado_facturas->rowCount();

        $sql_boletas="SELECT * FROM tbl_venta_cab where tipocomp ='03' AND fecha_emision='$hoy' AND idempresa = $_SESSION[id_empresa]";
        $resultado_boletas=$connect->prepare($sql_boletas);
        $resultado_boletas->execute();
        $num_reg_boletas=$resultado_boletas->rowCount();

        $sql_nc="SELECT * FROM tbl_venta_cab where tipocomp ='99' AND fecha_emision='$hoy' AND idempresa = $_SESSION[id_empresa]";
        $resultado_nc=$connect->prepare($sql_nc);
        $resultado_nc->execute();
        $num_reg_nc=$resultado_nc->rowCount();

    

        $query_ventas = "SELECT sum(total) as total_ventas FROM tbl_venta_cab  WHERE fecha_emision='$hoy' AND idempresa =$_SESSION[id_empresa] and vendedor = $_SESSION[id]";
        $resultado_ventas = $connect->prepare($query_ventas);
        $resultado_ventas->execute();
        $row_ventas = $resultado_ventas->fetch(PDO::FETCH_ASSOC);
        $ventas = $row_ventas['total_ventas'];
        
      


 ?>

<!DOCTYPE html>
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
                  <h2 class="h5 page-title">Bienvenido </h2>
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
              
              <!-- widgets -->
              <div class="row my-4">
                <div class="col-md-3 rounded-3">
                  <div class="card shadow mb-4">
                    <div class="card-body bg-primary" style="border: 2px solid white;">
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-bold mb-1" style="color: white;font-size: 16px;">Contribuyentes</small>
                          <h2 class="card-title mb-0" style="color:white;"><?=$num_reg_contribuyente?></h2>
                          
                        </div>
                        <div class="col-4 text-right">
                          <span class="fe fe-users" style="font-size: 50px;color: white;"></span>
                        </div>
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body bg-warning" style="border: 2px solid white;">
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-bold mb-1" style="color:white;font-size: 16px;">Facturas</small>
                          <h2 class="card-title mb-0" style="color:white;"><?=$num_reg_facturas?></h2>
                          
                        </div>
                        <div class="col-4 text-right">
                          <span class="fe fe-file" style="font-size: 50px; color: white;"></span>
                        </div>
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-3" >
                  <div class="card shadow mb-4">
                    <div class="card-body bg-success" style="border: 2px solid white;">
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-bold mb-1" style="color:white;font-size: 16px;">Boletas de Venta</small>
                          <h2 class="card-title mb-0" style="color:white;"><?=$num_reg_boletas?></h2>
                          
                        </div>
                        <div class="col-4 text-right">
                          <span class="fe fe-file" style="font-size: 50px;color: white;"></span>
                        </div>
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                
                <?php  if($_SESSION['perfil']=='1')
                   {?>
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body bg-danger" style="border: 2px solid white;">
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-bold mb-1" style="color:white;font-size: 16px;">Total Ventas</small>
                          <h2 class="card-title mb-0" style="color:white;"><?=number_format($ventas,2,'.',',')?></h2>
                          
                        </div>
                        <div class="col-4 text-right">
                          <span class="fe fe-credit-card" style="font-size: 50px;color: white;"></span>
                        </div>
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                
                <?php } ?>
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body bg-info" style="border: 2px solid white;">
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-bold mb-1" style="color:white;font-size: 16px;">Notas de Venta</small>
                          <h2 class="card-title mb-0" style="color:white;"><?=$num_reg_nc?></h2>
                          
                        </div>
                        <div class="col-4 text-right">
                          <span class="fe fe-file" style="font-size: 50px;"></span>
                        </div>
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body bg-secondary" style="border: 2px solid white;">
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-bold mb-1" style="color:white;font-size: 16px;">Productos</small>
                          <h2 class="card-title mb-0" style="color:white;"><?=$num_reg_productos?></h2>
                          
                        </div>
                        <div class="col-4 text-right">
                          <span class="fe fe-box" style="font-size: 50px; color: white"></span>
                        </div>
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body" style="border: 2px solid white; background-color: #A569BD;">
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-bold mb-1" style="color:white;font-size: 16px;">Almacenes</small>
                          <h2 class="card-title mb-0" style="color:white;"><?=$num_reg_almacenes?></h2>
                          
                        </div>
                        <div class="col-4 text-right">
                          <span class="fe fe-package" style="font-size: 50px; color: white"></span>
                        </div>
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body" style="border: 2px solid white;background-color: #046075 ;">
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-bold mb-1" style="color:white;font-size: 16px;">Total Compras</small>
                          <h2 class="card-title mb-0" style="color:white;"><?=number_format($ventas,2,'.',',')?></h2>
                          
                        </div>
                        <div class="col-4 text-right">
                          <span class="fe fe-truck" style="font-size: 50px;color: white;"></span>
                        </div>
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->

              </div> <!-- end section -->

              <hr>

              <div class="row">
                      <div class="col-md-6 my-4">
                        <div class="card shadow">
                         <div class="card-body">
                          <h3 class="card-title text-center">Top <?=$top1?> Productos</h3>
                          <hr>
                          <table class="table table-bordered table-hover mb-0">
                          <thead class="bg-dark" style="color:white;">
                            <tr>
                              <th>Id</th>
                              <th>Nombre</th>
                              <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="top-products">
                         
                        </tbody>
                        </table>
                         </div>
                        </div>
                      </div>
                      <div class="col-md-6 my-4">
                        <div class="card shadow">
                         <div class="card-body">
                          <h3 class="text-card-title text-center">Top <?=$top2?> Clientes</h3>
                          <hr>
                        <table id="tabla_producto" class="table table-bordered">
                          <thead class="bg-dark" style="color:white;">
                            <tr>
                              <th>Id</th>
                              <th>Nombre</th>
                              <th>Ventas</th>
                            </tr>
                        </thead>
                        <tbody id="top-customers">
                          
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
    <?php include 'views/template/pie.php';
           
 ?>

     <script>
        function alerta()
        {
           Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Certificado Digital Vencido!, puede ver como tramitar unos gratis en el link inferior, de lo contrario tendra que adquirir uno nuevo',
                  footer:'<a href="https://www.youtube.com/watch?v=dx0ycbodAFU" target="_blank">Descargar CDT - SUNAT</a>'
                });
        }
       </script>
       <?php  if($hoy>$fecha_certificado)
        {
         echo "<script>alerta()</script>";
        }
 ?>

 <script>

cargarDatosini(<?=$top1?>);
cargarDatosini1(<?=$top2?>);
 </script>
  </body>
</html>