<?php
namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Repositories\ExtratoRepository;
use App\Services\ExtratoService;

class Index extends BaseController 
{
    protected $extratoService;

    public function __construct()
    {
        $this->extratoService = new ExtratoService();
    }

    public function index(){
        $data = [
            'titulo' => 'Financeiro',
        ];
        $data['extrato'] = $this->extratoService->getExtratoPorConta(1);
        $print = $this->request->getGet('print');

        if($print){
            return view('financeiro/extratoImprimir', $data);
        }

        return view('financeiro/extrato', $data);
    }
    
    public function extrato($conta_id)
    {
    
    $extrato = $this->extratoService->getExtratoPorPeriodo($conta_id,"2025-02-01", "2025-02-28");
    
    echo '<pre>';
    print_r($extrato);
    
    
    }
}