<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carteira {

	protected $saldo;

	public function __construct($atributos) {
		$this->setSaldo($atributos['saldo']);
	}

	public function saque($valor = '') {
		if ($valor === '') {
			return array(
				'mensagem'  => 'Não foi informado o valor para o saque',
				'resultado' => false
			);
		}

		if (!is_numeric($valor)) {
			return array(
				'mensagem'  => 'O valor informado para o saque deve ser um número',
				'resultado' => false
			);
		}

		if ($valor <= 0) {
			return array(
				'mensagem'  => 'O valor informado para o saque deve ser maior do que zero',
				'resultado' => false
			);
		}
		if ($valor > $this->getSaldo()) {
			return array(
				'mensagem'  => 'Não há saldo disponível para o valor solicitado no saque',
				'resultado' => false
			);
		}

		$novoSaldo = $this->getSaldo() - $valor;
		$this->setSaldo($novoSaldo);

		return array(
			'mensagem'  => 'O saque foi realizado com sucesso',
			'resultado' => true
		);
	}

	public function deposito($valor = '') {
		if ($valor === '') {
			return array(
				'mensagem'  => 'Não foi informado o valor para o depósito',
				'resultado' => false
			);
		}

		if (!is_numeric($valor)) {
			return array(
				'mensagem'  => 'O valor informado para o depósito deve ser um número',
				'resultado' => false
			);
		}

		if ($valor <= 0) {
			return array(
				'mensagem'  => 'O valor informado para o depósito deve ser maior do que zero',
				'resultado' => false
			);
		}

		$novoSaldo = $this->getSaldo() + $valor;
		$this->setSaldo($novoSaldo);

		return array(
			'mensagem'  => 'O depósito foi realizado com sucesso',
			'resultado' => true
		);
	}

	public function getSaldo() {
		return $this->saldo;
	}

	public function setSaldo($saldo) {
		$this->saldo = $saldo;
	}

}