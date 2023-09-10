const humberger = document.querySelector(".humberger");
const navmenu = document.querySelector(".nav-menu");
const navbar = document.querySelector(".navbar");
const herobanner = document.querySelector(".hero-banner");
const navLinks = document.querySelectorAll(".nav-menu a"); // Select all links inside the navigation menu

humberger.addEventListener('click', () => {
    humberger.classList.toggle("active");
    navmenu.classList.toggle("active");
    navbar.classList.toggle("active");
    herobanner.style.zIndex = -1;
});

navLinks.forEach(link =>{
    link.addEventListener('click',()=>{
        humberger.classList.remove("active");
        navmenu.classList.remove("active");
        navbar.classList.remove("active");
        herobanner.style.zIndex='';
    });
});


/*photo lightbox*/
const lightbox = document.createElement('div');
lightbox.id = 'lightbox';
document.body.appendChild(lightbox);

const images = document.querySelectorAll('img');
images.forEach( image => {
    image.addEventListener('click', e => {
        lightbox.classList.add('active');
        const img = document.createElement('img');
        img.src = image.src
        while(lightbox.firstChild){
            lightbox.removeChild(lightbox.firstChild);
        }
        lightbox.appendChild(img);
    })
})

lightbox.addEventListener('click', e => {
    if(e.target !== e.currentTarget) return
    lightbox.classList.remove('active');
})


/*active animation*/
function activeAnimation(){
    var reveals = document.querySelectorAll(".reveal");
    for(var i=0; i< reveals.length; i++){
        var windowHight = window.innerHeight;
        var elementTop = reveals[i].getBoundingClientRect().top;
        var elementVisible = 150;

        if(elementTop < windowHight - elementVisible){
            reveals[i].classList.add("active");
        }else{
            reveals[i].classList.remove("active");
        }
    }
}

window.addEventListener("scroll", activeAnimation);

