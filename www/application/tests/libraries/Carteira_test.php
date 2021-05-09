<?php
class Carteira_test extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('api/carteira', array('saldo' => 500));
		$this->obj = $this->CI->carteira;
	}

	/**
	 * @group saque
	 */
	public function test_saque_sem_passar_valor_no_parametro()
	{
		$actual = $this->obj->saque();
		$expected = array(
			'mensagem' => 'Não foi informado o valor para o saque',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group saque
	 */
	public function test_saque_passando_parametro_com_valor_vazio()
	{
		$actual = $this->obj->saque('');
		$expected = array(
			'mensagem' => 'Não foi informado o valor para o saque',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group saque
	 */
	public function test_saque_passando_parametro_com_valor_string()
	{
		$actual = $this->obj->saque('teste');
		$expected = array(
			'mensagem' => 'O valor informado para o saque deve ser um número',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group saque
	 */
	public function test_saque_passando_parametro_com_valor_0()
	{
		$actual = $this->obj->saque(0);
		$expected = array(
			'mensagem'  => 'O valor informado para o saque deve ser maior do que zero',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group saque
	 */
	public function test_saque_passando_parametro_com_valor_negativo()
	{
		$actual = $this->obj->saque(-1);
		$expected = array(
			'mensagem'  => 'O valor informado para o saque deve ser maior do que zero',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group saque
	 */
	public function test_saque_passando_parametro_com_valor_acima_do_saldo()
	{
		$actual = $this->obj->saque(1000);
		$expected = array(
			'mensagem' => 'Não há saldo disponível para o valor solicitado no saque',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group saque
	 */
	public function test_saque_passando_parametro_com_valor_que_o_saldo_comporta()
	{
		$actual = $this->obj->saque(10);
		$expected = array(
			'mensagem' => 'O saque foi realizado com sucesso',
			'resultado' => true
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group deposito
	 */
	public function test_deposito_sem_passar_valor_no_parametro()
	{
		$actual = $this->obj->deposito();
		$expected = array(
			'mensagem' => 'Não foi informado o valor para o depósito',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group deposito
	 */
	public function test_deposito_passando_parametro_com_valor_vazio()
	{
		$actual = $this->obj->deposito('');
		$expected = array(
			'mensagem' => 'Não foi informado o valor para o depósito',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group deposito
	 */
	public function test_deposito_passando_parametro_com_valor_string()
	{
		$actual = $this->obj->deposito('teste');
		$expected = array(
			'mensagem' => 'O valor informado para o depósito deve ser um número',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group deposito
	 */
	public function test_deposito_passando_parametro_com_valor_0()
	{
		$actual = $this->obj->deposito(0);
		$expected = array(
			'mensagem'  => 'O valor informado para o depósito deve ser maior do que zero',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group deposito
	 */
	public function test_deposito_passando_parametro_com_valor_negativo()
	{
		$actual = $this->obj->deposito(-1);
		$expected = array(
			'mensagem'  => 'O valor informado para o depósito deve ser maior do que zero',
			'resultado' => false
		);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @group deposito
	 */
	public function test_deposito_passando_parametro_com_valor_correto()
	{
		$actual = $this->obj->deposito(10);
		$expected = array(
			'mensagem' => 'O depósito foi realizado com sucesso',
			'resultado' => true
		);
		$this->assertEquals($expected, $actual);
	}
}
