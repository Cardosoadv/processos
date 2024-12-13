import { siteUrl } from "./url.js";

export class Contas {

    constructor() {
        console.log("Class Contas");

        //setando as urls e o modal
        this.form = document.getElementById('form_contas');
        this.modal = document.getElementById('modal_contas');
        this.urlGetAccount = "accounts/get_account";
        this.urlEditar = "accounts/atualizar";
        this.urlAdicionar = "accounts/adicionar";

        //pegar as variáveis do formulário
        this.accountInput = document.querySelector('[name="account"]');
        this.idInput = document.querySelector('[name="id"]');
        this.commentInput = document.querySelector('[name="comments"]');
    }

    edit(id) {
        this.form.reset(); //limpando os dados do formulário

        // fetch client data
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `${siteUrl}/${this.urlGetAccount}/${id}`);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);

                // fill form with fetched data
                this.accountInput.value = data.account;
                this.idInput.value = data.id;
                this.commentInput.value = data.comments;
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

    novaConta() {
        // Reset the form
        document.getElementById('form_contas').reset();

        // Set the modal title
        const modalTitle = document.querySelector('.modal-title');
        modalTitle.textContent = 'Nova Conta';

        // Set the form action
        document.getElementById('form_contas').action = `${siteUrl}/${this.urlAdicionar}`;
    }
    close() {
        this.modal.style.display = 'none';
    }
}