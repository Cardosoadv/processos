<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdicionarStoredProcedureDespesas extends Migration
{
    public function up()
    {
        // Primeiro, criar a tabela permanente incluindo o conta_id
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'conta_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'ano' => [
                'type'       => 'INT',
                'constraint' => 4,
            ],
            'mes' => [
                'type'       => 'INT',
                'constraint' => 2,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['conta_id', 'ano', 'mes']);
        $this->forge->createTable('fin_saldo_mensal_despesas');

        // Em seguida, criar a stored procedure que insere na tabela permanente
        $sql = <<<SQL
        CREATE PROCEDURE CalcularTotalDespesasMensal()
        BEGIN
            -- Limpa os dados antigos da tabela
            TRUNCATE TABLE fin_saldo_mensal_despesas;
            
            -- Insere os novos resultados na tabela permanente, agora agrupando por conta_id
            INSERT INTO fin_saldo_mensal_despesas (conta_id, ano, mes, total, created_at)
            SELECT
                conta_id,
                YEAR(pagamento_despesa_dt) as ano,
                MONTH(pagamento_despesa_dt) as mes,
                SUM(valor) as total,
                NOW() as created_at
            FROM fin_pgto_despesas
            WHERE deleted_at IS NULL
            GROUP BY conta_id, YEAR(pagamento_despesa_dt), MONTH(pagamento_despesa_dt);
            
            -- Retorna os dados inseridos
            SELECT * FROM fin_saldo_mensal_despesas ORDER BY conta_id, ano, mes;
        END;
        SQL;

        $this->db->query($sql);

        // Adicionar trigger para inserção
        $sqlTriggerInsert = <<<SQL
        CREATE TRIGGER tr_despesas_insert AFTER INSERT ON fin_pgto_despesas
        FOR EACH ROW
        BEGIN
            DECLARE v_ano INT;
            DECLARE v_mes INT;
            DECLARE v_count INT;
            
            -- Obter ano e mês da nova despesa
            SET v_ano = YEAR(NEW.pagamento_despesa_dt);
            SET v_mes = MONTH(NEW.pagamento_despesa_dt);
            
            -- Verificar se já existe registro para esta conta/ano/mês
            SELECT COUNT(*) INTO v_count 
            FROM fin_saldo_mensal_despesas 
            WHERE conta_id = NEW.conta_id AND ano = v_ano AND mes = v_mes;
            
            IF v_count > 0 THEN
                -- Atualizar o registro existente
                UPDATE fin_saldo_mensal_despesas
                SET total = total + NEW.valor,
                    updated_at = NOW()
                WHERE conta_id = NEW.conta_id AND ano = v_ano AND mes = v_mes;
            ELSE
                -- Inserir novo registro
                INSERT INTO fin_saldo_mensal_despesas (conta_id, ano, mes, total, created_at, updated_at)
                VALUES (NEW.conta_id, v_ano, v_mes, NEW.valor, NOW(), NOW());
            END IF;
        END;
        SQL;

        $this->db->query($sqlTriggerInsert);

        // Adicionar trigger para atualização
        $sqlTriggerUpdate = <<<SQL
        CREATE TRIGGER tr_despesas_update AFTER UPDATE ON fin_pgto_despesas
        FOR EACH ROW
        BEGIN
            DECLARE v_ano_old INT;
            DECLARE v_mes_old INT;
            DECLARE v_ano_new INT;
            DECLARE v_mes_new INT;
            DECLARE v_count_new INT;
            
            -- Se o registro foi marcado como excluído (soft delete)
            IF NEW.deleted_at IS NOT NULL AND OLD.deleted_at IS NULL THEN
                -- Remover valor da tabela de saldos
                UPDATE fin_saldo_mensal_despesas
                SET total = total - OLD.valor,
                    updated_at = NOW()
                WHERE conta_id = OLD.conta_id 
                  AND ano = YEAR(OLD.pagamento_despesa_dt) 
                  AND mes = MONTH(OLD.pagamento_despesa_dt);
                
            -- Se a data, valor ou conta foi alterado
            ELSEIF NEW.deleted_at IS NULL THEN
                SET v_ano_old = YEAR(OLD.pagamento_despesa_dt);
                SET v_mes_old = MONTH(OLD.pagamento_despesa_dt);
                SET v_ano_new = YEAR(NEW.pagamento_despesa_dt);
                SET v_mes_new = MONTH(NEW.pagamento_despesa_dt);
                
                -- Se a data, conta ou ambos mudaram
                IF v_ano_old != v_ano_new OR v_mes_old != v_mes_new OR OLD.conta_id != NEW.conta_id THEN
                    -- Remover valor do registro antigo
                    UPDATE fin_saldo_mensal_despesas
                    SET total = total - OLD.valor,
                        updated_at = NOW()
                    WHERE conta_id = OLD.conta_id AND ano = v_ano_old AND mes = v_mes_old;
                    
                    -- Verificar se já existe registro para a nova conta/ano/mês
                    SELECT COUNT(*) INTO v_count_new 
                    FROM fin_saldo_mensal_despesas 
                    WHERE conta_id = NEW.conta_id AND ano = v_ano_new AND mes = v_mes_new;
                    
                    IF v_count_new > 0 THEN
                        -- Adicionar valor ao novo registro
                        UPDATE fin_saldo_mensal_despesas
                        SET total = total + NEW.valor,
                            updated_at = NOW()
                        WHERE conta_id = NEW.conta_id AND ano = v_ano_new AND mes = v_mes_new;
                    ELSE
                        -- Criar novo registro
                        INSERT INTO fin_saldo_mensal_despesas (conta_id, ano, mes, total, created_at, updated_at)
                        VALUES (NEW.conta_id, v_ano_new, v_mes_new, NEW.valor, NOW(), NOW());
                    END IF;
                
                -- Se apenas o valor mudou (mesma conta e período)
                ELSEIF OLD.valor != NEW.valor THEN
                    UPDATE fin_saldo_mensal_despesas
                    SET total = total - OLD.valor + NEW.valor,
                        updated_at = NOW()
                    WHERE conta_id = NEW.conta_id AND ano = v_ano_new AND mes = v_mes_new;
                END IF;
            END IF;
        END;
        SQL;

        $this->db->query($sqlTriggerUpdate);
    }

    public function down()
    {
        // Remove os triggers
        $this->db->query('DROP TRIGGER IF EXISTS tr_despesas_insert');
        $this->db->query('DROP TRIGGER IF EXISTS tr_despesas_update');

        // Remove a stored procedure
        $this->db->query('DROP PROCEDURE IF EXISTS CalcularTotalDespesasMensal');

        // Remove a tabela permanente
        $this->forge->dropTable('fin_saldo_mensal_despesas');
    }
}