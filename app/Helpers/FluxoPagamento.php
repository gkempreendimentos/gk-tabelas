<?php

namespace App\Helpers;

/**
 * Fluxo de pagamento esperado (referência) de um empreendimento.
 *
 * Os valores são cadastrados no painel (campos ACF do grupo
 * "Fluxo de pagamento esperado") e apenas exibidos ao corretor.
 * Nada aqui calcula parcela: o simulador continua independente.
 */
class FluxoPagamento
{
  /**
   * Retorna os dados formatados do fluxo de pagamento.
   *
   * @param int|null $post_id
   * @return array
   */
  public static function get($post_id = null)
  {
    $empty = [
      'show' => false,
      'entrada' => null,
      'entrada_label' => '',
      'parcelas' => null,
      'parcelas_label' => '',
      'periodicidade' => 'mensais',
      'reforcos' => '',
      'obs' => '',
      'resumo' => '',
    ];

    $post_id = $post_id ?: get_the_ID();

    if (!$post_id || !function_exists('get_field')) {
      return $empty;
    }

    $entrada = get_field('gk_fluxo_entrada', $post_id);
    $parcelas = get_field('gk_fluxo_parcelas', $post_id);
    $periodicidade = get_field('gk_fluxo_periodicidade', $post_id);
    $exibir = get_field('gk_fluxo_exibir', $post_id);

    $entrada = ($entrada === null || $entrada === '') ? null : (float) $entrada;
    $parcelas = ($parcelas === null || $parcelas === '') ? null : (int) $parcelas;
    $periodicidade = $periodicidade ?: 'mensais';

    $has_flow = $entrada !== null || $parcelas !== null;
    $show = $has_flow && ($exibir === null || $exibir === '' || $exibir);

    if (!$show) {
      return array_merge($empty, [
        'entrada' => $entrada,
        'parcelas' => $parcelas,
        'periodicidade' => $periodicidade,
      ]);
    }

    return [
      'show' => true,
      'entrada' => $entrada,
      'entrada_label' => self::percentLabel($entrada),
      'parcelas' => $parcelas,
      'parcelas_label' => self::installmentsLabel($parcelas, $periodicidade),
      'periodicidade' => $periodicidade,
      'reforcos' => self::nowrapCurrency(trim(wp_strip_all_tags((string) get_field('gk_fluxo_reforcos', $post_id)))),
      'obs' => self::nowrapCurrency(trim((string) get_field('gk_fluxo_obs', $post_id))),
      'resumo' => self::summary($entrada, $parcelas, $periodicidade),
    ];
  }

  /**
   * Frase curta — usada em listagens e no resumo do painel.
   *
   * @param int|null $post_id
   * @return string
   */
  public static function resumo($post_id = null)
  {
    $fluxo = self::get($post_id);

    return $fluxo['resumo'];
  }

  /**
   * Mantém "R$ 100.000,00" na mesma linha (espaço inquebrável).
   */
  protected static function nowrapCurrency($text)
  {
    if ($text === '' || $text === null) {
      return '';
    }

    return preg_replace('/R\$\s+/u', 'R$' . "\u{00A0}", $text);
  }

  /**
   * "20%"
   */
  protected static function percentLabel($value)
  {
    if ($value === null) {
      return '';
    }

    $formatted = rtrim(rtrim(number_format((float) $value, 2, ',', ''), '0'), ',');

    return $formatted . '%';
  }

  /**
   * "100 mensais"
   */
  protected static function installmentsLabel($value, $periodicidade)
  {
    if ($value === null) {
      return '';
    }

    return number_format((int) $value, 0, ',', '.') . ' ' . $periodicidade;
  }

  /**
   * "Entrada de 20% + 100 parcelas mensais"
   */
  protected static function summary($entrada, $parcelas, $periodicidade)
  {
    $parts = [];

    if ($entrada !== null) {
      $parts[] = 'Entrada de ' . self::percentLabel($entrada);
    }

    if ($parcelas !== null) {
      $parts[] = ($entrada !== null)
        ? '+ ' . number_format((int) $parcelas, 0, ',', '.') . ' parcelas ' . $periodicidade
        : number_format((int) $parcelas, 0, ',', '.') . ' parcelas ' . $periodicidade;
    }

    return implode(' ', $parts);
  }
}
