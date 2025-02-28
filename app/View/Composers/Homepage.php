<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Homepage extends Composer
{
  /**
   * List of views served by this composer.
   *
   * @var array
   */
  protected static $views = [
      'template-homepage',
  ];

  /**
   * Data to be passed to view before rendering.
   *
   * @return array
   */
  public function with()
  {
      return [
        'cub' => $this->getCub(),
        'enterprises' => $this->getEmpreendimentos()
      ];
  }

  /**
   * Return all "Empreendimento" posts.
   *
   * @return array
   */
  public function getEmpreendimentos()
  {
    $enterprises = [];
    $args = [
      'post_type' => 'empreendimento',
      'posts_per_page' => -1,
      'post_status' => 'publish'
    ];

    $query = new \WP_Query($args);
    
    if ($query->have_posts())
    {
      while ($query->have_posts())
      {
        $query->the_post();

        $enterprises[] = [
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'image' => get_the_post_thumbnail_url(),
          'color' => get_field('color'),
          'materials' => get_field('materials')
        ];
      }

      wp_reset_postdata();
    }

    return $enterprises;
  }

  public function getCub()
  {
    $field = get_field('cub', 'options');

    return $field;
  }
}
