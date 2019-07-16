<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gerant extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gerant_model');
        $this->load->model('produit_model');
        $this->load->model('commande_model');
        $this->load->model('unite_model');
        $this->load->model('chauffeur_model');
        $this->load->model('livraison_model');
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {

            redirect('user/login', 'refresh');
        } else {
            $this->data['page_title'] = 'Espace Gérant';

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $id = $this->session->userdata('user_id');

            $gerant = $this->gerant_model->monDepot($id);

            $this->session->set_userdata($gerant);

            $this->render('gerant' . DIRECTORY_SEPARATOR . 'accueil');
        }
    }

    public function mesProduits() {
        if (!$this->ion_auth->logged_in()) {

            redirect('user/login', 'refresh');
        } else {

            $idDepot = $this->session->userdata('idDepot');

            $this->data['page_title'] = 'Espace Gérant';

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['produits'] = $this->gerant_model->mesProduits($idDepot);
            
            foreach ($this->data['produits'] as $k => $p) {

                if ($p->enStock == 0) {
                    $this->data['produits'][$k]->unites = $this->produit_model->getUnites($p->idProd)->result();
                    $this->data['produits'][$k]->charges = $this->produit_model->getCharges($p->idProd, $p->idDepot)->result();
                }
            }

            $this->render('gerant' . DIRECTORY_SEPARATOR . 'produit');
        }
    }

    public function commandeClient() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else {
            $this->data['page_title'] = 'Commande pour un client';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $idDepot = $this->session->userdata('idDepot');

            $resultats2 = $this->gerant_model->mesCategories($idDepot)->result();

            $array2 = array();
            $array2[0] = '----- Sélectionner la catégorie -----';
            foreach ($resultats2 as $row) {
                $array2[$row->idCat] = $row->nomCat;
            }
            $this->data['categories'] = $array2;

            $this->data['quantite'] = [
                'name' => 'quantite',
                'id' => 'quantite',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantite'),
            ];

            $panier = ($this->session->userdata('panier') != null) ? $this->session->userdata('panier') : [];

            $this->session->set_userdata('panier', $panier);

            $this->render('gerant' . DIRECTORY_SEPARATOR . 'commande_client');
        }
    }

    public function createComClient() {
        $this->data['page_title'] = 'Ajout d\'un produit';

        if (!$this->ion_auth->logged_in()) {
            redirect('user', 'refresh');
        }

        $idDepot = $this->session->userdata('idDepot');

        $this->form_validation->set_rules('produit', 'Produit', 'trim|required');
        $this->form_validation->set_rules('quantite', 'Quantité du produit', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $id = $this->input->post('produit');

            $produit = $this->produit_model->getProduitDepot($id, $idDepot)->row();

            if ($produit->enStock == 1) {
                $item = [
                    'id' => $produit->idProd,
                    'depot' => $idDepot,
                    'nom' => $produit->nomProd,
                    'quantite' => $this->input->post('quantite'),
                    'idUnite' => '',
                    'unite' => '',
                    'prix' => $produit->prix,
                    'total' => $produit->prix * $this->input->post('quantite')
                ];
            } else {

                $unite = $this->unite_model->getUnite($this->input->post('unite'))->row();

                $item = [
                    'id' => $produit->idProd,
                    'depot' => $idDepot,
                    'nom' => $produit->nomProd,
                    'quantite' => $this->input->post('quantite'),
                    'idUnite' => $unite->id,
                    'unite' => $unite->nom,
                    'prix' => $unite->prix,
                    'total' => $unite->prix * $this->input->post('quantite')
                ];
            }


            $panier = ($this->session->userdata('panier') != null) ? $this->session->userdata('panier') : [];

            $existe = 0;

            $existeUnite = 0;

            foreach ($panier as $p => $v) {

                if (valeurCleExiste($v, 'id', $id)) {

                    $existe = 1;

                    if ($produit->enStock == 1) {
                        if (($panier[$p]['quantite'] + $this->input->post('quantite')) <= $produit->quantite) {
                            $panier[$p]['quantite'] += $this->input->post('quantite');
                            $panier[$p]['total'] = $produit->prix * $panier[$p]['quantite'];
                        } else {
                            $this->session->set_flashdata('message', 'Quantité insuffisante - restante : ' . $produit->quantite);
                        }
                    } else {
                        if (valeurCleExiste($panier[$p], 'idUnite', $unite->id)) {
                            $existeUnite = 1;
                            $panier[$p]['quantite'] += $this->input->post('quantite');
                            $panier[$p]['total'] = $unite->prix * $panier[$p]['quantite'];
                        }
                    }
                }
            }

            if ($existe == 0) {

                if ($produit->enStock == 1) {

                    if (($this->input->post('quantite')) <= $produit->quantite) {
                        $panier[] = $item;
                    } else {
                        $this->session->set_flashdata('message', 'Quantité insuffisante - restante : ' . $produit->quantite);
                    }
                } else {
                    if ($existeUnite == 0) {
                        $panier[] = $item;
                    }
                }
            } else {
                if ($produit->enStock == 0) {
                    if ($existeUnite == 0) {
                        $panier[] = $item;
                    }
                }
            }

            $this->session->set_userdata('panier', $panier);

            redirect('gerant/commandeClient');
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

//            $this->data['categories'] = $this->categorie_model->listeCategorie();

            $resultats2 = $this->gerant_model->mesCategories($id)->result();

            $array2 = array();
            $array2[0] = '----- Sélectionner la catégorie -----';
            foreach ($resultats2 as $row) {
                $array2[$row->idCat] = $row->nomCat;
            }
            $this->data['categories'] = $array2;

            $this->data['quantite'] = [
                'name' => 'quantite',
                'id' => 'quantite',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantite'),
            ];


            $this->render('gerant' . DIRECTORY_SEPARATOR . 'commande_client');
        }
    }

    public function finaliserClient() {
        $this->data['page_title'] = 'Ajouter un client';

        if (!$this->ion_auth->logged_in()) {
            redirect('user', 'refresh');
        }

        if ($this->input->post('choix') == 'nouveau') {
            $this->form_validation->set_rules('prenom', 'Prénom du client', 'trim|required');
            $this->form_validation->set_rules('nom', 'Nom du client', 'trim|required');
            $this->form_validation->set_rules('phone', 'Téléphone du client', 'trim|required');
            $this->form_validation->set_rules('adresse', 'Adresse du client', 'trim|required');
        } elseif ($this->input->post('choix') == 'ancien') {
            $this->form_validation->set_rules('client', 'Choix du client', 'trim|required');
        }

        $resultats = $this->commande_model->listeClient();
        $array = array();
        $array[0] = '----- Sélectionner le client -----';
        foreach ($resultats as $row) {
            $array[$row->idClient] = $row->prenom . ' ' . $row->nom . ' - Tél : ' . $row->telephone . ' - Adresse : ' . $row->adresse;
        }
        $this->data['clients'] = $array;

        if ($this->form_validation->run() === TRUE) {

            if ($this->input->post('choix') == 'nouveau') {

                $idclient = $this->commande_model->create_client($this->input->post('prenom'), $this->input->post('nom'), $this->input->post('phone'), $this->input->post('adresse'));

                $this->commande_model->create_commande($idclient, $this->session->userdata('panier'));

                redirect('gerant/listeCommandeClient', 'refresh');
            } elseif ($this->input->post('choix') == 'ancien') {
                $idclient = $this->input->post('client');
                $this->commande_model->create_commande($idclient, $this->session->userdata('panier'));
                redirect('gerant/listeCommandeClient', 'refresh');
            }
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['prenom'] = [
                'name' => 'prenom',
                'id' => 'prenom',
                'type' => 'text',
                'value' => $this->form_validation->set_value('prenom'),
            ];

            $this->data['nom'] = [
                'name' => 'nom',
                'id' => 'nom',
                'type' => 'text',
                'value' => $this->form_validation->set_value('nom'),
            ];

            $this->data['phone'] = [
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
            ];

            $this->data['adresse'] = [
                'name' => 'adresse',
                'id' => 'adresse',
                'type' => 'text',
                'value' => $this->form_validation->set_value('adresse'),
            ];


            $this->render('gerant' . DIRECTORY_SEPARATOR . 'finaliser_client');
        }
    }

    public function listeCommandeClient() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else {
            $this->data['page_title'] = 'Commande pour un client';

            $idDepot = $this->session->userdata('idDepot');

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['commandeClients'] = $this->commande_model->commandeDesClientsDepot($idDepot);

            foreach ($this->data['commandeClients'] as $k => $com) {

                $this->data['commandeClients'][$k]->produits = $this->commande_model->detailCommandeClient($com->idCommande)->result();
            }

            $this->render('gerant' . DIRECTORY_SEPARATOR . 'listeCommandeClient');
        }
    }

    public function annulerComClient() {
        $this->session->unset_userdata('panier');
        redirect('gerant/commandeClient', 'refresh');
    }
    
    public function livrerClient($id) {
        $this->data['page_title'] = 'Livraison clients';

        if (!$this->ion_auth->logged_in()) {
            redirect('user', 'refresh');
        }

        $resultats = $this->chauffeur_model->listeChauffeur();
        $array = array();
        $array[0] = '----- Sélectionner le chauffeur -----';
        foreach ($resultats as $row) {
            $array[$row->idChauf] = $row->prenomChauf . ' ' . $row->nomChauf;
        }
        $this->data['chauffeurs'] = $array;

        $this->data['commande'] = $id;

        $choix = $this->input->post('choix');

        $this->form_validation->set_rules('commande', '', 'trim|required|is_natural_no_zero');

        if ($choix == 'avec') {
            $this->form_validation->set_rules('chauffeur', 'Sélectionner un chauffeur', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('vehicule', 'Sélectionner un véhicule', 'trim|required|is_natural_no_zero');
        }

        if ($this->form_validation->run() === TRUE) {

            $this->livraison_model->create_livraison($id, $this->input->post('chauffeur'), $this->input->post('vehicule'));

            redirect('gerant/livraisonClient', 'refresh');
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->render('gerant' . DIRECTORY_SEPARATOR . 'livrerClient');
        }
    }
    
    public function livraisonClient() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else {
            $this->data['page_title'] = 'Livraisons pour les clients';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $idDepot = $this->session->userdata('idDepot');
            
            $this->data['livraisonClientsAvec'] = $this->gerant_model->getLivraisonClientsAvec($idDepot);
            
            $this->data['livraisonClientsSans'] = $this->gerant_model->getLivraisonClientsSans($idDepot);

            foreach ($this->data['livraisonClientsAvec'] as $k => $liv) {

                $this->data['livraisonClientsAvec'][$k]->frais = $this->livraison_model->detailLivraison($liv->idLiv)->result();
            }

            $this->render('gerant' . DIRECTORY_SEPARATOR . 'livraison_client');
        }
    }

    public function ajouterFraisClient($livraison) {
        $this->data['page_title'] = 'Ajouter un frais';

        if (!$this->ion_auth->logged_in()) {
            redirect('user', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('nomfrais', 'Nom du frais', 'trim|required');
        $this->form_validation->set_rules('somme', 'Somme de frais', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $new_id = $this->livraison_model->create_frais($livraison, $this->input->post('nomfrais'), $this->input->post('somme'));

            if ($new_id) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('gerant/livraisonClient', 'refresh');
            }
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['nomfrais'] = [
                'name' => 'nomfrais',
                'id' => 'nomfrais',
                'type' => 'text',
                'value' => $this->form_validation->set_value('nomfrais'),
            ];

            $this->data['somme'] = [
                'name' => 'somme',
                'id' => 'somme',
                'type' => 'text',
                'value' => $this->form_validation->set_value('marque'),
            ];


            $this->render('gerant' . DIRECTORY_SEPARATOR . 'ajouter_frais');
        }
    }
    
    public function annulerFrais($idFrais) {
        $this->livraison_model->supprimerFrais($idFrais);
        redirect('gerant/livraisonClient', 'refresh');
    }
    
    public function voirCommandeClient($idCommande) {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else {
            $this->data['page_title'] = 'Détail de la commande du client';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['commandeClient'] = $this->commande_model->getCommandeClient($idCommande)->result();

            foreach ($this->data['commandeClient'] as $k => $com) {

                $this->data['commandeClient'][$k]->produits = $this->commande_model->detailCommandeClient($com->idCommande)->result();
            }

            $this->render('gerant' . DIRECTORY_SEPARATOR . 'voirCommandeclient');
        }
    }
    
    public function validerLivraisonClient($idLiv) {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else {
            $this->data['page_title'] = 'Validation de la livraison';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $livraison = $this->livraison_model->getLivraison($idLiv)->row();
            
            $this->commande_model->validerCommandeClient($livraison->idCommande);
            
            $this->livraison_model->validerLivraison($idLiv);
            
            redirect('gerant/livraisonClient', 'refresh');
        }
    }
}
