<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicule_model extends CI_Model {
    
    public function listeVehicule() {
        return $this->db->get('vehicule')->result();
    }
    
    public function create_vehicule($immatricule, $marque, $modele) {

        $data = ['immatricule' => $immatricule, 'marque' => $marque, 'modele' => $modele, 'usercreation' => $this->session->userdata('email'), 'datecreation' => DATE('Y-m-d H:i:s')];

        $this->db->insert('vehicule', $data);
        
        if ($this->db->insert_id()) {
            $this->ion_auth->set_message('Véhicule créé');
        } else {
            $this->ion_auth->set_error('Véhicule non créé');
        }

        return $this->db->insert_id();
    }
    
    public function edit_vehicule($id, array $data) {
        $this->db->update('vehicule', $data, ['idVeh' => $id]);
        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Véhicule modifié');
        } else {
            $this->ion_auth->set_error('Véhicule non modifié');
        }

        return $return;
    }
    
    public function getVehicule($id) {
        return $this->db->get_where('vehicule', array('idVeh' => $id));
    }
    
}
