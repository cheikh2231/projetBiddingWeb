var formEstModifie = false;
var urlDestination = "";
var timeoutValidation = null;

document.addEventListener('DOMContentLoaded', function() {
    var formulaire = document.getElementById('editItemForm');
    
    if (formulaire) {
        
        // --- 1. DÉTECTER LES MODIFICATIONS ET VALIDER EN DIRECT (AJAX) ---
        formulaire.addEventListener('input', function() {
            formEstModifie = true; // Le formulaire n'est plus "pristine"

            // On cache les erreurs PHP d'origine car l'AJAX prend le relais
            var erreursPHP = document.querySelectorAll('.php-error');
            for(var i = 0; i < erreursPHP.length; i++) {
                erreursPHP[i].style.display = 'none';
            }

            // On attend 500ms après la dernière frappe avant de déranger le serveur
            clearTimeout(timeoutValidation);
            timeoutValidation = setTimeout(function() {
                
                var formData = new FormData(formulaire);

                // Envoi des données au contrôleur pour vérification
                fetch('item/validate_async', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(erreurs) {
                    
                    // A. On nettoie tout (on retire le rouge et le vert de toutes les cases)
                    var tousLesInputs = formulaire.querySelectorAll('.form-control');
                    for (var j = 0; j < tousLesInputs.length; j++) {
                        tousLesInputs[j].classList.remove('is-invalid', 'is-valid');
                    }

                    // B. On prépare la liste des champs basiques à vérifier
                    var champsAverifier = {
                        'title': { inputId: 'titleInput', errorId: 'titleError' },
                        'description': { inputId: 'descriptionInput', errorId: 'descriptionError' },
                        'duration': { inputId: 'durationInput', errorId: 'durationError' },
                        'instant_price': { inputId: 'instantPriceInput', errorId: 'instantPriceError' }
                    };

                    // C. On applique les couleurs et les messages pour les champs basiques
                    for (var cleModele in champsAverifier) {
                        var infos = champsAverifier[cleModele];
                        var inputElement = document.getElementById(infos.inputId);
                        var errorElement = document.getElementById(infos.errorId);

                        if (inputElement && errorElement) {
                            if (erreurs[cleModele]) {
                                // Si le serveur a renvoyé une erreur pour ce champ
                                inputElement.classList.add('is-invalid'); // Case en rouge
                                errorElement.innerHTML = '<ul><li>' + erreurs[cleModele].join('</li><li>') + '</li></ul>';
                            } else if (inputElement.value.trim() !== '') {
                                // Si c'est valide et pas vide
                                inputElement.classList.add('is-valid'); // Case en vert
                                errorElement.innerHTML = '';
                            }
                        }
                    }

                    // D. CAS SPÉCIAL : Le "startingAndOrSale" (Enchère VS Vente directe)
                    // Cette erreur concerne deux cases en même temps.
                    var inputEnchere = document.getElementById('startingInput');
                    var erreurEnchere = document.getElementById('startingAndOrSaleError');
                    
                    var inputVenteDirecte = document.getElementById('salePriceInput');
                    var erreurVenteDirecte = document.getElementById('salePriceError');

                    if (erreurs['startingAndOrSale']) {
                        // On met les deux champs en rouge
                        if (inputEnchere) inputEnchere.classList.add('is-invalid');
                        if (inputVenteDirecte) inputVenteDirecte.classList.add('is-invalid');
                        
                        // On affiche le message d'erreur sous les deux
                        var message = '<ul><li>' + erreurs['startingAndOrSale'].join('</li><li>') + '</li></ul>';
                        if (erreurEnchere) erreurEnchere.innerHTML = message;
                        if (erreurVenteDirecte) erreurVenteDirecte.innerHTML = message;
                    } else {
                        // Si pas d'erreur de conflit, on met en vert celles qui sont remplies
                        if (inputEnchere && inputEnchere.value.trim() !== '') inputEnchere.classList.add('is-valid');
                        if (inputVenteDirecte && inputVenteDirecte.value.trim() !== '') inputVenteDirecte.classList.add('is-valid');

                        if (erreurEnchere) erreurEnchere.innerHTML = ''; 
                        if (erreurVenteDirecte) erreurVenteDirecte.innerHTML = '';
                    }

                })
                .catch(function(erreur) {
                    console.error('Erreur AJAX:', erreur);
                });

            }, 500); 
        });

        // --- 2. LAISSER LE FORMULAIRE S'ENVOYER (Bouton Save) ---
        formulaire.addEventListener('submit', function() {
            // On désactive l'alerte pour laisser l'utilisateur sauvegarder
            formEstModifie = false; 
        });
    }

    // --- 3. INTERCEPTER LES LIENS POUR LA MODALE DE SORTIE ---
    var tousLesLiens = document.getElementsByTagName('a');
    for (var i = 0; i < tousLesLiens.length; i++) {
        tousLesLiens[i].addEventListener('click', function(evenement) {
            
            // Si on a tapé quelque chose et qu'on clique sur un lien (ex: retour)
            if (this.hasAttribute('href') && formEstModifie === true) {
                evenement.preventDefault(); // On bloque la navigation
                urlDestination = this.href; // On mémorise l'url de destination
                
                // On affiche la belle modale Bootstrap
                var maModaleHtml = document.getElementById('confirmLeaveModal');
                if (maModaleHtml) {
                    var modaleJs = new bootstrap.Modal(maModaleHtml);
                    modaleJs.show();
                }
            }
        });
    }

    // --- 4. SI ON CLIQUE SUR "QUITTER" DANS LA MODALE ---
    var boutonQuitterModale = document.getElementById('confirmLeaveBtn');
    if (boutonQuitterModale) {
        boutonQuitterModale.addEventListener('click', function() {
            formEstModifie = false; // On reset pour ne pas re-bloquer
            window.location.href = urlDestination; // On force la redirection mémorisée
        });
    }
});