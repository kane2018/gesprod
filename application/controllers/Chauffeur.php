<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chauffeur extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('chauffeur_model');
        $this->load->model('vehicule_model');
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Liste des chauffeurs';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the depots
            $this->data['chauffeurs'] = $this->chauffeur_model->listeChauffeur();


            $this->render('chauffeur' . DIRECTORY_SEPARATOR . 'index');
        }
    }

    /**
     * Create a new user
     */
    public function create() {
        $this->data['page_title'] = 'Ajout d\'un chauffeur';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('prenom', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('nom', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('vehicule', 'Véhicule', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $new_id = $this->chauffeur_model->create_chauffeur($this->input->post('prenom'), $this->input->post('nom'), $this->input->post('phone'));

            if ($new_id) {

                $this->chauffeur_model->chauffeurVehicule($new_id, intval($this->input->post('vehicule')));
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('chauffeur', 'refresh');
            }
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['vehicules'] = $this->vehicule_model->listeVehicule();

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


            $this->render('chauffeur' . DIRECTORY_SEPARATOR . 'create');
        }
    }

    /**
     * Editer un chauffeur
     *
     * @param int|string $id
     */
    public function edit($id) {
        // bail if no group id given
        if (!$id || empty($id)) {
            redirect('chauffeur', 'refresh');
        }

        $this->data['page_title'] = 'Modifier chauffeur';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $chauffeur = $this->chauffeur_model->getChauffeur($id)->row();
        
        //var_dump($chauffeur->idVeh);die;

        // validate form input
        $this->form_validation->set_rules('prenom', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('nom', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('vehicule', 'Véhicule', 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }


            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'prenomChauf' => $this->input->post('prenom'),
                    'nomChauf' => $this->input->post('nom'),
                    'telephone' => $this->input->post('phone'),
                    'userupdate' => $this->session->userdata('email'),
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];
                
                $idVeh = $this->input->post('vehicule');

                $resultat = $this->chauffeur_model->edit_chauffeur($id, $data, $idVeh);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("chauffeur", 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['chauffeur'] = $chauffeur;

        $resultats = $this->vehicule_model->listeVehicule();
        
        

        $array = array();
        $array[$chauffeur->idVeh] = $chauffeur->immatricule . ' - ' . $chauffeur->marque;
        foreach ($resultats as $row) {
            $array[$row->idVeh] = $row->immatricule . ' - ' . $row->marque;
        }
        $this->data['vehicules'] = $array;
        
        //var_dump($array);die;

        $this->data['prenom'] = [
            'name' => 'prenom',
            'id' => 'prenom',
            'type' => 'text',
            'value' => $this->form_validation->set_value('prenom', $chauffeur->prenomChauf),
        ];

        $this->data['nom'] = [
            'name' => 'nom',
            'id' => 'nom',
            'type' => 'text',
            'value' => $this->form_validation->set_value('nom', $chauffeur->nomChauf),
        ];

        $this->data['phone'] = [
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $chauffeur->telephone),
        ];

        $this->render('chauffeur' . DIRECTORY_SEPARATOR . 'edit');
    }

}
