<?php
/**
 * Created by PhpStorm.
 * User: podarok
 * Date: 03.03.17
 * Time: 18:53
 */

namespace Drupal\dranks_sync;


use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class Wrapper
 *
 * @see https://raw.githubusercontent.com/propeoplemd/ymca/master/docroot/modules/custom/ymca_google/src/GcalGroupexWrapper.php?token=AAiY1GL19BKMtlOFhfnYW3NyxPPgJ-5Kks5YwupewA%3D%3D
 *
 * @package Drupal\dranks_sync
 */
class Wrapper implements WrapperInterface{
  use StringTranslationTrait;

  /**
   * Entity type ID.
   */
  const ENTITY_TYPE = 'node';

  /**
   * GcalGroupexWrapper constructor.
   *
   * @param StateInterface $state
   *   State.
   * @param LoggerChannelInterface $logger
   *   The logger factory.
   * @param ImmutableConfig $settings
   *   The settings.
   */
  public function __construct(StateInterface $state, LoggerChannelInterface $logger, ImmutableConfig $settings) {
    $this->state = $state;
    $this->logger = $logger;
    $this->settings = $settings;
  }

  public function setProxyData(array $data) {
    // TODO: Implement setProxyData() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

  public function next() {
    // TODO: Implement next() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

  public function getProxyData() {
    // TODO: Implement getProxyData() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

  public function appendProxyItem($op, ContentEntityInterface $entity) {
    // TODO: Implement appendProxyItem() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

  public function getSourceData() {
    // TODO: Implement getSourceData() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

  public function setSourceData(array $data) {
    // TODO: Implement setSourceData() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

  public function getSchedule() {
    // TODO: Implement getSchedule() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

  public function getTimeFrame() {
    // TODO: Implement getTimeFrame() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

  public function setTimeFrame(array $frame) {
    // TODO: Implement setTimeFrame() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }
  public function terminate($status) {
    // TODO: Implement terminate() method.
    $this->logger->error($this->t("Implement __METHOD__ method"));
  }

}