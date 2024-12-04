$(document).ready(function(){
    //##################################CREAR##################################//
        $('#form_destino').submit(function(e)
        {
            e.preventDefault();
            $.ajax({
                url  :  base_url+'/assets/ajax/ajax_destino.php',
                type : "POST",
                async: true,
                data : $('#form_destino').serialize(),
    
                success: function(response)
                {
                    var iddestino =$('#contribuyente').val();
                    
                 console.log(response);
                 $('#ModalDestino').modal('hide');
                 buscardestino2(iddestino);
                },
                error: function(response)
                {
                 $('#error').modal('show'); 
                }
    
            });
        });
    });


function buscardestino2(id)
    {
        var id = id;
        var destinos = $('#pllegada').val();
        var action   = 'buscar_destino';
  
        $.ajax({
  
              url  :  base_url+'/assets/ajax/ajax_gre.php',
                  type : 'POST',
                  async: true,
                  data: {action:action,id:id},
  
                  success: function(response)
                                  
                      {
                          
                          console.log(response);
                           $("#pllegada").html(response);
  
                      },
                      error: function(error)
                      {
             console.log(error);
                      }
  
        });
    }




