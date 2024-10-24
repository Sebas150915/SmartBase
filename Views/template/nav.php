<nav class="navbar navbar-expand-lg navbar-light bg-white flex-row border-bottom shadow">
        <div class="container-fluid">
          <a class="navbar-brand mx-lg-1 mr-0" href="<?=base_url()?>">
            <strong class="text-bold"><?=logo()?></strong>
          </a>
          <button class="navbar-toggler mt-2 mr-auto toggle-sidebar text-muted">
            <i class="fe fe-menu navbar-toggler-icon"></i>
          </button>
          <div class="navbar-slide bg-white ml-lg-4" id="navbarSupportedContent">
            <a href="#" class="btn toggle-sidebar d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
              <i class="fe fe-x"><span class="sr-only"></span></i>
            </a>
            <ul class="navbar-nav mr-auto">
                <!----maestros--
              <li class="nav-item dropdown">
                <a href="#" id="dashboardDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="ml-lg-2"><i class="fe fe-home"></i> Maestros</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="dashboardDropdown">
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/categorias"><span class="ml-1">Categorias</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/marcas"><span class="ml-1">Marcas</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/productos"><span class="ml-1">Productos</span></a>
                  <hr>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/series"><span class="ml-1">Series</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/usuarios"><span class="ml-1">Usuarios</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/vendedor"><span class="ml-1">Vendedores</span></a>
                  <hr>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/almacenes"><span class="ml-1">Almacenes</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/contribuyente"><span class="ml-1">Contribuyentes</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/medio_pago"><span class="ml-1">Medio de Pago</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/tipo_pago"><span class="ml-1">Tipo Pago</span></a>
                  
                  <?php if($_SESSION['ruc'] == '10441689166'){ ?>
                    <a class="nav-link pl-lg-2" href="<?=base_url()?>/clientes"><span class="ml-1">Clientes</span></a>
                  <?php } ?>
                </div>
              </li>-->
              <!--maestros 2-->
               <li class="nav-item dropdown">
                <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe fe-home"></i> Maestros </a>
                <ul class="dropdown-menu" aria-labelledby="appsDropdown">
                 <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Articulos</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/categorias"><span class="ml-1">Categorias</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/marcas"><span class="ml-1">Marcas</span></a>
                      
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/productos"><span class="ml-1">Productos</span></a>
                     
                    </ul>
                  </li>
                  <!--FACTURACION--->
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Facturación</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/series"><span class="ml-1">Series</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/usuarios"><span class="ml-1">Usuarios</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/vendedor"><span class="ml-1">Vendedor</span></a>
                       <a class="nav-link pl-lg-2" href="<?=base_url()?>/contribuyente"><span class="ml-1">Contribuyentes</span></a>
                      
                    </ul>
                  </li>
                  <!--COMPRAS--->
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Compras</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/almacenes"><span class="ml-1">Almacenes</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/concepto_gasto"><span class="ml-1">Concepto de Gasto</span></a>
                      
                      
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Generales</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      
                    
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/medio_pago"><span class="ml-1">Medios de Pago</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/tipo_pago"><span class="ml-1">Tipo Pago</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/centro_costos"><span class="ml-1">Centro de Costos</span></a>
                       <?php if($_SESSION['ruc'] == '10441689166'){ ?>
                    <a class="nav-link pl-lg-2" href="<?=base_url()?>/clientes"><span class="ml-1">Clientes</span></a>
                    <a class="nav-link pl-lg-2" href="<?=base_url()?>/menu"><span class="ml-1">Menu</span></a>
                  <?php } ?>
                    </ul>
                  </li>
                  <?php if($_SESSION['ruc'] == '10441689166'){ ?>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Alquileres</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/locales"><span class="ml-1">Locales</span></a>
                   
                    </ul>
                  </li>
                  <?php } ?>
                  
                  
                 </ul>
              </li>
              <!--modulos-->
              <li class="nav-item dropdown">
                <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe fe-book"></i> Modulos </a>
                <ul class="dropdown-menu" aria-labelledby="appsDropdown">
                 <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Ventas</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/cotizacion"><span class="ml-1">Cotizacion/Pedidos</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/ventas"><span class="ml-1">Ventas</span></a>
                      
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/nota_venta"><span class="ml-1">Nota de Venta</span></a>
                       <a class="nav-link pl-lg-2" href="<?=base_url()?>/pos"><span class="ml-1">POS VENTA</span>
                       </a>
                      <a class="#" href="<?=base_url()?>/cuentas_por_cobrar"><span class="ml-1">Cuentas x Cobrar</span></a>
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Facturación</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/resumen_cpe"><span class="ml-1">Resumen de Boletas</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/baja_boletas"><span class="ml-1">Baja Boletas</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/baja_facturas"><span class="ml-1">Baja Facturas</span></a>
                      
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Compras</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/compras"><span class="ml-1">Compras</span></a>
                    
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/cuentas_por_pagar"><span class="ml-1">Cuentas x Pagar</span></a>
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="profileDropdown" aria-expanded="false">
                      <span class="ml-1">Almacen</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                      <a class="nav-link pl-lg-2" href="#"><span class="ml-1">Guias de Ingresos</span></a>
                      <a class="nav-link pl-lg-2" href="#"><span class="ml-1">Guias de Salidas</span></a>

                      
                    </ul>
                  </li>

                  <!--modulo para clinicas
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="profileDropdown" aria-expanded="false">
                      <span class="ml-1">Progr. Diaria</span>
                       <span class="badge badge-pill badge-danger">Nuevo</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/citas_especialidad"><span class="ml-1">Especialidad</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/citas"><span class="ml-1">Citas</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/citas_calendario"><span class="ml-1">Calendario</span></a>
                                          
                    </ul>
                  </li>-->


                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Guia de Remision</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/vehiculo"><span class="ml-1"><i class="fe fe-truck"></i> Vehiculo</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/chofer"><span class="ml-1"><i class="fe fe-user"></i> Chofer</span></a>

                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/transportista"><span class="ml-1"><i class="fe fe-users"></i> Transportista</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/gre"><span class="ml-1">GRE</span></a>
                       
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link pl-lg-2" href="<?=base_url()?>/contabilidad"><span class="ml-1">Contabilidad</span></a>
                    
                  </li>
                  
                </ul>
              </li>
              <!-- modulo SIRE-->

              <li class="nav-item dropdown">
                <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe fe-book"></i> Sire-SUNAT </a>
                <ul class="dropdown-menu" aria-labelledby="appsDropdown">
                 <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Ventas</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/sire_ventas"><span class="ml-1">Importa</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/comparar_ventas"><span class="ml-1">Comparar</span></a>
                      
                    </ul>
                    
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Compras</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/sire_compras"><span class="ml-1">Importa</span></a>
                    
                      
                    </ul>
                  </li>
                  
                  
                </ul>
              </li>

              <!--FIN  SIRE-->
              


              <li class="nav-item dropdown">
                <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe fe-file-minus"></i> Reportes </a>
                <ul class="dropdown-menu" aria-labelledby="appsDropdown">
                 <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Ventas</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_ventas_sunat"><span class="ml-1">Ventas Sunat</span></a>
                      <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_ventas_diarias"><span class="ml-1">Venta Resumen</span></a>
                      <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_ventas_detalle"><span class="ml-1">Ventas Detalle</span></a>

                       <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_detalle_pedido"><span class="ml-1">Detalle Pedido</span></a>
                       <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_detalle_anticipo"><span class="ml-1">Detalle Anticipo</span></a>

                       <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_ventas_vendedor"><span class="ml-1">Ventas Vendedor</span></a>
                       <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_ventas_documento"><span class="ml-1">Ventas por Doc.</span></a>
                       <a class="nav-link pl-lg-2" data-toggle="modal" data-target="#modalReporteZ" href="#"><span class="ml-1">Reporte Z</span></a>
                       <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_productos"><span class="ml-1">Ventas por Procutos</span></a>
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Compras</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_compras_sunat"><span class="ml-1">Compras</span></a>
                    
                      
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="profileDropdown" aria-expanded="false">
                      <span class="ml-1">Almacen</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                       <a class="nav-link pl-lg-2" href="<?=base_url()?>/rpt_ingresos"><span class="ml-1">Ingresos</span></a>
                       <a class="nav-link pl-lg-2" href="<?=base_url()?>/rpt_salidas"><span class="ml-1">Salidas</span></a>
                       <a class="nav-link pl-lg-2" href="<?=base_url()?>/rpt_kardex_unidades"><span class="ml-1">Kardex Unidades</span></a>
                       <a class="nav-link pl-lg-2" href="<?=base_url()?>/rpt_kardex_valorizado"><span class="ml-1">Kardex Valorizado</span></a>
                      
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="fileDropdown" aria-expanded="false">
                      <span class="ml-1">Contabilidad</span>
                      <span class="badge badge-pill badge-danger">Nuevo</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="fileDropdown">
                      <a class="nav-link pl-lg-2" href="#"><span class="ml-1">Plan de Cuentas</span></a>
                      <a class="nav-link pl-lg-2" href="#"><span class="ml-1">Clase Cuenta</span></a>
                      <a class="nav-link pl-lg-2" href="#"><span class="ml-1">Voucher</span></a>
                    </ul>
                  </li>
                  
                </ul>
              </li>
             
              
              <li class="nav-item dropdown">
                <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i> Utilitarios </a>
                <ul class="dropdown-menu" aria-labelledby="appsDropdown">
                 <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Datos Generales</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/perfil"><span class="ml-1">Parametros</span></a>
                      
                      
                    </ul>
                    
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Carga Masiva</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" data-toggle="modal" data-target="#modalImportaVenta" href="#"><span class="ml-1">Excel Ventas</span></a>
                    <a class="nav-link pl-lg-2" data-toggle="modal" data-target="#modalImportaCompra" href="#"><span class="ml-1">Excel Compras</span></a>
                      
                    </ul>
                    
                  </li>
                  
                  
                </ul>
              </li>

             

              
              
              
            </ul>
          </div>
          <form class="form-inline ml-md-auto d-none d-lg-flex text-muted mr-3">
            <!--poner algo-->
          </form>
          <ul class="navbar-nav d-flex flex-row">
            <li class="nav-item">
              <a class="nav-link text-muted my-2" href="./#" id="modeSwitcher" data-mode="dark">
                <i class="fe fe-sun fe-16"></i>
              </a>
            </li>
            
            
            <li class="nav-item dropdown ml-lg-0">
              <a class="nav-link dropdown-toggle text-muted" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="avatar avatar-sm mt-2">
                  <img src="<?=media()?>/images/avatars/face.png" alt="..." class="avatar-img rounded-circle">
                  
                </span>
                <strong class="text-bold text-danger"><?=$_SESSION["nombre"]?></strong>
              </a>
              <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <li class="nav-item">
                  <a class="nav-link pl-3"  href="#"><?= $_SESSION["ruc"]; ?></a>
                </li>
                <hr>
                <li class="nav-item">
                  <a class="nav-link pl-3"  href="#"><?=$row_empresas['razon_social'] ?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="<?=base_url()?>/cerrar">Cerrar Sesion</a>
                </li>
                
                
              </ul>
            </li>
          </ul>
        </div>
      </nav>

<!-- Modal exporta excel ventas-->
<div class="modal fade" id="modalImportaVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form method="POST" name="cargarVenta" id="cargarVenta" enctype="multipart/form-data">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">Cargar Ventas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group mb-3">
          <label for="customFile">Buscar archivo Excel</label>
          <div class="custom-file">
            <input type="hidden" name="action" value="cargar_excel_venta">
            <input type="hidden" name="empresa" value="<?=$_SESSION['id_empresa']?>">
            <input type="file" class="custom-file-input" accept=".xls,.xlsx" name="dataVentas" id="dataVentas">
            <label class="custom-file-label" for="customFile">Cargar archivo excel</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Cerrar</button>
        <button type="submit" class="btn btn-success text-white"><i class="fa-solid fa-upload"></i> Cargar Ventas</button>
      </div>
    </div>
  </div>
  </form>
</div> 


<!-- Modal exporta excel compras -->
<div class="modal fade" id="modalImportaCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form method="POST" name="cargarCompra" id="cargarCompra" enctype="multipart/form-data">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">Cargar Compras</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group mb-3">
          <label for="customFile">Buscar archivo Excel</label>
          <div class="custom-file">
            <input type="hidden" name="action" value="cargar_excel_compra">
            <input type="hidden" name="empresa" value="<?=$_SESSION['id_empresa']?>">
            <input type="file" class="custom-file-input" accept=".xls,.xlsx" name="dataVentas" id="dataVentas">
            <label class="custom-file-label" for="customFile">Cargar archivo excel</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Cerrar</button>
        <button type="submit" class="btn btn-success text-white"><i class="fa-solid fa-upload"></i> Cargar Compras</button>
      </div>
    </div>
  </div>
  </form>
</div>




<?php
$empresa = $_SESSION["id_empresa"];

$query_usuario = "SELECT * FROM tbl_usuarios WHERE id_empresa = $empresa";
$resultado_usuario=$connect->prepare($query_usuario);
$resultado_usuario->execute(); 
$num_reg_usuario=$resultado_usuario->rowCount();

?>

 