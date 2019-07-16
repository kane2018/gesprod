<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categorie_model extends CI_Model {
    
    public function listeCategorie() {
        return $this->db->get('categorie')->result();
    }
    
    public function create_categorie($nom, $desc) {
        $data = ['nomCat' => $nom, 'description' => $desc, 'usercreation' => $this->session->userdata('email'), 'datecreation' => DATE('Y-m-d H:i:s')];

        $this->db->insert('categorie', $data);

        if ($this->db->insert_id()) {
            $this->ion_auth->set_message('Catégorie créée');
        } else {
            $this->ion_auth->set_error('Catégorie non créée');
        }

        return $this->db->insert_id();
    }
    
    public function getCategorie($id) {
        return $this->db->get_where('categorie', array('idCat' => $id));
    }
    
    public function edit_categorie($id, array $data) {
        $this->db->update('categorie', $data, ['idCat' => $id]);
        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Catégorie modifiée');
        } else {
            $this->ion_auth->set_error('Catégorie non modifié');
        }

        return $return;
    }

}
