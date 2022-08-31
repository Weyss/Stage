'use strict'
/****** VARIABLE ********/
let form = document.querySelector('#formcontact');
let merci = document.querySelector('#merci');
let contacts = document.querySelectorAll('.contact');

form.addEventListener('submit', function (e) {

    // Si lors de l'envoi on a un message d'erreur, alors lors de la nouvelle tentative, on supprimer la div
    // Pour en recréer une autre
    let removeError = document.querySelector('.modal-body');
    let divError = document.querySelector('#errors');
    if (divError)
        removeError.removeChild(divError)

    e.preventDefault();

    let data = new FormData(form);
    let xhr = new XMLHttpRequest;

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status != 200) {
                // En cas de non succès, on créer une div qui affiche les messages d'erreurs
                let errors = JSON.parse(xhr.responseText);
                let divError = document.createElement('div');
                divError.setAttribute('id', 'errors');
                divError.className = 'alert alert-danger';
                errors.forEach(function (error) {
                    let parError = document.createElement('p');
                    parError.setAttribute('class', 'error');
                    divError.appendChild(parError)
                    parError.innerHTML = error
                });
                form.parentNode.prepend(divError);
            }
            if (xhr.status == 200) {
                // En cas de succès, on enleve le formulaire pour afficher un message de remerciment
                contacts.forEach(function (contact) {
                    contact.classList.add('display');
                })
                merci.classList.remove('display');
            }
        }
    }

    xhr.open('POST', form.getAttribute('action'), true);
    xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest')
    xhr.send(data);
})
