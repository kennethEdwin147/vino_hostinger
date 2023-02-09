let cellier_popup = document.querySelector('#cellier_popup');
let liste_cellier_popup = document.querySelector('#liste_cellier_popup');

/* message pour les celliers individuels */
if (cellier_popup) {
    let xButton = document.querySelector('[data-button-close]');
    xButton.addEventListener('click', ()=> {
        let idCellier = document.querySelector('[data-js-cel-id]').dataset.jsCelId;
        window.location.href = '/cellier/un/' + idCellier; 
    })   
}

/* message pour la liste des celliers */
if (liste_cellier_popup) {
    let xButton = document.querySelector('[data-button-close]');
    xButton.addEventListener('click', ()=> {
        window.location.href = '/cellier/cellier'; 
    })  
}