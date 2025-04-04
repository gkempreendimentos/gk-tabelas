<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use TCPDF;

class MYPDF extends TCPDF {

  // Colored table
  public function ColoredTable($header,$data) {
    // Colors, line width and bold font
    $this->SetFillColor(33, 43, 44);
    $this->SetTextColor(255);
    $this->SetDrawColor(8,19,20);
    $this->SetLineWidth(0.3);
    $this->SetFont('helvetica', '', 10);

    // Header
    $num_headers = count($header);
    for($i = 0; $i < $num_headers; ++$i) {
      if (!in_array($header[$i], ['Disponibilidade', 'Torre', 'Tipo', 'Posição', 'Grid'])) {
        $this->Cell(38.5, 5, $header[$i], 1, 0, 'C', 1);
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
        if ($key === 'Planta') {
          $this->Cell(38.5, 5, 'Ver planta', 1, 0, 'C', 1, $value);
        }

        if (!in_array($key, ['Disponibilidade', 'Torre', 'Tipo', 'Posição', 'Grid', 'Planta'])) {
          $this->Cell(38.5, 5, $value, 1, 0, 'C', $fill);
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


class Single extends Composer
{
  /**
   * List of views served by this composer.
   *
   * @var array
   */
  protected static $views = [
    'single',
  ];

  /**
   * Data to be passed to view before rendering.
   *
   * @return array
   */
  public function with()
  {
      return [
        'data' => $this->getData(),
        'table' => $this->getTable(),
      ];
  }

  /**
   * Returns enterprise materials
   *
   * @return array
   */
  public function getData()
  {
    $id = get_the_ID();

    return [
      'title' => get_the_title($id),
      'permalink' => get_the_permalink($id),
      'image' => get_the_post_thumbnail_url($id),
      'color' => get_field('color', $id),
      'materials' => get_field('materials', $id)
    ];
  }

  /**
   * Format Summarized Table
   * 
   * @param headers
   * @param values
   * @return array
   */
  function summarizedData($headers, $values)
  {
    $order_by = request()->get('order_by', 'apartamento_asc');
    $summarizedData = array_filter($values, function($row) {
      return isset($row['Disponibilidade']) && $row['Disponibilidade'] !== 'VENDIDO';
    });
    $summarizedData = array_values($summarizedData);

    if ($order_by) {
      usort($summarizedData, function ($a, $b) use ($order_by) {
        switch ($order_by) {
            case 'Apartamento_asc':
            case 'Apartamento_desc':
                // Separar a parte numérica e a parte alfabética do número do apartamento
                preg_match('/(\d+)([A-Z]*)/', $a['Apartamento'], $matchesA);
                preg_match('/(\d+)([A-Z]*)/', $b['Apartamento'], $matchesB);
    
                // Converte a parte numérica para inteiro
                $numA = (int) $matchesA[1];
                $numB = (int) $matchesB[1];
    
                // Ordena primeiro pelo número
                if ($numA !== $numB) {
                    return ($order_by === 'Apartamento_asc') ? $numA - $numB : $numB - $numA;
                }
    
                // Se os números forem iguais, ordena pela letra
                return ($order_by === 'Apartamento_asc')
                    ? strcmp($matchesA[2], $matchesB[2])
                    : strcmp($matchesB[2], $matchesA[2]);
    
            case 'Preço_asc':
            case 'Preço_desc':
                // Remove caracteres não numéricos e converte para float
                $precoA = (float) str_replace(['R$', '.', ','], ['', '', '.'], $a['Preço']);
                $precoB = (float) str_replace(['R$', '.', ','], ['', '', '.'], $b['Preço']);
    
                return ($order_by === 'Preço_asc') ? $precoA <=> $precoB : $precoB <=> $precoA;
    
            case 'Metragem_asc':
            case 'Metragem_desc':
                // Remove 'm²' e converte para float
                $metragemA = (float) str_replace(['m²', ','], ['', '.'], $a['Metragem']);
                $metragemB = (float) str_replace(['m²', ','], ['', '.'], $b['Metragem']);
    
                return ($order_by === 'Metragem_asc') ? $metragemA <=> $metragemB : $metragemB <=> $metragemA;
    
            case 'Dormitórios_asc':
            case 'Dormitórios_desc':
                // Extrai o número de dormitórios
                preg_match('/(\d+)/', $a['Dormitórios'], $matchesA);
                preg_match('/(\d+)/', $b['Dormitórios'], $matchesB);
                $dormitoriosA = (int) ($matchesA[1] ?? 0);
                $dormitoriosB = (int) ($matchesB[1] ?? 0);
    
                return ($order_by === 'Dormitórios_asc') ? $dormitoriosA <=> $dormitoriosB : $dormitoriosB <=> $dormitoriosA;
    
            default:
                return 0; // Sem ordenação
        }
      });    
    }

    return $summarizedData;
  }

  /**
   * Export PDF
   * 
   * @param table
   * @param combinations
   * @param headers
   * @return avoid
   */
  function exportPDF($table, $combinations, $headers)
  {
    $data = $this->getData();
    $upload_dir = wp_get_upload_dir();

    $pdf_path = $upload_dir['path'] . "/{$data['title']}.pdf";
    $pdf_url = $upload_dir['url'] . "/{$data['title']}.pdf";
    $logo_path = asset('images/pdf_logo.jpg')->path();
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('GK Empreendimentos');
    $pdf->SetTitle("Tabela Resumida - {$data['title']}");
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetHeaderData($logo_path, 13, "Tabela Resumida - {$data['title']}", 'GK Empreendimentos', array(8,19,20), array(8,19,20));
    $pdf->setFooterData(array(8,19,20), array(8,19,20));
    $pdf->SetMargins(8, 16, 8);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pageWidth = $pdf->getPageWidth();
    $margins = $pdf->getMargins();
    $usableWidth = $pageWidth - $margins['left'] - $margins['right'];

    static $image_cache = [];

    foreach ($combinations as $combination)
    {
      $pdf->AddPage();
      $pdf->SetFont('helvetica', 'B', 16);
      $pdf->Cell(0, 16, "{$combination['tower']} - Imóvel {$combination['type']}", 0, 1, 'C');

      $data = [];

      foreach($table as $row)
      {
        if ($row['Disponibilidade'] === 'DISPONÍVEL' && $row['Torre'] === $combination['tower'] && $row['Tipo'] === $combination['type'])
        {
          $data[] = $row;
        }
      }

      $pdf->ColoredTable($headers, $data);

      foreach (get_field('plans') as $plan) {
        if ($plan['plan_image'] && $plan['tower'] === $combination['tower'] && $plan['type'] === $combination['type']) {
          $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $plan['plan_image']['sizes']['large']);

          if (!isset($image_cache[$image_path])) {
            $pdf->Image($image_path, 8, 150, $usableWidth, 0, $plan['plan_image']['subtype'], '', '', true, 72, '', false, false, 0, false, false, false);
            $image_cache[$image_path] = true;
            $pdf->clearImageCache();
          }
        }
      }

      $pdf->MultiCell($usableWidth, 5, str_replace('<br />', '', get_field('pdf_obs', 'options')), 0, 'L', 0, 0, 8, 260, true);

      error_log("Memória após {$combination['tower']} - {$combination['type']}: " . round(memory_get_usage(true) / 1048576, 2) . "MB");

      ob_flush();
      flush();
      unset($data);
      gc_collect_cycles();
    }


    $pdf->Output($pdf_path, 'F');
    
    return home_url(str_replace(ABSPATH, '', $pdf_path));
  }

  /**
   * Return google spreadsheet by ID
   * 
   */
  public function getTable()
  {
    $api_key = get_field('api_google', 'options');
    $spreadsheet_id = get_field('spreadsheet_id');
    $range = get_field('spreadsheet_range');

    if (!$spreadsheet_id && !$range) {
      return;
    }

    $page = request()->get('pagina', 1);
    $itemsPerPage = request()->get('limit', 10);

    $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/values/{$range}?key={$api_key}";

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
      return 'Erro ao buscar dados da planilha';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!isset($data['values'])) {
      return 'Nenhum dado encontrado';
    }

    $headers = array_shift($data['values']);
    $formattedData = array_map(function($row) use ($headers) {
      return array_combine($headers, $row);
    }, $data['values']);
    $formattedData = array_values($formattedData);
    $summarizedData = $this->summarizedData($headers, $formattedData);
    
    $totalItems = count($summarizedData);
    $offset = ($page - 1) * $itemsPerPage;
    $paginatedData = array_slice($summarizedData, $offset, $itemsPerPage);

    $towers = [];
    $types = [];
    $towerTypeCombinations = [];
    $max_cols = 0;

    foreach ($formattedData as $ap) {
      $towers[] = $ap["Torre"];
      $types[] = $ap["Tipo"];
    }

    $towers = array_unique($towers);
    $types = array_unique($types);
    
    foreach ($towers as $tower) {
      foreach ($types as $type) {
        $towerTypeCombinations[] = [
          'tower' => $tower,
          'type' => $type
        ];
      }
    }

    return [
      'headers' => $headers,
      'data' => [
        'complete' => $formattedData,
        'summary' => $paginatedData,
        'towers' => $towers,
        'types' => $types,
        'pdf' => $this->exportPDF($formattedData, $towerTypeCombinations, $headers)
      ],
      'pagination' => [
        'total' => $totalItems,
        'currentPage' => $page,
        'itemsPerPage' => $itemsPerPage,
        'totalPages' => ceil($totalItems / $itemsPerPage),
      ]
    ];
  }
}
