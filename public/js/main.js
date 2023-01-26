let elBouteilles = document.querySelectorAll('[data-js-bouteille]');

for (let i = 0; i < elBouteilles.length; i++) {
    new Bouteille(elBouteilles[i]); 
}