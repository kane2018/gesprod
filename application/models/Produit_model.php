<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Produit_model extends CI_Model {

    public function listeProduit() {

        $this->db->select('p.idProd, c.nomCat, p.nomProd, d.idDepot, d.nomDepot,f.prenom, f.nom, f.company, pd.quantite, pd.prix, p.enStock, d.principal');
        $this->db->from('produit p');
        $this->db->join('categorie c', 'c.idCat = p.idCat');
        $this->db->join('produitdepot pd', 'pd.idProd = p.idProd');
        $this->db->join('depot d', 'pd.idDepot = d.idDepot');
        $this->db->join('fournisseur f', 'f.idFour = p.idFour');
        //$this->db->group_by('d.nomDepot');
        return $this->db->get()->result();
    }

    public function create_produit($depot, $fournisseur, $categorie, $nom, $quantite, $prix) {

        $data = [
            'idCat' => $categorie,
            'idFour' => $fournisseur,
            'nomProd' => $nom,
            'enStock' => $this->input->post('enstock'),
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];

        $this->db->insert('produit', $data);

        $idProd = $this->db->insert_id();

        if ($this->input->post('stock') == 'non') {
            $this->addUniteProduit($this->input->post('unites'), $idProd);
        }

        $daws = [
            'idProd' => $idProd,
            'idDepot' => $depot,
            'quantite' => $quantite,
            'prix' => $prix,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];

        $this->db->insert('produitdepot', $daws);

//        $this->db->insert('charge', [
//            'idProd' => $idProd,
//            'somme' => $this->input->post('prixcharge'),
//            'usercreation' => $this->session->userdata('email'),
//            'datecreation' => DATE('Y-m-d H:i:s')]
//        );

        if ($idProd) {
            $this->ion_auth->set_message('Produit créé');
        } else {
            $this->ion_auth->set_error('Produit non créé');
        }

        return $idProd;
    }

    public function getProduit($id) {
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->join('categorie', 'categorie.idCat = produit.idCat');
        $this->db->join('produitdepot', 'produitdepot.idProd = produit.idProd');
        $this->db->join('depot', 'produitdepot.idDepot = depot.idDepot');
        $this->db->join('fournisseur', 'fournisseur.idFour = produit.idFour AND produit.idProd = ' . $id);
        return $this->db->get();
    }
    
    public function getProduitDepot($id, $depot) {
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->join('categorie', 'categorie.idCat = produit.idCat');
        $this->db->join('produitdepot', 'produitdepot.idProd = produit.idProd');
        $this->db->join('depot', 'produitdepot.idDepot = depot.idDepot');
        $this->db->join('fournisseur', 'fournisseur.idFour = produit.idFour AND produit.idProd = ' . $id. ' AND depot.idDepot = '.$depot);
        return $this->db->get();
    }
    
    public function getCharge($id) {
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->join('charge', 'charge.idProd = produit.idProd AND charge.id = '.$id);
        return $this->db->get()->row();
    }
    
    public function edit_charge($id, $data) {
        $this->db->update('charge', $data, ['id' => $id]);
        
        $return = $this->db->affected_rows() == 1;

        if ($return) {
            $this->ion_auth->set_message('Charge modifiée');
        } else {
            $this->ion_auth->set_error('Charge non modifiée');
        }
        
        return $return;
    }
    
    public function add_charge() {
        
        $data = [
            'idProd' => $this->input->post('id'),
            'idDepot' => $this->input->post('idDepot'),
            'somme' => $this->input->post('prix'),
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];
        
        $this->db->insert('charge', $data);
        
        $return = $this->db->affected_rows() == 1;

        if ($return) {
            $this->ion_auth->set_message('Charge ajoutée');
        } else {
            $this->ion_auth->set_error('Charge non ajoutée');
        }
        
        return $return;
    }

    public function edit_produit($id, array $data, $depot, array $daws) {

        $this->db->update('produit', $data, ['idProd' => $id]);
        $this->db->update('produitdepot', $daws, ['idProd' => $id, 'idDepot' => $depot]);
        
        $produit = $this->getProduit($id)->row();

        if ($produit->enStock == 0) {
            $this->addUniteProduit($this->input->post('unites'), $id);
        }
        
        $return = $this->db->affected_rows() == 1;

        if ($return) {
            $this->ion_auth->set_message('Produit modifié');
        } else {
            $this->ion_auth->set_error('Produit non modifié');
        }

        return $return;
    }

    public function getByCategorie($id) {
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->join('categorie', 'categorie.idCat = produit.idCat');
        $this->db->join('produitdepot', 'produitdepot.idProd = produit.idProd');
        $this->db->join('depot', 'produitdepot.idDepot = depot.idDepot');
        $this->db->join('fournisseur', 'fournisseur.idFour = produit.idFour AND categorie.idCat = ' . $id);
        $this->db->group_by('produit.idProd');
        return $this->db->get()->result();
    }

    public function getByCategorieDp($id) {
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->join('categorie', 'categorie.idCat = produit.idCat');
        $this->db->join('produitdepot', 'produitdepot.idProd = produit.idProd');
        $this->db->join('depot', 'produitdepot.idDepot = depot.idDepot');
        $this->db->join('fournisseur', 'fournisseur.idFour = produit.idFour AND depot.principal = 1 AND categorie.idCat = ' . $id);
        //$this->db->group_by('produit.idProd');
        return $this->db->get()->result();
    }

    public function getByCategorieDepot($id) {
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->join('categorie', 'categorie.idCat = produit.idCat');
        $this->db->join('depot', 'depot.idDepot = produit.idDepot');
        $this->db->join('fournisseur', 'fournisseur.idFour = produit.idFour AND depot.principal = 1 AND categorie.idCat = ' . $id);
        return $this->db->get()->result();
    }

    public function getProduitsUnites() {
        return $this->db->get_where('produit', array('enStock' => 0));
    }

    public function addUniteProduit($unites, $idProd) {
        if ($unites != null) {
            foreach ($unites as $u) {
                $this->db->insert("produitunite", ['idProd' => $idProd, 'idUni' => $u]);
            }
        }
    }

    public function addQuantite($id) {
        $produit = $this->getProduit($id)->row();

        $quantite = $this->input->post('quantite');

        $this->db->update('produitdepot', ['quantite' => $produit->quantite + $quantite], ['idProd' => $id, 'idDepot' => $produit->idDepot]);
    }

    public function getUnites($p) {
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->join('produitunite', 'produitunite.idProd = produit.idProd');
        $this->db->join('unite', 'produitunite.idUni = unite.id AND produit.idProd = ' . $p);
        return $this->db->get();
    }
    
    public function getCharges($id, $depot) {
        $this->db->select('c.id, c.somme, c.datecreation, c.dateupdate');
        $this->db->from('produit p');
        $this->db->join('charge c', 'c.idProd = p.idProd');
        $this->db->join('depot d', 'd.idDepot = c.idDepot AND p.idProd = ' . $id. ' AND c.idDepot = ' . $depot);
        $this->db->order_by('c.id DESC');
        return $this->db->get();
    }

}
