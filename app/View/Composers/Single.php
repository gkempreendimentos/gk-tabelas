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
}
