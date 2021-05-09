<?php
class ServicoDeNotificacao_test extends TestCase
{
	public function test_executa_sem_passar_parametros()
	{
		$this->obj = $this->criaMockNotificacao('{"message": "Success"}');
		$actual = $this->obj->executa();
		$this->assertFalse($actual);
	}

	public function test_executa_retornando_que_a_notificacao_foi_enviada()
	{
		$this->obj = $this->criaMockNotificacao('{"message": "Success"}');
		$actual = $this->obj->executa(100, $this->criaMockUsuario());
		$this->assertTrue($actual);
	}

	public function test_executa_retornando_que_a_notificacao_falhou()
	{
		$this->obj = $this->criaMockNotificacao('{"message": "Error"}');
		$actual = $this->obj->executa(100, $this->criaMockUsuario());
		$this->assertFalse($actual);
	}

	private function criaMockNotificacao($retorno) {
		$this->resetInstance();
		$mock = $this->getMockBuilder('Curl')->getMock();
		$mock->method('execute')->willReturn($retorno);
		$atributos = array(
            'httpRequest' => $mock,
            'url' => 'http://o4d9z.mocklab.io/notify'
        );
		$this->CI->load->library('api/servicoDeNotificacao', $atributos);
		return $this->CI->servicodenotificacao;
	}

	private function criaMockUsuario() {
		$mock = $this->getMockBuilder('Usuario')->getMock();
		$mock->method('getNome')->willReturn('teste');
		$mock->method('getEmail')->willReturn('teste@teste.com.br');
		return $mock;
	}
}
