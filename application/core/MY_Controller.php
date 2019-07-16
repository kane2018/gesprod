<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class MY_Controller Extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('ion_auth');
        $this->lang->load('auth');
    }

    protected function render($the_view = NULL, $template = 'master') {
        if ($template == 'json' || $this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        } elseif (is_null($template)) {
            $this->load->view($the_view, $this->data);
        } else {
            $this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
            $this->load->view($template, $this->data);
        }
    }

}

class Admin_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->data['fonctionnalite'] = $this->uri->segment(1, 0);
        $this->data['action'] = $this->uri->segment(2, 0);

//        $exception_uris = array(
//            'user/login'
//        );
//
//        if (!in_array(uri_string(), $exception_uris)) {
//            $this->load->library('ion_auth');
//            if (!$this->ion_auth->logged_in()) {
//                redirect('user/login');
//            }
//        }
    }
    
    public function render($the_view = NULL, $template = 'master') {
        parent::render($the_view, $template);
    }
    
    /**
     * @return array A CSRF key-value pair
     */
    public function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return [$key => $value];
    }

    /**
     * @return bool Whether the posted CSRF token matches
     */
    public function _valid_csrf_nonce() {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
            return TRUE;
        }
        return FALSE;
    }

}
