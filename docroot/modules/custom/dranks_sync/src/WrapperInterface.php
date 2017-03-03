<?php

namespace Drupal\dranks_sync;

use Drupal\Core\Entity\ContentEntityInterface;


/**
 * Interface WrapperInterface.
 *
 * @package Drupal\dranks_google
 */
interface WrapperInterface {

  /**
   * Array of entities to be cached and enriched on host system.
   *
   * @return array
   *   Proxy data.
   */
  public function getProxyData();

  /**
   * Set proxy data.
   *
   * @param array $data
   *   Proxy data.
   */
  public function setProxyData(array $data);

  /**
   * Append item to proxy data.
   *
   * @param string $op
   *   Operation: insert, delete, update.
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   Entity.
   */
  public function appendProxyItem($op, ContentEntityInterface $entity);

  /**
   * Get array of source data.
   *
   * @return array
   *   Array of source data.
   */
  public function getSourceData();

  /**
   * Set source data.
   *
   * @param array $data
   *   Proxy data.
   */
  public function setSourceData(array $data);

  /**
   * Set time frame.
   *
   * @param array $frame
   *   Array with start and stop.
   */
  public function setTimeFrame(array $frame);

  /**
   * Get time frame.
   *
   * @return mixed
   *   Array with time frame.
   */
  public function getTimeFrame();

  /**
   * Get schedule.
   *
   * @return mixed
   *   Schedule.
   */
  public function getSchedule();

  /**
   * Update schedule and move pointer.
   */
  public function next();

  /**
   * Terminates syncer execution.
   */
  public function terminate($status);

}
