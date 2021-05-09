<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServicoDeNotificacao extends Servico {

	public function __construct($atributos) {
		$this->setHttpRequest($atributos['httpRequest']);
		$this->setUrl($atributos['url']);
	}

	public function executa($valor, $usuario) {
		if (is_numeric($valor) && is_object($usuario)) {
			$this->getHttpRequest()->create($this->getUrl());
			$this->getHttpRequest()->post(
				array(
					'nome' 	   => $usuario->getNome(),
					'email'    => $usuario->getEmail(),
					'mensagem' => 'Você recebeu um pagamento no valor de R$' . $valor,
				)
			);
			$notificacao = json_decode($this->getHttpRequest()->execute());
			if ($notificacao && $notificacao->message == 'Success') {
				return true;
			}
		}
		return false;
	}

}