<?php
session_start();
//error_reporting(0);

if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "cinema")
{
      $rutas = array();
/*=============================================
    CONTENIDO
    =============================================*/

    if(isset($_GET["ruta"]))
    {
       $rutas = explode("/",$_GET["ruta"]);

              if($rutas[0] == "inicio" ||
                 $rutas[0] == "cerrar" ||
                 $rutas[0] == "cerrar" ||

                 $rutas[0] == "citas" ||
                 $rutas[0] == "citas_calendario" ||
                 $rutas[0] == "citas_especialidad" ||
                 
                 $rutas[0] == "contribuyente" ||
                 $rutas[0] == "destinos" ||
                 $rutas[0] == "cotizacion" ||
                 $rutas[0] == "nota_pedido" ||
                 $rutas[0] == "nueva_cotizacion" ||
                 $rutas[0] == "editar_cotizacion" ||
                 $rutas[0] == "almacenes" ||
                 $rutas[0] == "categorias" ||
                 $rutas[0] == "marcas" ||
                 $rutas[0] == "series" ||
                 $rutas[0] == "insumos" ||
                 $rutas[0] == "productos" ||
                 $rutas[0] == "productos_ventas" ||
                 $rutas[0] == "recetas" ||
                 $rutas[0] == "serie_usuario" ||
                 $rutas[0] == "medio_pago" ||
                 $rutas[0] == "serie_usuario" ||

                 $rutas[0] == "empresa" ||
                 $rutas[0] == "perfil" ||
              
                 $rutas[0] == "compras" ||
                 $rutas[0] == "nueva_compra" ||
                 $rutas[0] == "editar_compra" ||
                 $rutas[0] == "nueva_nota_credito_compra" ||
                
                 $rutas[0] == "ventas" ||
                 $rutas[0] == "sire_ventas" ||
                 $rutas[0] == "nota_venta" ||
                 $rutas[0] == "nueva_venta" ||
                 $rutas[0] == "nueva_nota_venta" ||
                 $rutas[0] == "nueva_nota_pedido" ||
                 $rutas[0] == "pos" ||

                 $rutas[0] == "gre" ||
                 $rutas[0] == "gre_pdf" ||
                 $rutas[0] == "gre_pdf2" ||
                 $rutas[0] == "nueva_guia" ||
                 $rutas[0] == "vehiculo" ||
                 $rutas[0] == "transportista" ||
                 $rutas[0] == "chofer" ||


                 $rutas[0] == "nueva_compra" ||
                 $rutas[0] == "nueva_nota_credito" ||
                 $rutas[0] == "nueva_nota_debito" ||
                 $rutas[0] == "cuentas_por_cobrar" ||
                 $rutas[0] == "detalle_cobranza" ||
                 $rutas[0] == "detalle_cobros" ||
                 $rutas[0] == "cuentas_por_pagar" ||
                 $rutas[0] == "ticket_factura" ||
                 $rutas[0] == "ticket_cotizacion" ||
                 $rutas[0] == "ticket_factura_pdf" ||
                 $rutas[0] == "ticket_factura_compra" ||
                 $rutas[0] == "factura_pdf" ||
                 $rutas[0] == "factura_pdf2" ||
                 $rutas[0] == "factura_pdf3" ||
                 $rutas[0] == "cotizacion_pdf" ||
                 $rutas[0] == "factura_pdf_compra" ||

                 $rutas[0] == "rpt_ventas_sunat" ||
                 $rutas[0] == "rpt_compras_sunat" ||
                 $rutas[0] == "rpt_ventas_sunat_pdf" ||
                 $rutas[0] == "rpt_compras_sunat_pdf" ||
                 $rutas[0] == "rpt_ventas_diarias" ||
                 $rutas[0] == "rpt_ventas_diarias_pdf" ||
                 $rutas[0] == "rpt_ingresos" ||
                 $rutas[0] == "rpt_salidas" ||
                 $rutas[0] == "rpt_ventas_detalle" ||
                 $rutas[0] == "rpt_kardex_unidades" ||
                 $rutas[0] == "rpt_kardex_valorizado" ||
                 $rutas[0] == "rpt_ventas_vendedor" ||
                 $rutas[0] == "rpt_ventas_documento" ||
                 $rutas[0] == "reportez" ||
                 $rutas[0] == "rpt_productos" ||
                 $rutas[0] == "rpt_productos_pdf" ||
                 $rutas[0] == "rpt_detalle_pedido" ||
                 $rutas[0] == "rpt_detalle_anticipo" ||

                 $rutas[0] == "resumen_cpe" ||
                 $rutas[0] == "baja_boletas" ||
                 $rutas[0] == "baja_facturas" ||


                 $rutas[0] == "ws_siscont" ||
                 $rutas[0] == "ws_siscont_compras" ||
                 $rutas[0] == "contabilidad" ||
                 $rutas[0] == "plan_contable" ||
                 $rutas[0] == "centro_costo" ||
                 $rutas[0] == "tipo_cambio" ||
                 $rutas[0] == "movimiento" ||
                 $rutas[0] == "tipo_documento" ||
                 $rutas[0] == "tabla_bancos" ||
                 $rutas[0] == "cuenta_asiento" ||
                 $rutas[0] == "nueva_compra_farmacia" ||
                 $rutas[0] == "clientes" ||
                 $rutas[0] == "guia" ||
                 $rutas[0] == "editar_nota_venta"||
                 $rutas[0] == "tipo_pago"||

                 $rutas[0] == "concepto_gasto"||
                 $rutas[0] == "centro_costos"||

                 $rutas[0] == "sire_ventas" ||
                 $rutas[0] == "comparar_ventas" ||
                 $rutas[0] == "vendedor" ||
                 
                 $rutas[0] == "locales" ||
                 $rutas[0] == "contratos" ||
                 $rutas[0] == "garantias" ||
                 $rutas[0] == "separaciones" ||
                 $rutas[0] == "contratos_pdf" ||

                 $rutas[0] == "ticket1" ||
                 $rutas[0] == "usuarios")
              {

                include "modules/".$rutas[0].".php";

              }
              else
              {

                include "modules/404.php";

              }

    }
     else
    {
        include "modules/inicio.php";
    }

}
else
{
        include "modules/login3.php";
}


 ?>
