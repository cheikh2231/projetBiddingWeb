var formEstModifie = false;
var urlDestination = "";
var timeoutValidation = null;
var champsTouches = new Set();

document.addEventListener('DOMContentLoaded', function() {
    var formulaire = document.getElementById('editItemForm');
    
    if (formulaire) {
                // --- VALIDER A LA FRAPPE ---
        formulaire.addEventListener('input', function(e) {
            formEstModifie = true;
            champsTouches.add(e.target.id); // ← on marque comme touché dès la frappe

            var erreursPHP = document.querySelectorAll('.php-error');
            for(var i = 0; i < erreursPHP.length; i++)
                erreursPHP[i].style.display = 'none';

            clearTimeout(timeoutValidation);
            timeoutValidation = setTimeout(valider, 300);
        });

    
        // --- LAISSER LE FORMULAIRE S'ENVOYER ---
        formulaire.addEventListener('submit', function() {
             if(boutonSave && boutonSave.disabled){
                    e.preventDefault();
                    return;
                }
                formEstModifie = false; 
        });

        // --- CATÉGORIES (change) ---
        formulaire.querySelectorAll('input[name="categories[]"]').forEach(function(cb) {
            cb.addEventListener('change', function() {
                formEstModifie = true;
                champsTouches.add('categoriesGroup');

                clearTimeout(timeoutValidation);
                timeoutValidation = setTimeout(valider, 300);
            });
        });

        // --- VALIDATION AJAX ---
        function valider() {
            var formData = new FormData(formulaire);

            fetch('item/validate_async', { method: 'POST', body: formData })
            .then(function(response) { return response.json(); })
            .then(function(erreurs) {

                // Reset uniquement sur les champs touchés
                formulaire.querySelectorAll('.form-control').forEach(function(input) {
                    if(champsTouches.has(input.id))
                        input.classList.remove('is-invalid', 'is-valid');
                });

                var champsAverifier = {
                    'title':         { inputId: 'titleInput',        errorId: 'titleError' },
                    'description':   { inputId: 'descriptionInput',  errorId: 'descriptionError' },
                    'duration':      { inputId: 'durationInput',     errorId: 'durationError' },
                    'instant_price': { inputId: 'instantPriceInput', errorId: 'instantPriceError' },
                    'starting':      { inputId: 'startingInput',     errorId: 'startingAndOrSaleError' },
                    'sale_price':    { inputId: 'salePriceInput',    errorId: 'salePriceError' }
                };

                for (var cle in champsAverifier) {
                    var infos = champsAverifier[cle];
                    var input = document.getElementById(infos.inputId);
                    var error = document.getElementById(infos.errorId);

                    if (!input || !error || !champsTouches.has(infos.inputId)) continue;

                    error.innerHTML = '';
                    if (erreurs[cle]) {
                        input.classList.add('is-invalid');
                        error.innerHTML = '<ul><li>' + erreurs[cle].join('</li><li>') + '</li></ul>';
                    } else if (input.value.trim() !== '') {
                        input.classList.add('is-valid');
                    }
                }

                // --- CAS SPECIAL : startingAndOrSale ---
                var inputEnchere  = document.getElementById('startingInput');
                var erreurEnchere = document.getElementById('startingAndOrSaleError');
                var inputVente    = document.getElementById('salePriceInput');
                var erreurVente   = document.getElementById('salePriceError');

                if (erreurs['startingAndOrSale']) {
                    var message = '<ul><li>' + erreurs['startingAndOrSale'].join('</li><li>') + '</li></ul>';
                    if (champsTouches.has('startingInput') && inputEnchere) {
                        inputEnchere.classList.remove('is-valid');
                        inputEnchere.classList.add('is-invalid');
                        erreurEnchere.innerHTML = message;
                    }
                    if (champsTouches.has('salePriceInput') && inputVente) {
                        inputVente.classList.remove('is-valid');
                        inputVente.classList.add('is-invalid');
                        erreurVente.innerHTML = message;
                    }
                }
                // --- CATEGORIES ---
              var erreurCat = document.getElementById('catError');
                if (erreurCat && champsTouches.has('categoriesGroup')) {
                    erreurCat.innerHTML = '';
                    erreurCat.classList.remove('d-block'); 
                    if (erreurs['categories']) {
                        erreurCat.innerHTML = '<ul><li>' + erreurs['categories'].join('</li><li>') + '</li></ul>';
                        erreurCat.classList.add('d-block');  
                    }
                }
                var boutonSave = document.querySelector('[form="editItemForm"][type="submit"]');
                if(boutonSave){
                    boutonSave.disabled = Object.keys(erreurs).length > 0;
                }
            })
            .catch(function(erreur) {
                console.error('Erreur AJAX:', erreur);
            });
        }
    }
    // --- MODALE ---
    var tousLesLiens = document.getElementsByTagName('a');
    for (var i = 0; i < tousLesLiens.length; i++) {
        tousLesLiens[i].addEventListener('click', function(evenement) {
            if (this.hasAttribute('href') && formEstModifie === true) {
                evenement.preventDefault();
                urlDestination = this.href;
                var maModaleHtml = document.getElementById('confirmLeaveModal');
                if (maModaleHtml) {
                    new bootstrap.Modal(maModaleHtml).show();
                }
            }
        });
    }

    var boutonQuitterModale = document.getElementById('confirmLeaveBtn');
    if (boutonQuitterModale) {
        boutonQuitterModale.addEventListener('click', function() {
            formEstModifie = false;
            window.location.href = urlDestination;
        });
    }
     valider();
});