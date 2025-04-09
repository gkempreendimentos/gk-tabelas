<?php

namespace App\Services;

class GoogleSheetsService
{
  public static function getSheetData($post_id)
  {
    $api_key = get_field('api_google', 'options');
    $spreadsheet_id = get_field('spreadsheet_id', 'options');
    $range = get_field('spreadsheet_range', $post_id);

    if (!$spreadsheet_id && !$range) {
      return;
    }

    $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/values/{$range}?key={$api_key}";

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
      return 'Erro ao buscar dados da planilha.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!isset($data['values'])) {
      return 'Nenhum dado encontrado.';
    }

    return $data;
  }
}