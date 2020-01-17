$(document).ready(function(){
  $("#order_form").on('submit', function(event){
    send_form(event, "order/store", $("#order_form"), $("#order_submit"))
  });
});

function send_form(event, route, form, buttonsubmit){
  event.preventDefault();
  var disabled;

  splitUrl = $(location).attr("href").split("/");
  splitUrl[splitUrl.length-1] = route;
  url = splitUrl.join("/")

  $.ajax({
    type: "POST",
    url: url,
    data: form.serialize(),
    success: function(response){
      // Inhabilita el botón
      buttonsubmit.attr("disabled", true);
      disabled = setTimeout(function(){
        buttonsubmit.attr("disabled", false);
      }, 5000);

      // Padre de las partes importantes del modal
      padre = $("#Modal").children().children().children()

      // Cambiar el estilo y el mensaje dependiendo de la repsuesta recibida
      if(response.success === true){
        // Inhabilita el botón
        clearTimeout(disabled);
        buttonsubmit.attr("disabled", true);

        // Valida la clase del header y la actualiza
        if($(padre[0]).hasClass("alert-danger")){
          $(padre[0]).removeClass("alert-danger");
        }
        $(padre[0]).addClass("alert-success");

        show_modal_message(response.mensaje);

        // Crea la url a la que se enviara al usuario
        splitUrl[splitUrl.length-1] = "order/show/" + response.data.id;
        url = splitUrl.join("/")

        // Redirige al usuario a la nueva vista al cerrar el modal
        $('#Modal').on('hidden.bs.modal', function () {
          window.location.replace(url);
        });

        // Redirige al usuario en 4 segundos a la nueva vista si no ha cerrado el modal 
        setTimeout(function(){
          window.location.replace(url);
        }, 4000);
      }
      else{
        // Valida la clase del header y la actualiza
        if($(padre[0]).hasClass("alert-success")){
          $(padre[0]).removeClass("alert-success");
        }
        $(padre[0]).addClass("alert-danger");

        if(response.data !== null){
          show_modal_message(response.data);
        }
        else{
          show_modal_message(response.mensaje);
        }
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown){
      // Padre de las partes importantes del modal
      padre = $("#Modal").children().children().children()

      // Valida la clase del header y la actualiza
      if($(padre[0]).hasClass("alert-success")){
        $(padre[0]).removeClass("alert-success");
      }
      $(padre[0]).addClass("alert-danger");

      show_modal_message([textStatus]);
    }
  });
}

function show_modal_message(mensajes){
  cuerpo = $("#Modal").children().children().children()[1];

  // Inserta el texto que llevara el mensaje
  var html = "";
  if(Array.isArray(mensajes) || $.isPlainObject(mensajes)){
    jQuery.each(mensajes, function(i, value){
      if($.isArray(value)){
        html = html + "- " + value[0] + "<br>";
      }
      else{
        html = html + "- " + value + "<br>";
      }
    });
  }
  else{
    html = mensajes;
  }

  // Agrega el mensaje al cuerpo del modal
  $(cuerpo).html(html);

  // Muestra el modal
  $("#Modal").modal();
}