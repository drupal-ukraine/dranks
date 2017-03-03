<?php

namespace Drupal\dranks_sync;

/**
 * Interface DrupalProxyInterface.
 *
 * @package Drupal\dranks_groupex
 */
interface DrupalProxyInterface {

  /**
   * Save source data to cache entities.
   */
  public function saveEntities();

}
