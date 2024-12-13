import { siteUrl } from "./url.js";
import { calcularJuros, calcularMulta } from "./util.js";

export class Vendas{
    constructor() {

        //seta variavel Global valor do débito
        this.valorDebito;
        //setando as urls e o modal
        this.form               = document.getElementById('form_revenue');
        this.modal              = document.getElementById('modal_revenue');
        this.receiptForm        = document.getElementById('form_receipts');
        this.receiptModal       = document.getElementById('modal_receipts');
        this.urlGetRevenue      = "revenues/get_revenue";
        this.urlEditar          = "revenues/atualizar";
        this.urlAdicionar       = "revenues/adicionar";
        this.urlReceber         = "revenues/receber";
        this.urlGetReceipts     = "revenues/get_receipts";       

        //pegar as variáveis do formulário de Vendas
        this.revenuesInput      = document.querySelector('[name="revenues"]');
        this.dueDateInput       = document.querySelector('[name="due_dt"]');
        this.valueInput         = document.querySelector('[name="value"]');
        this.latefeeInput       = document.querySelector('[name="late_fee"]');
        this.interestInput      = document.querySelector('[name="interest"]');
        this.chargesInput       = document.querySelector('[name="charges"]');
        this.categoryInput      = document.querySelector('[name="category_id"]');
        this.clientIdInput      = document.querySelector('[name="client_id"]');
        this.reconciledInput    = document.querySelector('[name="reconciled"]');
        this.commentsInput      = document.querySelector('[name="comments"]');

        //pegar as variáveis do formulário share:
        this.user1Input         = document.querySelector('[name="user1"');
        this.user2Input         = document.querySelector('[name="user2"');
        this.user3Input         = document.querySelector('[name="user3"');
        this.user4Input         = document.querySelector('[name="user4"');
        this.user5Input         = document.querySelector('[name="user5"');
        this.user6Input         = document.querySelector('[name="user6"');
        this.shareUser1Input    = document.querySelector('[name="share_user1"');
        this.shareUser2Input    = document.querySelector('[name="share_user2"');
        this.shareUser3Input    = document.querySelector('[name="share_user3"');
        this.shareUser4Input    = document.querySelector('[name="share_user4"');
        this.shareUser5Input    = document.querySelector('[name="share_user5"');
        this.shareUser6Input    = document.querySelector('[name="share_user6"');

         //pegar as variáveis do formulário de Recebimento
         this.receiptRevenuesInput      = document.querySelector('[name="receipt_revenue"]');
         this.receiptDateInput          = document.querySelector('[name="receipt_dt"]');
         this.receiptValueInput         = document.querySelector('[name="receipt_value"]');
         this.receiptLatefeeInput       = document.querySelector('[name="receipt_late_fee"]');
         this.receiptInterestInput      = document.querySelector('[name="receipt_interest"]');
         this.receiptChargesInput       = document.querySelector('[name="receipt_charges"]');
         this.receiptReconciledInput    = document.querySelector('[name="receipt_reconciled"]');
         this.receiptCommentsInput      = document.querySelector('[name="receipt_comments"]');
        
        //pegar as variáveis do formulário share:
        this.receiptUser1Input          = document.querySelector('[name="receipt_user1"');
        this.receiptUser2Input          = document.querySelector('[name="receipt_user2"');
        this.receiptUser3Input          = document.querySelector('[name="receipt_user3"');
        this.receiptUser4Input          = document.querySelector('[name="receipt_user4"');
        this.receiptUser5Input          = document.querySelector('[name="receipt_user5"');
        this.receiptUser6Input          = document.querySelector('[name="receipt_user6"');
        this.receiptShareUser1Input     = document.querySelector('[name="receipt_share_user1"');
        this.receiptShareUser2Input     = document.querySelector('[name="receipt_share_user2"');
        this.receiptShareUser3Input     = document.querySelector('[name="receipt_share_user3"');
        this.receiptShareUser4Input     = document.querySelector('[name="receipt_share_user4"');
        this.receiptShareUser5Input     = document.querySelector('[name="receipt_share_user5"');
        this.receiptShareUser6Input     = document.querySelector('[name="receipt_share_user6"');
    }

    setValordebito(valor){
        this.valorDebito = valor;
    }

    edit(id) {
        this.form.reset(); //limpando os dados do formulário

        // fetch client data
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `${siteUrl}/${this.urlGetRevenue}/${id}`);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const verifiedChecked = ((data.reconciled==="1") ? true : false)
                // fill form with fetched data
                this.revenuesInput.value        = data.revenues;
                this.dueDateInput.value         = data.due_dt;
                this.valueInput.value           = data.value;
                this.chargesInput.value         = data.charges;
                this.interestInput.value        = data.interest;
                this.latefeeInput.value         = data.late_fee;
                this.categoryInput.value        = data.category;
                this.clientIdInput.value        = data.client_id;
                this.reconciledInput.checked    = verifiedChecked;
                this.commentsInput.textContent  = data.comments;
                this.user1Input.value           = data.user1;
                this.user2Input.value           = data.user2;
                this.user3Input.value           = data.user3;
                this.user4Input.value           = data.user4;
                this.user5Input.value           = data.user5;
                this.user6Input.value           = data.user6;
                this.shareUser1Input.value      = data.share_user1;
                this.shareUser2Input.value      = data.share_user2;
                this.shareUser3Input.value      = data.share_user3;
                this.shareUser4Input.value      = data.share_user4;
                this.shareUser5Input.value      = data.share_user5;
                this.shareUser6Input.value      = data.share_user6;
                console.log(verifiedChecked);
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

    receipt(id) {
        this.receiptForm.reset(); //limpando os dados do formulário

        // fetch revenues data
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `${siteUrl}/${this.urlGetRevenue}/${id}`);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const verifiedChecked = ((data.reconciled==="1") ? true : false);
                console.log(data);
                // Dispara a função quando a data de pagamento for preenchida
                this.receiptDateInput.addEventListener('change', () => {
                this.receiptInterestInput.value = calcularJuros(this.receiptDateInput.value, data.due_dt, data.interest);
                this.receiptLatefeeInput.value = calcularMulta(this.receiptDateInput.value, data.due_dt, data.late_fee)*data.value;
                this.valorDebito = (parseInt(this.receiptValueInput.value*100) + parseInt(this.receiptLatefeeInput.value*100) + parseInt(this.receiptInterestInput.value*100))/100;
                this.receiptShareUser1Input.value       = ((data.share_user1/100) * this.valorDebito).toFixed(2);
                this.receiptShareUser2Input.value       = ((data.share_user2/100) * this.valorDebito).toFixed(2);
                this.receiptShareUser3Input.value       = ((data.share_user3/100) * this.valorDebito).toFixed(2);
                this.receiptShareUser4Input.value       = ((data.share_user4/100) * this.valorDebito).toFixed(2);
                this.receiptShareUser5Input.value       = ((data.share_user5/100) * this.valorDebito).toFixed(2);
                this.receiptShareUser6Input.value       = ((data.share_user6/100) * this.valorDebito).toFixed(2);
            });
                // fill form with fetched data
                this.receiptRevenuesInput.value         = data.revenues;
                this.receiptValueInput.value            = data.value;
                this.receiptUser1Input.value            = data.user1; 
                this.receiptUser2Input.value            = data.user2; 
                this.receiptUser3Input.value            = data.user3; 
                this.receiptUser4Input.value            = data.user4; 
                this.receiptUser5Input.value            = data.user5; 
                this.receiptUser6Input.value            = data.user6;                
                this.receiptReconciledInput.checked     = verifiedChecked;
            } else {
                console.log('Erro ao receber dados do AJAX');
            }
        };
        xhr.send();

        // show modal
        this.receiptModal.classList.add('show');
        this.receiptModal.style.display = 'block';
        this.receiptModal.querySelector('.modal-title').textContent = 'Receber';

        // set form action
        this.form.action = `${siteUrl}/${this.urlReceber}/${id}`;
    }

    novaVenda() {
        // Reset the form
        this.form.reset();

        // Set the modal title
        const modalTitle = document.querySelector('.modal-title');
        modalTitle.textContent = 'Nova Venda';

        // Set the form action
        this.form.action = `${siteUrl}/${this.urlAdicionar}`;
    }
    close() {
        this.modal.style.display = 'none';
        this.receiptModal.style.display = 'none';
    }
}