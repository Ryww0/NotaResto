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
        });

});

