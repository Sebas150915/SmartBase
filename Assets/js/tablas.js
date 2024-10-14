 $('#datatable-pedidos').DataTable(
                {
                  
                  "scrollX": false,
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,
                  "order": [[ 0, "desc" ]],
              //"ordering": false,
               language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });
   var tablacontribuentes=$('#datatable-contribuyente').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,
                  

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });  
   
   
   
   
   $('#datatable-responsive').DataTable(
                {
                  "scrollY":        "300px",
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 10,

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });

        $('#datatable-producto').DataTable(
                {
                  
                  "scrollX": true,
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 8,
                  "order": [[ 3, "desc" ]],
                  //"ordering": false,

                      dom: "Blfrtip",
                buttons: [
                    
                    
                    {
                        extend: "excel",
                        title: 'REPORTE PRODUCTOS',
                        titleAttr: 'Exportar a Excel',
                        className: "btn-sm btn-dark"
                    },
                   
                    
                ],
                

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });

        $('#table-compras').DataTable(
          {
            "scrollY":        "300px",
            "scrollCollapse": true,
            "paging":         true,
            "pageLength": 10,

         language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
          });

         
          

          $('#datatable-insumos').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });

            $('#datatable-insumos2').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });
       
                 $('#datatable-ventas').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollX": true,
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 8,
                  "order": [[ 3, "desc" ]],

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                
                 dom: "Blfrtip",
                buttons: [
                    {
                        extend: "copy",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "csv",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "excel",
                        title: 'REPORTE DE VENTAS SUNAT',
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "pdfHtml5",
                        className: "btn-sm btn-dark",
                        orientation: 'landscape',
                        title: 'REPORTE DE VENTAS SUNAT',
                        titleAttr: 'Exportar a PDF',
                        
                       
                    },
                    {
                        extend: "print",
                        className: "btn-sm btn-dark"
                    },
                   
                ],
                
                
                
                
          });
             
             

                $('#datatable-rptvta').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollX": true,
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 16,
                  "ordering": false,
                  "order": [[ 3, "desc" ]],

                  dom: "Blfrtip",
                buttons: [
                    {
                        extend: "copy",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "csv",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "excel",
                        title: 'REPORTE DE VENTAS SUNAT',
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "pdfHtml5",
                        className: "btn-sm btn-dark",
                        orientation: 'landscape',
                        title: 'REPORTE DE VENTAS SUNAT',
                        titleAttr: 'Exportar a PDF',
                        
                       
                    },
                    {
                        extend: "print",
                        className: "btn-sm btn-dark"
                    },
                   
                ],

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });



                $('#datatable-rptvtad').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollX": true,
                  "scrollCollapse": true,
                  "paging":         false,
                  //"pageLength": 8,
                  "ordering": false,
                 "order": [[ 2, "asc" ]],

                  dom: "Blfrtip",
                buttons: [
                    {
                        extend: "copy",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "csv",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "excel",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "pdfHtml5",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "print",
                        className: "btn-sm btn-dark"
                    },
                ],

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });

                  $('#datatable-rptvtad1').DataTable(
                {
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,


                  dom: "Blfrtip",
                buttons: [
                    {
                        extend: "copy",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "csv",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "excel",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "pdfHtml5",
                        className: "btn-sm btn-dark"
                    },
                    {
                        extend: "print",
                        className: "btn-sm btn-dark"
                    },
                ],

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });

            $('#datatable-insumos1').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });
          
          
          
          $('#datatable-contribuyente1').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });
          
           $('#datatable-contribuyentex').DataTable(
                {
                  //"scrollY":        "200px",
                  "scrollCollapse": true,
                  "paging":         true,
                  "pageLength": 5,

                     language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": 
                    {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
          });
             

