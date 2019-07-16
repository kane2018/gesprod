<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unite extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('unite_model');
        $this->load->model('produit_model');
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Liste des unités';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the depots
            $this->data['unites'] = $this->unite_model->listeUnite();

            $this->render('unite' . DIRECTORY_SEPARATOR . 'index');
        }
    }

    public function create() {

        $this->data['page_title'] = 'Création d\'une unite';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $this->form_validation->set_rules('nom', 'Nom de l\'unité', 'trim|required');
        $this->form_validation->set_rules('prix', 'Prix de l\'unité', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $new_depot_id = $this->unite_model->create_unite($this->input->post('nom'), $this->input->post('prix'));
            if ($new_depot_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("unite", 'refresh');
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
            $this->data['prix'] = [
                'name' => 'prix',
                'id' => 'prix',
                'type' => 'text',
                'value' => $this->form_validation->set_value('prix'),
            ];

            $this->render('unite' . DIRECTORY_SEPARATOR . 'create');
        }
    }

    /**
     * Edit a depot
     *
     * @param int|string $id
     */
    public function edit($id) {
        $this->data['page_title'] = $this->lang->line('edit_user_heading');

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin())) {
            redirect('user', 'refresh');
        }

        $unite = $this->unite_model->getUnite($id)->row();

        $this->form_validation->set_rules('nom', 'Nom de l\'unité', 'trim|required');
        $this->form_validation->set_rules('prix', 'Prix de l\'unité', 'trim|required');


        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }


            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'nom' => $this->input->post('nom'),
                    'prix' => $this->input->post('prix'),
                    'userupdate' => $this->session->userdata('email'),
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];

                $resultat = $this->unite_model->edit_unite($id, $data);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("unite", 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['unite'] = $unite;

        $this->data['nom'] = [
            'name' => 'nom',
            'id' => 'nom',
            'type' => 'text',
            'value' => $this->form_validation->set_value('nom', $unite->nom),
        ];
        $this->data['prix'] = [
            'name' => 'prix',
            'id' => 'prix',
            'type' => 'text',
            'value' => $this->form_validation->set_value('prix', $unite->prix),
        ];

        $this->render('unite' . DIRECTORY_SEPARATOR . 'edit');
    }

}
