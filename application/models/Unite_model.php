<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unite_model extends CI_Model {
    
    
    public function listeUnite() {
        $this->db->select('*');
        $this->db->from('unite u');
        //$this->db->join('produit p', 'p.idProd = u.idProd');
        
        return $this->db->get()->result();
    }
    
    public function create_unite($nom, $prix) {
        $data = ['nom' => $nom, 'prix' => $prix, 'usercreation' => $this->session->userdata('email'), 'datecreation' => DATE('Y-m-d H:i:s')];
        
        $this->db->insert('unite', $data);

        if ($this->db->insert_id()) {
            $this->ion_auth->set_message('Unité créée');
        } else {
            $this->ion_auth->set_error('Unité non créée');
        }

        return $this->db->insert_id();
    }
    
    public function getUnite($id) {
        return $this->db->get_where('unite', array('id' => $id));
    }
    
    public function edit_unite($id, array $data) {
        $this->db->update('unite', $data, ['id' => $id]);
        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Unité modifiée');
        } else {
            $this->ion_auth->set_error('Unité non modifié');
        }

        return $return;
    }
    
    public function getUniteProduits($id) {
        $this->db->select('*');
        $this->db->from('unite');
        $this->db->join('produitunite', 'produitunite.idUni = unite.id');
        $this->db->join('produit', 'produit.idProd = produitunite.idProd AND produit.idProd = ' . $id);
        return $this->db->get()->result();
    }
    
    public function listeUniteNot($id) {
        
        $query = $this->db->query('SELECT * FROM `unite` u WHERE u.id NOT IN (SELECT idUni FROM produitunite pu INNER JOIN produit p ON pu.idProd = p.idProd AND p.enStock = 0 AND pu.idProd = '.$id.')');
        
        return $query->result();
    }
}
