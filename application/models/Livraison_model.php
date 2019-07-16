<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Livraison_model extends CI_Model {
    
    public function create_livraison($commande, $chauffeur = null, $vehicule = null) {
        $data = [
            'idCommande' => $commande,
            'idChauf' => $chauffeur,
            'idVeh' => $vehicule,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];
        
        $this->db->update('commande', array('statut' => 1), ['idCommande' => $commande]);

        $this->db->insert('livraison', $data);

        if ($this->db->insert_id()) {
            $this->ion_auth->set_message('Livraison créée');
        } else {
            $this->ion_auth->set_error('Livraison non créée');
        }

        return $this->db->insert_id();
    }
    
    public function listeLivraison() {
        
        $this->db->select('*');
        $this->db->from('livraison l');
        $this->db->join('commande c', 'c.idCommande = l.idCommande');
        return $this->db->get()->result();
    }
    
    public function getLivraisonDepots() {
        
        $this->db->select('l.idLiv, c.prenomChauf, c.nomChauf, v.immatricule, v.marque, l.statut, d.nomDepot, co.idCommande, l.dateLiv, l.facture');
        $this->db->from('livraison l');
        $this->db->join('chauffeur c', 'c.idChauf = l.idChauf');
        $this->db->join('vehicule v', 'v.idVeh = l.idVeh');
        $this->db->join('commande co', 'co.idCommande = l.idCommande');
        $this->db->join('depot d', 'd.idDepot = co.idDepot');
        $this->db->order_by('l.idLiv', 'DESC');
        return $this->db->get()->result();
    }
    
    public function getLivraisonClientsAvec() {
        
        $this->db->select('l.idLiv, c.prenomChauf, c.nomChauf, v.immatricule, v.marque, l.statut, cl.prenom, cl.nom, cl.telephone, co.idCommande, l.dateLiv, l.facture');
        $this->db->from('livraison l');
        $this->db->join('chauffeur c', 'c.idChauf = l.idChauf');
        $this->db->join('vehicule v', 'v.idVeh = l.idVeh');
        $this->db->join('commande co', 'co.idCommande = l.idCommande');
        $this->db->join('client cl', 'cl.idClient = co.idClient');
        $this->db->order_by('l.idLiv', 'DESC');
        return $this->db->get()->result();
    }
    
    public function getLivraisonClientsSans() {
        
        $this->db->select('l.idLiv, l.statut, co.idCommande, l.dateLiv, cl.prenom, cl.nom, cl.telephone, l.facture');
        $this->db->from('livraison l');
        $this->db->join('commande co', 'co.idCommande = l.idCommande');
        $this->db->join('client cl', 'cl.idClient = co.idClient AND l.idChauf = 0');
        $this->db->order_by('l.idLiv', 'DESC');
        return $this->db->get()->result();
    }
    
    public function detailLivraison($idLiv) {
        $this->db->select('f.idFrais, f.nomFrais, f.somme');
        $this->db->from('frais f');
        $this->db->join('livraison l', 'l.idLiv = f.idLiv AND l.idLiv = '.$idLiv);
        return $this->db->get();
    }
    
    public function create_frais($livraison, $nomfrais, $somme) {
        $data = [
            'idLiv' => $livraison,
            'nomFrais' => $nomfrais,
            'somme' => $somme,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];
        
        $this->db->insert('frais', $data);

        if ($this->db->insert_id()) {
            $this->ion_auth->set_message('Frais ajouté');
        } else {
            $this->ion_auth->set_error('Frais non créée');
        }
        
        return $this->db->insert_id();
    }
    
    public function supprimerFrais($idFrais) {
        $this->db->delete('frais', ['idFrais' => $idFrais]);
        
        $return = $this->db->affected_rows() >= 1;
        
        if ($return) {
            $this->ion_auth->set_message('Frais annulée');
        } else {
            $this->ion_auth->set_error('Frais non annulée');
        }

        return $return;
    }
    
    public function getLivraison($idLiv) {
        return $this->db->get_where('livraison', array('idLiv' => $idLiv));
    }
    
    public function validerLivraison($idLiv) {
        $this->db->update('livraison', array('statut' => 1, 'dateLiv' => DATE('Y-m-d H:i:s')), ['idLiv' => $idLiv]);
    }
    
    public function imprimerFacture($liv) {
        
        $query = $this->db->query('SELECT pc.quantite, pc.designation, pc.prix, pc.total FROM `livraison` l INNER JOIN `commande` c ON l.`idLiv` = c.`idCommande` INNER JOIN `produitcommande` pc ON c.`idCommande` = pc.`idCommande` AND l.`idLiv` = '.$liv);
        
        return $query->result();
        
    }
    
    public function getClient($liv) {
        $query = $this->db->query('SELECT cl.prenom, cl.nom, cl.telephone, cl.adresse FROM `livraison` l INNER JOIN `commande` c ON l.idCommande = c.idCommande INNER JOIN `client` cl ON cl.idClient = c.idClient AND l.idLiv = '.$liv);
        
        return $query->row();
    }
    
    public function facture($liv, $fichier) {
        
        $data = [
            'idLiv' => $liv,
            'nomFac' => $fichier,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];
        
        $this->db->insert('facture', $data);
        
        $idFac = $this->db->insert_id();
        
        $this->db->update('livraison', array('facture' => $idFac), ['idLiv' => $liv]);
        
        return $idFac;
    }
    
    public function getFacture($facture) {
        return $this->db->get_where('facture', array('idFac' => $facture));
    }
    
    public function getReleveDepot($date) {
        $query = $this->db->query('SELECT l.idLiv, l.dateLiv, pc.designation, pc.quantite, pc.total, d.lieu, v.immatricule FROM `livraison` l INNER JOIN `commande` c ON l.idCommande = c.idCommande INNER JOIN `produitcommande` pc ON c.idCommande = pc.idCommande INNER JOIN `depot` d ON c.idDepot = d.idDepot INNER JOIN `vehicule` v ON l.idVeh = v.idVeh AND l.`dateLiv` LIKE \''.$date.'%\'');
        
        return $query->result();
    }
    
    public function getReleveClientLiv($date) {
        $query = $this->db->query('SELECT l.idLiv, l.dateLiv, pc.designation, pc.quantite, pc.total, cl.prenom, cl.nom, cl.telephone, cl.adresse, v.immatricule FROM `livraison` l INNER JOIN `commande` c ON l.idCommande = c.idCommande INNER JOIN `produitcommande` pc ON c.idCommande = pc.idCommande INNER JOIN `client` cl ON cl.idClient = c.idClient INNER JOIN `vehicule` v ON l.idVeh = v.idVeh AND l.`dateLiv` LIKE \''.$date.'%\'');
        
        return $query->result();
    }
    
    public function getReleveClient($date) {
        $query = $this->db->query('SELECT l.idLiv, l.dateLiv, pc.designation, pc.quantite, pc.total, cl.prenom, cl.nom, cl.telephone,cl.adresse FROM `livraison` l INNER JOIN `commande` c ON l.idCommande = c.idCommande INNER JOIN `produitcommande` pc ON c.idCommande = pc.idCommande INNER JOIN `client` cl ON cl.idClient = c.idClient AND l.idChauf = 0 AND l.`dateLiv` LIKE \''.$date.'%\'');
        
        return $query->result();
    }
}