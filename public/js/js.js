$(document).ready(function(){

  $('.nieuw_film_slider').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    dots: true,
    speed: 600
  });
  $('.afleverDatum').hide();
  $('.nee').click(function(){
    $('.afleverDatum').show("fast");
    $('.vraag').hide("fast");
  });
});
