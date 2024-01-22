let formulaires = document.querySelectorAll('form');

formulaires.forEach(function(form){

    form.addEventListener('submit', function(event) {

        let newLoader = document.createElement("span");
        newLoader.classList.add('loader');

        let btn = form.querySelector('[type="submit"]');
        btn.appendChild(newLoader)
        btn.setAttribute('disabled','');
    })
   
});
