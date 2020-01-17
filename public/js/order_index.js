$(document).ready(function(){
  elements = $("#links").children().children().children();
  $(elements).each(function( index ) {
    $( this ).children().addClass( "page-link" );
  });
});