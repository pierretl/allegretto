let 
    liste = document.querySelector('.js_listeAFiltre'),
    listeSejours = liste.querySelectorAll('li'),
    familleUtilisateur = liste.dataset.famille;
    btnFiltrePerso = document.querySelector('.js_filtrePerso'),
    btnFiltreAutres = document.querySelector('.js_filtreAutres'),
    btnFiltreTous = document.querySelector('.js_filtreTous'),
    allBtn = [btnFiltrePerso,btnFiltreAutres,btnFiltreTous]
;

btnFiltrePerso.addEventListener('click', filtreLesSejours, false);
btnFiltrePerso.typeFiltre = 'perso';

btnFiltreAutres.addEventListener('click', filtreLesSejours, false);
btnFiltreAutres.typeFiltre = 'autres';

btnFiltreTous.addEventListener('click', filtreLesSejours, false);
btnFiltreTous.typeFiltre = 'tous';


function filtreLesSejours(evt) {
    allBtn.forEach(function(boutons) {
        boutons.classList.add(boutons.dataset.classOff);
        boutons.setAttribute("aria-pressed", false);
    });

    switch (evt.currentTarget.typeFiltre) {
        case 'autres':
            listeSejours.forEach(function(sejour) {
                sejour.classList.add('hide');
                if (sejour.dataset.famille != familleUtilisateur) {
                    sejour.classList.remove('hide');
                }
            });
            this.classList.remove(this.dataset.classOff);
            this.setAttribute("aria-pressed", true);
            break;
        case 'perso':
            listeSejours.forEach(function(sejour) {
                sejour.classList.add('hide');
                if (sejour.dataset.famille == familleUtilisateur) {
                    sejour.classList.remove('hide');
                }
            });
            this.classList.remove(this.dataset.classOff);
            this.setAttribute("aria-pressed", true);
            break;
        default:
            listeSejours.forEach((sejour) => sejour.classList.remove('hide'));
            this.classList.remove(this.dataset.classOff);
            this.setAttribute("aria-pressed", true);
            
    }
}
