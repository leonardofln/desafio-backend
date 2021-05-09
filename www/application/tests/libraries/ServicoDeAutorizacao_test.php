<?php
class ServicoDeAutorizacao_test extends TestCase
{
	public function test_executa_retornando_que_foi_autorizado()
	{
		$this->obj = $this->criaMock('{"message": "Autorizado"}');
		$actual = $this->obj->executa();
		$this->assertTrue($actual);
	}

	public function test_executa_retornando_que_a_autorizacao_falhou()
	{
		$this->obj = $this->criaMock('{"message": "Acesso negado"}');
		$actual = $this->obj->executa();
		$this->assertFalse($actual);
	}

	private function criaMock($retorno) {
		$this->resetInstance();
		$mock = $this->getMockBuilder('Curl')->getMock();
		$mock->method('execute')->willReturn($retorno);
		$atributos = array(
            'httpRequest' => $mock,
            'url' => 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6'
        );
		$this->CI->load->library('api/servicoDeAutorizacao', $atributos);
		return $this->CI->servicodeautorizacao;
	}
}
