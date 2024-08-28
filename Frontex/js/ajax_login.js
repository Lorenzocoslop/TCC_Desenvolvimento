$(document).ready(function(){
    //Login
    $('body').on('click', "#btn_login",function(e){
      e.preventDefault();
      
      var form = $("#form_login");
      var form_data = form.serialize();
      var url = "../controllers/c-login.php";

      $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType: 'JSON',

        success: function(data,textStatus,jqXHR){
            console.log("Sucesso:", data);
            if (data['status'] == 'success') {
                $(".result").text('');
                $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                $("#form_login") [0].reset();   
            } else if(data['status'] == 'info'){
                $(".result").text('');
                $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                $("#form_login") [0].reset();   
            } else if(data['status'] == 'warning'){
                $(".result").text('');
                $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                $("#form_login") [0].reset();
            } else {
                $(".result").text('');
                $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                $("#form_login") [0].reset();
            } 

            window.location.href = data['redirect'];
            
        }, error: function(jqXHR, textStatus, errorThrown) {
            console.error("Erro na requisição AJAX:", textStatus, errorThrown);
            console.log("Resposta do servidor:", jqXHR.responseText);
        }
      });
    });


    //Logout
    $('body').on('click', "#logout",function(e){
        e.preventDefault();
        
        var form_data = $('a').attr('id');
        var data = 'action=logout';
        var url = "../controllers/c-logout.php";
  
        $.ajax({
          url: url,
          type: 'GET',
          data: data,
          dataType: 'JSON',
  
          success: function(data,textStatus,jqXHR){
              if (data['status'] == 'success') {
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#logout") [0].reset();   
              } else if(data['status'] == 'info'){
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#logout") [0].reset();   
              } else if(data['status'] == 'warning'){
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#logout") [0].reset();
              } else {
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#logout") [0].reset();
              } 
  
              setTimeout(function(){
                  $("#status-container").hide();
                      window.location.href = data['redirect'];
              }, 3000);
          }
        });
      });
      
    //Recuperação de Senha
    $('body').on('click', "#btn_password",function(e){
        e.preventDefault();
        
        var form = $("#form_password");
        var form_data = form.serialize();
        var url = "../controllers/c-recupera.php";
  
        $.ajax({
          url: url,
          type: 'POST',
          data: form_data,
          dataType: 'JSON',
  
          success: function(data,textStatus,jqXHR){
              if (data['status'] == 'success') {
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#form_password") [0].reset();   
              } else if(data['status'] == 'info'){
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#form_password") [0].reset();   
              } else if(data['status'] == 'warning'){
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#form_password") [0].reset();
              } else {
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#form_password") [0].reset();
              } 
  
              setTimeout(function(){
                  $("#status-container").hide();
                  if(data['redirect'] != ''){
                      window.location.href = data['redirect'];
                  }
              }, 3000);
          }
        });
      });

    //Nova Senha
    $('body').on('click', "#btn_new_password",function(e){
        e.preventDefault();
        
        var form = $("#form_new_password");
        var form_data = form.serialize;
        var url = "../controllers/c-nova-senha.php";
  
        $.ajax({
          url: url,
          type: 'POST',
          data: form_data,
          dataType: 'JSON',
  
          success: function(data,textStatus,jqXHR){
              if (data['status'] == 'success') {
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#form_login") [0].reset();   
              } else if(data['status'] == 'info'){
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#form_login") [0].reset();   
              } else if(data['status'] == 'warning'){
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#form_login") [0].reset();
              } else {
                  $(".result").text('');
                  $(".result").prepend('<div class="status-top-right text-center" id="status-container"><div class="status status-'+data['status']+'"><div class="status-message">'+data['message']+'</div></div></div>');
                  $("#form_login") [0].reset();
              } 
  
              setTimeout(function(){
                  $("#status-container").hide();
                  if(data['redirect'] != ''){
                      window.location.href = data['redirect'];
                  }
              }, 3000);
          }
        });
      });

});