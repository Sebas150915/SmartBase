<?php

$query_con = "SELECT * FROM tbl_alq_local WHERE id= $idlocal ";
$resultado_con=$connect->prepare($query_con);
$resultado_con->execute();
$row_con = $resultado_con->fetch(PDO::FETCH_ASSOC); 
$num_reg_con=$resultado_con->rowCount();

$query_emp = "SELECT * FROM tbl_contribuyente WHERE empresa = $empresa ";
$resultado_emp=$connect->prepare($query_emp);
$resultado_emp->execute(); 
$num_reg_emp=$resultado_emp->rowCount();

?>


<div class="modal fade" id="ModalGarantia" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <form action="" name="form_add_garantia" id="form_add_garantia">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title" id="exampleModalLongTitle">Nueva Garantia</h5>
        <button type="button" class="close" style="color: white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      


      <div class="row">

      <div class="col-sm-6">
           <label for="">Cliente</label>
           <input type="hidden" name="action" value="addSeparacion">
           <input type="hidden" name="empresa" value="<?=$empresa?>">
           <input type="hidden" name="local" value="<?=$idlocal?>">
           <select id="cliente" name="cliente" class="form-control select2">
                  <option value="">Seleccionar Cliente</option>
                  <?php 
                        while($row_emp = $resultado_emp->fetch(PDO::FETCH_ASSOC) )
                   {?>
                    <option value="<?= $row_emp['id_persona'] ?>"><?= $row_emp['nombre_persona']?></option>;
                   <?php  } ?>
                  
              </select>
          </div>



          <div class="col-sm-6">
              <label for="">Fecha</label>
              <input type="hidden" name="action" value="addGarantia">
              <input type="hidden" name="empresa" value="<?=$empresa?>">
            <input type="date" name="fecha_garantia" id="fecha_garantia" class="form-control" required="" value="<?php echo date("Y-m-d"); ?>">
          </div>

          <div class="col-sm-6">
              <label for="">Meses</label>
            <input type="text" name="meses" id="meses" class="form-control" required="" value="<?=$row_con['meses_garantia'] ?>" readonly="">
          </div>

          <div class="col-sm-6">
              <label>Moneda</label>
              <select class="form-control" name="moneda" id="moneda" required="">
                  <option value="PEN">SOLES</option>
                  <option value="USD">DOLARES</option>
              </select>
          </div>


      </div>
      <div class="row mt-3">
        <div class="col-sm-6">
          <label for="">Importe Soles</label>
          <input type="text" class="form-control text-right" name="importe_soles" id="importe_soles" value="0.00">
        </div>
        <div class="col-sm-6">
          <label for="">Importe Dólares</label>
          <input type="text" class="form-control text-right" name="importe_dolares" id="importe_dolares" value="0.00">
       </div>
      </div>
   
</div> 
      <div class="modal-footer">
         
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Guardar</button>
        
      </div>
    </div>
  </div>
     </form>
</div>




<!-- Modal Edit-->
<div class="modal fade" id="EditGarantia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<form action="" name="form_edit_garantia" id="form_edit_garantia">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="exampleModalLabel">Editar Garantia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        

      <div class="row">
                   <div class="col-sm-6">
              <label for="">Fecha</label>
              <input type="hidden" name="action" value="editGarantia">
              <input type="hidden" name="empresa" value="<?=$empresa?>">
              <input type="hidden" name="update_id" id="update_id" value="">
            <input type="date" name="update_fecha" id="update_fecha" class="form-control" required="">
          </div>

          <div class="col-sm-6">
              <label for="">Meses</label>
            <input type="text" name="update_meses" id="update_meses" class="form-control" required="">
          </div>


      </div>
      <hr>
      <div class="row mt-3">
        <div class="col-sm-6">
          <label for="">Importe Soles</label>
          <input type="text" class="form-control text-right" name="update_soles" id="update_soles" value="0.00">
        </div>
        <div class="col-sm-6">
          <label for="">Importe Dólares</label>
          <input type="text" class="form-control text-right" name="update_dolares" id="update_dolares" value="0.00">
       </div>
      </div>
   
</div> 
      
    <div class="modal-footer mt-3">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Actualizar</button>
        
    </div> 
    </div>
  </div>
</div>
   </form>
</div>


<!-- Modal delete-->
<div class="modal fade" id="DeleteGarantia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" name="form_del_garantia" id="form_del_garantia">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="exampleModalLabel" style="color: white">Anular Garantia?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row">
          <div class="col-sm-12">
            
            <input type="hidden" name="delete_id" id="delete_id" value="">
            <input type="hidden" name="action" value="delGarantia">
          <h5 class="text-center">¿Esta seguro que desea realizar el proceso?</h5>
          </div>
      </div>
      
    <div class="modal-footer mt-3">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Anular</button>
        
    </div>
 
 
    </div>
  </div>
</div>
  </form>
</div>
