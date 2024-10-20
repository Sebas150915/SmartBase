 <!--=====================================
  MODAL TIPO DE PROVEEDOR
  ======================================-->
  <div id="modalCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
  <!--=====================================
  MODAL CABECERA
  ======================================-->
      <div class="modal-header bg-dark" >
        <h4 class="text-left">Seleccionar Contribuyente</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
  <!--=====================================
  MODAL CUERPO
  ======================================-->
      <div class="modal-body">
      <table id="datatable-insumos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
       <thead class="bg-dark" style="color: white">
          <tr>
            <th>RUC</th>
           <th>Nombre</th>
           <th>Seleccionar</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($resultado1 as $res2) { ?>
            <tr>
              <td><?php echo $res2->num_doc ?></td>
              <td><?php echo $res2->nombre_persona ?></td>
              
              <td  class="text-center"><a class="btn btn-success" onclick="enviacliente3('<?php echo $res2->id_persona; ?>','<?php echo $res2->num_doc; ?>','<?php echo $res2->nombre_persona; ?>','<?php echo $res2->direccion_persona; ?>');"><i class="fe fe-check"></i></a></td>
            </tr>
          <?php } ?>
        </tbody>
         
       </table>
      </div>
  <!--=====================================
  MODAL PIE
  ======================================-->
      <div class="modal-footer">
           <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
         </div>
    </div>
    </div>
  </div>


  