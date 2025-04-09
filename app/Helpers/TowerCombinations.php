<?php

namespace App\Helpers;

class TowerCombinations
{
  public static function doCombinations($formattedData)
  {
    $towers = [];
    $types = [];

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
      'towers' => $towers,
      'types' => $types,
      'combinations' => $towerTypeCombinations
    ];
  }
}