const menuBtn = document.querySelector('.menu-btn');
let menuOpen = false;

const menuMobile = document.getElementById('navList');

menuBtn.addEventListener('click', () => {
if(!menuOpen) {
    menuBtn.classList.add('open');
    menuOpen = true;
    menuMobile.style.display = 'block';
} else {
    menuBtn.classList.remove('open');
    menuOpen = false;
    menuMobile.style.display = 'none';
}
}),

window.addEventListener('resize', () => {

    var width = window.innerWidth;
    if (width > 800) {
        menuMobile.style.display = 'flex';
    }
    if (width < 800 && menuOpen == true) {
        menuMobile.style.display = 'block';
    } 
    if (width < 800 && menuOpen == false) {
        menuMobile.style.display = 'none';
    }

});