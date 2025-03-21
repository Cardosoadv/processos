<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    protected $navData; // Propriedade para armazenar os dados de nav()

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->navData = $this->nav();

        // E.g.: $this->session = \Config\Services::session();
    }

    private function nav(){
        
        $mensagensModel = model('MensagensModel');
        $despesasModel = model('Financeiro/FinanceiroDespesasModel');
        $tarefasModel = model('TarefasModel');

        $data['mensagensNaoLidas'] = $mensagensModel->mensagensNaoLidasPorDestinatario(user_id()) ?: [];
        $data['qteMensagensNaoLidas'] = count($data['mensagensNaoLidas']);


        $data['tarefasUsuario'] = $tarefasModel ->where('responsavel', user_id())
                                        ->whereNotIn('status', [4,5])
                                        ->get()->getResultArray() ?: [];

        $data['qteTarefas'] = count($data['tarefasUsuario']);
        $data['qteDespesasNaoPagas'] = $despesasModel->contarDespesasNaoPagas();
        
        $data['notificacoes'] = $data['qteMensagensNaoLidas'] + $data['qteTarefas'] + $data['qteDespesasNaoPagas']; 


        return $data;
    }

    // Método para carregar as views com os dados de nav()
    public function loadView($view, $data = [])
    {
        $data = array_merge($data, $this->navData); // Mescla os dados de nav() com os dados da view
        return view($view, $data);
    }

}
