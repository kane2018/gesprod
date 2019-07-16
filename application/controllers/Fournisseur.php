<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fournisseur extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('fournisseur_model');
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
            $this->data['fournisseurs'] = $this->fournisseur_model->listeFournisseur();


            $this->render('fournisseur' . DIRECTORY_SEPARATOR . 'index');
        }
    }
    
    public function create() {
        $this->data['page_title'] = 'Ajout d\'un fournisseur';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('prenom', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('nom', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', 'Socièté du fournisseur', 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $new_id = $this->fournisseur_model->create_fournisseur($this->input->post('prenom'), $this->input->post('nom'), $this->input->post('phone'), $this->input->post('company'));

            if ($new_id) {

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('fournisseur', 'refresh');
            }
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            //$this->data['fournisseurs'] = $this->fournisseur_model->listeFournisseur();

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
            
            $this->data['company'] = [
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            ];


            $this->render('fournisseur' . DIRECTORY_SEPARATOR . 'create');
        }
    }
    
    /**
     * Editer un fournisseur
     * @param int|string $id
     */
    public function edit($id) {
        if (!$id || empty($id)) {
            redirect('chauffeur', 'refresh');
        }

        $this->data['page_title'] = 'Modifier chauffeur';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $fournisseur = $this->fournisseur_model->getFournisseur($id)->row();
        
        //var_dump($chauffeur->idVeh);die;

        // validate form input
        $this->form_validation->set_rules('prenom', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('nom', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', 'Nom de la Société du Fournisseur', 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'prenom' => $this->input->post('prenom'),
                    'nom' => $this->input->post('nom'),
                    'telephone' => $this->input->post('phone'),
                    'company' => $this->input->post('company'),
                    'userupdate' => $this->session->userdata('email'),
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];
                
                $resultat = $this->fournisseur_model->edit_fournisseur($id, $data);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("fournisseur", 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['fournisseur'] = $fournisseur;

        $this->data['prenom'] = [
            'name' => 'prenom',
            'id' => 'prenom',
            'type' => 'text',
            'value' => $this->form_validation->set_value('prenom', $fournisseur->prenom),
        ];

        $this->data['nom'] = [
            'name' => 'nom',
            'id' => 'nom',
            'type' => 'text',
            'value' => $this->form_validation->set_value('nom', $fournisseur->nom),
        ];

        $this->data['phone'] = [
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $fournisseur->telephone),
        ];
        
        $this->data['company'] = [
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $fournisseur->company),
        ];

        $this->render('fournisseur' . DIRECTORY_SEPARATOR . 'edit');
    }
}
