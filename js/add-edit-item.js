var pristine = false; // false si non modifier
var urlDestination = "";

document.addEventListener('DOMContentLoaded', function(){
    var formulaire = document.getElementById('editItemForm');
    
    if(formulaire){
            formulaire.addEventListener('input', function(){ //add listener sur formulaire
                pristine = true;

                //reset synchrone errors
                var erreursPHP = document.querySelectorAll('.php-error');
                for(var i=0;i<erreursPHP.length;i++){
                    erreursPHP[i].style.display = 'none';
                }
                
                var formData = new FormData(formulaire);
                // envois vers model Item en ajax
                fetch('item/validate_async',{method: 'POST',body: formData})
                .then(function(response){return response.json();})
                .then(function(erreurs){
                    //reset msg d'erreurs
                    var champs =formulaire.querySelectorAll('.form-control');
                    for(var i = 0; i < champs.length; i++)
                        champs[i].classList.remove('is-invalid', 'is-valid');
                    
                    var champsAverifier={
                        'title': { inputId: 'titleInput', errorId: 'titleError'},
                        'description': { inputId: 'descriptionInput', errorId: 'descriptionError'},
                        'duration': { inputId: 'durationInput', errorId: 'durationError'},
                        'instant_price': { inputId: 'instantPriceInput', errorId: 'instantPriceError'}
                    };
                    
                    //////////////////////////////////
                    ///      verif basic info      ///
                    //////////////////////////////////
                    for(var cleModele in champsAverifier){
                        var infos = champsAverifier[cleModele];
                        var inputElement = document.getElementById(infos.inputId);
                        var errorElement = document.getElementById(infos.errorId);
                        if(inputElement && errorElement) //erreur
                            if(erreurs[cleModele]){
                                inputElement.classList.add('is-invalid');
                                errorElement.innerHTML = '<ul><li>' + erreurs[cleModele].join('</li><li>') + '</li></ul>';
                            }else if(inputElement.value.trim() !== ''){ //pas erreur
                                inputElement.classList.add('is-valid');
                                errorElement.innerHTML = '';
                            }
                    }
                //////////////////////////////////
                ///         verif sale         ///
                //////////////////////////////////
                var inputEnchere= document.getElementById('startingInput');
                var erreurEnchere= document.getElementById('startingAndOrSaleError');
                var inputVenteDirecte= document.getElementById('salePriceInput');
                var erreurVenteDirecte= document.getElementById('salePriceError');

                if(erreurs['startingAndOrSale']){ //si starting bid et direct sale sont remplis
                    if(inputEnchere)
                        inputEnchere.classList.add('is-invalid');
                    if(inputVenteDirecte)
                        inputVenteDirecte.classList.add('is-invalid');
                    var message = '<ul><li>' + erreurs['startingAndOrSale'].join('</li><li>') + '</li></ul>';
                    if (erreurEnchere) 
                        erreurEnchere.innerHTML = message;
                    if (erreurVenteDirecte) 
                        erreurVenteDirecte.innerHTML = message;
                } else { // sinon on remet comme avant
                    if (inputEnchere && inputEnchere.value.trim() !== '')
                        inputEnchere.classList.add('is-valid');
                    if (inputVenteDirecte && inputVenteDirecte.value.trim() !== '')
                        inputVenteDirecte.classList.add('is-valid');
                    if (erreurEnchere)
                        erreurEnchere.innerHTML = ''; 
                    if (erreurVenteDirecte)
                        erreurVenteDirecte.innerHTML = '';
                }
                })
            });

        formulaire.addEventListener('submit', function() {
            pristine = false; 
        });
    }
    //////////////////////////////////
    ///           MODALE           ///
    //////////////////////////////////
    var tousLesLiens= document.getElementsByTagName('a');
    for(var i=0; i<tousLesLiens.length; i++){
        tousLesLiens[i].addEventListener('click', function(evenement){ //add listener on click
            if(this.hasAttribute('href') && pristine===true) { //verif si il y a eu modif
                evenement.preventDefault();
                urlDestination = this.href;
                
                var modale= document.getElementById('confirmLeaveModal');
                if(modale){
                    var modaleJs = new bootstrap.Modal(modale);
                    modaleJs.show();
                }
            }
        });
    }
    //btn quitter
    var btnQuitter= document.getElementById('confirmLeaveBtn');
    if(btnQuitter){
        btnQuitter.addEventListener('click', function() {
            pristine = false;
            window.location.href = urlDestination;
        });
    }
});