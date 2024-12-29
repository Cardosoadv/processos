<?php

namespace App\Models;

use CodeIgniter\Model;


class ResposavelModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    public function getUsers()
    {
        $users = $this->table('users')
            ->select('id, username')
            ->where('active', 1)
            ->findAll();
        return $users;
    }
}
