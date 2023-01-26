class Bouteille {

    constructor(el) {
        this.el = el 
        this.id = this.el.dataset.jsId;
        this.el_btn_ajouter = this.el.querySelector('[data-js-ajouter]');
        this.el_btn_boire = this.el.querySelector('[data-js-boire]');
        this.el_quantite = this.el.querySelector('[data-js-quantite]');

        this.init();
    }

    init() {
        this.el_btn_ajouter.addEventListener('click', this.ajouterBouteille); 
        this.el_btn_boire.addEventListener('click', this.boireBouteille); 
    }

    /**
     * Requête fetch ajout quantite de bouteille
     */
    ajouterBouteille = () => {
        let requete = new Request("/bouteille/quantite/ajouter/", {method: 'POST', body: '{"id": '+this.id+'}'});
        fetch(requete).then( (res) => {
            if(res.ok) return res.json();
            else throw new Error('La réponse nest pas OK');
        })
        .then( (data) => {
            if (data == 1) this.el_quantite.textContent ++;
        }) 
    }

    /**
     * Requête fetch diminution quantite de bouteille
     */
    boireBouteille = () => {
        let requete = new Request("/bouteille/quantite/boire/", {method: 'POST', body: '{"id": '+this.id+'}'});
        fetch(requete).then( (res) => {
            if(res.ok) return res.json();
            else throw new Error('La réponse nest pas OK');
        })
        .then( (data) => {
            if (data == 1) this.el_quantite.textContent --;
        });
    }



}