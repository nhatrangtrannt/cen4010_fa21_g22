(function($) {
  "use strict"; // Start of use strict

  // Closes the sidebar menu
  $(".menu-toggle").click(function(e) {
    e.preventDefault();
    $("#sidebar-wrapper").toggleClass("active");
    $(".menu-toggle > .fa-bars, .menu-toggle > .fa-times").toggleClass("fa-bars fa-times");
    $(this).toggleClass("active");
  });

  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  $('#sidebar-wrapper .js-scroll-trigger').click(function() {
    $("#sidebar-wrapper").removeClass("active");
    $(".menu-toggle").removeClass("active");
    $(".menu-toggle > .fa-bars, .menu-toggle > .fa-times").toggleClass("fa-bars fa-times");
  });

  // Scroll to top button appear
  $(document).scroll(function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

})(jQuery); // End of use strict

// Disable Google Maps scrolling
// See http://stackoverflow.com/a/25904582/1607849
// Disable scroll zooming and bind back the click event
var onMapMouseleaveHandler = function(event) {
  var that = $(this);
  that.on('click', onMapClickHandler);
  that.off('mouseleave', onMapMouseleaveHandler);
  that.find('iframe').css("pointer-events", "none");
}
var onMapClickHandler = function(event) {
  var that = $(this);
  // Disable the click handler until the user leaves the map area
  that.off('click', onMapClickHandler);
  // Enable scrolling zoom
  that.find('iframe').css("pointer-events", "auto");
  // Handle the mouse leave event
  that.on('mouseleave', onMapMouseleaveHandler);
}
// Enable map zooming with mouse scroll when the user clicks the map
$('.map').on('click', onMapClickHandler);
function myMax()
{
  var a, b, c, temp;
  a = parseInt(document.getElementById("num1").value);
  b = parseInt(document.getElementById("num2").value);
  c = parseInt(document.getElementById("num3").value);
  temp = Math.max(a,b,c);
  console.log(temp);
  alert("The MAX of the three numbers is " +temp);
  document.getElementById("max").innerHTML = "The MAX is "+temp;
  return(temp);
}
function myMin()
{
    var a, b, c, temp;
    a = parseInt(document.getElementById("num1").value);
    b = parseInt(document.getElementById("num2").value);
    c = parseInt(document.getElementById("num3").value);
    temp = Math.min(a,b,c);
    console.log(temp);
    alert("The MIN of the three numbers is " +temp);
    document.getElementById("min").innerHTML = "The MIN is "+temp;
    return(temp);
}
function myMean()
{
    var a, b, c, temp;
    a = parseInt(document.getElementById("num1").value);
    b = parseInt(document.getElementById("num2").value);
    c = parseInt(document.getElementById("num3").value);
    temp = (a+b+c)/3;
    console.log(temp);
    alert("The MEAN of the three numbers is " +temp);
    document.getElementById("mean").innerHTML = "The MEAN is "+temp;
    return(temp);
}
function myMedian()
{
   var a, b, c, temp;
    a = parseInt(document.getElementById("num1").value);
    b = parseInt(document.getElementById("num2").value);
    c = parseInt(document.getElementById("num3").value);
   var ar = [a,b,c];
    ar.sort(function(a, b){return a-b;});
    console.log(ar[1]);
    alert("The MEDIAN of the three numbers is " +ar[1]);
    document.getElementById("median").innerHTML = "The MEDIAN is "+ar[1];
    return(ar[1]);
}
function myRange()
{
  var a, b, c, temp;
    a = parseInt(document.getElementById("num1").value);
    b = parseInt(document.getElementById("num2").value);
    c = parseInt(document.getElementById("num3").value);
   var ar = [a,b,c];
    ar.sort(function(a, b){return a-b;});
    temp = ar[2] - ar[0];
    console.log(temp);
    alert("The RANGE of the three numbers is " +temp);
    document.getElementById("range").innerHTML = "The RANGE is "+temp;
    return(temp);
}
function myCal()
{
    myMax();
    myMin();
    myMean();
    myMedian();
    myRange();
}