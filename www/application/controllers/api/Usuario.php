<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
     
class Usuario extends REST_Controller {
    
    public function __construct() {
       parent::__construct();
    }
    
	public function index_get($usuario = 0) {
        if (!empty($usuario)) {
            $data = $this->db->get_where("usuario", ['cd_usuario' => $usuario])->row_array();
            $this->response($data, REST_Controller::HTTP_OK);
        }
        $data = $this->db->get("usuario")->result();
        $this->response($data, REST_Controller::HTTP_OK);
	}
    
    public function index_post() {
        $input = $this->input->post();
        $this->db->insert('usuario',$input);
        $this->response(['Usuario cadastrado com sucesso.'], REST_Controller::HTTP_OK);
    } 
    
    public function index_put($usuario) {
        $input = $this->put();
        $this->db->update('usuario', $input, array('cd_usuario' => $usuario));
        $this->response(['Usuario atualizado com sucesso.'], REST_Controller::HTTP_OK);
    }

    public function index_delete($usuario) {
        $this->db->delete('usuario', array('cd_usuario' => $usuario));
        $this->response(['Usuario excluido com sucesso.'], REST_Controller::HTTP_OK);
    }
    	
}