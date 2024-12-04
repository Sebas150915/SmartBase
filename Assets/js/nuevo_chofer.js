$(document).ready(function(){
    //##################################CREAR##################################//
        $('#form_add_chofer').submit(function(e)
        {
            e.preventDefault();
            $.ajax({
                url  :  base_url+'/assets/ajax/ajax_chofer.php',
                type : "POST",
                async: true,
                data : $('#form_add_chofer').serialize(),
    
                success: function(response)
                {
                 console.log(response);
                 $('#ModalChofer').modal('hide');
                 buscarchofer();
                },
                error: function(response)
                {
                 $('#error').modal('show'); 
                }
    
            });
        });
    });




function buscarchofer()
       {
           var chofer = $('#chofer').val();
           var action   = 'buscar_chofer';
        
            $.ajax({
        
                  url  :  base_url+'/assets/ajax/ajax_gre.php',
                      type : 'POST',
                      async: true,
                      data: {action:action},
        
                      success: function(response)
                                      
                          {
                              
                              console.log(response);
                               $("#chofer").html(response);
        
                          },
                          error: function(error)
                          {
                 console.log(error);
                          }
        
            });
        }

