(function($) {
    

	"use strict";

	var fullHeight = function() {
		   $('.js-fullheight').css('height', $(window).height());
		   $(window).resize(function(){
		   $('.js-fullheight').css('height', $(window).height());
		});

	};
	fullHeight();

    $('#sidebarCollapse').on('mouseover', function () {
	    
      $('#sidebar').toggleClass('activeS');
    });
    
 /*
	$('#listado2,#listado3,#listado4,#listado5').on('mouseover', function () {
	    
      $('#sidebar').toggleClass('active');
    }); */


})(jQuery);



var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}

