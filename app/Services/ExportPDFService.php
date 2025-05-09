<?php 

namespace App\Services;
use TCPDF;
use App\Helpers\TowerCombinations;


class MYPDF extends TCPDF {

  // Colored table
  public function ColoredTable($header, $data) {
    // Colors, line width and bold font
    $this->SetFillColor(33, 43, 44);
    $this->SetTextColor(255);
    $this->SetDrawColor(8,19,20);
    $this->SetLineWidth(0.3);
    $this->SetFont('helvetica', '', 8);

    // Header
    $num_headers = count($header);
    for($i = 0; $i < $num_headers; ++$i) {
      if (!in_array($header[$i], ['Disponibilidade', 'Torre', 'Tipo', 'Posição', 'Grid'])) {
        $this->Cell(38.5, 4, $header[$i], 1, 0, 'C', 1);
      }
    }
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224, 235, 255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = 0;
    $w = [30, ];
    foreach($data as $row) { 
      foreach($row as $key => $value) {
           $isUnavailable = $row['Disponibilidade'] && $value === 'VENDIDO';
          if ($key === 'Preço') {
            $price = $isUnavailable ? 'Vendido' : $value;
            $this->Cell(38.5, 4, $price, 1, 0, 'C', $fill);
            }
          if ($key === 'Planta') {
          $this->Cell(38.5, 4, 'Ver planta', 1, 0, 'C', 1, $value);
        }

        if (!in_array($key, ['Disponibilidade', 'Torre', 'Tipo', 'Posição', 'Grid', 'Planta','Preço'])) {
          if ($key === 'Preço' && $disponibilidade !== 'disponivel') {
            $value = $disponibilidade;
          }
          $this->Cell(38.5, 4, $value, 1, 0, 'C', $fill);
        }
      }

      $this->Ln();
    }
    $this->Cell(100, 0, '', 'T');
  }

  public function clearImageCache() {
    $this->imagekeys = [];
  }
}

class ExportPDFService
{
  public static function export($post_id)
  {
    $data = GoogleSheetsService::getSheetData($post_id);
    $headers = array_shift($data['values']);
    $table = array_map(function($row) use ($headers) {
      return array_combine($headers, $row);
    }, $data['values']);
    $table = array_values($table);
    $combinations = TowerCombinations::doCombinations($table);
    $title = get_the_title($post_id);

    $upload_dir = wp_get_upload_dir();

    $filename = "{$title}.pdf";
    $pdf_path = $upload_dir['path'] . "/{$filename}";
    $pdf_url = $upload_dir['url'] . "/{$filename}";

    $logo_path = asset('images/pdf_logo.jpg')->path();

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('GK Empreendimentos');
    $pdf->SetTitle("Tabela Resumida - {$title}");
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $currentDate = date('d/m/Y');
    $pdf->SetHeaderData($logo_path, 13, "Tabela Resumida - {$title} - {$currentDate}", 'GK Empreendimentos', array(8,19,20), array(8,19,20));
    $pdf->setFooterData(array(8,19,20), array(8,19,20));
    $pdf->SetMargins(8, 16, 8);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pageWidth = $pdf->getPageWidth();
    $margins = $pdf->getMargins();
    $usableWidth = $pageWidth - $margins['left'] - $margins['right'];

    foreach ($combinations['combinations'] as $combination)
    {
      $filtered_data = [];

      foreach($table as $row)
      {
        if ($row['Torre'] === $combination['tower'] && $row['Tipo'] === $combination['type']) {
          if (sanitize_title($row['Disponibilidade']) !== 'disponivel') {
            // Replace price value (assuming the column is named 'Preço') with 'Disponibilidade'
            $row['Preço'] = $row['Disponibilidade'];
          }
          $filtered_data[] = $row;
        }
      }

      if (empty($filtered_data)) {
        continue;
      }

      $pdf->AddPage();
      $pdf->SetFont('helvetica', 'B', 16);
      $pdf->Cell(0, 16, "{$combination['tower']} - Imóvel {$combination['type']}", 0, 1, 'C');

      $pdf->ColoredTable($headers, $filtered_data);

      foreach (get_field('plans') as $plan) {
        $image = $plan['plan_pdf'] ? $plan['plan_pdf'] : $plan['plan_image'];
        if ($image && $plan['tower'] === $combination['tower'] && $plan['type'] === $combination['type']) {
          $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image['sizes']['large']);

          if (!isset($image_cache[$image_path])) {
            $pdf->Image($image_path, 8, 150, $usableWidth, 0, $image['subtype'], '', '', true, 300, '', false, false, 0, false, false, false);
          }
        }
      }

      $bottomY = $pdf->getPageHeight() - $pdf->getFooterMargin() - 20; 
      $pdf->MultiCell($usableWidth, 5, str_replace('<br />', '', get_field('pdf_obs')), 0, 'L', 0, 0, 8, $bottomY, true);

      unset($filtered_data);
      gc_collect_cycles();
    }

    $pdf->Output($pdf_path, 'F');
    update_field('pdf_table', str_replace('http', 'https', $pdf_url), $post_id);
    return home_url(str_replace(ABSPATH, '', $pdf_path));
  }
}
