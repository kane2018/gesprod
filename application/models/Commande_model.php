<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Commande_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('depot_model');
    }

    public function listeClient() {
        return $this->db->get('client')->result();
    }

    public function create_client($prenom, $nom, $phone, $adresse) {

        $data = [
            'prenom' => $prenom,
            'nom' => $nom,
            'telephone' => $phone,
            'adresse' => $adresse,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];

        $this->db->insert('client', $data);

        return $this->db->insert_id();
    }

    public function create_commande($client, $produits) {
        $data = [
            'idClient' => $client,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];

        $this->db->insert('commande', $data);

        $idCommande = $this->db->insert_id();

        foreach ($produits as $p) {

            $pc = [
                'idCommande' => $idCommande,
                'idProd' => $p['id'],
                'idDepot' => $p['depot'],
                'designation' => $p['nom'],
                'quantite' => $p['quantite'],
                'prix' => $p['prix'],
                'total' => $p['total'],
                'usercreation' => $this->session->userdata('email'),
                'datecreation' => DATE('Y-m-d H:i:s')
            ];

            $this->db->insert('produitcommande', $pc);
        }

        $this->session->unset_userdata('panier');

        $return = $this->db->affected_rows() >= 1;

        if ($return) {
            $this->ion_auth->set_message('Commande créée');
        } else {
            $this->ion_auth->set_error('Commande non créée');
        }

        return $return;
    }

    public function create_commande_depot($idDepot, $produits) {
        $data = [
            'idDepot' => $idDepot,
            'usercreation' => $this->session->userdata('email'),
            'datecreation' => DATE('Y-m-d H:i:s')
        ];

        $this->db->insert('commande', $data);

        $idCommande = $this->db->insert_id();
        
        $dp = $this->depot_model->getDepotPrincipal()->idDepot;

        foreach ($produits as $p) {

            $pc = [
                'idCommande' => $idCommande,
                'idProd' => $p['id'],
                'idDepot' => $dp,
                'designation' => $p['nom'],
                'quantite' => $p['quantite'],
                'prix' => $p['prix'],
                'total' => $p['total'],
                'usercreation' => $this->session->userdata('email'),
                'datecreation' => DATE('Y-m-d H:i:s')
            ];

            $this->db->insert('produitcommande', $pc);
        }

        $this->session->unset_userdata('panierDepot');

        $return = $this->db->affected_rows() >= 1;

        if ($return) {
            $this->ion_auth->set_message('Commande créée');
        } else {
            $this->ion_auth->set_error('Commande non créée');
        }

        return $return;
    }

    public function commandeDesClients() {

        $this->db->select('co.idCommande, co.datecreation, prenom, nom, telephone, adresse, co.statut');
        $this->db->from('commande co');
        $this->db->join('client c', 'c.idClient = co.idclient');
        $this->db->order_by('co.idCommande', 'DESC');
        return $this->db->get()->result();
    }

    public function commandeDesClientsDepot($id) {

        $this->db->select('co.idCommande, co.datecreation, prenom, nom, telephone, adresse, co.statut');
        $this->db->from('commande co');
        $this->db->join('client c', 'c.idClient = co.idclient');
        $this->db->join('produitcommande pc', 'pc.idCommande = co.idCommande');
        $this->db->join('depot d', 'd.idDepot = pc.idDepot AND pc.idDepot = ' . $id);
        $this->db->distinct();
        return $this->db->get()->result();
    }

    public function detailCommandeClient($idCommande) {
        $this->db->select('pc.idProd, p.nomProd, pc.quantite, pc.prix, pc.total, pc.idDepot');
        $this->db->from('produitcommande pc');
        $this->db->join('produit p', 'p.idProd = pc.idProd');
        $this->db->join('commande c', 'c.idCommande = pc.idCommande');
        $this->db->join('depot d', 'd.idDepot = pc.idDepot AND c.idCommande = ' . $idCommande);
        return $this->db->get();
    }

    public function commandeDesDepots() {

        $this->db->select('co.idCommande, co.datecreation, nomDepot, lieu, co.statut');
        $this->db->from('commande co');
        $this->db->join('depot d', 'd.idDepot = co.idDepot');
        $this->db->order_by('co.idCommande', 'DESC');
        return $this->db->get()->result();
    }

    public function detailCommandeDepot($idCommande) {
        $this->db->select('p.idCat, d.idDepot, p.idFour, pc.idProd, p.nomProd, pc.quantite, pc.prix, pc.total');
        $this->db->from('produitcommande pc');
        $this->db->join('produit p', 'p.idProd = pc.idProd');
        $this->db->join('commande c', 'c.idCommande = pc.idCommande');
        $this->db->join('depot d', 'c.idDepot = d.idDepot AND c.idCommande = ' . $idCommande);
        return $this->db->get();
    }

    public function getCommandeDepot($idCommande) {
        $this->db->select('co.idCommande, co.datecreation, nomDepot, lieu, co.statut');
        $this->db->from('commande co');
        $this->db->join('depot d', 'd.idDepot = co.idDepot AND co.idCommande = ' . $idCommande);
        return $this->db->get();
    }

    public function getCommandeClient($idCommande) {

        $this->db->select('co.idCommande, co.datecreation, prenom, nom, telephone, adresse, co.statut');
        $this->db->from('commande co');
        $this->db->join('client c', 'c.idClient = co.idclient AND co.idCommande = ' . $idCommande);
        return $this->db->get();
    }

    public function validerCommande($idCommande) {

        $produits = $this->detailCommandeDepot($idCommande)->result();

        foreach ($produits as $p) {

            $this->db->select('*');
            $this->db->from('produit');
            $this->db->join('produitdepot', 'produitdepot.idProd = produit.idProd');
            $this->db->join('depot', 'produitdepot.idDepot = depot.idDepot AND produit.idProd = ' . $p->idProd . ' AND depot.`principal` = 1');
            $prod = $this->db->get()->row();

            if ($prod->enStock == 1) {
                $this->db->update('produitdepot', array('quantite' => $prod->quantite - $p->quantite), ['idProd' => $prod->idProd, 'idDepot' => $prod->idDepot]);
            }

            $prd = $this->db->get_where('produitdepot', array('idProd' => $p->idProd, 'idDepot' => $p->idDepot))->row();

            if ($prd == null) {
                if ($prod->enStock == 1) {
                    $data = [
                        'idDepot' => $p->idDepot,
                        'idProd' => $p->idProd,
                        'quantite' => $p->quantite,
                        'prix' => $p->prix,
                        'usercreation' => $this->session->userdata('email'),
                        'datecreation' => DATE('Y-m-d H:i:s')
                    ];
                } else {

                    $data = [
                        'idDepot' => $p->idDepot,
                        'idProd' => $p->idProd,
                        'usercreation' => $this->session->userdata('email'),
                        'datecreation' => DATE('Y-m-d H:i:s')
                    ];

                    $datacharge = [
                        'idProd' => $p->idProd,
                        'idDepot' => $p->idDepot,
                        'somme' => $p->total,
                        'usercreation' => $this->session->userdata('email'),
                        'datecreation' => DATE('Y-m-d H:i:s')
                    ];

                    $this->db->insert('charge', $datacharge);
                }
                $this->db->insert('produitdepot', $data);
            } else {
                if ($prod->enStock == 1) {
                    $data = [
                        'quantite' => $p->quantite + $prd->quantite,
                        'prix' => $p->prix,
                        'userupdate' => $this->session->userdata('email'),
                        'dateupdate' => DATE('Y-m-d H:i:s')
                    ];

                    $this->db->update('produitdepot', $data, array('idDepot' => $p->idDepot, 'idProd' => $p->idProd));
                } else {
                    $data = [
                        'idProd' => $p->idProd,
                        'idDepot' => $p->idDepot,
                        'somme' => $p->total,
                        'usercreation' => $this->session->userdata('email'),
                        'datecreation' => DATE('Y-m-d H:i:s')
                    ];

                    $this->db->insert('charge', $data);
                }
            }
        }

        $this->db->update('commande', array('dateexpedition' => DATE('Y-m-d H:i:s')), ['idCommande' => $idCommande]);

        return $this->db->affected_rows() >= 1;
    }

    public function validerCommandeClient($idCommande) {

        $produits = $this->detailCommandeClient($idCommande)->result();
        
        foreach ($produits as $p) {

            $this->db->select('*');
            $this->db->from('produit');
            $this->db->join('produitdepot', 'produitdepot.idProd = produit.idProd');
            $this->db->join('depot', 'produitdepot.idDepot = depot.idDepot AND produit.idProd = ' . $p->idProd . ' AND depot.`idDepot` = ' . $p->idDepot);
            $prod = $this->db->get()->row();
            
            if ($prod->enStock == 1) {
                $this->db->update('produitdepot', array('quantite' => $prod->quantite - $p->quantite), ['idProd' => $prod->idProd, 'idDepot' => $prod->idDepot]);
            }
            
        }

        $this->db->update('commande', array('dateexpedition' => DATE('Y-m-d H:i:s')), ['idCommande' => $idCommande]);

        return $this->db->affected_rows() >= 1;
    }

}
