class Login extends Form {
    // On appel le constructeur du parent et on lui passe l'ID du formulaire qu'on cible
    constructor() {
        super('#formcreate')
    }

    create() {
        let form = this.form;
        let success = document.querySelector('#create #success')
        let contacts = document.querySelectorAll('.contact');

        form.addEventListener('submit', function (e) {

            // Si lors de l'envoi on a un message d'erreur, alors lors de la nouvelle tentative, on supprimer la div
            // Pour en recréer une autre avec les nouvelles erreurs detectées
            let removeError = document.querySelector('#create .modal-body');
            let divError = document.querySelector('#error');
            if (divError)
                removeError.removeChild(divError)
            // On stop le submit du formulaire
            e.preventDefault();

            let data = new FormData(form);
            let xhr = new XMLHttpRequest;

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status != 200) {
                        // En cas de non succès, on créer une div qui affiche les messages d'erreurs
                        let error = JSON.parse(xhr.responseText);
                        let divError = document.createElement('div');
                        divError.setAttribute('id', 'error');
                        divError.className = 'alert alert-danger';
                        let parError = document.createElement('p');
                        parError.setAttribute('class', 'error');
                        divError.appendChild(parError)
                        parError.innerHTML = error

                        form.parentNode.prepend(divError);

                    }
                    if (xhr.status == 200) {
                        contacts.forEach(function (contact) {
                            contact.classList.add('display');
                        })
                        success.classList.remove('display');
                        success.querySelector('h5').innerHTML = "Mot de passe bien ajouté";
                        setTimeout(function () {
                            window.location.reload();
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
form = new Login;
form.create();