<?php 
$idlocal = $rutas[1];
$empresa = $_SESSION["id_empresa"];
$query_data = "SELECT * FROM tbl_alq_contratos WHERE id_local='$idlocal'";
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
                  <h2 class="h5 page-title">Contratos </h2>
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
                             <h2><button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalCategoria"><i class="fe fe-plus-circle"></i> Nuevo Contrato</button></h2>
                          </div>
                         <div class="card-body">
                          
                          <table id="dataTable-1" class="table table-bordered table-hover table-striped datatables dataTable no-footer">
                          <thead class="bg-dark" style="color: white">
                        <tr>
                          <th>Acciones</th>
                          <th>Id</th>
                          <th>ID Cliente</th>
                          <th>Cliente</th>
                          <th>Fecha Contr</th>
                          <th>Fecha Ini</th>
                          <th>Fecha Fin</th>
                          <th>Moneda</th>
                          <th>T.C</th>
                          <th>Importe PEN</th>
                          <th>Importe USD</th>
                          <th>Obs</th>
                          <th>Doc</th>
                          <th>Garantias</th>
                        </tr>
                      </thead>
                        <tbody>
                        <?php foreach($resultado_data as $usuario ){
                        $id_cliente=$usuario['id_cliente'];
                        $query_data = "SELECT * FROM tbl_contribuyente WHERE id_persona=$id_cliente";
                        $resultado = $connect->prepare($query_data);
                        $resultado->execute();
                        $row_empresa = $resultado->fetch(PDO::FETCH_ASSOC);

                        ?>
                          <tr>
                            <td>
                              <button class="btn btn-warning rounded-circle" onclick="openModalEdit()"><i class="fe fe-edit"></i></button>
                              <button class="btn btn-danger rounded-circle" onclick="openModalDel()"><i class="fe fe-trash-2"></i></button></td>
                            <td><?= $usuario['id_contrato'] ?></td>
                            <td><?= $usuario['id_cliente'] ?></td>
                            <td><?= $row_empresa['nombre_persona'] ?></td>
                            <td><?= $usuario['fecha_contrato'] ?></td>
                            <td><?= $usuario['fecha_inicio_alquiler'] ?></td>
                            <td><?= $usuario['fecha_vencimiento'] ?></td>
                            <td><?= $usuario['moneda_alquiler'] ?></td>
                            <td><?= $usuario['tipo_cambio'] ?></td>
                            <td><?= $usuario['importe_alquiler_soles'] ?></td>
                            <td><?= $usuario['importe_alquiler_dolares'] ?></td>
                            <td><?= $usuario['observaciones'] ?></td>
                            <td><a class="btn btn-danger" href="<?=base_url()?>/contratos_pdf/<?= $usuario['id_contrato'] ?>"><i class="fas fa-file-alt"></i></a></td>
                            <td class="text-center" ><a class="btn btn-primary" href="<?=base_url()?>/garantias/<?= $usuario['id_contrato'] ?>"><i class="fas fa-file-alt"></i></a></td>
                           
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
<?php include 'views/modules/modals/contratos.php' ?>
    <?php include 'views/template/pie.php' ?>
      
     
      <script src="../assets/js/contratos.js?v=<?=date('s')?>"></script>
      <script src="../assets/js/funciones_contratos.js?v=<?=date('s')?>"></script>

  </body>
</html>