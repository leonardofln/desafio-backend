<?php
class Usuario_model extends CI_Model {

    public function get($id = null)
    {
        if (!empty($id)) {
            $result = $this->db->get_where('usuario', ['cd_usuario' => $id])->row_array();
        } else {
            $result = $this->db->get('usuario')->result();
        }
        return $result;
    }

    public function insert($dados)
    {
        return $this->db->insert('usuario', $dados);
    }

    public function update($id, $dados)
    {
        return $this->db->update('usuario', $dados, array('cd_usuario' => $id));
    }

    public function delete($id)
    {
        return $this->db->delete('usuario', array('cd_usuario' => $id));
    }
}
?>