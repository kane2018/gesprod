<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Produit extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('produit_model');
        $this->load->model('depot_model');
        $this->load->model('fournisseur_model');
        $this->load->model('categorie_model');
        $this->load->model('unite_model');
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Liste des produits';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the depots
            $this->data['produits'] = $this->produit_model->listeProduit();

            foreach ($this->data['produits'] as $k => $p) {

                if ($p->enStock == 0) {
                    $this->data['produits'][$k]->unites = $this->produit_model->getUnites($p->idProd)->result();
                    $this->data['produits'][$k]->charges = $this->produit_model->getCharges($p->idProd, $p->idDepot)->result();
                }
            }

            $this->render('produit' . DIRECTORY_SEPARATOR . 'index');
        }
    }

    public function create() {
        $this->data['page_title'] = 'Ajout d\'un produit';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $row = $this->depot_model->getDepotPrincipal();
        $array = array();

        if ($row != null) {
            $array[$row->idDepot] = $row->nomDepot;
        }

        $this->data['depots'] = $array;

        $resultats1 = $this->fournisseur_model->listeFournisseur();
        $array1 = array();
        $array1[0] = '----- Sélectionner le fournisseur -----';
        foreach ($resultats1 as $row) {
            $array1[$row->idFour] = $row->company;
        }
        $this->data['fournisseurs'] = $array1;

        $resultats2 = $this->categorie_model->listeCategorie();
        $array2 = array();
        $array2[0] = '----- Sélectionner la catégorie -----';
        foreach ($resultats2 as $row) {
            $array2[$row->idCat] = $row->nomCat;
        }
        $this->data['categories'] = $array2;

        $resultats3 = $this->unite_model->listeUnite();
        $array3 = array();
        //$array3[0] = '----- Sélectionner la catégorie -----';
        foreach ($resultats3 as $row) {
            $array3[$row->id] = $row->nom;
        }
        $this->data['unites'] = $array3;

        // validate form input
        $this->form_validation->set_rules('depot', 'Dépot accueillant le produit', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('fournisseur', 'Fournisseur du produit', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('categorie', 'Catégorie du produit', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('nom', 'Nom du produit', 'trim|required');
        if ($this->input->post('stock') == 'oui') {
            $this->form_validation->set_rules('quantite', 'Quantité du  produit', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('prix', 'Prix du produit', 'trim|required|is_natural_no_zero');
        } elseif ($this->input->post('stock') == 'non') {
            $this->form_validation->set_rules('unites[]', 'Unités du produit', 'trim|required');
        }


        if ($this->form_validation->run() === TRUE) {

            $new_id = $this->produit_model->create_produit($this->input->post('depot'), $this->input->post('fournisseur'), $this->input->post('categorie'), $this->input->post('nom'), $this->input->post('quantite'), $this->input->post('prix'));

            if ($new_id) {

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('produit', 'refresh');
            }
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['depot'] = [
                'name' => 'depot',
                'id' => 'depot',
                'type' => 'text',
                'value' => $this->form_validation->set_value('depot'),
            ];

            $this->data['fournisseur'] = [
                'name' => 'fournisseur',
                'type' => 'text',
                'value' => $this->form_validation->set_value('fournisseur'),
            ];

            $this->data['categorie'] = [
                'name' => 'categorie',
                'id' => 'categorie',
                'value' => $this->form_validation->set_value('categorie'),
            ];

            $this->data['nom'] = [
                'name' => 'nom',
                'id' => 'nom',
                'type' => 'text',
                'value' => $this->form_validation->set_value('nom'),
            ];

            $this->data['quantite'] = [
                'name' => 'quantite',
                'id' => 'quantite',
                'type' => 'number',
                'value' => $this->form_validation->set_value('quantite'),
            ];

            $this->data['prix'] = [
                'name' => 'prix',
                'id' => 'prix',
                'type' => 'text',
                'value' => $this->form_validation->set_value('prix'),
            ];

            $this->render('produit' . DIRECTORY_SEPARATOR . 'create');
        }
    }

    /**
     * 
     * @param int|string $id
     */
    public function edit($id) {
        $this->data['page_title'] = 'Modifier un produit';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $produit = $this->produit_model->getProduit($id)->row();

        $array = array();
        $array[$produit->idDepot] = $produit->nomDepot;

        $this->data['depots'] = $array;

        $resultats1 = $this->fournisseur_model->listeFournisseur();
        $array1 = array();
        $array1[$produit->idFour] = $produit->company;
        foreach ($resultats1 as $row) {
            $array1[$row->idFour] = $row->company;
        }
        $this->data['fournisseurs'] = $array1;

        $resultats2 = $this->categorie_model->listeCategorie();
        $array2 = array();
        $array2[$produit->idCat] = $produit->nomCat;
        foreach ($resultats2 as $row) {
            $array2[$row->idCat] = $row->nomCat;
        }
        $this->data['categories'] = $array2;

        $resultats3 = $this->unite_model->listeUniteNot($id);
        $array3 = array();
        //$array3[0] = '----- Sélectionner la catégorie -----';
        foreach ($resultats3 as $row) {
            $array3[$row->id] = $row->nom;
        }
        $this->data['unites'] = $array3;

        // validate form input
        $this->form_validation->set_rules('depot', 'Dépot accueillant le produit', 'trim|required');
        $this->form_validation->set_rules('fournisseur', 'Fournisseur du produit', 'trim|required');
        $this->form_validation->set_rules('categorie', 'Catégorie du produit', 'trim');
        $this->form_validation->set_rules('nom', 'Nom du produit', 'trim|required');
        if ($produit->enStock == 1) {
            $this->form_validation->set_rules('quantite', 'Quantité du  produit', 'trim|required');
            $this->form_validation->set_rules('prix', 'Prix du produit', 'trim|required');
        } else {
            $this->form_validation->set_rules('unites[]', 'Unités du  produit', 'trim|required');
        }


        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'idCat' => $this->input->post('categorie'),
                    'idFour' => $this->input->post('fournisseur'),
                    'nomProd' => $this->input->post('nom'),
                    'userupdate' => $this->session->userdata('email'),
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];

                $idDepot = $this->input->post('depot');

                $daws = [
                    'quantite' => $this->input->post('quantite'),
                    'prix' => $this->input->post('prix'),
                    'userupdate' => $this->session->userdata('email'),
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];

                $resultat = $this->produit_model->edit_produit($id, $data, $idDepot, $daws);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("produit", 'refresh');
            }
        }

        $this->data['produit'] = $produit;

        $this->data['csrf'] = $this->_get_csrf_nonce();
        // display the create user form
        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['nom'] = [
            'name' => 'nom',
            'id' => 'nom',
            'type' => 'text',
            'value' => $this->form_validation->set_value('nom', $produit->nomProd),
        ];

        $this->data['quantite'] = [
            'name' => 'quantite',
            'id' => 'quantite',
            'type' => 'number',
            'value' => $this->form_validation->set_value('quantite', $produit->quantite),
        ];

        $this->data['prix'] = [
            'name' => 'prix',
            'id' => 'prix',
            'type' => 'text',
            'value' => $this->form_validation->set_value('prix', $produit->prix),
        ];


        if ($produit->enStock == 1) {
            $this->render('produit' . DIRECTORY_SEPARATOR . 'edit');
        } else {
            $this->render('produit' . DIRECTORY_SEPARATOR . 'edit_unite');
        }
    }

    public function addunite($id) {
        
        $this->data['page_title'] = 'Ajout d\'un produit';
        
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $produit = $this->produit_model->getProduit($id)->row();

        $resultats3 = $this->unite_model->listeUniteNot($id);
        $array3 = array();
        //$array3[0] = '----- Sélectionner la catégorie -----';
        foreach ($resultats3 as $row) {
            $array3[$row->id] = $row->nom;
        }
        $this->data['unites'] = $array3;

        $this->form_validation->set_rules('unites[]', 'Unités du  produit', 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() === TRUE) {

                $resultat = $this->produit_model->addUniteProduit($this->input->post('unites'), $id);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("produit", 'refresh');
            }
        }

        $this->data['produit'] = $produit;

        $this->data['csrf'] = $this->_get_csrf_nonce();

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->render('produit' . DIRECTORY_SEPARATOR . 'ajouterunite');
    }

    public function addquantite($id) {
        
        $this->data['page_title'] = 'Ajout d\'une quantité';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $produit = $this->produit_model->getProduit($id)->row();

        $this->form_validation->set_rules('quantite', 'Quantité du  produit', 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() === TRUE) {

                $resultat = $this->produit_model->addQuantite($id);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("produit", 'refresh');
            }
        }

        $this->data['produit'] = $produit;

        $this->data['csrf'] = $this->_get_csrf_nonce();

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['quantite'] = [
            'name' => 'quantite',
            'id' => 'quantite',
            'type' => 'number',
            'value' => $this->form_validation->set_value('quantite'),
        ];

        $this->render('produit' . DIRECTORY_SEPARATOR . 'ajouterquantite');
    }

    public function updatecharge($id) {
        
        $this->data['page_title'] = 'Modification du charge';
        
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $charge = $this->produit_model->getCharge($id);

        $this->form_validation->set_rules('prix', 'Prix de la charge', 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() === TRUE) {

                $data = [
                    'somme' => $this->input->post('prix'),
                    'userupdate' => $this->session->userdata('email'),
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];

                $resultat = $this->produit_model->edit_charge($id, $data);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("produit", 'refresh');
            }
        }

        $this->data['charge'] = $charge;

        $this->data['csrf'] = $this->_get_csrf_nonce();

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['prix'] = [
            'name' => 'prix',
            'id' => 'prix',
            'type' => 'number',
            'value' => $this->form_validation->set_value('prix', $charge->somme),
        ];

        $this->render('produit' . DIRECTORY_SEPARATOR . 'updatecharge');
    }

    public function addcharge($id, $depot) {
        
        $this->data['page_title'] = 'Ajout d\'une charge';
        
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $this->form_validation->set_rules('prix', 'Prix de la charge', 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->form_validation->run() === TRUE) {

                $resultat = $this->produit_model->add_charge();

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("produit", 'refresh');
            }
        }

        $this->data['produit'] = $id;

        $this->data['depot'] = $depot;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['prix'] = [
            'name' => 'prix',
            'id' => 'prix',
            'type' => 'number',
            'value' => $this->form_validation->set_value('prix'),
        ];

        $this->render('produit' . DIRECTORY_SEPARATOR . 'addcharge');
    }

}

//bari nélaw
//bari leka
//bari diakhaso nit
//watoul lamine
//sakou ngueremal nitgni

//Allah
//