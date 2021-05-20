<?php
class Transacao_test extends TestCase
{
	public function test_transferencia_de_usuario_comum_para_usuario_lojista_tem_que_aceitar()
	{
		try {
			
			$usuarioPagador = array (
				'cd_usuario' => '1',
				'nm_usuario' => 'Leonardo de Oliveira',
				'nr_documento' => '006.268.039-00',
				'de_email' => 'leonardofln@gmail.com',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'C',
				'dt_cadastro' => '2021-05-19 02:55:35'
			);

			$carteiraPagador = array (
				'cd_usuario' => '1',
				'vl_saldo' => '500.00',
			);

			$usuarioBeneficiario = array (
				'cd_usuario' => '4',
				'nm_usuario' => 'Padaria Pão Quentinho',
				'nr_documento' => '40.875.146/0001-03',
				'de_email' => 'vendas@padariapaoquentinho.com.br',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'L',
				'dt_cadastro' => '2021-05-19 02:55:42',
			);

			$carteiraBeneficiario = array (
				'cd_usuario' => '4',
				'vl_saldo' => '0.00',
			);

			$this->request->setCallable(
				function ($CI) use ($usuarioPagador, $carteiraPagador, $usuarioBeneficiario, $carteiraBeneficiario) {
					// mock usuario
					$usuario_model = $this->getMockBuilder('Usuario_model')
						->disableOriginalConstructor()
						->getMock();
					$usuario_model->expects($this->at(0))
					    ->method('get')
					    ->with(1)
					    ->willReturn($usuarioPagador);
					$usuario_model->expects($this->at(1))
					    ->method('get')
					    ->with(4)
					    ->willReturn($usuarioBeneficiario);
					$CI->Usuario_model = $usuario_model;

					// mock carteira
					$carteira_model = $this->getMockBuilder('Carteira_model')
						->disableOriginalConstructor()
						->getMock();
					$carteira_model->expects($this->at(0))
					    ->method('get')
					    ->with(1)
					    ->willReturn($carteiraPagador);
					$carteira_model->expects($this->at(1))
					    ->method('get')
					    ->with(4)
					    ->willReturn($carteiraBeneficiario);
					$carteira_model->method('update')
					    ->willReturn(true);
					$CI->Carteira_model = $carteira_model;

					// mock extrato
					$extrato_model = $this->getDouble(
						'Extrato_model', ['insert' => true]
					);
					$CI->Extrato_model = $extrato_model;

					// mock servico de autorizacao
					$servicoDeAutorizacao = $this->getDouble(
						'ServicoDeAutorizacao', ['executa' => true]
					);
					$CI->servicodeautorizacao = $servicoDeAutorizacao;

					// mock servico de notificacao
					$servicoDeNotificacao = $this->getDouble(
						'ServicoDeNotificacao', ['executa' => true]
					);
					$CI->servicodenotificacao = $servicoDeNotificacao;
				}
			);

			$output = $this->request(
				'POST', 
				'api/transacao', 
				'value=10&payer=1&payee=4'
			);
		} catch (CIPHPUnitTestExitException $e) {
			$output = ob_get_clean();
			$this->assertEquals('Exit_to_exception', $e->class);
			$this->assertEquals('call_exit_in_controller_method', $e->method);
			$this->assertNull($e->exit_status);
		}
		$this->assertContains('Transação concluída com sucesso', $output);
	}

	public function test_transferencia_de_1000_reais_de_usuario_comum_para_usuario_lojista_tem_que_negar()
	{
		try {

			$usuarioPagador = array (
				'cd_usuario' => '1',
				'nm_usuario' => 'Leonardo de Oliveira',
				'nr_documento' => '006.268.039-00',
				'de_email' => 'leonardofln@gmail.com',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'C',
				'dt_cadastro' => '2021-05-19 02:55:35'
			);

			$carteiraPagador = array (
				'cd_usuario' => '1',
				'vl_saldo' => '500.00',
			);

			$usuarioBeneficiario = array (
				'cd_usuario' => '4',
				'nm_usuario' => 'Padaria Pão Quentinho',
				'nr_documento' => '40.875.146/0001-03',
				'de_email' => 'vendas@padariapaoquentinho.com.br',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'L',
				'dt_cadastro' => '2021-05-19 02:55:42',
			);

			$carteiraBeneficiario = array (
				'cd_usuario' => '4',
				'vl_saldo' => '0.00',
			);

			$this->request->setCallable(
				function ($CI) use ($usuarioPagador, $carteiraPagador, $usuarioBeneficiario, $carteiraBeneficiario) {
					// mock usuario
					$usuario_model = $this->getMockBuilder('Usuario_model')
						->disableOriginalConstructor()
						->getMock();
					$usuario_model->expects($this->at(0))
					    ->method('get')
					    ->with(1)
					    ->willReturn($usuarioPagador);
					$usuario_model->expects($this->at(1))
					    ->method('get')
					    ->with(4)
					    ->willReturn($usuarioBeneficiario);
					$CI->Usuario_model = $usuario_model;

					// mock carteira
					$carteira_model = $this->getMockBuilder('Carteira_model')
						->disableOriginalConstructor()
						->getMock();
					$carteira_model->expects($this->at(0))
					    ->method('get')
					    ->with(1)
					    ->willReturn($carteiraPagador);
					$carteira_model->expects($this->at(1))
					    ->method('get')
					    ->with(4)
					    ->willReturn($carteiraBeneficiario);
					$carteira_model->method('update')
					    ->willReturn(true);
					$CI->Carteira_model = $carteira_model;

					// mock extrato
					$extrato_model = $this->getDouble(
						'Extrato_model', ['insert' => true]
					);
					$CI->Extrato_model = $extrato_model;

					// mock servico de autorizacao
					$servicoDeAutorizacao = $this->getDouble(
						'ServicoDeAutorizacao', ['executa' => true]
					);
					$CI->servicodeautorizacao = $servicoDeAutorizacao;

					// mock servico de notificacao
					$servicoDeNotificacao = $this->getDouble(
						'ServicoDeNotificacao', ['executa' => true]
					);
					$CI->servicodenotificacao = $servicoDeNotificacao;
				}
			);

			$output = $this->request(
				'POST', 
				'api/transacao', 
				'value=1000&payer=1&payee=4'
			);
		} catch (CIPHPUnitTestExitException $e) {
			$output = ob_get_clean();
			$this->assertEquals('Exit_to_exception', $e->class);
			$this->assertEquals('call_exit_in_controller_method', $e->method);
			$this->assertNull($e->exit_status);
		}
		$this->assertContains('Não há saldo disponível para o valor solicitado no saque', $output);
	}

	public function test_transferencia_de_usuario_comum_para_outro_usuario_comum_tem_que_aceitar()
	{
		try {

			$usuarioPagador = array (
				'cd_usuario' => '1',
				'nm_usuario' => 'Leonardo de Oliveira',
				'nr_documento' => '006.268.039-00',
				'de_email' => 'leonardofln@gmail.com',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'C',
				'dt_cadastro' => '2021-05-19 02:55:35'
			);

			$carteiraPagador = array (
				'cd_usuario' => '1',
				'vl_saldo' => '500.00',
			);

			$usuarioBeneficiario = array (
				'cd_usuario' => '2',
				'nm_usuario' => 'Fulano da Silveira',
				'nr_documento' => '047.071.110-84',
				'de_email' => 'fulano.silveira@gmail.com',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'C',
				'dt_cadastro' => '2021-05-19 03:56:16',
			);

			$carteiraBeneficiario = array (
				'cd_usuario' => '2',
				'vl_saldo' => '0.00',
			);

			$this->request->setCallable(
				function ($CI) use ($usuarioPagador, $carteiraPagador, $usuarioBeneficiario, $carteiraBeneficiario) {
					// mock usuario
					$usuario_model = $this->getMockBuilder('Usuario_model')
						->disableOriginalConstructor()
						->getMock();
					$usuario_model->expects($this->at(0))
					    ->method('get')
					    ->with(1)
					    ->willReturn($usuarioPagador);
					$usuario_model->expects($this->at(1))
					    ->method('get')
					    ->with(2)
					    ->willReturn($usuarioBeneficiario);
					$CI->Usuario_model = $usuario_model;

					// mock carteira
					$carteira_model = $this->getMockBuilder('Carteira_model')
						->disableOriginalConstructor()
						->getMock();
					$carteira_model->expects($this->at(0))
					    ->method('get')
					    ->with(1)
					    ->willReturn($carteiraPagador);
					$carteira_model->expects($this->at(1))
					    ->method('get')
					    ->with(2)
					    ->willReturn($carteiraBeneficiario);
					$carteira_model->method('update')
					    ->willReturn(true);
					$CI->Carteira_model = $carteira_model;

					// mock extrato
					$extrato_model = $this->getDouble(
						'Extrato_model', ['insert' => true]
					);
					$CI->Extrato_model = $extrato_model;

					// mock servico de autorizacao
					$servicoDeAutorizacao = $this->getDouble(
						'ServicoDeAutorizacao', ['executa' => true]
					);
					$CI->servicodeautorizacao = $servicoDeAutorizacao;

					// mock servico de notificacao
					$servicoDeNotificacao = $this->getDouble(
						'ServicoDeNotificacao', ['executa' => true]
					);
					$CI->servicodenotificacao = $servicoDeNotificacao;
				}
			);

			$output = $this->request(
				'POST', 
				'api/transacao', 
				'value=10&payer=1&payee=2'
			);
		} catch (CIPHPUnitTestExitException $e) {
			$output = ob_get_clean();
			$this->assertEquals('Exit_to_exception', $e->class);
			$this->assertEquals('call_exit_in_controller_method', $e->method);
			$this->assertNull($e->exit_status);
		}
		$this->assertContains('Transação concluída com sucesso', $output);
	}

	public function test_transferencia_de_usuario_lojista_para_usuario_comum_tem_que_negar()
	{
		try {
 
 			$usuarioPagador = array (
				'cd_usuario' => '4',
				'nm_usuario' => 'Padaria Pão Quentinho',
				'nr_documento' => '40.875.146/0001-03',
				'de_email' => 'vendas@padariapaoquentinho.com.br',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'L',
				'dt_cadastro' => '2021-05-19 02:55:42',
			);

			$carteiraPagador = array (
				'cd_usuario' => '4',
				'vl_saldo' => '0.00',
			);

			$usuarioBeneficiario = array (
				'cd_usuario' => '1',
				'nm_usuario' => 'Leonardo de Oliveira',
				'nr_documento' => '006.268.039-00',
				'de_email' => 'leonardofln@gmail.com',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'C',
				'dt_cadastro' => '2021-05-19 02:55:35'
			);

			$carteiraBeneficiario = array (
				'cd_usuario' => '1',
				'vl_saldo' => '500.00',
			);

			$this->request->setCallable(
				function ($CI) use ($usuarioPagador, $carteiraPagador, $usuarioBeneficiario, $carteiraBeneficiario) {
					// mock usuario
					$usuario_model = $this->getMockBuilder('Usuario_model')
						->disableOriginalConstructor()
						->getMock();
					$usuario_model->expects($this->at(0))
					    ->method('get')
					    ->with(4)
					    ->willReturn($usuarioPagador);
					$usuario_model->expects($this->at(1))
					    ->method('get')
					    ->with(1)
					    ->willReturn($usuarioBeneficiario);
					$CI->Usuario_model = $usuario_model;

					// mock carteira
					$carteira_model = $this->getMockBuilder('Carteira_model')
						->disableOriginalConstructor()
						->getMock();
					$carteira_model->expects($this->at(0))
					    ->method('get')
					    ->with(4)
					    ->willReturn($carteiraPagador);
					$carteira_model->expects($this->at(1))
					    ->method('get')
					    ->with(1)
					    ->willReturn($carteiraBeneficiario);
					$carteira_model->method('update')
					    ->willReturn(true);
					$CI->Carteira_model = $carteira_model;

					// mock extrato
					$extrato_model = $this->getDouble(
						'Extrato_model', ['insert' => true]
					);
					$CI->Extrato_model = $extrato_model;

					// mock servico de autorizacao
					$servicoDeAutorizacao = $this->getDouble(
						'ServicoDeAutorizacao', ['executa' => true]
					);
					$CI->servicodeautorizacao = $servicoDeAutorizacao;

					// mock servico de notificacao
					$servicoDeNotificacao = $this->getDouble(
						'ServicoDeNotificacao', ['executa' => true]
					);
					$CI->servicodenotificacao = $servicoDeNotificacao;
				}
			);

			$output = $this->request(
				'POST', 
				'api/transacao', 
				'value=10&payer=4&payee=1'
			);
		} catch (CIPHPUnitTestExitException $e) {
			$output = ob_get_clean();
			$this->assertEquals('Exit_to_exception', $e->class);
			$this->assertEquals('call_exit_in_controller_method', $e->method);
			$this->assertNull($e->exit_status);
		}
		$this->assertContains('Usuário lojista não tem permissão para enviar dinheiro', $output);
	}

	public function test_transferencia_de_usuario_lojista_para_outro_usuario_lojista_tem_que_negar()
	{
		try {

			$usuarioPagador = array (
				'cd_usuario' => '4',
				'nm_usuario' => 'Padaria Pão Quentinho',
				'nr_documento' => '40.875.146/0001-03',
				'de_email' => 'vendas@padariapaoquentinho.com.br',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'L',
				'dt_cadastro' => '2021-05-19 02:55:42',
			);

			$carteiraPagador = array (
				'cd_usuario' => '4',
				'vl_saldo' => '0.00',
			);

			$usuarioBeneficiario = array (
				'cd_usuario' => '3',
				'nm_usuario' => 'Mercado da Esquina',
				'nr_documento' => '78.083.413/0001-74',
				'de_email' => 'contato@mercadodaesquina.com.br',
				'de_senha' => '289160db0d9f39f9ae1754c4ec9c16f90b50e32e09c5fb5481ae642b3d3d1a36',
				'id_tipo' => 'L',
				'dt_cadastro' => '2021-05-19 03:56:17'
			);

			$carteiraBeneficiario = array (
				'cd_usuario' => '3',
				'vl_saldo' => '0.00',
			);

			$this->request->setCallable(
				function ($CI) use ($usuarioPagador, $carteiraPagador, $usuarioBeneficiario, $carteiraBeneficiario) {
					// mock usuario
					$usuario_model = $this->getMockBuilder('Usuario_model')
						->disableOriginalConstructor()
						->getMock();
					$usuario_model->expects($this->at(0))
					    ->method('get')
					    ->with(4)
					    ->willReturn($usuarioPagador);
					$usuario_model->expects($this->at(1))
					    ->method('get')
					    ->with(3)
					    ->willReturn($usuarioBeneficiario);
					$CI->Usuario_model = $usuario_model;

					// mock carteira
					$carteira_model = $this->getMockBuilder('Carteira_model')
						->disableOriginalConstructor()
						->getMock();
					$carteira_model->expects($this->at(0))
					    ->method('get')
					    ->with(4)
					    ->willReturn($carteiraPagador);
					$carteira_model->expects($this->at(1))
					    ->method('get')
					    ->with(3)
					    ->willReturn($carteiraBeneficiario);
					$carteira_model->method('update')
					    ->willReturn(true);
					$CI->Carteira_model = $carteira_model;

					// mock extrato
					$extrato_model = $this->getDouble(
						'Extrato_model', ['insert' => true]
					);
					$CI->Extrato_model = $extrato_model;

					// mock servico de autorizacao
					$servicoDeAutorizacao = $this->getDouble(
						'ServicoDeAutorizacao', ['executa' => true]
					);
					$CI->servicodeautorizacao = $servicoDeAutorizacao;

					// mock servico de notificacao
					$servicoDeNotificacao = $this->getDouble(
						'ServicoDeNotificacao', ['executa' => true]
					);
					$CI->servicodenotificacao = $servicoDeNotificacao;
				}
			);

			$output = $this->request(
				'POST', 
				'api/transacao', 
				'value=10&payer=4&payee=3'
			);
		} catch (CIPHPUnitTestExitException $e) {
			$output = ob_get_clean();
			$this->assertEquals('Exit_to_exception', $e->class);
			$this->assertEquals('call_exit_in_controller_method', $e->method);
			$this->assertNull($e->exit_status);
		}
		$this->assertContains('Usuário lojista não tem permissão para enviar dinheiro', $output);
	}

	public function test_transferencia_sem_informar_o_valor_da_transacao()
	{
		try {
			$output = $this->request(
				'POST', 
				'api/transacao', 
				'value=&payer=4&payee=3'
			);
		} catch (CIPHPUnitTestExitException $e) {
			$output = ob_get_clean();
			$this->assertEquals('Exit_to_exception', $e->class);
			$this->assertEquals('call_exit_in_controller_method', $e->method);
			$this->assertNull($e->exit_status);
		}
		$this->assertContains('Valor da transação não foi informado', $output);
	}

}