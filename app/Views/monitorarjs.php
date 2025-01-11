<!DOCTYPE html>
<html>
<head>
    <title>Recebendo Intimações</title>
    
</head>
<body>
<h1>Recebendo Intimações</h1>
    <div class="loader" id="loader"></div>
    <div id="content" style="display: none;"> <p>API URL: <span id="api-url"></span></p>
        <div id="response"></div>
    </div>

<script>
    async function fazerRequisicao(numeroProcesso) {
        const url = '<?= $urlAtual?>'; // Substitua pela URL real
        const apiKey = '<?= $apiKey?>';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                        'Authorization': apiKey,
                        'Content-Type': 'application/json',
                        'x-li-format': 'json'
                },
                body: JSON.stringify({
                    query: {
                        match: {
                            numeroProcesso: numeroProcesso
                        }
                        }
                    })
                });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Erro na requisição: ${response.status} - ${errorText}`);
            }

            const resultados = await response.json();
            return resultados;
        } catch (error) {
            console.error("Erro ao fazer a requisição:", error);
            throw error; // Re-lança o erro para tratamento posterior, se necessário.
        }
    }

// Exemplo de uso:
const numero = '<?= $numeroProcesso?>';
fazerRequisicao(numero)
    .then(data => {
    console.log("Resposta:", data);
    // Faça algo com os dados recebidos
    })
    .catch(error => {
    // Trate o erro
    });

</script>
</body>
</html>