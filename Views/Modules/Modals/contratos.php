
<?php
$query_emp = "SELECT * FROM tbl_contribuyente WHERE empresa = $empresa ";
$resultado_emp=$connect->prepare($query_emp);
$resultado_emp->execute(); 
$num_reg_emp=$resultado_emp->rowCount();

$query_emp1 = "SELECT * FROM tbl_contribuyente WHERE empresa = $empresa ";
$resultado_emp1=$connect->prepare($query_emp1);
$resultado_emp1->execute(); 
$num_reg_emp1=$resultado_emp1->rowCount();


?>
<!-- Modal-->
<div class="modal fade" id="ModalCategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <form action="" name="form_add_contrato" id="form_add_contrato" enctype="multipart/form-data">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title" id="exampleModalLongTitle" style="color:white">Nuevo Contrato</h5>
        <button type="button" class="close" style="color: white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="row">
          <div class="col-sm-4">
              <label for="">Cliente</label>
              <input type="hidden" name="action" value="addContrato">
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
          <div class="col-sm-4">
              <label>Fecha Contrato</label>
              <input type="date" name="fcontrato" id="fcontrato" class="form-control" required="">
          </div>
          <div class="col-sm-4">
              <label>Fecha Inicio Alquiler</label>
              <input type="date" name="finialquiler" id="finialquiler" class="form-control" required="">
          </div>
          <div class="col-sm-4">
              <label>Fecha Vencimiento</label>
              <input type="date" name="fvencimiento" id="fvencimiento" class="form-control" required="">
          </div>
          <div class="col-sm-4">
              <label>Moneda</label>
              <select class="form-control" name="moneda" id="moneda" required="">
                  <option value="PEN">SOLES</option>
                  <option value="USD">DOLARES</option>
              </select>
          </div>
          <div class="col-sm-4">
              <label>Tipo Cambio</label>
              <input type="text" name="tcambio" id="tcambio"  class="form-control">
          </div>
          <div class="col-sm-4">
              <label>Importe S/</label>
              <input type="text" name="importesoles" value="0.00" id="importesoles" class="form-control text-right" min="0.00">
          </div>
          <div class="col-sm-4">
              <label>Importe $</label>
              <input type="text" name="importedolar" value="0.00" id="importedolar" class="form-control text-right" min="0.00">
          </div>
          
          <div class="col-sm-12">
              <label>Observacion</label>
              <textarea class="form-control" rows="4" name="obs" id="obs" cols="50"></textarea>
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
<div class="modal fade" id="ModalContratoEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<form action="" name="form_edit_contrato" id="form_edit_contrato">
 <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="exampleModalLabel">Editar Contrato</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        

       <div class="row">
          <div class="col-sm-4">
              <label for="">Cliente</label>
              <input type="hidden" name="action" value="editContrato">
              <input type="hidden" name="empresa" value="<?=$empresa?>">
              <input type="hidden" name="local" value="<?=$idlocal?>">
               <input type="hidden" name="update_id" id="update_id" value="">
              <select id="update_cliente" name="update_cliente" class="form-control">
                 <option selected="selected"> Seleccionar Cliente</option>
                  <?php 
                        while($row_emp1 = $resultado_emp1->fetch(PDO::FETCH_ASSOC) )
                   {?>
                    <option value="<?= $row_emp1['id_persona'] ?>"><?= $row_emp1['nombre_persona']?></option>;
                   <?php  } ?>
                  
              </select>
          </div>
          <div class="col-sm-4">
              <label>Fecha Contrato</label>
              <input type="date" name="update_fcontrato" id="update_fcontrato" class="form-control">
          </div>
          <div class="col-sm-4">
              <label>Fecha Inicio Alquiler</label>
              <input type="date" name="update_finialquiler" id="update_finialquiler" class="form-control">
          </div>
          <div class="col-sm-4">
              <label>Fecha Vencimiento</label>
              <input type="date" name="update_fvencimiento" id="update_fvencimiento" class="form-control">
          </div>
          <div class="col-sm-4">
              <label>Moneda</label>
              <select class="form-control" name="update_moneda" id="update_moneda">
                  <option value="PEN">SOLES</option>
                  <option value="USD">DOLARES</option>
              </select>
          </div>
          <div class="col-sm-4">
              <label>Tipo Cambio</label>
              <input type="text" name="update_tcambio" id="update_tcambio"  class="form-control">
          </div>
          <div class="col-sm-4">
              <label>Importe S/</label>
              <input type="text" name="update_importesoles" id="update_importesoles" class="form-control text-right">
          </div>
          <div class="col-sm-4">
              <label>Importe $</label>
              <input type="text" name="update_importedolar" id="update_importedolar" class="form-control text-right">
          </div>
          
          <div class="col-sm-12">
              <label>Observacion</label>
              <textarea class="form-control" rows="4" name="update_obs" id="update_obs" cols="50"></textarea>
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
<div class="modal fade" id="ModalCategoriaDlete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" name="form_del_categoria" id="form_del_categoria">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="exampleModalLabel" style="color: white">Anular Categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row">
          <div class="col-sm-12">
            
            <input type="hidden" name="delete_id" id="delete_id" value="">
            <input type="hidden" name="action" value="delCategoria">
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
