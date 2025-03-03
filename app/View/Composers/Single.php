<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

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
        'table' => $this->getTable()
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

    var_dump(get_field('materials', $id));

    return [
      'title' => get_the_title($id),
      'permalink' => get_the_permalink($id),
      'image' => get_the_post_thumbnail_url($id),
      'color' => get_field('color', $id),
      'materials' => get_field('materials', $id)
    ];
  }

  /**
   * Return google spreadsheet by ID
   * 
   */
  public function getTable()
  {
    $api_key = get_field('api_google', 'options');
    $spreadsheet_id = get_field('spreadsheet_id');
    $range = 'A1:E100';

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

    return [
      'headers' => $headers,
      'data' => $formattedData
    ];
  }
}
