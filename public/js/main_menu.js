/** main_menu.js
@author Ishtehar Hussain
@desc The animation for the drop-down menus
**/

$(document).ready(function(){

  $("#reportLabel").hover(function(){
    $("#theMenu2").hide();
    $("#theMenu3").hide();
    $("#theMenu").slideDown(500);});

  $("#infoLabel").hover(function(){
    $("#theMenu").hide();
    $("#theMenu3").hide();
    $("#theMenu2").slideDown(500);});

    $("#actionLabel").hover(function(){
      $("#theMenu").hide();
      $("#theMenu2").hide();
      $("#theMenu3").slideDown(500);});

    $("#navMain").hover(function(){$(".subMenu").slideUp();});


    $("#theMenu").hover(function(){
      $("#theMenu").show();
      //$("#reportLabel").addClass("dark-border-bottom");
    },
      function(){
        $("#theMenu").slideUp();
        //$("#reportLabel").removeClass("dark-border-bottom");
      });

    $("#theMenu2").hover(function(){
      $("#theMenu2").show();
      //$("#infoLabel").addClass("dark-border-bottom");
    },
      function(){
        $("#theMenu2").slideUp();
        //$("#infoLabel").removeClass("dark-border-bottom");
      });

    $("#theMenu3").hover(function(){
      $("#theMenu3").show();
      //$("#actionLabel").addClass("dark-border-bottom");
    },
    function(){
      $("#theMenu3").slideUp();
      //$("#actionLabel").removeClass("dark-border-bottom");
    });
});
