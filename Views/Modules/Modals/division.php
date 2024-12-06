<!-- Modal -->
 <div class="modal fade" id="ModalDivision" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <form action="" name="form_add_division" id="form_add_division">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h5 class="modal-title" style="color: white" id="exampleModalLongTitle" >Nueva Division</h5>
        <button type="button" class="close" style="color: white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      


      <div class="row">
          <div class="col-sm-12">
              <label for="">Nombre</label>
              <input type="hidden" name="action" value="addDivision">
              <input type="hidden" name="empresa" value="<?=$empresa?>">
            <input type="text" name="division" id="division" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
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
<div class="modal fade" id="ModalEditDivision" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" name="form_edit_division" id="form_edit_division">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="exampleModalLabel">Editar Division</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            

          <div class="row">
              <div class="col-sm-12">
                  <label for="">Nombre</label>
                <input type="hidden" name="update_id" id="update_id" value="">
                <input type="hidden" name="action" value="editDivision">
                <input type="text"  name="update_division" id="update_division" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
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
    <form action="" name="form_del_vendedor" id="form_del_vendedor">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="exampleModalLabel" style="color: white">Anular Vendedor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row">
          <div class="col-sm-12">
            
            <input type="hidden" name="delete_id" id="delete_id" value="">
            <input type="hidden" name="action" value="deleteZona">
          <h5 class="text-center">¿Esta seguro que desea realizar el proceso?</h5>

           <select name="estado" id="estado" class="form-control">
            <option value="1">ACTIVADO</option>
            <option value="0">DESACTIVADO</option>
          </select>
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
