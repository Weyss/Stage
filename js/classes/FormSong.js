class FormSong extends Form {
    // On appel le constructeur du parent et on lui passe l'ID du formulaire qu'on cible
    constructor() {
        super('#formsong')
    }

    add() {
        let form = this.form;
        let merci = document.querySelector('#addsong #merci');
        let contacts = document.querySelectorAll('.contact');

        form.addEventListener('submit', function (e) {

            // Si lors de l'envoi on a un message d'erreur, alors lors de la nouvelle tentative, on supprimer la div
            // Pour en recréer une autre avec les nouvelles erreurs detectées
            let removeError = document.querySelector('#addsong .modal-body');
            let divError = document.querySelector('#errors');
            if (divError)
                removeError.removeChild(divError)
            // On stop le submit du formulaire
            e.preventDefault();

            let data = new FormData(form);
            let xhr = new XMLHttpRequest;

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status != 200) {
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
                        // Et on effectue la redirection
                        if (window.location == 'http://localhost/Site_stage/admin/index.php?controller=dashboard&task=show') {
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);

                        } else
                            setTimeout(function () {
                                window.location.href = 'http://localhost/Site_stage/admin/index.php?controller=dashboard&task=show';
                            }, 1000);
                    }

                }
            }

            xhr.open('POST', form.getAttribute('action'), true);
            xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest')
            xhr.send(data);
        })
    }
}

form = new FormSong;
form.add();