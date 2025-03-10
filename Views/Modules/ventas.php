<?php 

$empresa = $_SESSION["id_empresa"];

$query_usuario = "SELECT * FROM tbl_usuarios WHERE id_empresa = $empresa";
$resultado_usuario=$connect->prepare($query_usuario);
$resultado_usuario->execute(); 
$num_reg_usuario=$resultado_usuario->rowCount();


//echo $empresa;
$vendedor = $_SESSION["id"];
$hoy = date('Y-m-d');
 $sql_empresas="SELECT * FROM tbl_empresas WHERE id_empresa = $_SESSION[id_empresa]";
        $resultado_empresas=$connect->prepare($sql_empresas);
        $resultado_empresas->execute();
        $row_empresas = $resultado_empresas->fetch(PDO::FETCH_ASSOC);

        $fecha_certificado = $row_empresas['fecha_certificado'];

        if($hoy>$fecha_certificado)
        {
          $estado_certificado = 'Cerificado Vencido';

       
        }
        else
        {
          $estado_certificado = 'Cerificado Activo';
        }



if(!empty($_POST))
{
    if($_SESSION["perfil"] == '1')
    {
      $fecha_ini = $_POST['f_ini'];
      $fecha_fin = $_POST['f_fin'];
      $tdoc      = $_POST['tdoc'];
      $user      = $_POST['idusuario'];
      $query_venta = "SELECT * FROM vw_tbl_venta_cab AS c LEFT JOIN tbl_contribuyente as x
      on c.idcliente = x.id_persona WHERE idempresa = $empresa  AND tipocomp <>'99' AND tipocomp like '$tdoc' AND vendedor like '$user' AND fecha_emision BETWEEN '$fecha_ini' AND '$fecha_fin' ";
      $resultado_venta=$connect->prepare($query_venta);
      $resultado_venta->execute();
      $num_reg_venta=$resultado_venta->rowCount();

    }
    else
    {
      $fecha_ini = $_POST['f_ini'];
      $fecha_fin = $_POST['f_fin'];
      $tdoc      = $_POST['tdoc'];
      $query_venta = "SELECT * FROM vw_tbl_venta_cab AS c LEFT JOIN tbl_contribuyente as x
      on c.idcliente = x.id_persona WHERE idempresa = $empresa AND vendedor=$vendedor AND tipocomp <>'99' AND tipocomp like '$tdoc' AND vendedor like '$user' AND fecha_emision BETWEEN '$fecha_ini' AND '$fecha_fin' ";
      $resultado_venta=$connect->prepare($query_venta);
      $resultado_venta->execute();
      $num_reg_venta=$resultado_venta->rowCount();
    }

}
else
{
      if($_SESSION["perfil"] == '1')
      {
          $hoy = date('Y-m-d');
          $fecha_ini = $hoy;
          $fecha_fin = $hoy;
          $query_venta = "SELECT * FROM vw_tbl_venta_cab AS c LEFT JOIN tbl_contribuyente as x
          on c.idcliente = x.id_persona WHERE idempresa = $empresa  AND tipocomp <>'99'AND fecha_emision='$hoy'";
          $resultado_venta=$connect->prepare($query_venta);
          $resultado_venta->execute();
          $num_reg_venta=$resultado_venta->rowCount();

      }
      else
      {
      $hoy = date('Y-m-d');
      $fecha_ini = $hoy;
      $fecha_fin = $hoy;
      $query_venta = "SELECT * FROM vw_tbl_venta_cab AS c LEFT JOIN tbl_contribuyente as x
      on c.idcliente = x.id_persona WHERE idempresa = $empresa AND vendedor=$vendedor AND tipocomp <>'99'AND fecha_emision='$hoy'";
      $resultado_venta=$connect->prepare($query_venta);
      $resultado_venta->execute();
      $num_reg_venta=$resultado_venta->rowCount();
      }
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
  <body class="horizontal dark">
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
                  <h2 class="h5 page-title">Listado de Ventas </h2>
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
                      <h2><a href="nueva_venta" type="button" class="btn btn-primary mr-4"><i class="fe fe-plus-circle"></i> Factura / Boleta</a>
                    <a href="nueva_nota_credito" type="button" class="btn btn-danger"><i class="fe fe-plus-circle"></i> Nota de Credito </a></h2>
                    <hr style="border-top: 1px solid #d5dde6;">
                    <form method="POST" class="form-inline">
                      <label for=""> Fecha Inicial :</label>
                      <input type="date" class="form-control mr-3" name="f_ini" id="f_ini" value="<?=$fecha_ini?>">
                      <label for=""> Fecha Final :</label>
                      <input type="date" class="form-control mr-3" name="f_fin" value="<?=$fecha_fin?>" id="f_fin"> 
                      <label for="">Tip. Doc.: </label>
                      <select name="tdoc" id="tdoc" class="form-control select2 mr-3">
                        <option value="%">Todos</option>
                        <option value="01">Facturas</option>
                        <option value="03">Boletas</option>
                        <option value="07">Nota de Credito</option>
                        
                      </select>
                    
                        <label for="">Usuario</label>
                        <select class="form-control select2"  name="idusuario" id="idusuario">
                             <option value="%">Todos</option>
                  
                            <?php 
                                    while($row_usuario = $resultado_usuario->fetch(PDO::FETCH_ASSOC) )
                               {?>
                                <option value="<?= $row_usuario['id_usuario'] ?>"><?=$row_usuario['usuario']?></option>;
                               <?php  } ?>
                          </select>
                 
                      <button class="btn btn-success ml-3" type="submit">Listar CPE</button>
                     
                    </form>
                          </div>
                         <div class="card-body">
                          
                         <table id="example" class="table table-striped table-bordered  compact nowrap" cellspacing="0" width="100%">
                          <thead class="bg-dark" style="color: white">
                              <tr>
                                
                                <th>Id</th>
                                <th>Editar</th>
                              
                                <th>Estado</th>
                                <th>Codigo</th>
                                <th>Fecha</th>
                                
                                 <th>Hora</th>
                                <th>T. Cpe</th>
                                <th>N. Cpe</th>
                                <th>Id Cliente</th>
                                <th>RUC/DNI</th>
                                
                                <th>Cliente</th>
                                <th>Op. Gravada</th>
                                <th>Op. Exonerada</th>
                                <th>Op. Inafecta</th>
                                <th>IGV</th>
                                <th>Total</th>
                                <th>Usuario</th>
                                <th>Opciones</th>
                                                                
                              </tr>
                      </thead>
                      <tbody>
                        <?php foreach($resultado_venta as $ventas ){ 
                            
/* if($ventas['feestado']=='0')
{
$table_color = 'table-warning';

<tr class="table-active">...</tr>

<tr class="table-primary">...</tr>
<tr class="table-secondary">...</tr>
<tr class="table-success">...</tr>
<tr class="table-danger">...</tr>
<tr class="table-warning">...</tr>
<tr class="table-info">...</tr>
<tr class="table-light">...</tr>
<tr class="table-dark">...</tr>

}
else if($ventas['feestado']=='3')
{
$table_color = 'table-danger';
}*/
                        
                        ?>
                          <tr class="<?=$table_color?>">
                            
                            <td><?= $ventas['id'] ?></td>
                            <td><a href="<?=base_url()?>/editar_venta/<?= $ventas['id'] ?>" class="btn btn-warning rounded-circle" ><i class="fas fa-edit"></i></a></td>
                            <td><?php $e = $ventas['feestado'];
                            if($e == 1)
                            {
                              $e ='fe fe-check';
                              $c = 'aceptado';
                              $x = 'success'; 
                            }
                            else
                            {
                              $e ='fe fe-x-circle';
                              $c = 'pendiente';
                              $x = 'danger';

                            }?> 
                            <span style="font-size: 10px;" class="badge badge-pill badge-<?=$x?>"><i class="<?=$e?>"><?=$ventas['estado_cpe']?></i></span></td>
                            <td><?=$ventas['fecodigoerror']?></td>
                            <td><?= $ventas['fecha_emision'] ?></td>
                            <td><?= $ventas['hora_emision'] ?></td>
                            <td><?= $ventas['tipocomp'] ?></td>
                            <td><?= $ventas['serie'].'-'.$ventas['correlativo'] ?></td>
                            <td><?= $ventas['idcliente'] ?></td>
                            <td><?= $ventas['codcliente'] ?></td>
                            <td><?= $ventas['nombre_persona'] ?></td>
                            <td align="right"><?= $ventas['op_gravadas'] ?></td>
                            <td align="right"><?= $ventas['op_exoneradas'] ?></td>
                            <td align="right"><?= $ventas['op_inafectas'] ?></td>
                            <td align="right"><?= $ventas['igv'] ?></td>
                            <td align="right"><?= $ventas['total'] ?></td>
                            <td align="center"><?= $ventas['nom_usuario'] ?></td>

                            <td>
                                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                <a target="_blank" href="<?=base_url()?>/ticket_factura/<?=$ventas['id']?>" class="btn btn-primary"><i class="fe fe-printer"></i></a>
                                <a href="#" class="btn btn-danger" onclick="openModalEnvia()"><i class="fe fe-send"></i></a>
                                <a href="<?=base_url()?>/sunat/<?=$row_empresa['ruc']?>/xml/<?=$row_empresa['ruc'].'-'.$ventas['tipocomp'].'-'.$ventas['serie'].'-'.$ventas['correlativo'].'.ZIP'?>" class="btn btn-success"><i class="far fa-file-excel"></i></a>
                                <?php if($ventas['feestado']==1){$x = ''; } else{$x='not-active';}?>
                              <a href="<?=base_url()?>/sunat/<?=$row_empresa['ruc']?>/cdr/<?= 'R-'. $row_empresa['ruc'].'-'.$ventas['tipocomp'].'-'.$ventas['serie'].'-'.$ventas['correlativo'].'.ZIP'?>" class="btn btn-secondary  <?=$x?>"  ><i class="far fa-file-code"></i></a>
                              <?php if($empresa==16){/*HTP LOGISTICA*/ ?>
                              <a target="_blank" href="factura_pdf3/<?= $ventas['id'] ?>" class="btn btn-danger "><i class="fa-solid fa-file-pdf"></i></a>
                              <?php } 
                              else {?>
                               <a target="_blank" href="factura_pdf/<?= $ventas['id'] ?>" class="btn btn-danger "><i class="fa-solid fa-file-pdf"></i></a>
                              <?php } ?>
                              
                              <a target="_blank" href="ticket_factura_pdf/<?= $ventas['id'] ?>" class="btn btn-primary "><i class="fas fa-ticket-alt"></i></a>
                                <button onclick="enviacorreo(<?=$ventas['id']?>,<?=$ventas['idcliente']?>)" class="btn btn-info "><i class="fe fe-mail"></i></button>
                                <button onclick="validarCpe(<?=$ventas['id']?>)" class="btn btn-warning "><i class="fa-solid fa-eye"></i></button>
                                </div>
                                
                                </div>
                      </td>
                            
                            
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
     <?php include 'views/modules/modals/validar_cpe.php' ?>
    <?php include 'views/template/pie.php' ?>
    <script defer="" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/imask"></script>
     <!-- <script type="text/javascript" src="Assets/vendors/inputMask/inputmask.js" charset="utf-8"></script>-->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.8/jquery.inputmask.bundle.min.js"></script>


<script src="assets/js/funciones_ventas.js"></script>

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
     var table = $('#example').DataTable( {
         "scrollX": true,
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,
                  "order": [[ 3, "desc" ]],

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                
                 dom: "Blfrtip",
                buttons: [
                    {
                        extend: "copy",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "csv",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "excel",
                        footer: true,
                        title: 'REPORTE DE VENTAS',
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "pdfHtml5",
                        className: "btn-sm btn-dark",
                        orientation: 'landscape',
                        title: 'REPORTE DE VENTAS SUNAT',
                        titleAttr: 'Exportar a PDF',
                        
                       
                    },
                    {
                        extend: "print",
                        className: "btn-sm btn-dark"
                    },
                   
                ],
    initComplete: function(settings, json) {
      // calculate the sum when table is first created:
      doSum();
    }
  } );

  $('#example').on( 'draw.dt', function () {
    // re-calculate the sum whenever the table is re-displayed:
    doSum();
  } );

  // This provides the sum of all records:
  function doSum() {
    // get the DataTables API object:
    var table = $('#example').DataTable();
    // set up the initial (unsummed) data array for the footer row:
    var totals = ['','','','','','','','','','',0,0,0,0,0,'',''];
    // iterate all rows - use table.rows( {search: 'applied'} ).data()
    // if you want to sum only filtered (visible) rows:
    totals = table.rows( ).data()
      // sum the amounts:
      .reduce( function ( sum, record ) {
        for (let i = 10; i <= 14; i++) {
          sum[i] = sum[i] + numberFromString(record[i]);
        } 
        return sum;
      }, totals ); 
    // place the sum in the relevant footer cell:
    for (let i = 10; i <= 14; i++) {
      var column = table.column( i );
      $( column.footer() ).html( formatNumber(totals[i]) );
    }
  }

  function numberFromString(s) {
    return typeof s === 'string' ?
      s.replace(/[\$,]/g, '') * 1 :
      typeof s === 'number' ?
      s : 0;
  }

  function formatNumber(n) {
     return n.toLocaleString(); // or whatever you prefer here
  }

    
</script>
  </body>
</html>