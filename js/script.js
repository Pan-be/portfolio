const btnOpen = document.querySelector("#btnOpen");
const btnClose = document.querySelector("#btnClose");
const media = window.matchMedia('(width < 40em)');
const topNavMenu = document.querySelector('.header__menu')
const main = document.querySelector('main');

function setupTopNav(e) {
    if (e.matches) {
        //is mobile
        topNavMenu.setAttribute('inert', '');
        topNavMenu.style.transition = 'none';
    }
    else {
        //tablet/desktop
        closeMobileMenu();
        topNavMenu.removeAttribute('inert');
    }
}

function openMobileMenu() {
    btnOpen.setAttribute('aria-expanded', 'true');
    topNavMenu.removeAttribute('inert');
    topNavMenu.removeAttribute('style');
    main.setAttribute('inert', '');
    btnOpen.focus();

};
function closeMobileMenu() {
    btnOpen.setAttribute('aria-expanded', 'false');
    topNavMenu.setAttribute('inert', '');
    main.removeAttribute('inert');
    btnClose.focus();

    setTimeout(() => {
        topNavMenu.style.transition = 'none';
    }, 500)
};

setupTopNav(media)

btnOpen.addEventListener('click', openMobileMenu);
btnClose.addEventListener('click', closeMobileMenu);

media.addEventListener('change', function (e) {
    setupTopNav(e);
})