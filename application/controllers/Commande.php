<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Commande extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('categorie_model');
        $this->load->model('produit_model');
        $this->load->model('commande_model');
        $this->load->model('depot_model');
        $this->load->model('chauffeur_model');
        $this->load->model('livraison_model');
        $this->load->model('unite_model');
        $this->load->model('gerant_model');
    }

    public function commandeDepot() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Commande pour un dépôt';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');


            $resultats2 = $this->categorie_model->listeCategorie();
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

            $panierDepot = ($this->session->userdata('panierDepot') != null) ? $this->session->userdata('panierDepot') : [];

            $this->session->set_userdata('panierDepot', $panierDepot);

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'commande_depot');
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

            $resultats = $this->depot_model->listeDepot();
            $array = array();
            $array[0] = '----- Sélectionner le dépôt -----';
            foreach ($resultats as $row) {
                $array[$row->idDepot] = $row->nomDepot;
            }
            $this->data['depots'] = $array;

            $this->data['quantite'] = [
                'name' => 'quantite',
                'id' => 'quantite',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantite'),
            ];

            $panier = ($this->session->userdata('panier') != null) ? $this->session->userdata('panier') : [];

            $this->session->set_userdata('panier', $panier);

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'commande_client');
        }
    }

    public function createComClient() {
        $this->data['page_title'] = 'Ajout d\'un produit';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $this->form_validation->set_rules('produit', 'Produit', 'trim|required');
        $this->form_validation->set_rules('quantite', 'Quantité du produit', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $id = $this->input->post('produit');

            $produit = $this->produit_model->getProduitDepot($id, $this->input->post('depot'))->row();

            if ($produit->enStock == 1) {
                $item = [
                    'id' => $produit->idProd,
                    'depot' => $this->input->post('depot'),
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
                    'depot' => $this->input->post('depot'),
                    'nom' => $produit->nomProd. ' - '.$unite->nom,
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

            redirect('commande/commandeClient');
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['categories'] = $this->categorie_model->listeCategorie();

            $this->data['quantite'] = [
                'name' => 'quantite',
                'id' => 'quantite',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantite'),
            ];


            $this->render('gestion' . DIRECTORY_SEPARATOR . 'commande_client');
        }
    }

    public function getCategories() {

        $id = $this->input->post('idDepot');
        $resultats = $this->gerant_model->mesCategories($id)->result();
        $json = '<option value="">---- Sélectionner un produit ----</option>';
        $i = 1;

        foreach ($resultats as $row) {
            $json .= '<option value="' . $row->idCat . '">' . $row->nomCat . '</option>';
            $i++;
        }
        $this->output->set_header('Content-Type: application/json;charset=utf-8');

        echo json_encode($json);
    }

    public function getProduits() {

        $id = $this->input->post('idCat');
        $resultats = $this->produit_model->getByCategorie($id);
        $json = '<option value="">---- Sélectionner un produit ----</option>';
        $i = 1;

        foreach ($resultats as $row) {
            $json .= '<option value="' . $row->idProd . '">' . $row->nomProd . '</option>';
            $i++;
        }
        $this->output->set_header('Content-Type: application/json;charset=utf-8');

        echo json_encode($json);
    }

    public function getProduitsDepot() {

        $id = $this->input->post('idCat');
        $resultats = $this->produit_model->getByCategorieDp($id);
        $json = '<option value="">---- Sélectionner un produit ----</option>';
        $i = 1;

        foreach ($resultats as $row) {
            $json .= '<option value="' . $row->idProd . '">' . $row->nomProd . '</option>';
            $i++;
        }
        $this->output->set_header('Content-Type: application/json;charset=utf-8');

        echo json_encode($json);
    }

    public function getTypeVente() {
        $id = $this->input->post('idProd');
        $produit = $this->produit_model->getProduit($id)->row();
        $json = '';
        if ($produit->enStock == 1) {
            $json .= '<div class="form-group"><label for="quantite" style="color: #000">Quantité du produit</label><input type="number" name="quantite" id="quantite" class="form-control" /></div>';
        } else {
            $unites = $this->unite_model->getUniteProduits($id);

            $json .= '<div class="form-group"><label for="unite" style="color: #000">Unité du produit</label><select name="unite" class="form-control">';

            $json .= '<option value="">---- Sélectionner une unité ----</option>';

            foreach ($unites as $row) {
                $json .= '<option value="' . $row->id . '">' . $row->nom . '</option>';
            }

            $json .= '</select></div>';

            $json .= '<div class="form-group"><label for="quantite" style="color: #000">Quantité du produit</label><input type="number" name="quantite" id="quantite" class="form-control" /></div>';
        }

        $this->output->set_header('Content-Type: application/json;charset=utf-8');

        echo json_encode($json);
    }

    public function getVehicule() {

        $id = $this->input->post('idChauf');
        $row = $this->chauffeur_model->getVehicule($id);
        $json = '';

        $json .= '<option value="' . $row->idVeh . '">' . $row->marque . ' - ' . $row->immatricule . '</option>';

        $this->output->set_header('Content-Type: application/json;charset=utf-8');

        echo json_encode($json);
    }

    public function annulerComClient() {
        $this->session->unset_userdata('panier');
        redirect('commande/commandeClient', 'refresh');
    }

    public function annulerComDepot() {
        $this->session->unset_userdata('panierDepot');
        redirect('commande/commandeDepot', 'refresh');
    }

    public function valider() {
        
    }

    public function finaliserClient() {
        $this->data['page_title'] = 'Ajouter un client';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
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

                redirect('commande/listeCommandeClient', 'refresh');
            } elseif ($this->input->post('choix') == 'ancien') {
                $idclient = $this->input->post('client');
                $this->commande_model->create_commande($idclient, $this->session->userdata('panier'));
                redirect('commande/listeCommandeClient', 'refresh');
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


            $this->render('gestion' . DIRECTORY_SEPARATOR . 'finaliser_client');
        }
    }

    public function listeCommandeClient() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Commande pour un client';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['commandeClients'] = $this->commande_model->commandeDesClients();

            foreach ($this->data['commandeClients'] as $k => $com) {

                $this->data['commandeClients'][$k]->produits = $this->commande_model->detailCommandeClient($com->idCommande)->result();
            }

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'listeCommandeClient');
        }
    }

    public function createComDepot() {
        $this->data['page_title'] = 'Ajout d\'un produit';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $this->form_validation->set_rules('produit', 'Produit', 'trim|required');
        $this->form_validation->set_rules('quantite', 'Quantité du produit', 'trim|required');


        if ($this->form_validation->run() === TRUE) {

            $id = $this->input->post('produit');

            $depot = $this->depot_model->getDepotPrincipal()->idDepot;

            $produit = $this->produit_model->getProduitDepot($id, $depot)->row();

            if ($produit->enStock == 1) {
                $item = [
                    'id' => $produit->idProd,
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
                    'nom' => $produit->nomProd.' - '.$unite->nom ,
                    'quantite' => $this->input->post('quantite'),
                    'idUnite' => $unite->id,
                    'unite' => $unite->nom,
                    'prix' => $unite->prix,
                    'total' => $unite->prix * $this->input->post('quantite')
                ];
            }

            $panierDepot = ($this->session->userdata('panierDepot') != null) ? $this->session->userdata('panierDepot') : [];

            $existe = 0;
            
            $exiteUnite = 0;

            foreach ($panierDepot as $p => $v) {

                if (valeurCleExiste($v, 'id', $id)) {

                    $existe = 1;

                    if ($produit->enStock == 1) {
                        if ((($panierDepot[$p]['quantite'] + $this->input->post('quantite')) <= $produit->quantite)) {
                            $panierDepot[$p]['quantite'] += $this->input->post('quantite');
                            $panierDepot[$p]['total'] = $produit->prix * $panierDepot[$p]['quantite'];
                        } else {
                            $this->session->set_flashdata('message', 'Quantité insuffisante - restante : ' . $produit->quantite);
                        }
                    } else {
                        if (valeurCleExiste($v, 'idUnite', $unite->id)) {
                            $exiteUnite = 1;
                            $panierDepot[$p]['quantite'] += $this->input->post('quantite');
                            $panierDepot[$p]['total'] = $unite->prix * $panierDepot[$p]['quantite'];
                        }
                    }
                }
            }

            if ($existe == 0) {
                if ($produit->enStock == 1) {
                    if (($this->input->post('quantite')) <= $produit->quantite) {
                        $panierDepot[] = $item;
                    } else {
                        $this->session->set_flashdata('message', 'Quantité insuffisante - restante : ' . $produit->quantite);
                    }
                } else {
                    $panierDepot[] = $item;
                }
            } else {
                if ($produit->enStock == 0) {
                    if($exiteUnite == 0) {
                        $panierDepot[] = $item;
                    }
                }
            }

            $this->session->set_userdata('panierDepot', $panierDepot);

            redirect('commande/commandeDepot');
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['categories'] = $this->categorie_model->listeCategorie();

            $this->data['quantite'] = [
                'name' => 'quantite',
                'id' => 'quantite',
                'type' => 'text',
                'value' => $this->form_validation->set_value('quantite'),
            ];


            $this->render('gestion' . DIRECTORY_SEPARATOR . 'commande_depot');
        }
    }

    public function finaliserDepot() {
        $this->data['page_title'] = 'Sélectionner un dépôt';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $resultats = $this->depot_model->listeDepotSec();
        $array = array();
        $array[0] = '----- Sélectionner le Dépot -----';
        foreach ($resultats as $row) {
            $array[$row->idDepot] = $row->nomDepot . ' - ' . $row->lieu;
        }
        $this->data['depots'] = $array;

        $this->form_validation->set_rules('depot', 'Sélectionner un dépôt', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $idDepot = $this->input->post('depot');

            $this->commande_model->create_commande_depot($idDepot, $this->session->userdata('panierDepot'));

            redirect('commande/listeCommandeDepot', 'refresh');
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'finaliser_depot');
        }
    }

    public function listeCommandeDepot() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Commande des dépôts';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['commandeDepots'] = $this->commande_model->commandeDesDepots();
            
            //var_dump($this->data['commandeDepots']);die;

            foreach ($this->data['commandeDepots'] as $k => $com) {

                $this->data['commandeDepots'][$k]->produits = $this->commande_model->detailCommandeDepot($com->idCommande)->result();
            }

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'listeCommandeDepot');
        }
    }

    public function livrerClient($id) {
        $this->data['page_title'] = 'Livraison clients';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
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

            redirect('livraison/livraisonClient', 'refresh');
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'livrerClient');
        }
    }

    public function livrerDepot($id) {
        $this->data['page_title'] = 'Sélectionner un dépôt';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
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

        $this->form_validation->set_rules('chauffeur', 'Sélectionner un chauffeur', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('vehicule', 'Sélectionner un véhicule', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() === TRUE) {

            $this->livraison_model->create_livraison($id, $this->input->post('chauffeur'), $this->input->post('vehicule'));

            redirect('livraison/livraisonDepot', 'refresh');
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'livrerDepot');
        }
    }

}
