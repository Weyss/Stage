class Delete extends Form {

    // On appel le constructeur du parent et on lui passe l'ID du formulaire qu'on cible
    constructor() {
        super('#formdelete')
    }

    delete() {
        let form = this.form;
        let erreur = document.querySelector('#delete #erreur');
        let success = document.querySelector('#delete #success')
        let contacts = document.querySelectorAll('.contact');

        form.addEventListener('submit', function (e) {
            // On stop le submit du formulaire
            e.preventDefault();

            let data = new FormData(form);
            let xhr = new XMLHttpRequest;

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status != 200) {
                        contacts.forEach(function (contact) {
                            contact.classList.add('display');
                        })
                        erreur.classList.remove('display');
                        document.querySelector('#erreur h5').innerHTML = "La suppression à échouée : l'élément n'existe pas";
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                    if (xhr.status == 200) {
                        contacts.forEach(function (contact) {
                            contact.classList.add('display');
                        })
                        success.classList.remove('display');
                        document.querySelector('#success h5').innerHTML = "La suppression s'est effectuée avec succès";
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
supp = new Delete;
supp.delete();