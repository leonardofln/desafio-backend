<?php
class Carteira_model extends CI_Model {

    public function get($id = null)
    {
        if (!empty($id)) {
            $result = $this->db->get_where('carteira', ['cd_usuario' => $id])->row_array();
        } else {
            $result = $this->db->get('carteira')->result();
        }
        return $result;
    }

    public function insert($dados)
    {
        return $this->db->insert('carteira', $dados);
    }

    public function update($id, $dados)
    {
        return $this->db->update('carteira', $dados, array('cd_usuario' => $id));
    }

    public function delete($id)
    {
        return $this->db->delete('carteira', array('cd_usuario' => $id));
    }
}
?>