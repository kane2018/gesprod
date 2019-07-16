<?php

// create new PDF document
$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );

$pdf->setPrintHeader ( false );
$pdf->setPrintFooter ( false );

// set default monospaced font
$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );

// set margins
$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
// set auto page breaks
$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );

// set image scale factor
$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );

// set some language-dependent strings (optional)
if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
	require_once (dirname ( __FILE__ ) . '/lang/eng.php');
	$pdf->setLanguageArray ( $l );
}

// helvetica or times to reduce file size.
$pdf->SetFont ( 'times', '', 14, '', true );

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage ();

$pdf->setTitle($title);

// set text shadow effect
$pdf->setTextShadow ( array (
		'enabled' => false,
		'depth_w' => 0.2,
		'depth_h' => 0.2,
		'color' => array (
				196,
				196,
				196 
		),
		'opacity' => 1,
		'blend_mode' => 'Normal' 
) );


$html = '<h1 style="font-size:60px; font-weight: bold; color: blue">W.M.C.A</h1>';

$pdf->writeHTMLCell ( 100, 0, '', 10, $html, 0, 1, 0, true, 'L', true );

$html = '<h3 style="color:blue; border-top: 1px blue; border-bottom: 1px blue">WAKEUR MAME CHEIKH ANTA</h3>';

$pdf->writeHTMLCell ( 90, 0, '', '', $html, 0, 1, 0, true, 'L', true );

$img = img_url('facture.jpg');

$html = '<figure><img src="'.$img.'" alt="Logo" width="300px" /></figure>';

$pdf->writeHTMLCell ( 100, 0, 100, 10, $html, 0, 1, 0, true, 'R', true );

$html = '<h3 style="color: red;">N° : '.sprintf('%7d',$numero).'</h3>';

$pdf->writeHTMLCell ( 100, 0, 100, '', $html, 0, 1, 0, true, 'R', true );

$html = '<h2 style="color: blue;">FACTURE</h2>';

$pdf->writeHTMLCell ( 100, 0, 100, '', $html, 0, 1, 0, true, 'R', true );

$html = '<div style="text-align:center"><p style="color: blue;">Distribution de Matériels de Construction<br/>';

$html .= 'Béton - Fer - Ciment - Sable - Prestations de Services<br/>'
        . 'TEL : 33 973 80 54 / 77 659 41 99 / 78 224 00 00 / 76 513 09 01'
        . '</p></div>';

$pdf->writeHTMLCell ( 135, 0, 10, 30, $html, 0, 1, 0, true, 'L', true );

$date = date('d-m-Y H:i:s', time());

$html = '___________________________________________Date le : '.$date.'<br/>';

$pdf->writeHTMLCell ( '', 0, '', 70, $html, 0, 1, 0, true, 'L', true );

$table = '
<style>
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
}        
</style>
<table border="1" cellpadding="2" align="center">
	<tr>
            <th width="20%">Quantité</th>
            <th width="40%">Désignation</th>
            <th width="20%">Prix Unitaire</th>
            <th width="20%">Prix Total</th>
	</tr>';

foreach ($produitscommande as $pc) {
    $table .= '<tr><td>'.$pc->quantite.'</td><td>'.$pc->designation.'</td><td>'.$pc->prix.'</td><td>'.$pc->total.'</td></tr>';
}

$table .= '</table>';

$pdf->writeHTML($table, true, false, false, false, '');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($path, 'FI');

//============================================================+
// END OF FILE
//============================================================+
