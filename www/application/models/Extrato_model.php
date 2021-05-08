<?php
class Extrato_model extends CI_Model {

    public function get($id = null)
    {
        if (!empty($id)) {
            $result = $this->db->get_where('extrato', ['cd_extrato' => $id])->row_array();
        } else {
            $result = $this->db->get('extrato')->result();
        }
        return $result;
    }

    public function getExtratoByUsuario($id)
    {
        return $this->db->get_where('extrato', ['cd_usuario' => $id])->row_array();
    }

    public function insert($dados)
    {
        return $this->db->insert('extrato', $dados);
    }

    public function update($id, $dados)
    {
        return $this->db->update('extrato', $dados, array('cd_extrato' => $id));
    }

    public function delete($id)
    {
        return $this->db->delete('extrato', array('cd_extrato' => $id));
    }
}
?>