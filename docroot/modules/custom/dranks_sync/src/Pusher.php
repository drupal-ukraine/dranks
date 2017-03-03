<?php
/**
 * Created by PhpStorm.
 * User: podarok
 * Date: 03.03.17
 * Time: 18:52
 */

namespace Drupal\dranks_sync;


use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\Query\QueryFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;

class Pusher implements PusherInterface{


  /**
   * Wrapper to be used.
   *
   * @var Wrapper
   */
  protected $dataWrapper;

  /**
   * Config Factory.
   *
   * @var ConfigFactory
   */
  protected $configFactory;


  /**
   * The logger channel.
   *
   * @var LoggerChannelInterface
   */
  protected $logger;

  /**
   * Entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Proxy.
   *
   * @var DrupalProxy
   */
  protected $proxy;

  /**
   * Query factory.
   *
   * @var QueryFactory
   */
  protected $query;

  /**
   * The entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $cacheStorage;

  /**
   * Pusher constructor.
   *
   * @param Wrapper $data_wrapper
   *   Data wrapper.
   * @param ConfigFactoryInterface $config_factory
   *   Config Factory.
   * @param LoggerChannelInterface $logger
   *   The logger channel.
   * @param EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param DrupalProxyInterface $proxy
   *   The Proxy.
   * @param QueryFactoryInterface $query
   *   Query factory.
   */
  public function __construct(Wrapper $data_wrapper, ConfigFactoryInterface $config_factory, LoggerChannelInterface $logger, EntityTypeManagerInterface $entity_type_manager, DrupalProxyInterface $proxy, QueryFactoryInterface $query) {
    $this->dataWrapper = $data_wrapper;
    $this->configFactory = $config_factory;
    $this->logger = $logger;
    $this->entityTypeManager = $entity_type_manager;
    $this->proxy = $proxy;
    $this->query = $query;

    $this->cacheStorage = $this->entityTypeManager->getStorage(Wrapper::ENTITY_TYPE);

  }

  public function push() {
    // TODO: Implement push() method.
  }

}