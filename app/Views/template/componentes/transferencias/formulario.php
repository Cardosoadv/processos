<?php
$contas = model('Financeiro/FinanceiroContasModel')->orderBy('conta')->findAll();
?>

<form method="post" id="form_transferencia" name="form_transferencia" action="<?= site_url('financeiro/transferencias/salvar') ?>">
    <input type="hidden" name="id_transferencia" value="<?= $transferencia['id_transferencia'] ?? '' ?>">

    <div class="row mb-3">
        <div class="form-group col">
            <label for="transferencia">Transferencia</label>
            <input type="text" class="form-control" name="transferencia" id="transferencia" value="<?= $transferencia['transferencia'] ?? '' ?>" required>
        </div>
        <div class="form-group col">
            <label for="data_transferencia">Data</label>
            <input type="date" class="form-control" name="data_transferencia" id="data_transferencia" value="<?= $transferencia['data_transferencia'] ?? '' ?>" required>
        </div>
        <div class="form-group col">
            <label for="valor">Valor</label>
            <input type="text" class="form-control" name="valor" id="valor" value="<?= $transferencia['valor'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">

        <div class="form-group col">
            <label for="id_conta_origem">De:</label>
            <select class="form-control" name="id_conta_origem" id="id_conta_origem">
                <option value="">Selecione...</option>
                <?php foreach ($contas as $conta) : ?>
                    <option value="<?= $conta['id_conta'] ?>" <?= ($transferencia['id_conta_origem'] ?? '') == $conta['id_conta'] ? 'selected' : '' ?>>
                        <?= $conta['banco'] . ' - ' . $conta['conta'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col">
            <label for="id_conta_destino">Para:</label>
            <select class="form-control" name="id_conta_destino" id="id_conta_destino">
                <option value="">Selecione...</option>
                <?php foreach ($contas as $conta) : ?>
                    <option value="<?= $conta['id_conta'] ?>" <?= ($transferencia['id_conta_destino'] ?? '') == $conta['id_conta'] ? 'selected' : '' ?>>
                        <?= $conta['banco'] . ' - ' . $conta['conta'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-group col">
            <label for="comentarios">Comentários</label>
            <textarea class="form-control" name="comentarios" id="comentarios"><?= $transferencia['comentarios'] ?? '' ?></textarea>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('financeiro/transferencias/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<script>

    // Função para formatar o valor monetário
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

// Formata os valores iniciais ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const campos = ['valor', 'valorCausa', 'valorCondenacao'];
    
    campos.forEach(idCampo => {
        const campo = document.getElementById(idCampo);
        if (campo) {
            let valor = campo.value;
            if (valor) {
                valor = formatarNumero(valor);
                valor = formatarAoPerderFoco(valor);
                campo.value = valor;
            }
        }
        
        // Configura os eventos para cada campo
        configurarCampoMonetario(idCampo);
    });
});
</script>