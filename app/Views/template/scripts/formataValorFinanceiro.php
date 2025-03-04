<script>
function formatarNumero(valor) {
    if (!valor) return '';
    
    // Substitui ponto por vírgula
    valor = valor.replace(/\./g, ',');
    
    // Remove tudo que não é número ou vírgula
    valor = valor.replace(/[^\d,]/g, '');
    
    // Garante que só existe uma vírgula
    const partes = valor.split(',');
    if (partes.length > 2) {
        valor = partes[0] + ',' + partes[1];
    }
    
    // Se existir parte decimal, limita a 2 dígitos
    if (partes.length === 2) {
        valor = partes[0] + ',' + partes[1].substring(0, 2);
    }
    
    return valor;
}

function formatarAoPerderFoco(valor) {
    if (!valor) {
        return '0,00';
    }
    
    // Se não tem vírgula, adiciona ,00
    if (!valor.includes(',')) {
        valor = valor + ',00';
    } else {
        // Se tem vírgula mas não tem 2 casas decimais
        const partes = valor.split(',');
        if (partes[1].length === 0) {
            valor = valor + '00';
        } else if (partes[1].length === 1) {
            valor = valor + '0';
        }
    }
    
    // Formata com separadores de milhar
    const partes = valor.split(',');
    const parteInteira = partes[0].replace(/\D/g, '');
    const numeroFormatado = Number(parteInteira).toLocaleString('pt-BR').replace(/,/g, '.') + ',' + partes[1];
    
    return numeroFormatado;
}

// Função auxiliar para adicionar os eventos a um campo
function configurarCampoMonetario(idCampo) {
    const campo = document.getElementById(idCampo);
    if (!campo) return;

    // Evento input
    campo.addEventListener('input', function(e) {
        let valorAtual = e.target.value;
        e.target.value = formatarNumero(valorAtual);
    });

    // Evento blur
    campo.addEventListener('blur', function(e) {
        let valor = e.target.value;
        e.target.value = formatarAoPerderFoco(valor);
    });
}
</script>
