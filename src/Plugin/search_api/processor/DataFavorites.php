<?php

namespace Drupal\oda\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Search API Processor for indexing data reports favorites.
 *
 * @SearchApiProcessor(
 *   id = "custom_data_favorites",
 *   label = @Translation("Custom Event Type"),
 *   description = @Translation("Add event type to the index."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class DataFavorites extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(?DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Custom Favorite data reports'),
        'description' => $this->t('Data reports favorites.'),
        'type' => 'string',
        'processor_id' => $this->getPluginId(),
      ];
      $properties['search_api_custom_data_report_fav'] = new ProcessorProperty($definition);

    }
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $entity = $item->getOriginalObject()->getValue();

    $flag = \Drupal::service('flag')->getFlaggingUsers($entity);

    $flag_uids = array_keys($flag);

    $fields = $item->getFields();
    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($fields, NULL, 'search_api_custom_data_report_fav');
    foreach ($fields as $field) {

      foreach ($flag_uids as $uid) {
        $field->addValue($uid);
      }

    }
  }

}
