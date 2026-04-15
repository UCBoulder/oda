<?php

namespace Drupal\oda\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Top pages block.
 *
 * @Block(
 *   id = "oda_header",
 *   admin_label = @Translation("ODA header link")
 * )
 */
class OdaHeader extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * @return array<string, mixed>
   *   A renderable array representing the block content.
   */
  public function build(): array {
    $block['string'] = [
      '#type' => 'inline_template',
      '#template' => '<div class="site-name-wrapper">
      <div class="site-name">
        <a href="/" title="Home" class="header__site-link" rel="home"><span>{{ title }}</span></a>
      </div>
      <div class="affiliation">
        <a href="https://oit.colorado.edu/">{{ subtitle }}</a></div>
      </div>',
      '#context' => [
        'title' => $this->t('Data & Analytics'),
        'subtitle' => $this->t('Office of Information Technology'),
      ],
    ];

    return $block;
  }

}
