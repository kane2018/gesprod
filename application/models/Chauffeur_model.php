<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chauffeur_model extends CI_Model {

    public function listeChauffeur() {
        $this->db->select('*');
        $this->db->from('chauffeur');
        $this->db->join('usercar', 'usercar.idChauf = chauffeur.idChauf');
        $this->db->join('vehicule', 'usercar.idVeh = vehicule.idVeh AND usercar.statut = 1');
        return $this->db->get()->result_object();
    }

    public function create_chauffeur($prenom, $nom, $phone) {

        $data = [
            'prenomChauf' => $prenom,
            'nomChauf' => $nom,
            'telephone' => $phone,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];

        $this->db->insert('chauffeur', $data);

        if ($this->db->insert_id()) {
            $this->ion_auth->set_message('Chauffeur créé');
        } else {
            $this->ion_auth->set_error('Chauffeur non créé');
        }

        return $this->db->insert_id();
    }

    public function getChauffeur($id) {
        $this->db->select('*');
        $this->db->from('chauffeur');
        $this->db->join('usercar', 'usercar.idChauf = chauffeur.idChauf');
        $this->db->join('vehicule', 'usercar.idVeh = vehicule.idVeh AND usercar.statut = 1 AND chauffeur.idChauf = ' . $id);
        return $this->db->get();
    }

    public function edit_chauffeur($id, array $data, $idVeh) {

        $this->db->update('usercar', ['statut' => 0, 'userupdate' => $this->session->userdata('email'), 'dateupdate' => DATE('Y-m-d H:i:s')], ['idChauf' => $id, 'statut' => 1]);

        $rep = $this->db->get_where('usercar', ['idChauf' => $id, 'idVeh' => $idVeh])->row();

        if ($rep != null) {
            $this->db->update('usercar', ['statut' => 1, 'userupdate' => $this->session->userdata('email'), 'dateupdate' => DATE('Y-m-d H:i:s')], ['idChauf' => $id, 'idVeh' => $idVeh]);
        } else {
            $this->db->insert('usercar', ['idVeh' => $idVeh, 'idChauf' => $id, 'statut' => 1, 'usercreation' => $this->session->userdata('email'), 'datecreation' => DATE('Y-m-d H:i:s')]);
        }

        $this->db->update('chauffeur', $data, ['idChauf' => $id]);
        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Chauffeur modifié');
        } else {
            $this->ion_auth->set_error('Chauffeur non modifié');
        }

        return $return;
    }

    public function chauffeurVehicule($chauffeur, $vehicule) {
        
//        $rep = $this->db->get_where('usercar', ['statut' => 1, 'idVeh' => $vehicule])->row();
//        
//        if($rep != null) {
//            $this->db->update('usercar', ['statut' => 0, 'userupdate' => $this->session->userdata('email'), 'dateupdate' => DATE('Y-m-d H:i:s')], ['statut' => 1, 'idVeh' => $vehicule]);
//        }

        $data = ['idVeh' => $vehicule, 'idChauf' => $chauffeur, 'statut' => 1, 'usercreation' => $this->session->userdata('email'), 'datecreation' => DATE('Y-m-d H:i:s')];

        $this->db->insert('usercar', $data);
    }
    
    public function getVehicule($id) {
        $this->db->select('*');
        $this->db->from('chauffeur');
        $this->db->join('usercar', 'usercar.idChauf = chauffeur.idChauf');
        $this->db->join('vehicule', 'usercar.idVeh = vehicule.idVeh AND usercar.statut = 1 AND chauffeur.idChauf = '.$id);
        return $this->db->get()->row();
    }

}
