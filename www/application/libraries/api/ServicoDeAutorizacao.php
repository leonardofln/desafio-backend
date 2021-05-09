<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServicoDeAutorizacao extends Servico {

	public function __construct($atributos) {
		$this->setHttpRequest($atributos['httpRequest']);
		$this->setUrl($atributos['url']);
	}

	public function executa() {
		$this->getHttpRequest()->create($this->getUrl());
		$autorizacao = json_decode($this->getHttpRequest()->execute());
		if ($autorizacao && $autorizacao->message == 'Autorizado') {
			return true;
		}
		return false;
	}

}