<?php

namespace Drupal\oda\EventSubscriber;

use Drupal\search_api\Entity\Index;
use Drupal\flag\Event\FlagEvents;
use Drupal\flag\Event\FlaggingEvent;
use Drupal\flag\Event\UnflaggingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Flag event subscriber.
 */
class FlagSubscriber implements EventSubscriberInterface {

  /**
   * Subscribe to onFlag events.
   *
   * - Set state for flagging CI Links so email notifications can be send daily.
   * - Invalidate CI Link cache on flagging.
   */
  public function onFlag(FlaggingEvent $event): void {
    $flagging = $event->getFlagging();
    $flag_id = $flagging->getFlagId();
    if ($flag_id == 'oda_reports') {
      $indexes = Index::loadMultiple();
      $index = $indexes['oda_data'];
      $entity = $flagging->getFlaggable();
      $entity_id = $flagging->getFlaggableId();
      $langcode = $entity->language()->getId();
      $index->trackItemsUpdated('entity:node', [$entity_id . ':' . $langcode]);
    }
  }

  /**
   * Subscribe to onUnFlag events.
   *
   * - Set state for unflagging CI Links so notifications can be sent daily.
   * - Invalidate CI Link cache on flagging.
   */
  public function onUnflag(UnflaggingEvent $event): void {
    $flagging = $event->getFlaggings();
    $flagging = reset($flagging);
    $flag_id = $flagging->getFlagId();
    if ($flag_id == 'oda_reports') {
      $indexes = Index::loadMultiple();
      $index = $indexes['oda_data'];
      $entity = $flagging->getFlaggable();
      $entity_id = $flagging->getFlaggableId();
      $langcode = $entity->language()->getId();
      $index->trackItemsUpdated('entity:node', [$entity_id . ':' . $langcode]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[FlagEvents::ENTITY_FLAGGED][] = ['onFlag'];
    $events[FlagEvents::ENTITY_UNFLAGGED][] = ['onUnflag'];
    return $events;
  }

}
