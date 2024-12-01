<?php
$query_nivel = "SELECT * FROM tbl_alq_nivel WHERE estado='1' AND id_empresa=$_SESSION[id_empresa]";
$resultado_nivel=$connect->prepare($query_nivel);
$resultado_nivel->execute(); 
$num_reg_nivel=$resultado_nivel->rowCount();
?>
<!-- Modal -->
<div class="modal fade" id="ModalCategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <form action="" name="form_add_local" id="form_add_local">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title" id="exampleModalLongTitle" style="color:white">Nuevo Local</h5>
        <button type="button" class="close" style="color: white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="row">
          <div class="col-sm-6">
              <label for="">Código Local</label>
              <input type="hidden" name="action" value="addLocal">
              <input type="hidden" name="empresa" value="<?=$empresa?>">
            <input type="text" name="local" id="local" class="form-control text-right" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>

          <div class="col-sm-6">
          <label for="">Ubicación</label>
          <input type="text" class="form-control text-right"  name="ubicacion" id="ubicacion">
       </div>
       
          
       <div class="col-sm-6">
                      <label for="">Nivel</label>
               <select class="form-control select2" style="width: 100%;" name="nivel" id="nivel" required>
                          
            <option value="">Seleccionar Nivel</option>
            <?php 
                    while($row_nivel = $resultado_nivel->fetch(PDO::FETCH_ASSOC) )
               {?>
                <option value="<?= $row_nivel['id'] ?>"><?=$row_nivel['nombre']?></option>;
               <?php  } ?>
          </select>
                  </div>

         <div class="col-sm-6">
            <label for="">Meses Garantia</label>
            <input type="text" class="form-control text-right"  name="meses_garantia" id="meses_garantia" onkeyup="calcula_garantia_soles();">
         </div>


      </div>

      <div class="row">

       <div class="col-sm-6">
       <label for="">Escala</label>
            <select name="escala" id="escala" class="form-control select2" >
              <option value="5" selected >5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
            </select>
       </div>

          <div class="col-sm-6">
          <label for="">Metrado</label>
          <input type="text" class="form-control text-right" name="metrado" id="metrado" onkeyup="calcula_metrado();">
        </div>

       <div class="col-sm-6">
          <label for="">Precio Metrado</label>
          <input type="text" class="form-control text-right"  name="precio_metrado" id="precio_metrado" onkeyup="calcula_metrado();">
       </div>

       <div class="col-sm-6">
          <label for="">Tipo de Cambio</label>
          <input type="text" class="form-control text-right" name="tc" id="tc" onkeyup="calcula_soles();">
       </div>

       <div class="col-sm-6">
          <label for="">Importe Soles</label>
             <input type="text" class="form-control text-right" name="importe_soles" id="importe_soles" readonly="" onkeyup="calcula_garantia_soles();">
        </div>

        <div class="col-sm-6">
          <label for="">Importe Dólares</label>
          <input type="text" class="form-control text-right"  name="importe_dolar" id="importe_dolar" readonly="" onkeyup="calcula_garantia_dolar();">
       </div>

       <div class="col-sm-6">
          <label for="">Importe Garantia Soles</label>
          <input type="text" class="form-control text-right"  name="importe_garantia_soles" id="importe_garantia_soles" readonly="" onkeyup="calcula_garantia_soles();">
       </div>

       <div class="col-sm-6">
          <label for="">Importe Garantia Dólares</label>
          <input type="text" class="form-control text-right"  name="importe_garantia_dolar" id="importe_garantia_dolar" readonly="" onkeyup="calcula_garantia_dolar();">
       </div>

      </div>

      <div class="row">
            <div class="col-sm-12">
              <label for="">Observaciones</label>
              <textarea name="obs" id="obs" cols="10" rows="3" class="form-control"></textarea>
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
<form action="" name="form_edi_local" id="form_edi_local">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="exampleModalLabel" style="color:white">Editar Local</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        

      <div class="row">
          <div class="col-sm-6">
              <label for="">Nombre Local</label>
            <input type="hidden" name="update_id" id="update_id" value="">
            <input type="hidden" name="action" value="ediLocal">
            <input type="text"  name="update_nombre" id="update_nombre" class="form-control" required="" onkeyup="javascript:this.value=this.value.toUpperCase();">
          </div>
          
        <div class="col-sm-6">
         <label for="">Importe Soles</label>
            <input type="text" class="form-control text-right" name="update_importe" id="update_importe">
       </div>
          
      </div>
      <hr>
      <div class="row mt-3">
        <div class="col-sm-6">
          <label for="">Area Local (M²)</label>
          <input type="text" class="form-control text-right"  name="update_area" id="update_area">
        </div>
        <div class="col-sm-6">
          <label for="">Ubicacion</label>
          <input type="text" class="form-control text-right" name="update_ubi" id="update_ubi">
       </div>

       <div class="col-sm-6">
       <label for="">Escala</label>
            <select name="update_escala" id="update_escala" class="form-control select2" >
              <option value="5" selected >5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
            </select>
       </div>

       <div class="col-sm-6">
          <label for="">Nivel</label>
          <input type="text" class="form-control text-right"  name="update_nivel" id="update_nivel">
       </div>


       <div class="col-sm-6">
          <label for="">Precio M² - Dólar</label>
          <input type="text" class="form-control text-right"  name="update_precio_m2" id="update_precio_m2" onchange="multiplicar();">
       </div>

       <div class="col-sm-6">
          <label for="">Tipo de Cambio</label>
          <input type="text" class="form-control text-right"  name="update_tc" id="update_tc" onchange="multiplicar();" >
       </div>


      </div>
      
    <div class="modal-footer mt-3">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Cerrar</button>
        <button type="submit" class="btn btn-success"><i class="fas fa-redo"></i> Actualizar</button>
        
    </div> 
    </div>
  </div>
</div>
   </form>
</div>


<!-- Modal delete-->
<div class="modal fade" id="ModalCategoriaDlete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" name="form_del_local" id="form_del_local">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="exampleModalLabel" style="color: white">Activar / Desactivar Local</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row">
          <div class="col-sm-12">
            
            <input type="hidden" name="delete_id" id="delete_id" value="">
            <input type="hidden" name="action" value="delLocal">
          <h5 class="text-center">¿Esta seguro que desea realizar el proceso?</h5>
          </div>
          <div class="col-sm-12">
              <select name="estado" id="estado" class="form-control">
                  <option value="0">Disponible</option>
                  <option value="1">Separado</option>
                  <option value="2">Alquilado</option>
                  <option value="3">Uso Propio</option>
              </select>
          </div>
      </div>
      
    <div class="modal-footer mt-3">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Actualizar Estado</button>
        
    </div>
 
 
    </div>
  </div>
</div>
  </form>
</div>
