$(document).ready(function(){
    //##################################CREAR##################################//
        $('#form_add_vehiculo').submit(function(e)
        {
            e.preventDefault();
            $.ajax({
                url  :  base_url+'/assets/ajax/ajax_vehiculo.php',
                type : "POST",
                async: true,
                data : $('#form_add_vehiculo').serialize(),
    
                success: function(response)
                {
                 console.log(response);
                 $('#ModalVehiculo').modal('hide');
                 buscarvehiculo();
                },
                error: function(response)
                {
                 $('#error').modal('show'); 
                }
    
            });
        });
    });




function buscarvehiculo()
       {
           var vehiculo = $('#vehiculo').val();
           var action   = 'buscar_vehiculo';
        
            $.ajax({
        
                  url  :  base_url+'/assets/ajax/ajax_gre.php',
                      type : 'POST',
                      async: true,
                      data: {action:action},
        
                      success: function(response)
                                      
                          {
                              
                              console.log(response);
                               $("#vehiculo").html(response);
        
                          },
                          error: function(error)
                          {
                 console.log(error);
                          }
        
            });
        }


