<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

/**
 * Description of Pdf
 *
 * @author Kane
 */
class Pdf extends TCPDF {

    public function __construct() {
        parent::__construct();
    }

}
