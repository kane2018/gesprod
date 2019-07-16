<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fournisseur_model extends CI_Model {

    public function listeFournisseur() {
        return $this->db->get('fournisseur')->result();
    }

    public function create_fournisseur($prenom, $nom, $phone, $company) {
        $data = [
            'prenom' => $prenom,
            'nom' => $nom,
            'telephone' => $phone,
            'company' => $company,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];
        
        $this->db->insert('fournisseur', $data);

        if ($this->db->insert_id()) {
            $this->ion_auth->set_message('Fournisseur créé');
        } else {
            $this->ion_auth->set_error('Fournisseur non créé');
        }

        return $this->db->insert_id();
    }

    public function edit_fournisseur($id, $data) {
        $this->db->update('fournisseur', $data, ['idFour' => $id]);
        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Fournisseur modifié');
        } else {
            $this->ion_auth->set_error('Fournisseur non modifié');
        }

        return $return;
    }
    
    public function getFournisseur($id) {
        return $this->db->get_where('fournisseur', ['idFour' => $id]);
    }

}
