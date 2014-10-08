<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of etiketten
 *
 * @author kmm
 */
class register_model extends CI_Model {

    private $email;
    public $myconfig;

    public function __construct() {
        $this->load->database();
        $this->email = new CI_Email();
    }

    public function calc_checksumDigit($digits) {
        $sum = 0;
        $multiplier = 3;
        for ($i = strlen($digits); $i > 0; $i--) {
            $sum += substr($digits, $i - 1, 1) * $multiplier;
            $multiplier = ($multiplier == 3) ? 1 : 3;
        }
        return (10 - $sum % 10) % 10;
    }

    public function replace_strings($txt, $array = array()) {
        foreach ($array as $key => $val) {
            $txt = str_replace('${' . $key . '}', $val, $txt);
        }
        return $txt;
    }

    public function generateXML($array) {
        $xml = Array2xml::createXML('seller', $array);
        return $xml->saveXML();
    }

    public function genquestion() {
        $query = $this->db->get_where('jflohmarktweb_options', array('option' => 'question'), 1, 0);
        $result = $query->result();
        if ($result) {
            return $result;
        }
        return FALSE;
    }

    public function validateregistration($email, $name) {
//        Removed because we allow multiple registrations with same mail
//        $query = $this->db->get_where('jflohmarktweb_registrations', array(
//            'email' => $email
//                ), 1, 0);
//        $result = $query->result();
//        if ($result) {
        if (! $email) {
            return false;
        }

        $this->db->insert('jflohmarktweb_registrations', array('email' => $email, 'name' => $name));
        $this->db->where('id', $this->db->insert_id());

        $nr = $this->db->insert_id() . $this->calc_checksumDigit($this->db->insert_id());

        $this->db->update('jflohmarktweb_registrations', array(
            'number' => $nr)
        );
        return $nr;
    }

    public function register_validation() {
        $file = file_get_contents('./templates/vertrag.html');

        $givenName = $this->input->post('givenName', TRUE);
        $name = $this->input->post('name', TRUE);
        $street = $this->input->post('street', TRUE);
        $zipCode = $this->input->post('zipCode', TRUE);
        $city = $this->input->post('city', TRUE);
        $phone = $this->input->post('phone', TRUE);
        $email = $this->input->post('email', TRUE);
        $iban = $this->input->post('iban', TRUE);
        $bic = $this->input->post('bic', TRUE);
        $addressAppendix = $this->input->post('addressAppendix', TRUE);
        $accountHolder = $this->input->post('accountHolder', TRUE);
        $question = $this->input->post('question', TRUE);
        $answer = $this->input->post('answer', TRUE);

        $config = array(
            array(
                'field' => 'addressAppendix',
                'label' => 'Adresszusatz',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'accountHolder',
                'label' => 'Kontoinhaber',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'answer',
                'label' => 'Sicherheitsfrage',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'givenName',
                'label' => 'Vorname',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'name',
                'label' => 'Nachname',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'street',
                'label' => 'Straße',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'zipCode',
                'label' => 'Postleitzahl',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'city',
                'label' => 'Ort',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'phone',
                'label' => 'Telefon',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|trim|xss_clean|valid_email'
            ),
            array(
                'field' => 'email2',
                'label' => 'Email (Wiederholung)',
                'rules' => 'required|trim|matches[email]|xss_clean|valid_email'
            ),
            array(
                'field' => 'iban',
                'label' => 'IBAN',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'bic',
                'label' => 'BIC',
                'rules' => 'trim|xss_clean'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_rules('question', 'question', 'required|trim|xss_clean|callback_validatequestion');
        if ($this->form_validation->run()) {
            $nr = $this->validateregistration($email, $givenName . ' ' . $name);
            if ($nr === FALSE) {
                return false;
            }
            if (empty($iban)
                    OR empty($bic)) {
                $iban = '-';
                $bic = $iban;
            }

            $array = array(
                'accountHolder' => $accountHolder,
                'iban' => $iban,
                'bic' => $bic,
                'givenName' => $givenName,
                'name' => $name,
                'street' => $street,
                'addressAppendix' => $addressAppendix,
                'zipCode' => $zipCode,
                'city' => $city,
                'phone' => $phone,
                'email' => $email,
                'number' => $nr,
                'registration' => date("Y-m-d\TH:i:s", time()),
            );

            $this->create($this->register_model->replace_strings($file, $array), $nr);
            $userMailTopic = $this->register_model->replace_strings($this->myconfig['user_mail_topic'], $array);
            $userMailBody = $this->register_model->replace_strings(file_get_contents('./templates/mail.txt'), $array);
            $this->sendMail(
                    $email, 'vertrag_' . $nr . '.pdf', $userMailTopic, $userMailBody);
            $adminMailTopic = $this->register_model->replace_strings($this->myconfig['admin_mail_topic'], $array);
            $this->sendMail(
                    $this->myconfig['admin_mail_address'], '', $adminMailTopic, wordwrap(base64_encode($this->register_model->generateXML($array)), 64, "\n", true)
            );
            return true;
        }
        return false;
    }

    public function sendMail($mail, $filename = '', $subject = 'Testmail', $text = '') {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => $this->myconfig['smtp_host'],
            'smtp_port' => $this->myconfig['smtp_port'],
            'smtp_user' => $this->myconfig['smtp_user'],
            'smtp_pass' => $this->myconfig['smtp_pass']
        );

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_wordwrap(false);
        $this->email->from($this->myconfig['smtp_from_address'], $this->myconfig['smtp_from_name']);
        $this->email->to($mail);
        $this->email->subject($subject);
        $this->email->message($text);

        if (!empty($filename)) {
            $path = sys_get_temp_dir();
            $file = $path . '/' . $filename;
            $this->email->attach($file);
        }
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function create($html = '<h1>No Input</h1>', $filename) {
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->myconfig['pdf_author']);
        $pdf->SetTitle($this->myconfig['pdf_title']);
        $pdf->SetSubject($this->myconfig['pdf_subject']);
        $pdf->SetKeywords($this->myconfig['pdf_keywords']);

        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $this->myconfig['pdf_title'], $this->myconfig['pdf_subject'], array(0, 0, 0), array(0, 0, 0));
        //$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set header and footer fonts
        //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        //$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetMargins(20, 10, 20);

        // set auto page breaks
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetAutoPageBreak(TRUE, 10);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------	
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 9, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        //$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // ---------------------------------------------------------	
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output(sys_get_temp_dir() . '/' . 'vertrag_' . intval($filename) . '.pdf', 'F');
    }

}
