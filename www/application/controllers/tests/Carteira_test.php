<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carteira_test extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('unit_test');
	}

	public function index() {
		$this->load->library('api/carteira', array('saldo' => 500));
		
		// testando o método saque
		$test = $this->carteira->saque();
		$expected_result = array(
			'mensagem' => 'Não foi informado o valor para o saque',
			'resultado' => false
		);
		$test_name = 'Saque sem passar parâmetro, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->saque('');
		$expected_result = array(
			'mensagem' => 'Não foi informado o valor para o saque',
			'resultado' => false
		);
		$test_name = 'Saque passando valor vazio, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->saque('teste');
		$expected_result = array(
			'mensagem' => 'O valor informado para o saque deve ser um número',
			'resultado' => false
		);
		$test_name = 'Saque passando string, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->saque(0);
		$expected_result = array(
			'mensagem' => 'O valor informado para o saque deve ser maior do que zero',
			'resultado' => false
		);
		$test_name = 'Saque passando valor 0, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->saque(-1);
		$expected_result = array(
			'mensagem' => 'O valor informado para o saque deve ser maior do que zero',
			'resultado' => false
		);
		$test_name = 'Saque passando valor -1, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->saque(1000);
		$expected_result = array(
			'mensagem' => 'Não há saldo disponível para o valor solicitado no saque',
			'resultado' => false
		);
		$test_name = 'Saque passando valor 1000, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->saque(10);
		$expected_result = array(
			'mensagem' => 'O saque foi realizado com sucesso',
			'resultado' => true
		);
		$test_name = 'Saque passando valor 10, deve retornar true e uma mensagem de sucesso';
		$this->unit->run($test, $expected_result, $test_name);

		// testando o método deposito

		$test = $this->carteira->deposito();
		$expected_result = array(
			'mensagem' => 'Não foi informado o valor para o depósito',
			'resultado' => false
		);
		$test_name = 'Depósito sem passar parâmetro, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->deposito('');
		$expected_result = array(
			'mensagem' => 'Não foi informado o valor para o depósito',
			'resultado' => false
		);
		$test_name = 'Depósito passando valor vazio, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->deposito('teste');
		$expected_result = array(
			'mensagem' => 'O valor informado para o depósito deve ser um número',
			'resultado' => false
		);
		$test_name = 'Depósito passando string, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->deposito(0);
		$expected_result = array(
			'mensagem' => 'O valor informado para o depósito deve ser maior do que zero',
			'resultado' => false
		);
		$test_name = 'Depósito passando valor 0, deve retornar false e uma mensagem de erro';
		$this->unit->run($test, $expected_result, $test_name);

		$test = $this->carteira->deposito(10);
		$expected_result = array(
			'mensagem' => 'O depósito foi realizado com sucesso',
			'resultado' => true
		);
		$test_name = 'Depósito passando valor 10, deve retornar true e uma mensagem de sucesso';
		$this->unit->run($test, $expected_result, $test_name);

		echo $this->unit->report();
	}
}