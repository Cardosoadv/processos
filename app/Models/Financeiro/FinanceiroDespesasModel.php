<?php

namespace App\Models\Financeiro;

use CodeIgniter\Model;
use InvalidArgumentException;

class FinanceiroDespesasModel extends Model
{
    protected $table            = 'fin_despesas';
    protected $primaryKey       = 'id_despesa';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'despesa',
        'vencimento_dt',
        'valor',
        'categoria',
        'fornecedor',
        'comentario',
        'rateio',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Improved type definitions
    protected array $casts = [
    ];
    
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation rules
    protected $validationRules = [
        'despesa'       => 'required|string|max_length[255]',
        'vencimento_dt' => 'required|valid_date',
        'valor'         => 'required|numeric|greater_than[0]',
        'categoria'     => 'required|integer', // ID da categoria
        'fornecedor'    => 'required|integer', // ID do fornecedor
        'comentario'    => 'permit_empty|string',
        'rateio'        => 'permit_empty',
    ];

    protected $validationMessages = [
        'despesa' => [
            'required' => 'O campo despesa é obrigatório',
            'max_length' => 'O campo despesa não pode ter mais que 255 caracteres',
        ],
        'valor' => [
            'required' => 'O valor é obrigatório',
            'numeric' => 'O valor deve ser numérico',
            'greater_than' => 'O valor deve ser maior que zero',
        ],
        // Add other validation messages as needed
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['preparaSetRateio'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['preparaSetRateio'];
    protected $afterUpdate    = [];
    protected $beforeFind     = ['preparaGetRateio'];
    protected $afterFind      = ['preparaGetRateio'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Prepares the rateio field for retrieval by decoding JSON
     * @param array $data The data array from the database
     * @return array The processed data array
     */
    protected function preparaGetRateio(array $data): array
    {
        if (!isset($data['data'])) {
            return $data;
        }

        if (is_array($data['data'])) {
            foreach ($data['data'] as &$row) {
                if (isset($row['rateio'])) {
                    try {
                        $decodedRateio = json_decode($row['rateio'], true);
                        $row['rateio'] = $decodedRateio === null ? [] : $decodedRateio;
                    } catch (\Exception $e) {
                        log_message('error', 'Error decoding rateio JSON: ' . $e->getMessage());
                        $row['rateio'] = [];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Prepares the rateio field for storage by encoding to JSON
     * @param array $data The data array to be stored
     * @return array The processed data array
     * @throws InvalidArgumentException If rateio is invalid
     */
    protected function preparaSetRateio(array $data): array
    {
        if (!isset($data['data']['rateio'])) {
            return $data;
        }

        $rateio = $data['data']['rateio'];

        if (is_array($rateio)) {
            try {
                // Validate rateio structure if needed
                $this->validateRateioStructure($rateio);
                
                $data['data']['rateio'] = json_encode($rateio, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                throw new InvalidArgumentException('Invalid rateio data structure: ' . $e->getMessage());
            }
        } else {
            $data['data']['rateio'] = null;
        }

        return $data;
    }

    /**
     * Validates the structure of the rateio array
     * @param array $rateio The rateio data to validate
     * @throws InvalidArgumentException If the structure is invalid
     */
    private function validateRateioStructure(array $rateio): void
    {
        // Add your validation logic here
        // Example:
        foreach ($rateio as $item) {
            if (!is_array($item)) {
                throw new InvalidArgumentException('Each rateio item must be an array');
            }
            
            // Add more specific validation rules based on your needs
            if (!isset($item['valor']) || !is_numeric($item['valor'])) {
                throw new InvalidArgumentException('Each rateio item must have a numeric valor');
            }
        }
    }

    /**
     * Get total value of expense
     * @param array $rateio The rateio array
     * @return float The total value
     */
    public function calculateTotal(array $rateio): float
    {
        return array_reduce($rateio, function($carry, $item) {
            return $carry + ($item['valor'] ?? 0);
        }, 0.0);
    }
}