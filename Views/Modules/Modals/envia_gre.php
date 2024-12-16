<div class="modal fade" id="ModalGreEnviada">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-dark">
              <h4 class="modal-title">Envia Gre</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="envia_gre" name="form1" enctype="multipart/form-data">
                <div class="row mb-3">
                <div class="col">
                  <input type="hidden" name="action" value="sunat_gre">
                  <input type="hidden" name="enviar_id" id="enviar_id">
                  <input type="hidden" name="empresa_id" id="empresa_id" value="<?=$empresa?>">
                  <input type="hidden" name="ruc_id" id="ruc_id" value="">
                  <h3 class="text-center rounded-circle" style="color: red; font-size: 30px"><i class="fa fa-check"></i></h3>
                  <hr>
                   <h3 class="text-center">¿Seguro desea enviar a SUNAT?</h3>
                </div>
              </div>



            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
              <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Enviar</button>
            </div>
             </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>
      <!-- /.modal -->