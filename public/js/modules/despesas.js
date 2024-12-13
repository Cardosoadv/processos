import { siteUrl } from "./url.js";
import { calcularJuros, calcularMulta } from "./util.js";

/**
 * Metódos relacionados às despesas e pagamentos
 */
export class Despesas{
    constructor() {

        //setando as urls e o modal
        this.form               = document.getElementById('form_expense');
        this.modal              = document.getElementById('modal_expense');
        this.paymentForm        = document.getElementById('form_payments');
        this.paymentModal       = document.getElementById('modal_payments');
        this.urlGetExpense      = "expenses/get_expense";
        this.urlEditar          = "expenses/atualizar";
        this.urlAdicionar       = "expenses/adicionar";
        this.urlReceber         = "expenses/receber";
        this.urlGetPayments     = "expenses/get_payments";       

        //pegar as variáveis do formulário de Despesas
        this.expensesInput      = document.querySelector('[name="expenses"]');
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

         //pegar as variáveis do formulário de Pagamento
         this.paymentRevenuesInput      = document.querySelector('[name="payment_expense"]');
         this.paymentDateInput          = document.querySelector('[name="payment_dt"]');
         this.paymentValueInput         = document.querySelector('[name="payment_value"]');
         this.paymentLatefeeInput       = document.querySelector('[name="payment_late_fee"]');
         this.paymentInterestInput      = document.querySelector('[name="payment_interest"]');
         this.paymentChargesInput       = document.querySelector('[name="payment_charges"]');
         this.paymentReconciledInput    = document.querySelector('[name="payment_reconciled"]');
         this.paymentCommentsInput      = document.querySelector('[name="payment_comments"]');
        
        //pegar as variáveis do formulário share:
        this.paymentUser1Input          = document.querySelector('[name="payment_user1"');
        this.paymentUser2Input          = document.querySelector('[name="payment_user2"');
        this.paymentUser3Input          = document.querySelector('[name="payment_user3"');
        this.paymentUser4Input          = document.querySelector('[name="payment_user4"');
        this.paymentUser5Input          = document.querySelector('[name="payment_user5"');
        this.paymentUser6Input          = document.querySelector('[name="payment_user6"');
        this.paymentShareUser1Input     = document.querySelector('[name="payment_share_user1"');
        this.paymentShareUser2Input     = document.querySelector('[name="payment_share_user2"');
        this.paymentShareUser3Input     = document.querySelector('[name="payment_share_user3"');
        this.paymentShareUser4Input     = document.querySelector('[name="payment_share_user4"');
        this.paymentShareUser5Input     = document.querySelector('[name="payment_share_user5"');
        this.paymentShareUser6Input     = document.querySelector('[name="payment_share_user6"');
    }

    /**
     * Metódo para editar a despesa
     * @param {*} id Id da despesa a ser editada
     */
    edit(id) {
        this.form.reset(); //limpando os dados do formulário

        // fetch client data
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `${siteUrl}/${this.urlGetExpense}/${id}`);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const verifiedChecked = ((data.reconciled==="1") ? true : false)
                // fill form with fetched data
                this.expensesInput.value        = data.expeses;
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

    payment(id) {
        this.paymentForm.reset(); //limpando os dados do formulário

        // fetch revenues data
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `${siteUrl}/${this.urlGetPayments}/${id}`);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const verifiedChecked = ((data.reconciled==="1") ? true : false);
                console.log(data);
                // Dispara a função quando a data de pagamento for preenchida
                this.paymentDateInput.addEventListener('change', () => {
                this.paymentInterestInput.value = calcularJuros(this.paymentDateInput.value, data.due_dt, data.interest);
                this.paymentLatefeeInput.value = calcularMulta(this.paymentDateInput.value, data.due_dt, data.late_fee)*data.value;
                this.valorDebito = (parseInt(this.paymentValueInput.value*100) + parseInt(this.paymentLatefeeInput.value*100) + parseInt(this.paymentInterestInput.value*100))/100;
                this.paymentShareUser1Input.value       = ((data.share_user1/100) * this.valorDebito).toFixed(2);
                this.paymentShareUser2Input.value       = ((data.share_user2/100) * this.valorDebito).toFixed(2);
                this.paymentShareUser3Input.value       = ((data.share_user3/100) * this.valorDebito).toFixed(2);
                this.paymentShareUser4Input.value       = ((data.share_user4/100) * this.valorDebito).toFixed(2);
                this.paymentShareUser5Input.value       = ((data.share_user5/100) * this.valorDebito).toFixed(2);
                this.paymentShareUser6Input.value       = ((data.share_user6/100) * this.valorDebito).toFixed(2);
            });
                // fill form with fetched data
                this.paymentRevenuesInput.value         = data.revenues;
                this.paymentValueInput.value            = data.value;
                this.paymentUser1Input.value            = data.user1; 
                this.paymentUser2Input.value            = data.user2; 
                this.paymentUser3Input.value            = data.user3; 
                this.paymentUser4Input.value            = data.user4; 
                this.paymentUser5Input.value            = data.user5; 
                this.paymentUser6Input.value            = data.user6;                
                this.paymentReconciledInput.checked     = verifiedChecked;
            } else {
                console.log('Erro ao receber dados do AJAX');
            }
        };
        xhr.send();

        // show modal
        this.paymentModal.classList.add('show');
        this.paymentModal.style.display = 'block';
        this.paymentModal.querySelector('.modal-title').textContent = 'Pagar';

        // set form action
        this.form.action = `${siteUrl}/${this.urlPagar}/${id}`;
    }
 
    novaDespesa() {
        // Reset the form
        this.form.reset();

        // Set the modal title
        const modalTitle = document.querySelector('.modal-title');
        modalTitle.textContent = 'Nova Despesa';

        // Set the form action
        this.form.action = `${siteUrl}/${this.urlAdicionar}`;
    }

    close() {
        this.modal.style.display = 'none';
        this.receiptModal.style.display = 'none';
    }
}