<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ProcessoService;
use CodeIgniter\API\ResponseTrait;

/**
 * Controller for managing process-related objects
 * 
 * Handles operations for listing, saving, and deleting objects associated with processes
 * using a ProcessoService for data management and interactions
 */
class ProcessoObjeto extends BaseController
{
    use ResponseTrait;
    protected ProcessoService $processoService;

    public function __construct()
    {
        $this->processoService = new ProcessoService();
    }

    public function index()
    {
        $objetos = $this->processoService->listarObjetos();
        echo '<pre>';
        print_r($objetos);
        //return $this->loadView('objetos/listar', ['objetos' => $objetos]);
    }

    /**
     * Saves an object associated with a process
     * 
     * Retrieves POST data, attempts to save the object via service,
     * and redirects to the process detail page with a success or error message
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to process detail page
     */
    public function salvar()
    {
        $dados = $this->request->getPost();


        $id = $this->processoService->salvarObjeto($dados);
        if ($id) {
            return redirect()->to(base_url('processos/consultarprocesso/') . $dados['processo_id'])->with('success', 'Objeto salvo com sucesso!');
        } else {
            return redirect()->to(base_url('processos/consultarprocesso/') . $dados['processo_id'])->with('erro', 'Erro ao salvar o objeto.');
        }
    }
 
    /**
     * Deletes an object by its identifier
     * 
     * Removes an object from the system using the provided identifier
     * and redirects back to the previous page with a success message
     * 
     * @param int $id The unique identifier of the object to be deleted
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to the previous page
     */
    public function deletar(int $id)
    {
        $this->processoService->deletarObjeto($id);
        return redirect()->back()->with('success', 'Objeto deletado com sucesso!');
    }
}
