<?php

namespace App\Services;

use CodeIgniter\Email\Email;
use Config\Services;

class EmailService
{
    protected $email;

    public function __construct()
    {
        // Carrega a biblioteca de e-mail do CodeIgniter
        $this->email = Services::email();
    }

    /**
     * Envia um e-mail.
     *
     * @param string $to Endereço de e-mail do destinatário.
     * @param string $subject Assunto do e-mail.
     * @param string $message Conteúdo do e-mail.
     * @param string $from (Opcional) Endereço de e-mail do remetente.
     * @param string $fromName (Opcional) Nome do remetente.
     * @return bool Retorna true se o e-mail for enviado com sucesso, false caso contrário.
     */
    public function sendEmail(string $to, string $subject, string $message): bool
    {

        // Configura o destinatário, assunto e mensagem
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);

        // Tenta enviar o e-mail
        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', 'Erro ao enviar e-mail: ' . $this->email->printDebugger());
            return false;
        }
    }
}