<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Services\GoogleSheetsService;
use App\Helpers\TowerCombinations;

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
        'fluxo' => \App\Helpers\FluxoPagamento::get(get_the_ID()),
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
   * Return google spreadsheet by ID
   * 
   */
  public function getTable()
  {
    $data = GoogleSheetsService::getSheetData(get_the_ID());
    $page = request()->get('pagina', 1);
    $itemsPerPage = request()->get('limit', 10);

    if (!isset($data['values'])) {
      return;
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

    $combinations = TowerCombinations::doCombinations($formattedData);

    return [
      'headers' => $headers,
      'data' => [
        'complete' => $formattedData,
        'summary' => $paginatedData,
        'towers' => $combinations['towers'],
        'types' => $combinations['types']
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
