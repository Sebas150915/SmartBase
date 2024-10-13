 <!--=====================================
  MODAL TIPO DE PROVEEDOR
  ======================================-->
  <div id="modalDocrefpedido" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
  <!--=====================================
  MODAL CABECERA
  ======================================-->
      <div class="modal-header bg-dark" >
        <h4 class="text-left">Seleccionar Documento de referencia</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
  <!--=====================================
  MODAL CUERPO
  ======================================-->
      <div class="modal-body">
      <table id="datatable-contribuyente" class="table table-bordered table-striped table-condensed table-hover" width="100%">
       <thead class="bg-dark" style="color: white">
          <tr>
          <th>ID</th>
            <th>Tipo</th>
           <th>Serie</th>
        <th>Fecha</th>
           <th>Cliente</th>
           <th>Total</th>
           <th>Saldo</th>
           <th>Anticipo</th>
           <th>Accion</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($resultado2 as $res2) {
            
$sql=" SELECT sum(total) as total FROM tbl_venta_cab WHERE relacionado_id='$res2->id' AND estado IN (0,1,2,8) ";
$sql22=$connect->prepare($sql);
$sql22->execute();
$mos22 = $sql22->fetchObject();

$saldo='0.00';
if($mos22){ $saldo=number_format($res2->total-$mos22->total, 2); }

?>
            <tr>
            <td><?php echo $res2->id ?></td>
              <td><?php echo $res2->tipocomp ?></td>
              
              <td><?php echo  $res2->serie.'-'.$res2->correlativo; ?></td>
              <td><?php echo $res2->fecha_emision ?></td>
               <td><?php echo $res2->nombre_persona ?></td>
               <td><?php echo $res2->total; ?></td>
               <td><?php echo $saldo; ?></td>
<td><input type="text" class="form-control" name="anticipo<?php echo $res2->id; ?>" id="anticipo<?php echo $res2->id; ?>" value="0.00" ></td>

<td  class="text-center"><a class="btn btn-success" onclick="agregaranticipos('<?php echo $res2->id; ?>', '<?php echo $res2->tipocomp; ?>', '<?php echo $res2->nomdoc; ?>', '<?php echo $res2->serie; ?>', '<?php echo $res2->correlativo; ?>', '<?php echo $res2->id_cliente; ?>', '<?php echo $res2->num_doc; ?>', '<?php echo $res2->nombre_persona; ?>','<?php echo $res2->direccion_persona; ?>','<?php echo $res2->op_gravadas; ?>','<?php echo $res2->op_exoneradas; ?>','<?php echo $res2->op_inafectas; ?>','<?php echo $res2->igv; ?>', '<?php echo $res2->total; ?>');"><i class="fe fe-check"></i></a></td>
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


  