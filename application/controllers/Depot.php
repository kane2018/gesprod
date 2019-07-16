<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Depot extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('depot_model');
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('user/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        } else {
            $this->data['page_title'] = 'Liste des dépôts';

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the depots
            $this->data['depots'] = $this->depot_model->listeDepot();

            foreach ($this->data['depots'] as $k => $p) {
                $this->data['depots'][$k]->users = $this->depot_model->getDepotUsers($p->idDepot)->result();
            }


            $this->render('depot' . DIRECTORY_SEPARATOR . 'index');
        }
    }

    public function create() {

        $this->data['page_title'] = 'Création nouveau dépot';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }

        $this->form_validation->set_rules('nom', 'Nom du dépot', 'trim|required');
        $this->form_validation->set_rules('lieu', 'Lieu du dépot', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $new_depot_id = $this->depot_model->create_depot($this->input->post('nom'), $this->input->post('lieu'));
            if ($new_depot_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("depot", 'refresh');
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
            $this->data['lieu'] = [
                'name' => 'lieu',
                'id' => 'lieu',
                'type' => 'text',
                'value' => $this->form_validation->set_value('lieu'),
            ];

            $this->render('depot' . DIRECTORY_SEPARATOR . 'create');
        }
    }

//    public function desactivate($id) {
//        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//            // redirect them to the home page because they must be an administrator to view this
//            show_error('You must be an administrator to view this page.');
//        }
//
//        $id = (int) $id;
//
//        $this->load->library('form_validation');
//        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
//        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');
//
//        if ($this->form_validation->run() === FALSE) {
//            $this->data['page_title'] = 'Désactivation dépôt';
//            // insert csrf check
//            $this->data['csrf'] = $this->_get_csrf_nonce();
//            $this->data['depot'] = $this->depot_model->getDepot($id)->row();
//
//            $this->render('depot' . DIRECTORY_SEPARATOR . 'desactivate');
//        } else {
//            // do we really want to deactivate?
//            if ($this->input->post('confirm') == 'yes') {
//                // do we have a valid request?
//                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
//                    show_error($this->lang->line('error_csrf'));
//                }
//
//                // do we have the right userlevel?
//                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
//                    $this->depot_model->desactivate($id);
//                }
//            }
//
//            // redirect them back to the auth page
//            redirect('depot', 'refresh');
//        }
//    }

//    public function activate($id) {
//
//        $activation = $this->depot_model->activate($id);
//
//        if ($activation) {
//            // redirect them to the auth page
//            $this->session->set_flashdata('message', $this->ion_auth->messages());
//            redirect("depot", 'refresh');
//        } else {
//            // redirect them to the forgot password page
//            $this->session->set_flashdata('message', $this->ion_auth->errors());
//            redirect("depot", 'refresh');
//        }
//    }

    /**
     * Edit a depot
     *
     * @param int|string $id
     */
    public function edit($id) {
        $this->data['page_title'] = $this->lang->line('edit_user_heading');

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && ($this->depot_model->getDepot($id)->row() == null))) {
            redirect('user', 'refresh');
        }

        $depot = $this->depot_model->getDepot($id)->row();

        $this->form_validation->set_rules('nom', 'Nom du dépôt', 'trim|required');
        $this->form_validation->set_rules('lieu', 'Lieu du dépôt', 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }


            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'nomDepot' => $this->input->post('nom'),
                    'lieu' => $this->input->post('lieu'),
                    'userupdate' => $this->session->userdata('email'),
                    'dateupdate' => DATE('Y-m-d H:i:s')
                ];

                $resultat = $this->depot_model->edit_depot($id, $data);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("depot", 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['depot'] = $depot;

        $this->data['nom'] = [
            'name' => 'nom',
            'id' => 'nom',
            'type' => 'text',
            'value' => $this->form_validation->set_value('nom', $depot->nomDepot),
        ];
        $this->data['lieu'] = [
            'name' => 'lieu',
            'id' => 'lieu',
            'type' => 'text',
            'value' => $this->form_validation->set_value('lieu', $depot->lieu),
        ];

        $this->render('depot' . DIRECTORY_SEPARATOR . 'edit');
    }

    public function definir($id) {

        $this->data['page_title'] = 'Dépot';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        }

        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() === FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['depot'] = $this->depot_model->getDepot($id)->row();

            $this->render('depot' . DIRECTORY_SEPARATOR . 'definir');
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->depot_model->definir_principal($id);
                }
            }

            // redirect them back to the auth page
            redirect('depot', 'refresh');
        }
    }

    public function edituser($user, $depot) {
        $this->depot_model->retirerUserDepot($user, $depot);
        redirect('depot', 'refresh');
    }
    
    public function adduser($depot) {
        
        $this->data['page_title'] = 'Ajouter un utilisateur à un dépot';
        
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('user', 'refresh');
        }
        
        $resultats1 = $this->depot_model->getUserNonDepot();
        
        $array1 = array();
        $array1[0] = '----- Sélectionner le gérant -----';
        foreach ($resultats1 as $row) {
            $array1[$row['id']] = $row['first_name'].' '.$row['last_name'];
        }
        $this->data['users'] = $array1;
        
        $this->form_validation->set_rules('user', 'Gérant', 'trim|required');
        
        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            
            if ($this->form_validation->run() === TRUE) {
                
                $resultat = $this->depot_model->addUserDepot($this->input->post('user'), $depot);

                if ($resultat) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("depot", 'refresh');
            }
        }

        $this->data['depot'] = $this->depot_model->getDepot($depot)->row();
        
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        
//        $this->data['user'] = [
//            'name' => 'prix',
//            'id' => 'prix',
//            'type' => 'number',
//            'value' => $this->form_validation->set_value('prix'),
//        ];
        
        $this->render('depot' . DIRECTORY_SEPARATOR . 'adduser');
    }

}
