<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categorie extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('categorie_model');
    }
    
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = $this->lang->line('index_heading');

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the depots
            $this->data['categories'] = $this->categorie_model->listeCategorie();


            $this->render('categorie' . DIRECTORY_SEPARATOR . 'index');
        }
    }

    public function create() {

        $this->data['page_title'] = 'Création d\'une catégorie';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $this->form_validation->set_rules('nom', 'Nom de la catégorie', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');



        if ($this->form_validation->run() === TRUE) {
            $new_depot_id = $this->categorie_model->create_categorie($this->input->post('nom'), $this->input->post('description'));
            if ($new_depot_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("categorie", 'refresh');
            }
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['nom'] = [
                'name' => 'nom',
                'id' => 'nom',
                'type' => 'text',
                'value' => $this->form_validation->set_value('nom'),
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description'),
            ];

            $this->render('categorie' . DIRECTORY_SEPARATOR . 'create');
        }
    }
    
    /**
     * Edit a depot
     *
     * @param int|string $id
     */
    public function edit($id) {
        $this->data['page_title'] = $this->lang->line('edit_user_heading');

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && ($this->categorie_model->getCategorie($id)->row() == null))) {
            redirect('user', 'refresh');
        }

        $categorie = $this->categorie_model->getCategorie($id)->row();

        $this->form_validation->set_rules('nom', 'Nom de la catégorie', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }


            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'nomCat' => $this->input->post('nom'),
                    'description' => $this->input->post('description'),
                    'userupdate' => $this->session->userdata('email'), 
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];
                
                $resultat = $this->categorie_model->edit_categorie($id, $data);
                
                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("categorie", 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['categorie'] = $categorie;
        
        $this->data['nom'] = [
            'name' => 'nom',
            'id' => 'nom',
            'type' => 'text',
            'value' => $this->form_validation->set_value('nom', $categorie->nomCat),
        ];
        $this->data['description'] = [
            'name' => 'description',
            'id' => 'description',
            'type' => 'text',
            'value' => $this->form_validation->set_value('lieu', $categorie->description),
        ];

        $this->render('categorie' . DIRECTORY_SEPARATOR . 'edit');
    }
}
