<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicule extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();
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
            $this->data['page_title'] = 'Liste des véhicules';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the depots
            $this->data['vehicules'] = $this->vehicule_model->listeVehicule();


            $this->render('vehicule' . DIRECTORY_SEPARATOR . 'index');
        }
    }
    
    public function create() {
        $this->data['page_title'] = $this->lang->line('create_user_heading');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('immatricule', 'Immatricule du  véhicule', 'trim|required');
        $this->form_validation->set_rules('marque', 'Marque du véhicule', 'trim|required');
        $this->form_validation->set_rules('modele', 'Modèle du véhicule', 'trim');

        if ($this->form_validation->run() === TRUE) {

            $new_id = $this->vehicule_model->create_vehicule($this->input->post('immatricule'), $this->input->post('marque'), $this->input->post('modele'));

            if ($new_id) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('vehicule', 'refresh');
            }
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['immatricule'] = [
                'name' => 'immatricule',
                'id' => 'immatricule',
                'type' => 'text',
                'value' => $this->form_validation->set_value('immatricule'),
            ];

            $this->data['marque'] = [
                'name' => 'marque',
                'id' => 'marque',
                'type' => 'text',
                'value' => $this->form_validation->set_value('marque'),
            ];

            $this->data['modele'] = [
                'name' => 'modele',
                'id' => 'modele',
                'type' => 'text',
                'value' => $this->form_validation->set_value('modele'),
            ];


            $this->render('vehicule' . DIRECTORY_SEPARATOR . 'create');
        }
    }
    
    /**
     * Editer un véhicule
     *
     * @param int|string $id
     */
    public function edit($id) {
        $this->data['page_title'] = $this->lang->line('edit_user_heading');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $vehicule = $this->vehicule_model->getVehicule($id)->row();

        $this->form_validation->set_rules('immatricule', 'Immatricule du  véhicule', 'trim|required');
        $this->form_validation->set_rules('marque', 'Marque du véhicule', 'trim|required');
        $this->form_validation->set_rules('modele', 'Modèle du véhicule', 'trim');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }


            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'immatricule' => $this->input->post('immatricule'),
                    'marque' => $this->input->post('marque'),
                    'modele' => $this->input->post('modele'),
                    'userupdate' => $this->session->userdata('email'), 
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];
                
                $resultat = $this->vehicule_model->edit_vehicule($id, $data);
                
                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("vehicule", 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['vehicule'] = $vehicule;
        
        $this->data['immatricule'] = [
            'name' => 'immatricule',
            'id' => 'immatricule',
            'type' => 'text',
            'value' => $this->form_validation->set_value('nom', $vehicule->immatricule),
        ];
        $this->data['marque'] = [
            'name' => 'marque',
            'id' => 'marque',
            'type' => 'text',
            'value' => $this->form_validation->set_value('lieu', $vehicule->marque),
        ];
        
        $this->data['modele'] = [
            'name' => 'modele',
            'id' => 'modele',
            'type' => 'text',
            'value' => $this->form_validation->set_value('lieu', $vehicule->modele),
        ];

        $this->render('vehicule' . DIRECTORY_SEPARATOR . 'edit');
    }
}
