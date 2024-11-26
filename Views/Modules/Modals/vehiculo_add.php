<!-- Modal -->
<div class="modal fade" id="ModalVehiculo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <form action="" name="form_add_vehiculo" id="form_add_vehiculo">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo Vehiculo</h5>
        <button type="button" class="close" style="color: white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      


      <div class="row">
          <div class="col-sm-4">
              <label for="">Placa</label>
              <input type="hidden" name="action" value="addVehiculo">
              <input type="hidden" name="empresa" value="<?=$empresa?>">
            <input type="text" name="placa" id="placa" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>
          <div class="col-sm-4">
              <label for="">Marca</label>
             
            <input type="text" name="marca" id="marca" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>
          <div class="col-sm-4">
              <label for="">Certificado</label>
             
            <input type="text" name="certificado" id="certificado" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>


      </div>
      <hr>
      <div class="row mt-3">
        <div class="col-sm-6">
          <label for="">Modelo</label>
          <input type="text" class="form-control text-right" maxlength="7" minlength="5" name="modelo" id="modelo" onkeyup="javascript:this.value=this.value.toUpperCase();">
        </div>
        <div class="col-sm-6">
          <label for="">Año</label>
          <input type="text" class="form-control text-right" maxlength="4"  name="anio" id="anio">
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
    <form action="" name="form_edi_vehiculo" id="form_edi_vehiculo">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="exampleModalLabel">Editar Vehiculo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            

          <div class="row">
              <div class="col-sm-4">
                  <label for="">Placa</label>
                <input type="hidden" name="update_id" id="update_id" value="">
                <input type="hidden" name="action" value="ediVehiculo">
                <input type="text"  name="update_placa" id="update_placa" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
              </div>
              <div class="col-sm-4">
                  <label for="">Marca</label>
                 
                <input type="text" name="update_marca" id="update_marca" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
              </div>
              <div class="col-sm-4">
                  <label for="">Certificado</label>
                 
                <input type="text" name="update_certificado" id="update_certificado" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
              </div>
          </div>
          <hr>
          <div class="row mt-3">
            <div class="col-sm-6">
              <label for="">Modelo</label>
              <input type="text" class="form-control text-right" maxlength="7" minlength="5" name="update_modelo" id="update_modelo">
            </div>
            <div class="col-sm-6">
              <label for="">Año</label>
              <input type="text" class="form-control text-right" maxlength="4" minlength="4" name="update_anio" id="update_anio">
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
    <form action="" name="form_del_vehiculo" id="form_del_vehiculo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="exampleModalLabel" style="color: white">Anular Vehiculo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row">
          <div class="col-sm-12">
            
            <input type="hidden" name="delete_id" id="delete_id" value="">
            <input type="hidden" name="action" value="delVehiculo">
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
