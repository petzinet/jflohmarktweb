<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class register extends CI_Controller {

    public $myconfig = array(
        'user_mail_topic' => 'Registrierung zum Flohmarkt',
        'admin_mail_address' => 'test@example.com',
        'admin_mail_topic' => '[Flohmarkt] Registrierung ${number} (${name}, ${givenName})',
        'smtp_host' => 'smtp.example.com',
        'smtp_port' => 25,
        'smtp_user' => '',
        'smtp_pass' => '',
        'smtp_from_address' => 'test@example.com',
        'smtp_from_name' => 'Förderverein der KitaBü',
        'pdf_author' => 'Förderverein der KitaBü',
        'pdf_title' => 'Flohmarkt des Fördervereins der KitaBü',
        'pdf_subject' => 'Vertrag',
        'pdf_keywords' => 'Vertrag'
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('register_model');
        $this->register_model->myconfig = $this->myconfig;
        $this->load->library('email');
    }

    public function index() {
        if ($this->register_model->register_validation()) {
            $this->template->set_layout('default');
            $this->template->build('finished');
        } else {
            $this->template->set_layout('default');
            $this->template->set('question', $this->register_model->genquestion());
            $this->template->build('register');
        }
    }

    public function validatequestion() {
        $question = $this->input->post('question');
        $answer = $this->input->post('answer');
        if (!empty($question) AND ! empty($answer)) {
            $query = $this->db->get_where('jflohmarktweb_options', array(
                'option' => 'question',
                'id' => intval($question),
                'value' => strtolower($answer)
                    ), 1, 0);
            $result = $query->result();
            if ($result) {
                return true;
            }
        }
        $this->form_validation->set_message('validatequestion', 'Die Sicherheitsfrage muss korrekt beantwortet werden.');
        return false;
    }

}
