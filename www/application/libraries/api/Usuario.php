<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario {

	protected $codigo;
	protected $nome;
	protected $documento;
	protected $email;
	protected $senha;
	protected $tipo;
	protected $cadastro;
	protected $carteira;

	public function __construct($atributos) {
		$this->setCodigo($atributos['codigo']);
		$this->setNome($atributos['nome']);
		$this->setDocumento($atributos['documento']);
		$this->setEmail($atributos['email']);
		$this->setSenha($atributos['senha']);
		$this->setTipo($atributos['tipo']);
		$this->setCadastro($atributos['cadastro']);
		$this->setCarteira($atributos['carteira']);
	}

	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function getDocumento() {
		return $this->documento;
	}

	public function setDocumento($documento) {
		$this->documento = $documento;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getSenha() {
		return $this->senha;
	}

	public function setSenha($senha) {
		$this->senha = $senha;
	}

	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	public function getCadastro() {
		return $this->cadastro;
	}

	public function setCadastro($cadastro) {
		$this->cadastro = $cadastro;
	}

	public function getCarteira() {
		return $this->carteira;
	}

	public function setCarteira($carteira) {
		$this->carteira = $carteira;
	}

}