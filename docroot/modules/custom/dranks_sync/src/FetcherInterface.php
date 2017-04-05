<?php

namespace Drupal\dranks_sync;

/**
 * Interface FetcherInterface.
 *
 * @package Drupal\dranks_groupex
 */
interface FetcherInterface {

  /**
   * Fetch data from external api.
   *
   * @param array $args
   *   Arguments.
   */
  public function fetch(array $args);

}
