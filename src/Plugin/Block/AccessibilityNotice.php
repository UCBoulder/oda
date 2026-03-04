<?php

namespace Drupal\oda\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\oit\Plugin\BlockUuidQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Oda Accessibility Notice.
 *
 * @Block(
 *   id = "oda_accessibility_notice",
 *   admin_label = @Translation("ODA Accessibility Notice Block")
 * )
 */
final class AccessibilityNotice extends BlockBase implements
  ContainerFactoryPluginInterface {

  /**
   * Invoke renderer.
   *
   * @var \Drupal\oit\Plugin\BlockUuidQuery
   */
  protected $blockUuidQuery;

  /**
   * Invoke renderer.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityInterface;

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container pulled in.
   * @param array<string, mixed> $configuration
   *   Configuration added.
   * @param string $plugin_id
   *   Plugin_id added.
   * @param mixed $plugin_definition
   *   Plugin_definition added.
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('oit.block.uuid.query'),
      $container->get('current_route_match'),
    );
  }

  /**
   * {@inheritdoc}
   *
   * @param array<string, mixed> $configuration
   *   Configuration array.
   * @param string $plugin_id
   *   Plugin id string.
   * @param mixed $plugin_definition
   *   Plugin Definition mixed.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_interface
   *   Invokes renderer.
   * @param \Drupal\oit\Plugin\BlockUuidQuery $block_uuid_query
   *   Loads block.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_interface,
    BlockUuidQuery $block_uuid_query,
    RouteMatchInterface $route_match,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityInterface = $entity_interface;
    $this->blockUuidQuery = $block_uuid_query;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   *
   * @return array<string, mixed>
   */
  public function build(): array {
    $node = $this->routeMatch->getParameter('node');
    $type = $node ? $node->get('field_oda_type')->getValue()[0]['target_id'] : NULL;

    if ($type == 1129) {
      $this->blockUuidQuery->getBidByUuid('1a9a7a82-bd59-4078-90d4-2b410959fac5');
      $render = $this->blockUuidQuery->loadBlock();
    }
    else {
      $render = '';
    }
    return [
      '#type' => 'inline_template',
      '#template' => '{{ block }}',
      '#context' => [
        'block' => $render,
      ],
    ];
  }

}
