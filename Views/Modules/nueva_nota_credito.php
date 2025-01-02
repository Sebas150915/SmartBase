<?php 

$empresa = $_SESSION["id_empresa"];
$hoy = date('Y-m-d');

$query_documento = "SELECT * FROM tbl_tipo_documento WHERE fe='1' AND id in ('07')";
$resultado_documento=$connect->prepare($query_documento);
$resultado_documento->execute(); 
$num_reg_documento=$resultado_documento->rowCount();

$query_nc = "SELECT * FROM tbl_not_cre_deb WHERE tipo='1'";
$resultado_nc=$connect->prepare($query_nc);
$resultado_nc->execute(); 
$num_reg_nc=$resultado_nc->rowCount();


$lista1=$connect->query("SELECT * FROM vw_tbl_venta_cab WHERE empresa= $empresa AND tipocomp in ('01','03')");
$resultado1=$lista1->fetchAll(PDO::FETCH_OBJ);


$query_forma = "SELECT * FROM tbl_forma_pago ORDER BY tipo";
$resultado_forma=$connect->prepare($query_forma);
$resultado_forma->execute(); 
$num_reg_forma=$resultado_forma->rowCount();

?>
<!doctype html>
<html lang="en">
  <head>
       <?php include 'views/template/head.php' ?>
       <script type="text/javascript">
function ShowSelected()
{
/* Para obtener el valor */
var cod = document.getElementById("motivo").value;
$("#motivo1").val(cod);

var porcion = cod.split('-');
if(porcion[0]=='01')
  {
    $("#btnListar").show();
    //$('#motivo').prop('disabled', 'disabled');
    $('#motivo').attr("disabled","true");
  }
  else
  {
    $("#btnListar").hide();
  }
/* Para obtener el texto 
var combo = document.getElementById("motivo");
var selected = combo.options[combo.selectedIndex].text;
alert(selected);*/
}
</script>

       <style>
         .table-bordered th, .table-bordered td {
                      border: 1px solid #dce4ec;
                        border-left-width: 1px;
                    }
          .table-bordered {
                border: 1px solid #d3dbe3;
                  border-right-width: 1px;
              }
                    
           </style> 
  </head>
  <body class="horizontal dark  ">
    <div class="wrapper">
      <?php
       if($_SESSION['perfil']=='1')
       {
       include 'views/template/nav.php';
       }
       else
       {
       include 'views/template/nav_ventas.php';
       } ?>
      
      <main role="main" class="main-content">
      <form id="venta_nueva_nc" name="venta_nueva_nc">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">

              <div class="row">
                <div class="col-sm-2">
                    <label for="">Tipo Doc:</label>
                     <select class="form-control select2" style="width: 100%;" name="tip_cpe" id="tip_cpe" required>
              
                      <option value="">Seleccionar Documento</option>
                      <?php 
                              while($row_documento = $resultado_documento->fetch(PDO::FETCH_ASSOC) )
                         {?>
                          <option value="<?= $row_documento['cod'].'-'.$row_documento['id'] ?>"><?=$row_documento['nombre']?></option>;
                         <?php  } ?>
                    </select>
                </div>
                <div class="col-sm-2">
                            <label for="">Serie:</label>
                            <input type="text" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="4" readonly name="serie" id="serie">
                          </div>
                          <div class="col-sm-2">
                            <label for="">Numero:</label>
                            <input type="text" class="form-control" readonly name="numero" id="numero">
                            <input type="hidden" id="vendedor" name="vendedor" value="<?= $_SESSION['id'] ?>">
                            <input type="hidden" id="empresa" name="empresa" value="<?= $_SESSION['id_empresa']?>">
                          </div>
                          
                          <div class="col-sm-2">
                        <label for="">Tipo Doc Ref.</label>
                         <div class="input-group">
                          <input type="hidden" name="id_venta_ref" id="id_venta_ref">
                          <input type="hidden" name="id_ruc" id="id_ruc">
                          <input type="hidden" id="cod_doc_ref" name="cod_doc_ref">
                          <input type="text" class="form-control" name="tip_ref" id="tip_ref" readonly>
                          <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" onclick="documentoref()"><i class="fe fe-search"></i></button>
                          </span>
                        </div>
                        </div>
                        <div class="col-sm-2">
                        <label for="">Serie Doc Ref.</label>
                          <input type="text" class="form-control" id="serie_ref" name="serie_ref" readonly>
                        </div>
                      <div class="col-sm-2">
                          <label for="">Num Doc Ref</label>
                            <input type="text" class="form-control" id="num_ref" name="num_ref" readonly>
                          </div>
              </div>

              <div class="row">
                      <div class="col-sm-2">
                        <label for="">Cliente</label>
                                              
                          <input type="hidden" name="action" value="nueva_nota_de_credito">
                          <input type="text" class="form-control" name="ruc_persona" id="ruc_persona" maxlength="11" required readonly>
                        
                      
                      </div>
                      <div class="col-sm-4">

                        <label for="">Razon Social</label>
                        <input type="text" class="form-control" name="razon_social" id="razon_social" readonly>
                      </div>
                      <div class="col-sm-4">
                        <label for="">Direccion</label>
                        <input type="text" class="form-control" name="razon_direccion" id="razon_direccion" readonly>
                      </div>
                      <div class="col-sm-2">
                        <label for="">Moneda</label>
                        <select name="moneda" id="moneda" class="form-control">
                          <option value="PEN">SOLES</option>
                          <option value="USD">DOLARES</option>
                        </select>
                      </div>
                    
                    </div>
                    <div class="row">
                          <div class="col-sm-2">
                            <label for="">Fecha Emision</label>
                            <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_emision" id="fecha_emision">
                          </div>
                          <div class="col-sm-2">
                            <label for="">Condicion</label>
                            <select class="form-control select2" style="width: 100%;" name="condicion" id="condicion">
                      
                                
                                <?php 
                                        while($row_forma = $resultado_forma->fetch(PDO::FETCH_ASSOC) )
                                   {?>
                                    <option value="<?= $row_forma['tipo'] ?>"><?=$row_forma['nombre_fdp']?></option>;
                                   <?php  } ?>
                              </select>
                          </div>
                          <div class="col-sm-2">
                            <label for="">Fecha Vencimiento</label>
                            <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_vencimiento" id="fecha_vencimiento">
                          </div>
                          <div class="col-sm-4">
                            <label for="">Motivo :</label>
                             <select class="form-control select2" style="width: 100%;" name="motivo" id="motivo" required onchange="ShowSelected();">
                      
                              <option selected="selected">Seleccionar Motivo</option>
                              <?php 
                                      while($row_nc = $resultado_nc->fetch(PDO::FETCH_ASSOC) )
                                 {?>
                                  <option value="<?= $row_nc['codigo'].'-'.$row_nc['descripcion']?>"><?=$row_nc['descripcion']?></option>;
                                 <?php  } ?>
                            </select>
                            <input type="hidden" id="motivo1" name="motivo1">
                          </div>

                          <div class="col-sm-2">
                        <label for="">Exportacion</label>
                        <select class="form-control select2" name="exportacion" id="exportacion" >
                          <option value="NO">NO</option>
                          <option value="SI">SI</option>
                        </select>
                      </div>
                          
                          
                          
                    </div>



                 <hr>
                      
                    


                    <div class="clearfix">
                      <div class="row mt-3">
                        <div class="col-sm-6">
                          <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addProdcuto"><i class="fe fe-plus-circle"></i> Agregar Producto</button>
                          <button class="btn btn-success" type="submit" id="btnGuardarNC"><i class="fe fe-save"></i> Guardar</button>
                          
                          <a href="<?=base_url()?>/ventas" class="btn btn-danger" type="button"><i class="fe fe-close"></i> Cancelar</a>
                          <button type="button" class="btn btn-secondary btnListar" id="btnListar"><i class="fe fe-table"></i> Listar</button>
                        </div>
                       
                      </div>
                    </div>
                    <hr>

                    <div class="row">
                     <div class="col-xs-12 col-sm-12 col-md-12" style="overflow: auto; position: relative;border: 0px; width: 100%; ">
                    <table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
                  <thead class="bg-dark" style="color:white">
                    <tr>
                      <th width="10%">Acciones</th>
                      <th width="5%">Item</th>
                      <th>Producto</th>
                      <th width="10%">Cant.</th>
                      <th width="10%">Uni.</th>
                      <th width="10%">Precio</th>
                      <th width="10%">Total</th>
                      
                    </tr>
                  </thead>
                    <tbody id="detalleventa">
                      
                    </tbody>
                   <tfoot>
                    <tr>
                      <td colspan="4"></td>
                      <td>Op. Gravadas</td>
                      <td>S/.</td>
                      <td><input type="text" class="form-control text-right" name="op_g" id="op_g" value="0.00" readonly></td>
                    </tr>
                     <tr>
                      <td colspan="4"></td>
                      <td>Op. Exonerada</td>
                      <td>S/.</td>
                      <td><input type="text" class="form-control text-right" name="op_e" id="op_e" value="0.00" readonly></td>
                    </tr>
                     <tr>
                      <td colspan="4"></td>
                      <td>Op. Inafecta</td>
                      <td>S/.</td>
                      <td><input type="text" class="form-control text-right" name="op_i" id="op_i" value="0.00" readonly></td>
                    </tr>
                     <tr>
                      <td colspan="4"></td>
                      <td>I.G.V.</td>
                      <td>S/.</td>
                      <td><input type="text" class="form-control text-right" name="igv" id="igv" value="0.00" readonly></td>
                    </tr>
                     <tr>
                      <td colspan="4"></td>
                      <td>Total</td>
                      <td>S/.</td>
                      <td><input type="text" class="form-control text-right" name="total" id="total" value="0.00" readonly></td>
                    </tr>
                  </tfoot>
                </table>
                   </div>
                  </div>



                                  
              
              
             
            </div> <!-- /.col -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
          


        </form>
      </main> <!-- main -->
    </div> <!-- .wrapper -->

          <!-- /fin modal pagos -->
         
      <?php include 'views/modules/modals/buscar_doc_ref.php' ?>
      <?php include 'views/modules/modals/articulo_venta.php' ?>
    <?php include 'views/template/pie.php' ?>

  

      <script src="<?=media()?>/js/funciones_ventas.js"></script>
   

      <script src="<?=media()?>/js/sunat_reniec.js"></script>

  </body>
</html>