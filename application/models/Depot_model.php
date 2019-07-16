<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Depot_model extends CI_Model {

    public function listeDepot() {
        return $this->db->get('depot')->result();
    }
    
    public function getDepotPrincipal() {
        return $this->db->get_where('depot', array('principal' => 1))->row();
    }
    
    public function listeDepotSec() {
        return $this->db->get_where('depot', array('principal <>' => 1))->result();
    }

    public function create_depot($nom, $lieu) {

        $data = ['nomDepot' => $nom, 'lieu' => $lieu, 'usercreation' => $this->session->userdata('email'), 'datecreation' => DATE('Y-m-d H:i:s')];

        $this->db->insert('depot', $data);

        if ($this->db->insert_id()) {
            $this->ion_auth->set_message('Dépot créé');
        } else {
            $this->ion_auth->set_error('Dépot non créé');
        }

        return $this->db->insert_id();
    }

    public function edit_depot($id, array $data) {
        $this->db->update('depot', $data, ['idDepot' => $id]);
        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Dépot modifié');
        } else {
            $this->ion_auth->set_error('Dépot non modifié');
        }

        return $return;
    }

//    public function desactivate($id) {
//        $data = [
//            'active' => 0
//        ];
//
//        $this->db->update('depot', $data, ['idDepot' => $id]);
//
//        $return = $this->db->affected_rows() == 1;
//        if ($return) {
//            $this->ion_auth->set_message('Dépot désactivé');
//        } else {
//            $this->ion_auth->set_error('Dépot non désactivé');
//        }
//
//        return $return;
//    }
//
//    public function activate($id) {
//        $data = [
//            'active' => 1
//        ];
//
//        $this->db->update('depot', $data, ['idDepot' => $id]);
//
//        $return = $this->db->affected_rows() == 1;
//        if ($return) {
//            $this->ion_auth->set_message('Dépot activé');
//        } else {
//            $this->ion_auth->set_error('Dépot non activé');
//        }
//
//        return $return;
//    }

    public function getDepot($id) {
        return $this->db->get_where('depot', array('idDepot' => $id));
    }
    
    public function definir_principal($id) {
        
        $this->db->update('depot', ['principal' => 0], ['principal' => 1]);
        
        $data = [
            'principal' => 1
        ];

        $this->db->update('depot', $data, ['idDepot' => $id]);

        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Définition dépôt principal effectuée');
        } else {
            $this->ion_auth->set_error('Définition dépôt principal non effectuée');
        }

        return $return;
    }
    
    public function getDepotUsers($id) {
        $this->db->select('*');
        $this->db->from('users u');
        $this->db->join('depotuser du', 'du.idUser = u.id');
        $this->db->join('depot d', 'du.idDepot = d.idDepot AND du.statut = 1 AND d.idDepot = '.$id);
        return $this->db->get();
    }
    
    public function addUserDepot($user, $depot) {
        
        //$gerant = $this->db->get_where('depotuser', array('id' => $user, 'idDepot' => $depot, 'statut' => 1))->row();
        
//        if($gerant != null) {
//            $this->ion_auth->set_message('Utilisateur gère déjà un dépôt');
//            return 0;
//        }
        
        $existe = $this->db->get_where('depotuser', array('idUser' => $user, 'idDepot' => $depot))->row();
        
        if($existe == null) {
            $this->db->insert('depotuser', array('idUser' => $user, 'idDepot' => $depot, 'statut' => 1, 'usercreation' => $this->session->userdata('email'), 'datecreation' => DATE('Y-m-d H:i:s')));
        } else {
            $this->db->update('depotuser', ['statut' => 1, 'userupdate' => $this->session->userdata('email'), 'dateupdate' => DATE('Y-m-d H:i:s')], ['idUser' => $user, 'idDepot' => $depot]);
        }
        
        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Utilisateur ajouté au dépôt');
        } else {
            $this->ion_auth->set_error('Utilisateur non ajouté au dépôt');
        }

        return $return;
    }
    
    public function retirerUserDepot($user, $depot) {
        
        $data = [
            'statut' => 0,
        ];
        
        $this->db->update('depotuser', $data, ['idUser' => $user, 'idDepot' => $depot]);

        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->ion_auth->set_message('Utilisateur retiré du dépôt');
        } else {
            $this->ion_auth->set_error('Utilisateur non retiré du dépôt');
        }

        return $return;
    }
    
    public function getUserNonDepot() {
        $query = $this->db->query('SELECT u.id, u.first_name, u.last_name FROM `users` u WHERE u.`id` NOT IN (SELECT idUser FROM `depotuser`) UNION SELECT u.id, u.first_name, u.last_name FROM `users` u INNER JOIN `depotuser` du ON u.id = du.idUser AND du.statut = 0');
        
        return $query->result_array();
        
    }

}
