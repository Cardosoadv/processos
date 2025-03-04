<?php

namespace App\Controllers;

use App\Traits\FormataValorTrait;
use App\Traits\ValidacoesTrait;
use CodeIgniter\HTTP\Files\UploadedFile;

class ImportacaoClientes extends BaseController
{
    
    use ValidacoesTrait;
    use FormataValorTrait;
    
    protected $clientesModel;

    public function __construct()
    {
        $this->clientesModel = model('ClientesModel');
    }


    /**
     * Exibe a página de upload de arquivo TXT
     */
    public function index()
    {
        $data['titulo'] = 'Importação de Clientes';
        return view('importacao/index', $data);
    }

    /**
     * Processa o arquivo TXT enviado
     */
    public function importar()
    {
        
        $validationRule = [
            'arquivo_txt' => [
                'label' => 'Arquivo TXT',
                'rules' => 'uploaded[arquivo_txt]|mime_in[arquivo_txt,text/csv,text/plain]|max_size[arquivo_txt,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {

            $data = ['errors' => $this->validator->getErrors()];
            $data['titulo'] = 'Erro na importação de Clientes';
            return view('importacao/index', $data);
        }

        $arquivo = $this->request->getFile('arquivo_txt');

        if (!$arquivo->isValid()) {
            return redirect()->back()->with('error', 'Arquivo inválido');
        }

        $resultado = $this->processarArquivoTxt($arquivo);

        return redirect()->to('importacaoclientes')->with('message', $resultado);
    }

    /**
     * Processa o arquivo TXT e importa os dados
     * 
     * @param UploadedFile $arquivo
     * @return string Mensagem com o resultado da importação
     */
    private function processarArquivoTxt(UploadedFile $arquivo)
    {
        $newName = $arquivo->getRandomName();
        $arquivo->move(WRITEPATH . 'uploads', $newName);
        $filepath = WRITEPATH . 'uploads/' . $newName;

        $handle = fopen($filepath, 'r');
        if ($handle === false) {
            return 'Não foi possível abrir o arquivo';
        }

        $linhaProcessada = 0;
        $linhasSucesso = 0;
        $linhasErro = 0;
        $linhaRepetida = 0;

        while (($linha = fgets($handle)) !== false) {
            $linhaProcessada++;
            $linha = trim($linha);

            // Extrair valores usando expressões regulares
            preg_match("/'nome'\s*=>\s*'([^']+)'/", $linha, $matchesNome);
            preg_match("/'tipo_cliente'\s*=>\s*'([^']+)'/", $linha, $matchesTipo);
            preg_match("/'documento'\s*=>\s*'([^']+)'/", $linha, $matchesDocumento);
            preg_match("/'dataAquisicao'\s*=>\s*'([^']+)'/", $linha, $matchesData);
            preg_match("/'ativo'\s*=>\s*'([^']+)'/", $linha, $matchesAtivo);

            if (
                !isset($matchesNome[1]) ||
                !isset($matchesTipo[1]) ||
                !isset($matchesDocumento[1]) ||
                !isset($matchesData[1]) ||
                !isset($matchesAtivo[1])
            ) {
                $linhasErro++;
                continue;
            }

            $dados = [
                'nome' => $matchesNome[1],
                'tipo_cliente' => $matchesTipo[1],
                'documento' => $matchesDocumento[1],
                'dataAquisicao' => $matchesData[1],
                'ativo' => $matchesAtivo[1],
            ];

            // Tratamento de dados
            $dados['ativo'] = (strtolower($dados['ativo']) === 'sim' || strtolower($dados['ativo']) === 'true' || $dados['ativo'] === '1') ? 1 : 0;
            $dados['dataAquisicao'] = $this->excelSerialDateToDate($dados['dataAquisicao']);
            $dados['documento'] = preg_replace('/[^0-9]/', '', $dados['documento']);

            if (empty($dados['nome']) || empty($dados['documento'])){
                $linhasErro++;
                continue;
            }
            
            $dados['documento'] = $this->formataValorCpf_cnpj($dados['documento']); 
            // Verifica se o cliente já existe no banco de dados
            if ($this->clientesModel->jaExisteCliente($dados['documento'])) {
                $linhaRepetida++;
                continue;
            }

            try {
                $this->clientesModel->insert($dados);
                $linhasSucesso++;
            } catch (\Exception $e) {
                log_message('error', 'Erro ao importar linha ' . $linhaProcessada . ': ' . $e->getMessage());
                $linhasErro++;
            }
        }

        fclose($handle);
        unlink($filepath);

        return "Importação concluída: $linhasSucesso cliente(s) importado(s) com sucesso, $linhasErro erro(s), $linhaRepetida linha(s) repetida(s).";
        }

        private function excelSerialDateToDate($excelSerialDate)
    {
        $unixTimestamp = ($excelSerialDate - 25569) * 86400;
        return date('Y-m-d', $unixTimestamp);
    }
}