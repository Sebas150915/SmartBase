<div id="deleteProducto" class="modal fade deleteAlmacen" id="deleteSerie" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <form name="form_delete_producto" id="form_delete_producto">


    <div class="modal-content">
      

      <div class="modal-header bg-danger">
        <h4 class="modal-title" id="myModalLabel" style="color: white;">Anular Producto</h4>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="color: white;">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-4">
            <input type="hidden" name="action"  value="delete_producto">
            <input type="hidden" name="delete_empresa" value="<?=$_SESSION["id_empresa"]?>">
            <input type="hidden" name="delete_id" id="delete_id" value="">  
          </div>         
          
        </div>
        <div class="row">
          <div class="col-sm-12" style="margin: auto;">
            <h2 class="h2 text-center"><i class="fa fa-trash"></i></h2>
            <h3>¿Seguro desea anular el registro?</h3>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Anular</button>
      </div>

    </div>
     </form>
  </div>
</div>