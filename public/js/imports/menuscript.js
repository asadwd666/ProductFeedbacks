$(document).ready(function(){
var headerHeight = $('header.header').outerHeight();
$('body').css('padding-top', headerHeight)
$('#searchicon').on('click', function() {
  $('#topsearches').slideToggle().css('top', headerHeight);
  if ($(this).hasClass('fa-search')){
        $(this).removeClass('fa-search').addClass('fa-close text-danger');     
     }
     else {
      $(this).addClass('fa-search').removeClass('fa-close text-danger');   
    }
})
$('.navbar .dropdown-toggle').append('<em class="fa fa-angle-down"></em>');
})  
// if ($(window).width() < 768){
//         $('.backlink a').removeClass('menuarrow').addClass('mobarrow'); 
//         $('.sidebar').addClass('sidebarin');

// $(document).on('click', '.mobarrow', function(){

//           if ($(this).hasClass('actarrow')){
//             $(this).removeClass('actarrow');     
//             $('.sidebar').animate({width: '70px'}, 500).addClass('sidebarin');
//             // setTimeout(function(){ $('.sidebar').addClass('sidebarin'); }, 100);
//             $('.sidebarbg').animate({width: '70px'}, 500);
//           } 
//           else {
//             $(this).addClass('actarrow');
//             $('.sidebar').animate({width: '230px'}, 500);
//             setTimeout(function(){ $('.sidebar').removeClass('sidebarin'); }, 200);
//             $('.sidebarbg').animate({width: '230px'}, 500);
//           }
//         })    
//    }

$('.menuarrow').on('click', function() {
  
   if ($(this).hasClass('actarrow') && $(window).width() <  992){
        $(this).removeClass('actarrow');     
        $('.sidebar').animate({width: '190px'}, 500);
        setTimeout(function(){ $('.sidebar').removeClass('sidebarin'); }, 400);
        $('.sidebarbg').animate({width: '190px'}, 500);
        $('.logo').animate({width: '190px'}, 500).removeClass('logoin');
    }
    else if ($(this).hasClass('actarrow') && $(window).width() <  1199){
        $(this).removeClass('actarrow');     
        $('.sidebar').animate({width: '230px'}, 500)
        setTimeout(function(){ $('.sidebar').removeClass('sidebarin'); }, 400);
        $('.sidebarbg').animate({width: '230px'}, 500);
        $('.logo').animate({width: '230px'}, 500).removeClass('logoin');
    }
    else if ($(this).hasClass('actarrow')){
        $(this).removeClass('actarrow');     
        $('.sidebar').animate({width: '290px'}, 500);
        setTimeout(function(){ $('.sidebar').removeClass('sidebarin'); }, 400);
        $('.sidebarbg').animate({width: '290px'}, 500);
        $('.logo').animate({width: '290px'}, 500).removeClass('logoin');
    }
     else {
        $(this).addClass('actarrow');
        $('.sidebar').animate({width: '80px'}, 1000).addClass('sidebarin');
        $('.sidebarbg').animate({width: '80px'}, 1000);
        $('.logo').animate({width: '80px'}, 1000).addClass('logoin');
     }
})  


if($(window).width() <= 560 ) {
  $('.title-col').siblings('div').addClass('mt-2');
  $('.col-md-8.col-7.title-col').siblings('.col-md-4.col-5').removeClass('mt-2');
}
else {
  $('.title-col').siblings('div').removeClass('mt-2');
}
