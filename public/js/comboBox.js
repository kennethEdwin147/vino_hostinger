document.addEventListener('alpine:init', () => {

    Alpine.data('combobox', () => ({
        bouteilles: [],
        message:'',
    
        recherche() {
           
            let requete = new Request("/bouteille/api/select", {method: 'POST', body: '{"nom": "'+this.message+'"}'});

            fetch(requete).then( res => {
                if (res.ok) return res.json();
                else throw new Error('response not OK');
            })
            .then( data => {
                this.bouteilles = data;
                console.log('this.bouteilles', this.bouteilles);
            }) 
            .catch( err => {
                console.log('err' + err.message);
            });

            if (this.message.trim() == '' || this.message == ' ' ) {
                this.bouteilles = [];
            }
        }
    }))
    
    });