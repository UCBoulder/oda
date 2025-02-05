<?php

namespace Drupal\oda\EventSubscriber;

use Drupal\Core\Cache\Cache;
use Drupal\flag\Event\FlagEvents;
use Drupal\flag\Event\FlaggingEvent;
use Drupal\flag\Event\UnflaggingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
class FlagSubscriber implements EventSubscriberInterface {

  /**
   * Subscribe to onFlag events.
   *
   * - Set state for flagging CI Links so email notifications can be send daily.
   * - Invalidate CI Link cache on flagging.
   */
  public function onFlag(FlaggingEvent $event) {
    $flagging = $event->getFlagging();
    $flag_id = $flagging->getFlagId();
    $entity_sid = $flagging->getFlaggable()->id();
    if ($flag_id == 'oda_reports') {
      $indexes = \Drupal\search_api\Entity\Index::loadMultiple();
      $datasource_id = 'oda_data';
      $indexes[$datasource_id]->reindex();
    }
  }

  /**
   * Subscribe to onUnFlag events.
   *
   * - Set state for unflagging CI Links so notifications can be sent daily.
   * - Invalidate CI Link cache on flagging.
   */
  public function onUnflag(UnflaggingEvent $event) {
    $flagging = $event->getFlaggings();
    $flagging = reset($flagging);
    $flag_id = $flagging->getFlagId();
    $entity_sid = $flagging->getFlaggable()->id();
    if ($flag_id == 'oda_reports') {
      $indexes = \Drupal\search_api\Entity\Index::loadMultiple();
      $datasource_id = 'oda_data';
      $indexes[$datasource_id]->reindex();
    }
  }

  /**
   *
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[FlagEvents::ENTITY_FLAGGED][] = ['onFlag'];
    $events[FlagEvents::ENTITY_UNFLAGGED][] = ['onUnflag'];
    return $events;
  }

}
