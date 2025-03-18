<script>
    const BASE_URL = '<?= base_url() ?>';

    // Utility functions
    //Formata a data para o formato brasileiro
    const formatDate = timestamp => {
        const date = new Date(timestamp);
        return date.toLocaleDateString('pt-BR');
    };

    // Cria o link para o processo
    const createProcessLink = (numeroProcesso) => {
        return `${BASE_URL}/processos/editarpornumerodeprocesso/${numeroProcesso}`;
    };

    //Gera a mensagem de erro
    const handleFetchError = (error, elementId) => {
        console.error('Erro:', error);
        document.getElementById(elementId).innerHTML = `
                <div class="alert alert-danger">
                    Erro ao carregar informações. Tente novamente mais tarde.
                </div>
            `;
    };

    // Process data rendering
    const renderProcessItem = (item) => `
            <div class="list-group-item">
                <a href="${createProcessLink(item.numero_processo)}" class="text-primary">
                    ${item.numero_processo}
                </a>
                <p class="mb-1">${item.nome || 'Sem descrição'}</p>
                <small class="text-muted">Data: ${formatDate(item.dataHora)}</small>
            </div>
        `;
    // Renderiza os itens da intimação
    const renderIntimacaoItem = (item) => `
            <div class="list-group-item">
                <a href="${createProcessLink(item.numero_processo)}" class="text-primary">
                    ${item.numero_processo}
                </a>
                <p class="mb-1">${item.tipoComunicacao || 'Sem descrição'}</p>
                <small class="text-muted">Data: ${formatDate(item.data_disponibilizacao)}</small>
            </div>
        `;

    // Recebe os dados do Prcesso
    async function fetchProcessos() {
        try {
            const response = await fetch(`${BASE_URL}/processos/processosmovimentados/30`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();

            const container = document.getElementById('processoMovimentados');
            container.innerHTML = Array.isArray(data) && data.length > 0 ?
                data.map(renderProcessItem).join('') :
                '<div class="list-group-item">Nenhum processo encontrado</div>';
        } catch (error) {
            handleFetchError(error, 'processoMovimentados');
        }
    }

    // Recebe os dados das Intimações
    async function fetchIntimacoes() {
        try {
            const response = await fetch(`${BASE_URL}/intimacoes/intimacoesporperiodo/30`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();

            const container = document.getElementById('intimacoes');
            container.innerHTML = Array.isArray(data) && data.length > 0 ?
                data.map(renderIntimacaoItem).join('') :
                '<div class="list-group-item">Nenhuma intimação encontrada</div>';
        } catch (error) {
            handleFetchError(error, 'intimacoes');
        }
    }

    // Inicializa
    document.addEventListener('DOMContentLoaded', () => {
        fetchProcessos();
        fetchIntimacoes();
    });
</script>