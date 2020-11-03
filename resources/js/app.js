require('./bootstrap');


window.onscroll = function () { scrollFunction() };

function scrollFunction() {
  if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 100) {
    document.getElementById("navbar").style.padding = "30px 10px";
    document.getElementById("navbar-brand").style.fontSize = "25px";
  } else {
    document.getElementById("navbar").style.padding = "80px 10px";
    document.getElementById("navbar-brand").style.fontSize = "35px";
  }
}