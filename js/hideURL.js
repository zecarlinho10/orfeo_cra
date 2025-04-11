// Oculta la URL al ubicar el puntero sobre un enlace.
// Ref: http://jsfiddle.net/5KYLx/1/
$(document).ready(function() {
   $('.hideURL').each(function() {
       var address = $(this).attr('onclick');
       var element = $(this).contents().unwrap().wrap('<div/>').parent();
       element.data("hrefAddress", address).addClass("link");
       
       element.click(function() {               
           eval(element.data("hrefAddress"));
       });
   });
});
