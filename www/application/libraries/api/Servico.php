<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servico {

	protected $httpRequest;
	protected $url;

	public function getHttpRequest() {
		return $this->httpRequest;
	}

	public function setHttpRequest($httpRequest) {
		$this->httpRequest = $httpRequest;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

}