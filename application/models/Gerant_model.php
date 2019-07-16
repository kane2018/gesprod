<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gerant_model extends CI_Model {
    
    public function monDepot($id) {
        $this->db->select('*');
        $this->db->from('users u');
        $this->db->join('depotuser du', 'du.idUser = u.id');
        $this->db->join('depot d', 'du.idDepot = d.idDepot AND du.statut = 1 AND u.id = '.$id);
        return $this->db->get()->row_array();
    }
    
    public function mesCategories($id) {
        $this->db->select('c.idCat, c.nomCat');
        $this->db->from('produit p');
        $this->db->join('categorie c', 'c.idCat = p.idCat');
        $this->db->join('produitdepot pd', 'pd.idProd = p.idProd');
        $this->db->join('depot d', 'd.idDepot = pd.idDepot AND d.idDepot = '.$id);
        $this->db->distinct();
        return $this->db->get();
    }
    
    public function mesProduits($id) {
        $this->db->select('*');
        $this->db->from('produit p');
        $this->db->join('categorie c', 'c.idCat = p.idCat');
        $this->db->join('produitdepot pd', 'pd.idProd = p.idProd');
        $this->db->join('fournisseur f', 'f.idFour = p.idFour');
        $this->db->join('depot d', 'd.idDepot = pd.idDepot AND d.idDepot = '.$id);
        return $this->db->get()->result();
    }
    
    public function getLivraisonClientsAvec($idDepot) {
        
        $this->db->select('l.idLiv, co.idCommande, c.prenomChauf, c.nomChauf, v.immatricule, v.marque, l.statut, cl.prenom, cl.nom, cl.telephone, l.dateLiv');
        $this->db->from('livraison l');
        $this->db->join('chauffeur c', 'c.idChauf = l.idChauf');
        $this->db->join('vehicule v', 'v.idVeh = l.idVeh');
        $this->db->join('commande co', 'co.idCommande = l.idCommande');
        $this->db->join('client cl', 'cl.idClient = co.idClient');
        $this->db->join('produitcommande pc', 'pc.idCommande = co.idCommande');
        $this->db->join('depot d', 'd.idDepot = pc.idDepot AND d.idDepot = '.$idDepot);
        $this->db->distinct();
        return $this->db->get()->result();
    }
    
    public function getLivraisonClientsSans($idDepot) {
        
        $this->db->select('l.idLiv, co.idCommande, l.statut, l.dateLiv, cl.prenom, cl.nom, cl.telephone');
        $this->db->from('livraison l');
        $this->db->join('commande co', 'co.idCommande = l.idCommande');
        $this->db->join('client cl', 'cl.idClient = co.idClient');
        $this->db->join('produitcommande pc', 'pc.idCommande = co.idCommande');
        $this->db->join('depot d', 'd.idDepot = pc.idDepot AND l.idChauf = 0 AND d.idDepot = '.$idDepot);
        $this->db->distinct();
        return $this->db->get()->result();
    }
}
