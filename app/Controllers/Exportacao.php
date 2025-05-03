<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Exportacao extends BaseController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Verifique se a biblioteca PhpSpreadsheet está instalada
        if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            die('A biblioteca PhpSpreadsheet não está instalada. Execute: composer require phpoffice/phpspreadsheet');
        }
    }

    /**
     * Método para gerar e baixar um arquivo Excel a partir de um array de dados
     * 
     * @param array $dados Array de dados para exportar
     * @param string $nomeArquivo Nome do arquivo Excel (sem extensão)
     * @param array $cabecalhos Cabeçalhos das colunas (opcional)
     * @return void
     */
    public function gerarExcel($dados = null, $nomeArquivo = 'exportacao', $cabecalhos = [])
    {
        // Se os dados não foram fornecidos, use alguns dados de exemplo
        if ($dados === null) {
            $dados = $this->exemplosDados();
            $cabecalhos = ['ID', 'Nome', 'Email', 'Telefone', 'Data'];
        }

        // Crie uma nova instância do Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configure a planilha
        $sheet->setTitle('Dados Exportados');
        
        // Adicione os cabeçalhos se fornecidos
        $coluna = 1;
        if (!empty($cabecalhos)) {
            foreach ($cabecalhos as $cabecalho) {
                $sheet->setCellValue([$coluna, 1], $cabecalho);
                // Estilize os cabeçalhos
                $sheet->getStyle($coluna, 1)->getFont()->setBold(true);
                $coluna++;
            }
        }
        
        // Adicione os dados
        $linha = empty($cabecalhos) ? 1 : 2;
        foreach ($dados as $registro) {
            $coluna = 1;
            foreach ($registro as $valor) {
                $sheet->setCellValue([$coluna, $linha], $valor);
                $coluna++;
            }
            $linha++;
        }
        
        // Auto-dimensione as colunas
        $highestColumn = $sheet->getHighestDataColumn();
        $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        for ($i = 1; $i <= $columnIndex; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
        
        // Crie o escritor para salvar o arquivo
        $writer = new Xlsx($spreadsheet);
        
        // Configure os cabeçalhos HTTP
        $response = service('response');
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment;filename="' . $nomeArquivo . '.xlsx"');
        $response->setHeader('Cache-Control', 'max-age=0');
        
        // Escreva o arquivo para a saída
        ob_start();
        $writer->save('php://output');
        $fileContent = ob_get_clean();
        
        return $response->setBody($fileContent)->send();
    }
    
    /**
     * Método de exemplo para exportar dados
     */
    public function exportar()
    {
        // Aqui você normalmente buscaria os dados de um modelo
        $dados = $this->exemplosDados();
        $cabecalhos = ['ID', 'Nome', 'Email', 'Telefone', 'Data'];
        
        // Gere e baixe o arquivo Excel
        return $this->gerarExcel($dados, 'usuarios_exportados', $cabecalhos);
    }
    
    /**
     * Método para fornecer dados de exemplo
     * 
     * @return array
     */
    private function exemplosDados()
    {
        return [
            ['1', 'João Silva', 'joao@exemplo.com', '(11) 98765-4321', '2023-01-15'],
            ['2', 'Maria Santos', 'maria@exemplo.com', '(21) 91234-5678', '2023-02-20'],
            ['3', 'Pedro Oliveira', 'pedro@exemplo.com', '(31) 99876-5432', '2023-03-25'],
            ['4', 'Ana Costa', 'ana@exemplo.com', '(41) 98765-1234', '2023-04-30'],
            ['5', 'Lucas Pereira', 'lucas@exemplo.com', '(51) 91234-9876', '2023-05-10']
        ];
    }
    
    /**
     * Método para exportar dados específicos de um modelo
     */
    public function exportarDados()
    {
        // Carregue o modelo que contém os dados
        // Exemplo: $model = new \App\Models\UsuarioModel();
        // $dados = $model->findAll();
        
        // Aqui estamos usando dados de exemplo
        $dados = $this->exemplosDados();
        $cabecalhos = ['ID', 'Nome', 'Email', 'Telefone', 'Data'];
        
        // Gere e baixe o arquivo Excel
        return $this->gerarExcel($dados, 'dados_exportados', $cabecalhos);
    }
    
    /**
     * Método para mostrar um formulário que permite ao usuário exportar dados
     */
    public function index()
    {
        return view('exportacao/index');
    }
}