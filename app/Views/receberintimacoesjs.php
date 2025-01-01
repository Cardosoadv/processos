<!DOCTYPE html>
<html>
<head>
    <title>Recebendo Intimações</title>
    <style>
        .loader {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #content {
            display: none; /* Initially hide the content */
        }
    </style>
</head>
<body>
<h1>Recebendo Intimações</h1>
    <div class="loader" id="loader"></div>
    <div id="content" style="display: none;"> <p>API URL: <span id="api-url"></span></p>
        <div id="response"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const apiUrlElement = document.getElementById('api-url');
            const loader = document.getElementById('loader');
            const content = document.getElementById('content');
            const responseDiv = document.getElementById('response');

            apiUrlElement.textContent = '<?= $apiUrl ?>';

            function sendDataToProcessarIntimacoes(jsonData) { // Nome da variável mais descritivo
                fetch('<?= base_url('testes/processarIntimacoes') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Importante: Content-Type correto
                    },
                    body: JSON.stringify(jsonData) // Envia o JSON diretamente
                })
                .then(response => {
                    if (!response.ok) { // Verifica se a resposta foi bem sucedida
                        throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
                    }
                    return response.text(); // ou response.json() se o backend retornar JSON
                })
                .then(responseData => {
                    console.log('Dados enviados com sucesso:', responseData);
                    window.location.href = "<?= base_url('processos') ?>";
                })
                .catch(error => {
                    console.error('Erro ao enviar dados:', error);
                    responseDiv.textContent = 'Erro ao processar intimações: ' + error.message;
                    loader.style.display = 'none';
                    content.style.display = 'block';
                });
            }

            function fetchIntimacoes() {
                fetch('<?= $apiUrl ?>')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                      responseDiv.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                        sendDataToProcessarIntimacoes(data); // Agora passando o 'data' correto
                    })
                    .catch(error => {
                        console.error('Erro ao buscar intimações:', error);
                        responseDiv.textContent = 'Erro ao buscar intimações: ' + error.message;
                        loader.style.display = 'none';
                        content.style.display = 'block';
                    });
            }

            fetchIntimacoes();
        });
    </script>
</body>
</html>