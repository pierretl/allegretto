let burgerBtn = document.querySelector('.js-burger-btn');
let burgerMenu = document.querySelector('.js-burger-menu');
let navigation = document.querySelector('.js-nav');
let contenu = document.getElementById('contenu');
let hideClassMenu = 'hide-for-small-only';

burgerBtn.addEventListener('click', function() {

    burgerBtn.classList.toggle('v-rotation');
    contenu.classList.toggle('hide');
    navigation.classList.toggle('v-open-menu')

    if (burgerMenu.classList.contains(hideClassMenu)) {
        
        burgerMenu.classList.remove(hideClassMenu);
        burgerBtn.setAttribute('aria-expanded', true);

    } else {
        burgerMenu.classList.add(hideClassMenu);
        burgerBtn.setAttribute('aria-expanded', false);

    }

});