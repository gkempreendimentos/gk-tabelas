<?php

/**
 * Campos ACF do fluxo de pagamento esperado por empreendimento.
 *
 * O grupo é registrado em código (e não só no banco) para ficar versionado
 * no Git: o que está no repositório é o que existe no painel.
 */

namespace App;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_gk_fluxo_pagamento',
        'title' => 'Fluxo de pagamento esperado (referência)',
        'description' => 'Fluxo padrão oferecido pela GK para este empreendimento. Aparece para o corretor na página do empreendimento, acima do download dos materiais.',
        'menu_order' => 6,
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'empreendimento',
                ],
            ],
        ],
        'fields' => [
            [
                'key' => 'field_gk_fluxo_exibir',
                'label' => 'Exibir na página do empreendimento',
                'name' => 'gk_fluxo_exibir',
                'type' => 'true_false',
                'ui' => 1,
                'ui_on_text' => 'Sim',
                'ui_off_text' => 'Não',
                'default_value' => 1,
                'instructions' => 'Desmarque para esconder o bloco sem apagar os valores.',
            ],
            [
                'key' => 'field_gk_fluxo_entrada',
                'label' => '% de entrada',
                'name' => 'gk_fluxo_entrada',
                'type' => 'number',
                'min' => 0,
                'max' => 100,
                'step' => 0.01,
                'append' => '%',
                'instructions' => 'Percentual de entrada esperado. Ex.: 20',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_gk_fluxo_parcelas',
                'label' => 'Nº de parcelas',
                'name' => 'gk_fluxo_parcelas',
                'type' => 'number',
                'min' => 0,
                'step' => 1,
                'instructions' => 'Quantidade de parcelas depois da entrada. Ex.: 100',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_gk_fluxo_periodicidade',
                'label' => 'Periodicidade',
                'name' => 'gk_fluxo_periodicidade',
                'type' => 'select',
                'choices' => [
                    'mensais' => 'Mensais',
                    'bimestrais' => 'Bimestrais',
                    'trimestrais' => 'Trimestrais',
                    'semestrais' => 'Semestrais',
                    'anuais' => 'Anuais',
                ],
                'default_value' => 'mensais',
                'allow_null' => 0,
                'instructions' => 'Só para exibição (o simulador calcula por mês).',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_gk_fluxo_reforcos',
                'label' => 'Reforços (texto livre)',
                'name' => 'gk_fluxo_reforcos',
                'type' => 'text',
                'placeholder' => '10 reforços anuais de R$ 100.000,00',
                'instructions' => 'Opcional. Deixe vazio se não houver reforço.',
            ],
            [
                'key' => 'field_gk_fluxo_obs',
                'label' => 'Observação',
                'name' => 'gk_fluxo_obs',
                'type' => 'textarea',
                'rows' => 3,
                'new_lines' => 'br',
                'instructions' => 'Opcional. Ex.: "Sinal parcelado em 4x durante a obra. Valores de referência, sujeitos à análise de crédito."',
            ],
        ],
    ]);
});

/**
 * Coluna "Fluxo esperado" na listagem de empreendimentos do painel.
 *
 * Serve como referência rápida de todos os empreendimentos de uma vez.
 */
add_filter('manage_empreendimento_posts_columns', function ($columns) {
    $new = [];

    foreach ($columns as $key => $label) {
        $new[$key] = $label;

        if ($key === 'title') {
            $new['gk_fluxo'] = 'Fluxo esperado';
        }
    }

    if (!isset($new['gk_fluxo'])) {
        $new['gk_fluxo'] = 'Fluxo esperado';
    }

    return $new;
}, 20);

add_action('manage_empreendimento_posts_custom_column', function ($column, $post_id) {
    if ($column !== 'gk_fluxo') {
        return;
    }

    $resumo = \App\Helpers\FluxoPagamento::resumo($post_id);

    echo $resumo
        ? '<strong>' . esc_html($resumo) . '</strong>'
        : '<span style="color:#a7aaad">— não cadastrado</span>';
}, 10, 2);
