<?php

namespace App\Controllers;


class Usuarios extends BaseController
{
    
    private $userImageModel;

    public function __construct()
    {
        $this->userImageModel = model('UserImageModel');
    }

    public function index(){

    }

    public function salvar(){
        $id = $this->request->getPost('id');
        $img = $this->request->getFile('foto-perfil');
        
        // Verifica se o arquivo foi enviado e é válido
        if (!$img->isValid()) {
            return redirect()->back()->with('error', 'Nenhum arquivo foi enviado ou o upload falhou');
        }
        
        // Verificar se o arquivo é uma imagem
        $tipoArquivo = $img->getClientMimeType();
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($tipoArquivo, $tiposPermitidos)) {
            return redirect()->back()->with('error', 'O arquivo enviado não é uma imagem válida');
        }
        
        // Verificar tamanho do arquivo (máximo 2MB)
        if ($img->getSize() > 2097152) { // 2MB em bytes
            return redirect()->back()->with('error', 'A imagem deve ter menos de 2MB');
        }
        
        // Verifica se o usuário já possui uma imagem
        $imagemExistente = $this->userImageModel->where('user_id', $id)->first();
        
        // Gera um nome único para o arquivo
        $novoNomeArquivo = $img->getRandomName();
        
        // Cria o diretório se não existir
        $diretorioDestino = WRITEPATH . 'uploads/userImgs/' . $id;
        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0777, true);
        }
        
        // Move o arquivo para o diretório
        $img->move($diretorioDestino, $novoNomeArquivo);
        
        // Dados para salvar no banco
        $dadosImagem = [
            'user_id' => $id,
            'image_path' => $novoNomeArquivo
        ];
        
        // Apaga a imagem anterior se existir
        if ($imagemExistente) {
            $caminhoImagemAntiga = $diretorioDestino . '/' . $imagemExistente['image_path'];
            if (file_exists($caminhoImagemAntiga)) {
                unlink($caminhoImagemAntiga);
            }
            
            // Atualiza o registro existente
            $dadosImagem['id'] = $imagemExistente['id'];
        }
        
        // Salva/atualiza no banco de dados
        $this->userImageModel->save($dadosImagem);

        return redirect()->to('/')->with('success', 'Imagem de perfil atualizada com sucesso');
    }

    /**
     * Exibe a foto do perfil do usuário
     * 
     * @param int $id ID do usuário
     * @return \CodeIgniter\HTTP\Response
     */
    public function exibirFoto($id = null)
    {
        // Verifica se o ID foi fornecido
        if ($id === null) {
            return $this->response->setStatusCode(404, 'ID do usuário não fornecido');
        }

        // Busca a imagem no banco de dados
        $userImage = $this->userImageModel->where('user_id', $id)->first();
        
        // Verifica se o usuário tem uma imagem
        if ($userImage === null) {
            return $this->response->setStatusCode(404, 'Imagem não encontrada');
        }

        // Caminho completo para a imagem
        $caminhoImagem = WRITEPATH . 'uploads/userImgs/' . $id . '/' . $userImage['image_path'];
        
        // Verifica se o arquivo existe
        if (!file_exists($caminhoImagem)) {
            return $this->response->setStatusCode(404, 'Arquivo de imagem não encontrado');
        }

        // Obtém o tipo MIME do arquivo
        $mimeType = mime_content_type($caminhoImagem);
        
        // Lê o conteúdo do arquivo
        $conteudoImagem = file_get_contents($caminhoImagem);
        
        // Retorna a imagem com o cabeçalho correto
        return $this->response
            ->setContentType($mimeType)
            ->setBody($conteudoImagem);
    }
}