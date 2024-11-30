<!-- Modal -->
<div class="modal fade" id="ModalChofer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <form action="" name="form_add_chofer" id="form_add_chofer">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo Conductor</h5>
        <button type="button" class="close" style="color: white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      


      <div class="row">
          <div class="col-sm-6">
              <label for="">Nombre</label>
              <input type="hidden" name="action" value="addChofer">
              <input type="hidden" name="empresa" value="<?=$empresa?>">
            <input type="text" name="nombre" id="nombre" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>
          <div class="col-sm-6">
              <label for="">Apellido</label>
             
            <input type="text" name="apellido" id="apellido" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>
          
      </div>
      
      <div class="row mt-3">
        <div class="col-sm-6">
          <label for="">T. Doc</label>
          <select name="tdoc" id="tdoc" class="form-control select2">
            <option value="1">DNI</option>
            <option value="4">CEDULA</option>
          </select>
        </div>
        <div class="col-sm-6">
          <label for="">N. Doc</label>
          <input type="text" class="form-control text-right"  name="ndoc" id="ndoc">
       </div>
      </div>
      <div class="row mt-3">
        <div class="col-sm-12">
          <label for="">Direccion</label>
          <input type="text" class="form-control"   name="direccion" id="direccion" onkeyup="javascript:this.value=this.value.toUpperCase();">
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-sm-6">
          <label for="">Telefono</label>
          <input type="text" class="form-control text-right"  name="telefono" id="telefono">
        </div>
        <div class="col-sm-6">
          <label for="">Licencia</label>
          <input type="text" class="form-control text-right"  name="licencia" id="licencia">
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
<div class="modal fade" id="ModalCategoriaEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" name="form_edi_chofer" id="form_edi_chofer">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="exampleModalLabel">Editar Chofer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
      


      <div class="row">
          <div class="col-sm-6">
              <label for="">Nombre</label>
              <input type="hidden" name="action" value="ediChofer">
              <input type="hidden" name="update_empresa" value="<?=$empresa?>">
              <input type="hidden" name="update_id" id="update_id" value="">
            <input type="text" name="update_nombre" id="update_nombre" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>
          <div class="col-sm-6">
              <label for="">Apellido</label>
             
            <input type="text" name="update_apellido" id="update_apellido" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>
          
      </div>
      
      <div class="row mt-3">
        <div class="col-sm-6">
          <label for="">T. Doc</label>
          <select name="update_tdoc" id="update_tdoc" class="form-control">
            <option selected="selected">Seleccionar Tipo Doc</option>
            <option value="1">DNI</option>
            <option value="4">CEDULA</option>
          </select>
        </div>
        <div class="col-sm-6">
          <label for="">N. Doc</label>
          <input type="text" class="form-control text-right"  name="update_ndoc" id="update_ndoc">
       </div>
      </div>
      <div class="row mt-3">
        <div class="col-sm-12">
          <label for="">Direccion</label>
          <input type="text" class="form-control"   name="update_direccion" id="update_direccion" onkeyup="javascript:this.value=this.value.toUpperCase();">
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-sm-6">
          <label for="">Telefono</label>
          <input type="text" class="form-control text-right"  name="update_telefono" id="update_telefono">
        </div>
        <div class="col-sm-6">
          <label for="">Licencia</label>
          <input type="text" class="form-control text-right"  name="update_licencia" id="update_licencia">
       </div>
      </div>
   
   </div> 
        <div class="modal-footer mt-3">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Actualizar</button>
            
        </div> 
       
      </div>
    </div>
    </form>
</div>


<!-- Modal delete-->
<div class="modal fade" id="ModalCategoriaDlete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" name="form_del_chofer" id="form_del_chofer">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="exampleModalLabel" style="color: white">Anular Chofer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row">
          <div class="col-sm-12">
            
            <input type="hidden" name="delete_id" id="delete_id" value="">
            <input type="hidden" name="action" value="delChofer">
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
