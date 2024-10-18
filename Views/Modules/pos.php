<?php 

$empresa = $_SESSION["id_empresa"];
$hoy = date('Y-m-d');


$query_documento = "SELECT * FROM tbl_tipo_documento WHERE fe='1' AND id in ('01','03') ORDER BY id DESC";
$resultado_documento=$connect->prepare($query_documento);
$resultado_documento->execute(); 
$num_reg_documento=$resultado_documento->rowCount();
/*tipo doc por defecto*/
$tdocdef = '03';
$iduser = $_SESSION['id'];
$query_def = "SELECT * FROM vw_tbl_serie_usuario WHERE empresa=$empresa AND usuario = $iduser  AND cod_doc = $tdocdef";
$resultado_def = $connect->prepare($query_def);
$resultado_def->execute();
$row_def = $resultado_def->fetch(PDO::FETCH_ASSOC);
//var_dump($row_def);
/*fin*/


$lista1=$connect->query("SELECT * FROM tbl_contribuyente WHERE empresa= $empresa");
$resultado1=$lista1->fetchAll(PDO::FETCH_OBJ);


$query_forma = "SELECT * FROM tbl_forma_pago ORDER BY tipo";
$resultado_forma=$connect->prepare($query_forma);
$resultado_forma->execute(); 
$num_reg_forma=$resultado_forma->rowCount();

$query_tipo = "SELECT * FROM tbl_tipo_pago WHERE empresa=$empresa ";
$resultado_tipo=$connect->prepare($query_tipo);
$resultado_tipo->execute(); 
$num_reg_tipo=$resultado_tipo->rowCount();


$query_data = "SELECT 
p.id as id,
p.nombre as nombre,
p.precio_venta as precio_venta,
p.precio2 as precio2,
p.afectacion as afectacion,
p.costo as costo,
p.stock as stock,
p.factor as factor,
m.nombre as marca,
p.estado as estado,
p.imagen as imagen,
p.empresa as empresa
from tbl_productos as p LEFT JOIN tbl_marcas as m 
on p.marca = m.id WHERE p.estado='1' AND p.empresa =  $_SESSION[id_empresa]";
$resultado_data=$connect->prepare($query_data);
$resultado_data->execute();
$num_reg_data=$resultado_data->rowCount();


$lista2=$connect->query("SELECT * FROM vw_tbl_coti_cab WHERE empresa= $empresa AND tipocomp in ('CT','NP')");
$resultado2=$lista2->fetchAll(PDO::FETCH_OBJ);



$query_vendedor = "SELECT * FROM tbl_vendedor WHERE idempresa = $empresa";
$resultado_vendedor=$connect->prepare($query_vendedor);
$resultado_vendedor->execute(); 
$num_reg_vendedor=$resultado_vendedor->rowCount();
?>
<!doctype html>
<html lang="en">
  <head>
       <?php include 'views/template/head.php' ?>

       <style>
         .table-bordered th, .table-bordered td {
                      border: 1px solid #dce4ec;
                        border-left-width: 1px;
                    }
          .table-bordered {
                border: 1px solid #d3dbe3;
                  border-right-width: 1px;
              }
        .vertical-scrollable>.row
         {
            position: absolute;
            top: 120px;
            bottom: 100px;
            left: 180px;
            width: 50%;
            overflow-y: scroll;
        }    

        .tableFixHead   { overflow: auto; height: 250px; }
        .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

        /* Just common table stuff. Really. */
        table  { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 16px; }
        
        .este {
  height: 652px;
  line-height: 1em;
  overflow-x: hidden;
  overflow-y: scroll;
  width: 100%;
  border: 1px solid;
  margin: 3px;
  border-radius: 10px;
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
      <form id="venta_nueva_pos" name="venta_nueva_pos">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <!--lado izquierdo-->
            <div class="col-sm-6">

             <div class="col-12 mt-2">
             <div class="row border border-danger rounded mb-3 mr-1">
              <div class="row mt-2" style="padding:10px">
                <div class="col">
                  <label for="">Codigo de Barras</label>
                  <input type="hidden" id="detalles" name="detalles" value="0">
                  <input type="text" class="form-control" name="cbarraspos" id="cbarraspos" onkeyup="listarproductospos()">
                </div>
                
              </div>
             </div>
             </div>
             <div class="col-12 mt-2">
             
            <div class="row border border-danger rounded mr-1" style="min-height: 100vh;">

            
            <div id="resultados" class="row p-3 este mr-1">
            <!-- Aquí se mostrarán los resultados de la búsqueda -->
            </div>
             </div>
             </div>


            

          </div>
            <!-- /.col -->

              <!--lado derecho-->
          
          
          
          
           <div class="col-sm-6">  
              <div class="border border-danger rounded" style=" padding: 10px; ">

                <div class="row">
                  <div class="col-lg-6">
                        <label for="">Tipo Doc:</label>
                         <select class="form-control" style="width: 100%;" name="tip_cpe" id="tip_cpe" required>
                  
                           <?php 
                                  while($row_documento = $resultado_documento->fetch(PDO::FETCH_ASSOC) )
                             {?>
                              <option value="<?= $row_documento['cod'].'-'.$row_documento['id'] ?>"><?=$row_documento['nombre']?></option>;
                             <?php  } ?>
                        </select>
                      </div>
                
                
                       <input type="hidden" name="ven" value="0">
                                                
                     
                  <div class="col-lg-3">


                        <label for="">Serie:</label>
                        <input type="text" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="4" readonly name="serie" id="serie" value="<?=$row_def['serie']?>">
                      </div>
                      <div class="col-lg-3">
                        <label for="">Numero:</label>
                        <input type="text" class="form-control" readonly name="numero" id="numero" value="<?=$row_def['numero']?>">
                        <input type="hidden" id="vendedor" name="vendedor" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" id="empresa" name="empresa" value="<?= $_SESSION['id_empresa']?>">
                      </div>
                  
                  
                </div>
                        
                <div class="row">
                  <div class="col-lg-3">
                    <input type="hidden" class="form-control" name="orden_compra" id="orden_compra" value="">
                    <input type="hidden" class="form-control" name="condicion" id="condicion" value="1">
                    <label for="">Fec. Emision</label>
                    <input type="date" class="form-control" value="<?=$hoy?>" name="fecha_emision" id="fecha_emision" readonly>
                    <input type="hidden" class="form-control" value="<?=$hoy?>" name="fecha_vencimiento" id="fecha_vencimiento" readonly>
                  </div>
                  <div class="col-lg-4">
                     <label for="">Cliente</label>
                         <div class="input-group">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger go-class" data-toggle="modal" data-target="#ModalClientes"><i class="fe fe-search"></i></button>
                        </span>
                          <input type="hidden" id="id_ruc" name="id_ruc" value="">
                          <input type="hidden" name="action" value="nueva_venta_pos">
                          <input type="text" class="form-control" name="ruc_persona" id="ruc_persona" maxlength="11" required>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" onclick="cliente2()"><i class="fe fe-search"></i></button>
                        </span>
                        </div>
                  </div>
                  <div class="col-lg-5">

                    <label for="">Razon Social</label>
                    <input type="text" class="form-control" name="razon_social" id="razon_social" readonly>
                  </div>
                 
                  <div class="col-lg-6">
                    <label for="">Cotizacion</label>
                    <div class="input-group">
                    <input name="id_coti_ref" id="id_coti_ref" type="hidden">
                    <input name="cod_doc_ref" id="cod_doc_ref" type="hidden">
                   
                    
                    
                    <input type="text" class="form-control" name="tip_ref" id="tip_ref" >
                    <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" onclick="documentrefpedido()"><i class="fe fe-search"></i></button>
                    </span>
                        </div>
                  </div>
                   <div class="col-lg-3">
                    <label for="">Serie</label>
                    <input type="hidden" class="form-control" name="razon_direccion" id="razon_direccion" readonly>
                    <input name="serie_ref" id="serie_ref" type="text" class="form-control">
                  </div>
                  <div class="col-lg-3">
                    <label for="">Numero</label>
                    
                    <input name="num_ref" id="num_ref" type="text" class="form-control">
                  </div>

                  <hr>

                  
                </div>
                  
                  <div class="row mt-3">
                    <div class="col-lg-12">

                   <div class="">
                    <table id="tabla"  class="table table-bordered table-striped table-hover table-condensed " width="100%">
                  <thead class="bg-dark">
                    <tr>
                      <th></th>
                      <th width="3%">#</th>
                      <th>Producto</th>
                      <th width="20%" style="visibility:collapse; display:none;">Cant.</th>
                      <th width="20%">Unidad</th>
                      <th width="20%">Precio</th>
                      <th width="15%">Total</th>
                      
                    </tr>
                  </thead>
                  <tbody id="detalleventa">
                    
                  </tbody>
                  </table>
                  </div>

                  <hr>
                    <table   class="table table-bordered table-striped table-hover table-condensed" width="100%">
                      <thead class="bg-dark">

                        <tr>
                      
                      <th width="70%">Op. Gravadas</th>
                      <td colspan="2"><input type="text" class="form-control text-right input-sm" name="op_g" id="op_g" value="0.00" readonly></td>
                    </tr>
                    <tr>
                    
                      <th width="70%">Op. Exonerada</th>
                      <td colspan="2"><input type="text" class="form-control text-right input-sm" name="op_e" id="op_e" value="0.00" readonly></td>
                    </tr>
                    <tr>
                      
                      <th width="70%">Op. Inafecta</th>
                      <td colspan="2"><input type="text" class="form-control text-right input-sm" name="op_i" id="op_i" value="0.00" readonly></td>
                    </tr>
                    <tr>
                     
                      <th width="70%">I.G.V.</th>
                      <td colspan="2"><input type="text" class="form-control text-right input-sm" name="igv" id="igv" value="0.00" readonly></td>
                    </tr>
                    <tr>
                     
                      <th width="70%">Redondeo</th>
                      <td colspan="2"><input type="text" class="form-control text-right input-sm" name="redondeo" id="redondeo" value="0.00" readonly></td>
                    </tr>
                    <tr>
                    
                      <th width="70%">Total</th>
                       <td colspan="2"><input type="text" class="form-control text-right input-sm" name="total" id="total" value="0.00" readonly></td>
                    </tr>
                      
                    </thead>
                    
                  </table>
                  </div>
                  </div>

                
                  <div class="row">
                    
                         <div class="col-lg-6">
                          <button id="btnPagar" class="btn btn-warning btn-lg btn-block" type="button" data-toggle="modal" data-target="#addPago"><i class="fe fe-usd"></i> Pagos</button>
                           
                        </div>
                          <div class="col-lg-6">                                                
                          <a href="<?=base_url()?>/ventas" class="btn btn-danger btn-lg btn-block" type="button"><i class="fe fe-x-square"></i> Cancelar Venta</a>
                          </div>

                          <div class="col-lg-12 mt-3">
                          <button class="btn btn-success btn-lg btn-block" type="button" id="btnGuardar"><i class="fe fe-save"></i> Guardar</button>
                         </div>
                  </div>
               
                  
                </div>
            </div>
          </div> <!-- .row -->

        
        </div> <!-- .container-fluid -->
          <div class="modal fade" id="addPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Pagos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <label for="">Monto a Pagar</label>
           <input type="text" name="montopago" id="montopago" value="" readonly class="form-control border-danger text-center text-bold" style="color: red; background-color: #96EC94; font-weight: bold; font-size: 20px;">
          </div>
           </div>
          <div class="row">
            <div class="col-sm-12">
              <label for="">Efectivo</label>
              <input type="text" name="efectivo" onkeyup="pagos()" id="efectivo" value="0.00" class="form-control text-right">
            </div>
            
          </div>
          <div class="row">
            <div class="col-sm-6">
              <label for="">Otro Medio de Pago</label>
              <select name="cvisa" id="cvisa" class="form-control">
                
               
                <?php 
                        while($row_tipo = $resultado_tipo->fetch(PDO::FETCH_ASSOC) )
                   {?>
                <option value="<?= $row_tipo['id'] ?>"><?=$row_tipo['nombre']?></option>;
                   <?php  } ?>
              </select>

            </div>
            <div class="col-sm-6">
              <label for="">Importe</label>
               <input type="text" name="visa" onkeyup="pagos()"  id="visa" value="0.00" class="form-control text-right"> 
            </div>

          </div>
          <div class="row">
            <div class="col-sm-6">
              <label for="">Saldo</label>
            <input type="text" value="0.00" name="saldo" id="saldo" readonly class="form-control border-danger text-center text-bold" style="color: red; background-color: #96EC94; font-weight: bold; font-size: 20px;">
            </div>
            <div class="col-sm-6">
              <label for="">Vuelto</label>
            <input type="text" value="0.00" name="vuelto" id="vuelto" readonly class="form-control border-danger text-center text-bold" style="color: red; background-color: #96EC94; font-weight: bold; font-size: 20px;">
            </div>           
            
          </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-save"></i> Guardar Pago</button>
       
      </div>
    </div>
  </div>
</div>
        </form>
      </main> <!-- main -->
    </div> <!-- .wrapper -->

          <!-- /fin modal pagos -->
       <?php include 'views/modules/modals/persona.php' ?> 
       <?php include 'views/modules/modals/buscar_doc_ref_pedidp.php' ?> 
     <?php include 'views/modules/modals/buscar_contribuyente_pos.php' ?> 
 
    <?php include 'views/template/pie.php' ?>


      <script src="<?=media()?>/js/funciones_ventas.js?v=<?=date('s')?>"></script>
   

      <script src="<?=media()?>/js/sunat_reniec.js"></script>
<script type="text/javascript">
    listarproductospos();
</script>
  </body>
</html>