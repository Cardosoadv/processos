// Formata Data
export function inputData(data){
    // Expressão regular para extrair partes da data
    const regex = /^(\d{1,2})(\d{1,2})(\d{4})$/;

    // Extrair dia, mês e ano da string
    const partesData = regex.exec(data);
    if (!partesData) {
        return "Data inválida";
    }
    const dia = partesData[1];
    const mes = partesData[2];
    const ano = partesData[3];

    // Retornar data formatada
    return `${dia}/${mes}/${ano}`;
}

// Formata o número de Telefone (00) 0000-0000
export function inputTelefone(fone){
const regex = /^(\d{2})(\d{5})(\d{4})$/;
const partesTel = regex.exec(fone);
if (!partesTel){
    return "Telefone inválido!";
}
const ddd = partesTel[1];
const parte1 = partesTel[2];
const parte2 = partesTel[3];

//retornar telefone formatado
return `(${ddd}) ${parte1}-${parte2}`;
}

// Formata o valor para o padrão brasileiro
export function formataValor(valor) {
    var valorBr = valor.replace(".", ",");
    const arrvbr = valorBr.split(",");
    const inteiros = arrvbr[0];
    const decimais = arrvbr[1] || "00"; // garante que há pelo menos dois dígitos decimais
    const inteirosArr = inteiros.split("");
    if (inteirosArr.length > 6) {
        inteirosArr.splice(inteirosArr.length - 6, 0, "."); // insere um ponto antes dos últimos 3 dígitos
    }
    if (inteirosArr.length > 3) {
        inteirosArr.splice(inteirosArr.length - 3, 0, "."); // insere um ponto antes dos últimos 3 dígitos
    }
    const valorFormatado = inteirosArr.join("") + "," + decimais; // junta os elementos do array em uma string novamente
    console.log(valorFormatado);
    return valorFormatado;
}

// Formata a data para exibir
export function dateFormat(date) { 
    var dateArray = date.split("-");
    var year = dateArray[0];
    var month = dateArray[1];
    var day = dateArray[2];
    const newDate = day + "/" + month + "/" + year;
    return newDate;
}
/*
*@param dataPagamento
*
*/
export function calcularJuros(dataPagamento, dataVencimento, taxaJuros) {
    // Converte as datas para objetos Date

    const dataPagamentoObj = new Date(dataPagamento);
    const dataVencimentoObj = new Date(dataVencimento);
  
    // Calcula a diferença em dias entre as datas
    const diferencaDias = Math.floor((dataPagamentoObj - dataVencimentoObj) / (1000 * 60 * 60 * 24));
  
    // Se a data de pagamento for posterior à data de vencimento, calcula os juros
    if (diferencaDias > 0) {
      const valorJuros = (diferencaDias * taxaJuros) / 100;
      return valorJuros.toFixed(2);
    }
  
    // Se a data de pagamento for igual ou anterior à data de vencimento, não há juros
    return 0;
  }

  export function calcularMulta(dataPagamento, dataVencimento, taxaMulta) {
    // Converte as datas para objetos Date
    const dataPagamentoObj = new Date(dataPagamento);
    const dataVencimentoObj = new Date(dataVencimento);
  
    // Calcula a diferença em dias entre as datas
    const diferencaDias = Math.floor((dataPagamentoObj - dataVencimentoObj) / (1000 * 60 * 60 * 24));
  
    // Se a data de pagamento for posterior à data de vencimento, calcula os juros
    if (diferencaDias > 0) {
      const valorMulta = (taxaMulta) / 100;
      return valorMulta.toFixed(2);
    }
  
    // Se a data de pagamento for igual ou anterior à data de vencimento, não há juros
    return 0;
  }