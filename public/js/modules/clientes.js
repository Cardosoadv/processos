import { siteUrl } from "./url.js";

export class Clientes {
    constructor() {
        //setando as urls e o modal
        this.form               = document.getElementById('form_cliente');
        this.modal              = document.getElementById('modal_cliente');
        this.urlGetCliente      = "clients/get_client";
        this.urlEditar          = "clients/atualizar";
        this.urlAdicionar       = "clients/adicionar";

        //pegar as variáveis do formulário
        this.nameInput          = document.querySelector('[name="name"]');
        this.idInput            = document.querySelector('[name="id"]');
        this.telefoneInput      = document.querySelector('[name="celular"]');
        this.dataAquisicaoInput = document.querySelector('[name="landed_at"]');
        this.emailInput         = document.querySelector('[name="email"]');
    }

    edit(id) {
        this.form.reset(); //limpando os dados do formulário

        // fetch client data
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `${siteUrl}/${this.urlGetCliente}/${id}`);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);

                // fill form with fetched data
                this.nameInput.value            = data.name;
                this.idInput.value              = data.id;
                this.telefoneInput.value        = data.celular;
                this.dataAquisicaoInput.value   = data.landed_at;
                this.emailInput.value           = data.email;
            } else {
                console.log('Erro ao receber dados do AJAX');
            }
        };
        xhr.send();

        // show modal
        this.modal.classList.add('show');
        this.modal.style.display = 'block';
        this.modal.querySelector('.modal-title').textContent = 'Editar';

        // set form action
        this.form.action = `${siteUrl}/${this.urlEditar}/${id}`;
    }

    novoCliente() {
        // Reset the form
        document.getElementById('form_cliente').reset();

        // Set the modal title
        const modalTitle = document.querySelector('.modal-title');
        modalTitle.textContent = 'Novo Cliente';

        // Set the form action
        document.getElementById('form_cliente').action = `${siteUrl}/${this.urlAdicionar}`;
    }
    close() {
        this.modal.style.display = 'none';
    }    
}