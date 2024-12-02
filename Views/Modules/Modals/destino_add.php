<?php 
$contribuyente = $rutas[1];

$empresa = $_SESSION["id_empresa"];
$query_ubigeo = "SELECT * FROM tbl_ubigeo";
$resultado_ubigeo=$connect->prepare($query_ubigeo);
$resultado_ubigeo->execute(); 
$num_reg_ubigeo=$resultado_ubigeo->rowCount();

?>


<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form name="form_destino" id="form_destino">

    <div class="modal-content">


      <div class="modal-header bg-primary">
        <h4 class="modal-title" id="myModalLabel" style="color: white;">Agregar Direccion</h4>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="color: white;">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <input type="hidden" name="action"  value="nuevo_destino">
            <input type="hidden" name="empresa" value="<?=$empresa?>">
            <input type="hidden" name="contribuyente" id="contribuyente" value="<?=$contribuyente?>">
           <label for="">Direccion</label>
           <input type="text" class="form-control" name="destino" id="destino" required onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>      
          
          
        </div>
        <hr>  
        <div class="row"> 
        <div class="col-sm-12">
            <label for="">Ubigeo(distrito-provincia-departamento)*</label>
            <select name="ubigeo" id="ubigeo" class="from-control select2">
              <option value=" ">-SELECCIONAR-</option>
              <?php foreach($resultado_ubigeo as $ubigeo) { ?>
                <option value="<?=$ubigeo['codigo']?>"><?=strtoupper($ubigeo['distrito'].'-'.$ubigeo['provincia'].'-'.$ubigeo['departamento'])?></option>
              <?php   } ?>
            </select>
          </div>
        </div>
     
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
      </div>

    </div>
     </form>
  </div>
</div>



