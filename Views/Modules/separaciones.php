<?php 
$idlocal = $rutas[1];
$empresa = $_SESSION["id_empresa"];
$query_data = "SELECT * FROM tbl_alq_separaciones";
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
                  <h2 class="h5 page-title">Separaciones</h2>
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
                             <h2><button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalSeparacion"><i class="fe fe-plus-circle"></i> Nueva Separación</button>
                            </h2>
                          </div>
                         <div class="card-body">
                          
                          <table id="dataTable-1" class="table table-bordered table-hover table-striped datatables dataTable no-footer">
                          <thead class="bg-dark" style="color: white">
                        <tr>
                          <th>Acciones</th>
                          <th>Id</th>
                          <th>Id Cliente</th>
                          <th>Cliente</th>
                          <th>Moneda</th>
                          <th>Importe Soles</th>
                          <th>Importe Dólares</th>
                          <th>Fecha Separación</th>
                          <th>Estado</th>
                          
                        </tr>
                      </thead>
                        <tbody>
                        <?php foreach($resultado_data as $usuario ){
                        $idcliente=$usuario['id_cliente'];
                        
                        $query_cli = "SELECT * FROM tbl_contribuyente WHERE id_persona=$idcliente";
                        $resultado_cli = $connect->prepare($query_cli);
                        $resultado_cli->execute();
                        $row_clie = $resultado_cli->fetch(PDO::FETCH_ASSOC);
                        
                        ?>
                          <tr>
                            <td>
                              <button class="btn btn-warning rounded-circle" onclick="openModalEdit()"><i class="fe fe-edit"></i></button>
                              <button class="btn btn-danger rounded-circle" onclick="openModalDel()"><i class="fe fe-trash-2"></i></button>
                              <a class="btn btn-success rounded-circle" href="<?=base_url()?>\nueva_nota_venta\<?=$data['id_separacion']?>"> <i class="fe fe-dollar-sign"></i></a>
                                
                            </td>
                            <td><?= $usuario['id_separacion'] ?></td>
                            <td><?= $usuario['id_cliente'] ?></td>
                            <td><?= $row_clie['nombre_persona'] ?></td>
                            <td><?= $usuario['moneda'] ?></td>
                            <td><?= $usuario['monto_separacion_soles'] ?></td>
                            <td><?= $usuario['monto_separacion_dolar'] ?></td>
                            <td><?= $usuario['fecha_separacion'] ?></td>
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

    <?php include 'views/template/pie.php' ?>
     <?php include 'views/modules/modals/separaciones.php' ?>
      <script src="../assets/js/separaciones.js?v=<?=date('s')?>"></script>
      <script src="../assets/js/funciones_separaciones.js?v=<?=date('s')?>"></script>

  </body>
</html>


<!-- <button class="btn btn-success rounded-circle" onclick="openModalCash()"><i class="fe fe-dollar-sign"></i></button> -->