<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Livraison extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('livraison_model');
        $this->load->model('commande_model');
    }

    public function livraisonDepot() {

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Livraisons pour les dépôts';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['livraisonDepots'] = $this->livraison_model->getLivraisonDepots();

            foreach ($this->data['livraisonDepots'] as $k => $liv) {

                $this->data['livraisonDepots'][$k]->frais = $this->livraison_model->detailLivraison($liv->idLiv)->result();
            }

            $this->render('livraison' . DIRECTORY_SEPARATOR . 'livraison_depot');
        }
    }

    public function livraisonClient() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Livraisons pour les clients';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['livraisonClientsAvec'] = $this->livraison_model->getLivraisonClientsAvec();

            $this->data['livraisonClientsSans'] = $this->livraison_model->getLivraisonClientsSans();

            foreach ($this->data['livraisonClientsAvec'] as $k => $liv) {

                $this->data['livraisonClientsAvec'][$k]->frais = $this->livraison_model->detailLivraison($liv->idLiv)->result();
            }

            $this->render('livraison' . DIRECTORY_SEPARATOR . 'livraison_client');
        }
    }

    public function ajouterFrais($livraison) {
        $this->data['page_title'] = 'Ajouter un frais';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('nomfrais', 'Nom du frais', 'trim|required');
        $this->form_validation->set_rules('somme', 'Somme de frais', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $new_id = $this->livraison_model->create_frais($livraison, $this->input->post('nomfrais'), $this->input->post('somme'));

            if ($new_id) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('livraison/livraisonDepot', 'refresh');
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


            $this->render('livraison' . DIRECTORY_SEPARATOR . 'ajouter_frais');
        }
    }

    public function ajouterFraisClient($livraison) {
        $this->data['page_title'] = 'Ajouter un frais';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('nomfrais', 'Nom du frais', 'trim|required');
        $this->form_validation->set_rules('somme', 'Somme de frais', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $new_id = $this->livraison_model->create_frais($livraison, $this->input->post('nomfrais'), $this->input->post('somme'));

            if ($new_id) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('livraison/livraisonClient', 'refresh');
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


            $this->render('livraison' . DIRECTORY_SEPARATOR . 'ajouter_frais');
        }
    }

    public function annulerFrais($idFrais) {
        $this->livraison_model->supprimerFrais($idFrais);
        redirect('livraison/livraisonDepot', 'refresh');
    }

    public function validerLivraison($idLiv) {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Validation de la livraison';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $livraison = $this->livraison_model->getLivraison($idLiv)->row();

            $this->commande_model->validerCommande($livraison->idCommande);

            $this->livraison_model->validerLivraison($idLiv);

            redirect('livraison/livraisonDepot', 'refresh');
        }
    }

    public function validerLivraisonClient($idLiv) {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Validation de la livraison';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $livraison = $this->livraison_model->getLivraison($idLiv)->row();

            //var_dump($livraison); die;

            $this->commande_model->validerCommandeClient($livraison->idCommande);

            $this->livraison_model->validerLivraison($idLiv);

            redirect('livraison/livraisonClient', 'refresh');
        }
    }

    public function voirCommandeDepot($idCommande) {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Détails de la commande';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['commandeDepot'] = $this->commande_model->getCommandeDepot($idCommande)->result();

            foreach ($this->data['commandeDepot'] as $k => $com) {

                $this->data['commandeDepot'][$k]->produits = $this->commande_model->detailCommandeDepot($com->idCommande)->result();
            }

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'voirCommandedepot');
        }
    }

    public function voirCommandeClient($idCommande) {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Détail de la commande du client';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['commandeClient'] = $this->commande_model->getCommandeClient($idCommande)->result();

            foreach ($this->data['commandeClient'] as $k => $com) {

                $this->data['commandeClient'][$k]->produits = $this->commande_model->detailCommandeClient($com->idCommande)->result();
            }

            $this->render('gestion' . DIRECTORY_SEPARATOR . 'voirCommandeclient');
        }
    }

    public function imprimerFacture($liv) {

        $donnees['title'] = 'Facture';

        $donnees['produitscommande'] = $this->livraison_model->imprimerFacture($liv);

        $fichier = 'facture_' . date('d_m_Y__H_i_s', time()) . '.pdf';

        $donnees['numero'] = $this->livraison_model->facture($liv, $fichier);

        $path = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'factures' . DIRECTORY_SEPARATOR . $fichier;

        $donnees['path'] = $path;

        $this->load->view('livraison/factureDepot', $donnees);
    }
    
    public function imprimerFactureClient($liv) {

        $donnees['title'] = 'Facture';

        $donnees['produitscommande'] = $this->livraison_model->imprimerFacture($liv);
        
        $donnees['client'] = $this->livraison_model->getClient($liv);

        $fichier = 'facture_' . date('d_m_Y__H_i_s', time()) . '.pdf';

        $donnees['numero'] = $this->livraison_model->facture($liv, $fichier);

        $path = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'factures' . DIRECTORY_SEPARATOR . $fichier;

        $donnees['path'] = $path;

        $this->load->view('livraison/factureClient', $donnees);
    }

    public function afficher($facture) {
        header('Content-type: application/pdf');

        $pdf = $this->livraison_model->getFacture($facture)->row();

        readfile(dirname(APPPATH) . '/factures/' . $pdf->nomFac);
    }

    public function releve() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Livraisons pour les dépôts';
            
            if($this->input->post('date') !== null) {
                $table = explode('/', $this->input->post('date'));
            
                $d = $table[2].'-'.$table[1].'-'.$table[0];
                
            }
            
            $date = $this->input->post('date') !== null ? $d : date('Y-m-d');

            $this->data['relevedepots'] = $this->livraison_model->getReleveDepot($date);
            
            $depots = array();
            
            foreach ($this->data['relevedepots'] as $k => $liv) {
                if(!in_array($liv->idLiv, $depots)) {
                    $this->data['relevedepots'][$k]->frais = $this->livraison_model->detailLivraison($liv->idLiv)->result();
                    $depots[] = $liv->idLiv;
                } else {
                    $this->data['relevedepots'][$k]->frais = null;
                }
            }
            
            $this->data['releveclients'] = $this->livraison_model->getReleveClientLiv($date);
            
            $clients = array();
            
            foreach ($this->data['releveclients'] as $k => $liv) {
                if(!in_array($liv->idLiv, $clients)) {
                    $this->data['releveclients'][$k]->frais = $this->livraison_model->detailLivraison($liv->idLiv)->result();
                    $clients[] = $liv->idLiv;
                } else {
                    $this->data['releveclients'][$k]->frais = null;
                }
            }
            
            $this->data['releveclientsdirects'] = $this->livraison_model->getReleveClient($date);
            
            $this->session->set_userdata('reldate', $date);
            
            $this->session->set_userdata('reldepots', $this->data['relevedepots']);
            
            $this->session->set_userdata('relclients', $this->data['releveclients']);
            
            $this->session->set_userdata('relclientsdirects', $this->data['releveclientsdirects']);
            
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->render('livraison' . DIRECTORY_SEPARATOR . 'releve');
        }
    }
    
    public function imprimerReleve() {
        $donnees['title'] = 'Relevé journalier';
        
        $donnees['relevedepots'] = $this->session->userdata('reldepots');
        
        $donnees['releveclients'] = $this->session->userdata('relclients');
        
        $donnees['relclientsdirects'] = $this->session->userdata('relclientsdirects');

        $this->load->view('livraison/releveImprimer', $donnees);
    }

}
