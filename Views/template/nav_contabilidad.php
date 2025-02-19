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
              <li class="nav-item dropdown">
                <a href="#" id="dashboardDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="ml-lg-2">Maestros</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="dashboardDropdown">
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/plan_contable"><span class="ml-1">Plan de Cuentas</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/centro_costo"><span class="ml-1">Centro de Costos</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/tipo_cambio"><span class="ml-1">Tipo de Cambio</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/movimiento"><span class="ml-1">Movimiento</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/tipo_documento"><span class="ml-1">Tipo Documento</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/tabla_bancos"><span class="ml-1">Tablas Bancos</span></a>
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/cuenta_asiento"><span class="ml-1">Cuentas de Asientos</span></a>
                  
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Modulos </a>
                <ul class="dropdown-menu" aria-labelledby="appsDropdown">
                 <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Ventas</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/cotizacion"><span class="ml-1">Cotizacion</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/ventas"><span class="ml-1">Ventas</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/resumen_cpe"><span class="ml-1">Resumen de Boletas</span></a>
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/nota_venta"><span class="ml-1">Nota de Venta</span></a>
                       <a class="nav-link pl-lg-2" href="<?=base_url()?>/pos"><span class="ml-1">POS</span>
                       <span class="badge badge-pill badge-danger">Nuevo</span></a>
                      <a class="#" href="#"><span class="ml-1">Cuentas x Cobrar</span></a>
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Compras</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?=base_url()?>/compras"><span class="ml-1">Compras</span></a>
                    
                      <a class="nav-link pl-lg-2" href="#"><span class="ml-1">Cuentas x Pagar</span></a>
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
                  <li class="nav-item dropdown">
                    <a class="nav-link pl-lg-2" href="<?=base_url()?>/contabilidad"><span class="ml-1">Contabilidad</span></a>
                    
                  </li>
                  
                </ul>
              </li>

              <li class="nav-item dropdown">
                <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Reportes </a>
                <ul class="dropdown-menu" aria-labelledby="appsDropdown">
                 <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="ml-1">Ventas</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                      <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_ventas_sunat"><span class="ml-1">Ventas Sunat</span></a>
                      <a class="nav-link pl-lg-2" href="<?= base_url()?>/rpt_ventas_diarias"><span class="ml-1">Venta Resumen</span></a>
                      <a class="nav-link pl-lg-2" href="#"><span class="ml-1">Ventas por Vendedor</span></a>
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
                <a href="#" id="dashboardDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="ml-lg-2">Configuracion</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="dashboardDropdown">
                  <a class="nav-link pl-lg-2" href="<?=base_url()?>/empresa"><span class="ml-1">Datos Generales</span></a>
                  
                </div>
              </li>

             

              
              
            </ul>
          </div>
          <form class="form-inline ml-md-auto d-none d-lg-flex text-muted">
            
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
                  <img src="<?=media()?>/images/avatars/face-1.jpg" alt="..." class="avatar-img rounded-circle">
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <li class="nav-item">
                  <a class="nav-link pl-3" href="<?=base_url()?>/cerrar">Cerrar Sesion</a>
                </li>
                
                
              </ul>
            </li>
          </ul>
        </div>
      </nav>



      <!-- <nav class="navbar navbar-expand-lg navbar-light bg-white flex-row border-bottom shadow">
  
    <div class="container-fluid">
  
  <a class="navbar-brand mx-lg-1 mr-0" href="./index.html">
    <svg version="1.1" id="logo" class="navbar-brand-img brand-sm" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 120 120" xml:space="preserve">
    <g>
      <polygon class="st0" points="78,105 15,105 24,87 87,87  "/>
      <polygon class="st0" points="96,69 33,69 42,51 105,51   "/>
      <polygon class="st0" points="78,33 15,33 24,15 87,15  "/>
    </g>
    </svg>
  </a>

  <button class="navbar-toggler mt-2 mr-auto toggle-sidebar text-muted">
    <i class="fe fe-menu navbar-toggler-icon"></i>
  </button>

  <div class="navbar-slide bg-white ml-lg-4" id="navbarSupportedContent">
    <a href="#" class="btn toggle-sidebar d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
      <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a href="#" id="dashboardDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="ml-lg-2">Dashboard</span><span class="sr-only">(current)</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="dashboardDropdown">
          <a class="nav-link pl-lg-2" href="./index.html"><span class="ml-1">Default</span></a>
          <a class="nav-link pl-lg-2" href="./dashboard-analytics.html"><span class="ml-1">Analytics</span></a>
          <a class="nav-link pl-lg-2" href="./dashboard-sales.html"><span class="ml-1">E-commerce</span></a>
          <a class="nav-link pl-lg-2" href="./dashboard-saas.html"><span class="ml-1">Saas Dashboard</span></a>
          <a class="nav-link pl-lg-2" href="./dashboard-system.html"><span class="ml-1">Systems</span></a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a href="#" id="ui-elementsDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="ml-lg-2">UI elements</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="ui-elementsDropdown">
          <a class="nav-link pl-lg-2" href="./ui-color.html"><span class="ml-1">Colors</span></a>
          <a class="nav-link pl-lg-2" href="./ui-typograpy.html"><span class="ml-1">Typograpy</span></a>
          <a class="nav-link pl-lg-2" href="./ui-icons.html"><span class="ml-1">Icons</span></a>
          <a class="nav-link pl-lg-2" href="./ui-buttons.html"><span class="ml-1">Buttons</span></a>
          <a class="nav-link pl-lg-2" href="./ui-notification.html"><span class="ml-1">Notifications</span></a>
          <a class="nav-link pl-lg-2" href="./ui-modals.html"><span class="ml-1">Modals</span></a>
          <a class="nav-link pl-lg-2" href="./ui-tabs-accordion.html"><span class="ml-1">Tabs & Accordion</span></a>
          <a class="nav-link pl-lg-2" href="./ui-progress.html"><span class="ml-1">Progress</span></a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="widgets.html">
          <span class="ml-lg-2">Widgets</span>
          <span class="badge badge-pill badge-primary">New</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="#" id="formsDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="ml-lg-2">Forms</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="formsDropdown">
          <a class="nav-link pl-lg-2" href="./form_elements.html"><span class="ml-1">Basic Elements</span></a>
          <a class="nav-link pl-lg-2" href="./form_advanced.html"><span class="ml-1">Advanced Elements</span></a>
          <a class="nav-link pl-lg-2" href="./form_validation.html"><span class="ml-1">Validation</span></a>
          <a class="nav-link pl-lg-2" href="./form_layouts.html"><span class="ml-1">Layouts</span></a>
          <a class="nav-link pl-lg-2" href="./form_upload.html"><span class="ml-1">File upload</span></a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a href="#" id="tablesDropdown" class="dropdown-toggle nav-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="ml-lg-2">Tables</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="tablesDropdown">
          <a class="nav-link pl-lg-2" href="./table_basic.html"><span class="ml-1">Basic Tables</span></a>
          <a class="nav-link pl-lg-2" href="./table_advanced.html"><span class="ml-1">Advanced Tables</span></a>
          <a class="nav-link pl-lg-2" href="./table_datatables.html"><span class="ml-1">Data Tables</span></a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="chartsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Charts
        </a>
        <ul class="dropdown-menu" aria-labelledby="chartsDropdown">
          <a class="nav-link pl-lg-2" href="./chart-inline.html"><span class="ml-1">Inline Chart</span></a>
          <a class="nav-link pl-lg-2" href="./chart-chartjs.html"><span class="ml-1">Chartjs</span></a>
          <a class="nav-link pl-lg-2" href="./chart-apexcharts.html"><span class="ml-1">ApexCharts</span></a>
          <a class="nav-link pl-lg-2" href="./datamaps.html"><span class="ml-1">Datamaps</span></a>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Apps
        </a>
        <ul class="dropdown-menu" aria-labelledby="appsDropdown">
          <li class="nav-item">
            <a class="nav-link pl-lg-2" href="./calendar.html">
              <span class="ml-1">Calendar</span>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="ml-1">Contacts</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="contactDropdown">
              <a class="nav-link pl-lg-2" href="./contacts-list.html"><span class="ml-1">Contact List</span></a>
              <a class="nav-link pl-lg-2" href="./contacts-grid.html"><span class="ml-1">Contact Grid</span></a>
              <a class="nav-link pl-lg-2" href="./contacts-new.html"><span class="ml-1">New Contact</span></a>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="profileDropdown" aria-expanded="false">
              <span class="ml-1">Profile</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
              <a class="nav-link pl-lg-2" href="./profile.html"><span class="ml-1">Overview</span></a>
              <a class="nav-link pl-lg-2" href="./profile-settings.html"><span class="ml-1">Settings</span></a>
              <a class="nav-link pl-lg-2" href="./profile-security.html"><span class="ml-1">Security</span></a>
              <a class="nav-link pl-lg-2" href="./profile-notification.html"><span class="ml-1">Notifications</span></a>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="fileDropdown" aria-expanded="false">
              <span class="ml-1">File Manager</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="fileDropdown">
              <a class="nav-link pl-lg-2" href="./files-list.html"><span class="ml-1">Files List</span></a>
              <a class="nav-link pl-lg-2" href="./files-grid.html"><span class="ml-1">Files Grid</span></a>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="supportDropdown" aria-expanded="false">
              <span class="ml-1">Help Desk</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="supportDropdown">
              <a class="nav-link pl-lg-2" href="./support-center.html"><span class="ml-1">Home</span></a>
              <a class="nav-link pl-lg-2" href="./support-tickets.html"><span class="ml-1">Tickets</span></a>
              <a class="nav-link pl-lg-2" href="./support-ticket-detail.html"><span class="ml-1">Ticket Detail</span></a>
              <a class="nav-link pl-lg-2" href="./support-faqs.html"><span class="ml-1">FAQs</span></a>
            </ul>
          </li>
        </ul>
      </li>
      <li class="nav-item dropdown more">
        <a class="dropdown-toggle more-horizontal nav-link" href="#" id="moreDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="ml-2 sr-only">More</span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="moreDropdown">
          <li class="nav-item dropdown">
            <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="pagesDropdown" aria-expanded="false">
              <span class="ml-1">Pages</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
              <a class="nav-link pl-lg-2" href="./page-orders.html">
                <span class="ml-1">Orders</span>
              </a>
              <a class="nav-link pl-lg-2" href="./page-timeline.html">
                <span class="ml-1">Timeline</span>
              </a>
              <a class="nav-link pl-lg-2" href="./page-invoice.html">
                <span class="ml-1">Invoice</span>
              </a>
              <a class="nav-link pl-lg-2" href="./page-404.html">
                <span class="ml-1">Page 404</span>
              </a>
              <a class="nav-link pl-lg-2" href="./page-500.html">
                <span class="ml-1">Page 500</span>
              </a>
              <a class="nav-link pl-lg-2" href="./page-blank.html">
                <span class="ml-1">Blank</span>
              </a>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="authDropdown" aria-expanded="false">
              <span class="ml-1">Authentication</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="authDropdown">
              <a class="nav-link pl-lg-2" href="./auth-login.html"><span class="ml-1">Login 1</span></a>
              <a class="nav-link pl-lg-2" href="./auth-login-half.html"><span class="ml-1">Login 2</span></a>
              <a class="nav-link pl-lg-2" href="./auth-register.html"><span class="ml-1">Register</span></a>
              <a class="nav-link pl-lg-2" href="./auth-resetpw.html"><span class="ml-1">Reset Password</span></a>
              <a class="nav-link pl-lg-2" href="./auth-confirm.html"><span class="ml-1">Confirm Password</span></a>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="dropdown-toggle nav-link pl-lg-2" href="#" data-toggle="collapse" id="layoutsDropdown" aria-expanded="false">
              <span class="ml-1">Layouts</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="layoutsDropdown">
                <a class="nav-link pl-lg-2" href="./index.html"><span class="ml-1">Default</span></a>
                
                  <a class="nav-link pl-lg-2" href="./index-horizontal.html"><span class="ml-1">Top Navigation</span></a>
                
                
                <a class="nav-link pl-lg-2" href="./index-boxed.html"><span class="ml-1">Boxed</span></a>
                
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </div>
  <form class="form-inline ml-md-auto d-none d-lg-flex searchform text-muted">
    <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search" placeholder="Type something..." aria-label="Search">
  </form>
  <ul class="navbar-nav d-flex flex-row">
    <li class="nav-item">
      <a class="nav-link text-muted my-2" href="./#" id="modeSwitcher" data-mode="dark">
          <i class="fe fe-sun fe-16"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-shortcut">
          <i class="fe fe-grid fe-16"></i>
      </a>
    </li>
    <li class="nav-item nav-notif">
      <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-notif">
        <i class="fe fe-bell fe-16"></i>
        <span class="dot dot-md bg-success"></span>
      </a>
    </li>
    <li class="nav-item dropdown ml-lg-0">
      <a class="nav-link dropdown-toggle text-muted" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="avatar avatar-sm mt-2">
          <img src="./assets/avatars/face-1.jpg" alt="..." class="avatar-img rounded-circle">
        </span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
        <li class="nav-item">
          <a class="nav-link pl-3" href="#">Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link pl-3" href="#">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link pl-3" href="#">Activities</a>
        </li>
      </ul>
    </li>
  </ul>
  </div>
</nav>
 -->