<?php   
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
     
class Transacao extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('Usuario_model');
        $this->load->model('Carteira_model');
        $this->load->model('Extrato_model');
    }

    public function index_post() {
        $input = $this->input->input_stream();

        $valor        = $input['value'];
        $pagador      = $input['payer'];
        $beneficiario = $input['payee'];

        // validações dos parâmetros informados na requisição
        $this->validaParametros($valor, $pagador, $beneficiario);

        // buscando as informações de carterira e usuário da base
        $usuarioPagador       = $this->Usuario_model->get($pagador);
        $carteiraPagador      = $this->Carteira_model->get($pagador);
        $usuarioBeneficiario  = $this->Usuario_model->get($beneficiario);
        $carteiraBeneficiario = $this->Carteira_model->get($beneficiario);

        // validações dos dados retornados da base
        $this->validaDadosDoBanco($usuarioPagador, $carteiraPagador, $usuarioBeneficiario, $carteiraBeneficiario);
        
        // criando os objetos pagador e beneficiário
        $objUsuarioPagador = $this->criaObjUsuario($usuarioPagador, $carteiraPagador);
        $objUsuarioBenefic = $this->criaObjUsuario($usuarioBeneficiario, $carteiraBeneficiario);

        // fazendo a transferência do valor entre os usuários
        $this->transferencia($valor, $objUsuarioPagador, $objUsuarioBenefic);
    }

    private function validaParametros($valor, $pagador, $beneficiario) {
        if ($valor == '') {
            $this->response(['Valor da transação não foi informado'], REST_Controller::HTTP_UNAUTHORIZED);
        }

        if (!is_numeric($valor)) {
            $this->response(['Valor da transação informado é inválido'], REST_Controller::HTTP_UNAUTHORIZED);
        }

        if ($valor <= 0) {
            $this->response(['Valor da transação deve ser maior do que 0'], REST_Controller::HTTP_UNAUTHORIZED);
        }

        if ($pagador == '') {
            $this->response(['Código do usuário pagador não foi informado'], REST_Controller::HTTP_UNAUTHORIZED);
        }

        if (!is_numeric($pagador)) {
            $this->response(['Código do usuário pagador informado é inválido'], REST_Controller::HTTP_UNAUTHORIZED);
        }

        if ($beneficiario == '') {
            $this->response(['Código do usuário beneficiário não foi informado'], REST_Controller::HTTP_UNAUTHORIZED);
        }

        if (!is_numeric($beneficiario)) {
            $this->response(['Código do usuário beneficiário informado é inválido'], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    private function validaDadosDoBanco($usuarioPagador, $carteiraPagador, $usuarioBeneficiario, $carteiraBeneficiario) {
        if (empty($usuarioPagador)) {
            $this->response(
                ['Usuário pagador informado não existe na base'],
                REST_Controller::HTTP_UNAUTHORIZED
            );
        }

        if ($usuarioPagador['id_tipo'] == 'L') {
            $this->response(
                ['Usuário lojista não tem permissão para enviar dinheiro'], 
                REST_Controller::HTTP_UNAUTHORIZED
            );
        }

        if (empty($carteiraPagador)) {
            $this->response(
                ['Usuário pagador informado não possui carteira cadastrada na base'], 
                REST_Controller::HTTP_UNAUTHORIZED
            );
        }

        if (empty($usuarioBeneficiario)) {
            $this->response(
                ['Usuário beneficiário informado não existe na base'], 
                REST_Controller::HTTP_UNAUTHORIZED
            );
        }

        if (empty($carteiraBeneficiario)) {
            $this->response(
                ['Usuário beneficiário informado não possui carteira cadastrada na base'], 
                REST_Controller::HTTP_UNAUTHORIZED
            );
        }
    }

    private function criaObjUsuario($usuario, $carteira) {
        // obj carteira
        $atributos = array(
            'saldo' => $carteira['vl_saldo']
        );
        $this->load->library('api/carteira', $atributos);
        $objCarteira = $this->carteira;
        unset($this->carteira);

        // obj usuário
        $atributos = array(
            'codigo'    => $usuario['cd_usuario'],
            'nome'      => $usuario['nm_usuario'],
            'documento' => $usuario['nr_documento'],
            'email'     => $usuario['de_email'],
            'senha'     => $usuario['de_senha'],
            'tipo'      => $usuario['id_tipo'],
            'cadastro'  => $usuario['dt_cadastro'],
            'carteira'  => $objCarteira
        );
        $this->load->library('api/usuario', $atributos);
        $objUsuario = $this->usuario;
        unset($this->usuario);

        return $objUsuario;
    }

    private function insereExtrato($tipo, $valor, $usuario) {
        $dados = array(
            'id_tipo'      => $tipo,
            'vl_transacao' => $valor,
            'cd_usuario'   => $usuario
        );
        return $this->Extrato_model->insert($dados);
    }

    private function atualizaCarteira($usuario) {
        return $this->Carteira_model->update(
            $usuario->getCodigo(), 
            array('vl_saldo' => $usuario->getCarteira()->getSaldo())
        );
    }

    private function verificaSePossuiAutorizacao() {
        $atributos = array(
            'httpRequest' => $this->curl,
            'url' => 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6'
        );
        $this->load->library('api/servicoDeAutorizacao', $atributos);
        $autorizacao = $this->servicodeautorizacao;
        return $autorizacao->executa();
    }

    private function enviaNotificacao($valor, $usuario) {
        $atributos = array(
            'httpRequest' => $this->curl,
            'url' => 'http://o4d9z.mocklab.io/notify'
        );
        $this->load->library('api/servicoDeNotificacao', $atributos);
        $notificacao = $this->servicodenotificacao;
        return $notificacao->executa($valor, $usuario);
    }

    // verificar possibilidade de utilizar o design pattern: Chain of Responsibility
    private function transferencia($valor, $pagador, $beneficiario) {
        $this->db->trans_begin();
        $saque = $pagador->getCarteira()->saque($valor);
        if ($saque['resultado']) {
            $updateCarteira = $this->atualizaCarteira($pagador);
            if ($updateCarteira) {
                $insertExtrato = $this->insereExtrato('S', $valor, $pagador->getCodigo());
                if ($insertExtrato) {
                    $deposito = $beneficiario->getCarteira()->deposito($valor);
                    if ($deposito['resultado']) {
                        $updateCarteira = $this->atualizaCarteira($beneficiario);
                        if ($updateCarteira) {
                            $insertExtrato = $this->insereExtrato('E', $valor, $beneficiario->getCodigo());
                            if ($insertExtrato) {
                                if ($this->verificaSePossuiAutorizacao()) {
                                    if ($this->enviaNotificacao($valor, $beneficiario)) {
                                        $this->db->trans_commit();
                                        $this->response(['Transação concluída com sucesso'], REST_Controller::HTTP_OK);
                                    }
                                    $this->db->trans_rollback();
                                    $this->response(['Falha na tentativa de notificar o usuário da transação'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                                }
                                $this->db->trans_rollback();
                                $this->response(['Esta transação não foi autorizada. Entre em contato com...'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                            }
                            $this->db->trans_rollback();
                            $this->response(['Ocorreu um erro ao registrar no extrato do beneficiário'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }
                        $this->db->trans_rollback();
                        $this->response(['Ocorreu um erro ao atualizar a carteira do beneficiário'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                    $this->response([$deposito['mensagem']], REST_Controller::HTTP_UNAUTHORIZED);
                }
                $this->db->trans_rollback();
                $this->response(['Ocorreu um erro ao registrar no extrato do pagador'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
            $this->response(['Ocorreu um erro ao atualizar a carteira do pagador'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        $this->response([$saque['mensagem']], REST_Controller::HTTP_UNAUTHORIZED);
    }
}