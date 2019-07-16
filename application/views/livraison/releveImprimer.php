<?php

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once (dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// helvetica or times to reduce file size.
$pdf->SetFont('times', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage('L');

$pdf->setTitle($title);

// set text shadow effect
$pdf->setTextShadow(array(
    'enabled' => false,
    'depth_w' => 0.2,
    'depth_h' => 0.2,
    'color' => array(
        196,
        196,
        196
    ),
    'opacity' => 1,
    'blend_mode' => 'Normal'
));


$html = '<h4>EGDS ENTREPRISE GENERALE DAROU SALOU</h4>';

$pdf->writeHTMLCell(100, 0, '', 10, $html, 0, 1, 0, true, 'L', true);

$html = '<h4>NINEA : 006430590J2 </h5>';

$pdf->writeHTMLCell(90, 0, '', '', $html, 0, 1, 0, true, 'L', true);

$html = '<h4>TEL : 77 659 41 99 / 76 513 09 01 </h4>';

$pdf->writeHTMLCell(90, 0, '', '', $html, 0, 1, 0, true, 'L', true);

$img = img_url('facture.jpg');

$html = '<figure><img src="' . $img . '" alt="Logo" width="200px" /></figure>';

$pdf->writeHTMLCell('', 0, '', 10, $html, 0, 1, 0, true, 'R', true);

$html = '<h3>JOURNAL DU ' . dateFormat('d-m-Y', $this->session->userdata('reldate')). '</h3>';

$pdf->writeHTMLCell(100, 0, 100, '', $html, 0, 1, 0, true, 'C', true);

$table = '<style>
table, td, th {
    border: 1px solid black;
    text-align:center;
}

table {
    border-collapse: collapse;
}        
</style><h4 style="text-align:center">Livraisons des clients avec transport</h4>
    <table>
                            <tr>
                                <th>Date de livraison</th>
                                <th>Clients</th>
                                <th>Téléphone</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Destination</th>
                                <th>Véhicule</th>
                                <th>Nom Frais</th>
                                <th>Somme Frais</th>
                            </tr>';

$som2 = 0;

$somfrais2 = 0;

foreach ($releveclients as $cc) {
    
    $r = 0;
    
    $som2 = $som2 + $cc->total;
    
    if($cc->frais != null) {
        $r = intval(count($cc->frais) + 1);
    }
    
    $table .= '
                                <tr>
                                    <td rowspan="' . $r . '">' . dateFormatTime('d-m-Y H:i:s', $cc->dateLiv) . '</td>
                                    <td rowspan="' . $r . '">' . $cc->prenom . ' ' . $cc->nom . '</td>
                                    <td rowspan="' . $r . '">' . $cc->telephone . '</td>
                                    <td rowspan="' . $r . '">' . $cc->designation . '</td>
                                    <td rowspan="' . $r . '">' . $cc->quantite . '</td>
                                    <td rowspan="' . $r . '" style="text-align:right">' . $cc->total . '</td>
                                    <td rowspan="' . $r . '">' . $cc->adresse . '</td>
                                    <td rowspan="' . $r . '">' . $cc->immatricule . '</td>

                                ';
    if ($cc->frais != null) {
        $j = 0;
        foreach ($cc->frais as $f) {
            
            $somfrais2 = $somfrais2 + $f->somme;
            
            if($j == 0) {
                $table .= '
                                            <td>' . $f->nomFrais . '</td>
                                            <td style="text-align:right">' . $f->somme . '</td>
                                        </tr>';
            } else {
                $table .= '<tr>
                                            <td>' . $f->nomFrais . '</td>
                                            <td style="text-align:right">' . $f->somme . '</td>
                                        </tr>';
            }
            
           $j++; 
        }
    } else {
        $table .= '<td colspan="2"></td></tr>';
    }
}

$table .= '</table>';

$pdf->writeHTML($table, true, false, false, false, '');

$t2 = $som2 + $somfrais2;

$table = '
<style>
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
}        
</style>
<table>
	<tr>
            <th>Total des produits et frais</th>
            <th style="text-align:right">'.$t2.'</th>
	</tr>';

$table .= '</table><br/>';

$pdf->writeHTML($table, true, false, false, false, '');

$pdf->AddPage('L');

$table = '<style>
table, td, th {
    border: 1px solid black;
    text-align:center;
}

table {
    border-collapse: collapse;
}        
</style><h4 style="text-align:center">Livraisons directes pour les clients</h4>
    <table>
                            <tr>
                                <th>Date de livraison</th>
                                <th>Clients</th>
                                <th>Téléphone</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Destination</th>
                            </tr>';

$som3 = 0;

foreach ($relclientsdirects as $cc) {
    
    $som3 = $som3 + $cc->total;
    
    $table .= '
                                <tr>
                                    <td>' . dateFormatTime('d-m-Y H:i:s', $cc->dateLiv) . '</td>
                                    <td>' . $cc->prenom . ' ' . $cc->nom . '</td>
                                    <td>' . $cc->telephone . '</td>
                                    <td>' . $cc->designation . '</td>
                                    <td>' . $cc->quantite . '</td>
                                    <td style="text-align:right">' . $cc->total . '</td>
                                    <td>' . $cc->adresse . '</td>

                                </tr>';
    
}

$table .= '</table>';

$pdf->writeHTML($table, true, false, false, false, '');

$table = '
<style>
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
}        
</style>
<table>
	<tr>
            <th>Total des produits</th>
            <th style="text-align:right">'.$som3.'</th>
	</tr>';

$table .= '</table><br/>';

$pdf->writeHTML($table, true, false, false, false, '');

$grand = $t2 + $som3;

$table = '
<style>
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
}        
</style>
<table>
	<tr>
            <th>Grand Total</th>
            <th style="text-align:right">'.$grand.'</th>
	</tr>';

$table .= '</table><br/>';

$pdf->writeHTML($table, true, false, false, false, '');


// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Releve_journalier', 'I');

//============================================================+
// END OF FILE
//============================================================+
