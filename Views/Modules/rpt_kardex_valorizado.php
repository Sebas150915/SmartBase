<?php
$id_empresa = $_SESSION["id_empresa"];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include 'views/template/head.php'; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
  </head>

  <body class="horizontal dark">
    <div class="wrapper">
      <?php include 'views/template/nav.php'; ?>
      <main role="main" class="main-content">      
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <h2 class="h5 page-title">Reporte de Kardex</h2>
              <hr>
              <div class="row my-4">
                <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-header">
                      <form id="form_filtro">
                         <div class="row mr-3 ml-3">
                          <div class="col-sm-4">                           
                            <label for="fecha_ini" class="col-form-label"> Fecha Inicio: </label>
                          <input type="hidden" id="id_empresa" name="id_empresa" value="<?=$id_empresa?>">
                          <input type="date" id="fecha_ini" name="fecha_ini" class="form-control">
                          </div>
                          <div class="col-sm-4">
                          
                          <label for="fecha_fin" class="col-form-label"> Fecha Fin: </label>
                          <input type="date" id="fecha_fin" name="fecha_fin" class="form-control">
                        
                        </div>
                        <div class="col-sm-4">
                          <label for="fecha_fin" class="col-form-label"> . </label>
                          <button type="button" id="btn_filtrar" class="btn btn-success mx-1 btn-block">Procesar</button>
                        </div>
                          
                        </div>
                        
                       
                      </form>
                    </div>

                    <div class="card-body">
                      <table id="kardex_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                        <thead class="bg-dark" style="color: white">
                          <tr>
                             <th rowspan="2">Producto</th>
                             <th colspan="4">DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO INTERNO O SIMILAR</th>
                             <th rowspan="2">Tipo Operacion</th>
                             <th colspan="3" class="text-center">Entradas</th>
                             <th colspan="3" class="text-center">Salidas</th>
                             <th colspan="3" class="text-center">Saldo Final</th>
                          </tr>
                          <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Serie</th>
                            <th>Número</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <?php include 'views/template/pie.php'; ?>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script>
      $(document).ready(function() {
        var table = $('#kardex_table').DataTable({
          "scrollX": true,
          "paging": false,
          "order": [[0, 'asc'], [1, 'asc']],
          "dom": 'Bfrtip',
          "buttons": [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true, title: 'Kardex Valorizado' },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
          ],
          "rowGroup": 
          {
            dataSrc: 'nombre_producto'
          },
          "ajax": 
          {
            "url": base_url+'/assets/ajax/kardex_data.php',
            "data": function (d) 
            {
              d.fecha_ini = $('#fecha_ini').val();
              d.fecha_fin = $('#fecha_fin').val();
              d.id_empresa = $('#id_empresa').val();
            }
          },
          "columns": [
            { "data": "nombre_producto" },
            { "data": "fecha" },
            { "data": "tipo_doc" },
            { "data": "serie_doc" },
            { "data": "num_doc" },
            { "data": "tipo_movimiento" },
            { "data": "cantidad_entrada" },
            { "data": "costo_unitario_entrada" },
            { "data": "total_entrada" },
            { "data": "cantidad_salida" },
            { "data": "costo_unitario_salida" },
            { "data": "total_salida" },
            { "data": "saldo_final" },
            { "data": "costo_promedio" },
            { "data": "total_final" }
          ]
        });

        // Filtrar datos al hacer clic en "Procesar"
        $('#btn_filtrar').on('click', function() {
          table.ajax.reload();
        });
      });
    </script>
  </body>
</html>
