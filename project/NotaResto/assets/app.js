/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// You can specify which plugins you need
import {Tooltip, Toast, Popover} from 'bootstrap';

// start the Stimulus application
import './bootstrap';

const formVideo = document.querySelector('#form_video');
const list_opinion = document.querySelector("#list-opinion")

formVideo.addEventListener('submit', function (e) {
    e.preventDefault();

    fetch(this.action, {
        body: new FormData(e.target), method: 'POST'
    })
        .then(response => response.json())
        .then(json => {
            list_opinion.innerHTML = json.html + list_opinion.innerHTML;
            loop();
        });

});

window.onload = () => {
    //on va chercher les etoiles
    let stars = document.querySelectorAll('.bi-star');

    //on va chercher l'input
    let note = document.querySelector('#opinion_note');

    //on boucle sur les etoiles pour ajouter ecouteurs event
    for (let star of stars) {
        //on ecoute le survol
        star.addEventListener("mouseover", function () {
            resetStars();

            //on enleve l'ancienne class
            this.classList.remove("bi-star");

            //on inject la nouvelle class
            this.classList.add("bi-star-fill");

            //element precedent (baslise soeur) dans le Dom
            let previousStar = this.previousElementSibling;

            while (previousStar) {
                //on change la class de l'etoile pour la remplir
                previousStar.classList.remove("bi-star");
                previousStar.classList.add("bi-star-fill");
                //on recupère l'étoile précédente
                previousStar = previousStar.previousElementSibling;
            }

        });
        //on ecoute le click
        star.addEventListener("click", function () {
            note.value = this.dataset.value;
        });

        star.addEventListener("mouseout", function () {
            resetStars(note.value);
        });
    }


    function resetStars(note = 0) {
        for (let star of stars) {
            if (star.dataset.value > note) {
                star.classList.remove("bi-star-fill");
                star.classList.add("bi-star");
            } else {
                star.classList.remove("bi-star");
                star.classList.add("bi-star-fill");
            }

        }
    }
}


function deleteMessage() {
    let errorMessage = document.querySelector('#error');
    if(errorMessage != null){
        errorMessage.remove();
    }
}

function loop() {
    setTimeout(() => {
        deleteMessage();
    }, 7000);
}




