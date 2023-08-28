/*Create new veriable*/
const humberger = document.querySelector(".humberger");
const navmenu = document.querySelector(".nav-menu");
const navbar = document.querySelector(".navbar");
const herobanner = document.querySelector(".hero-banner");

humberger.addEventListener('click',()=>{
    humberger.classList.toggle("active");
    navmenu.classList.toggle("active");
    navbar.classList.toggle("active");
    herobanner.style.zIndex = -1;
});
