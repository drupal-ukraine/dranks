<?php
/**
 * Created by PhpStorm.
 * User: podarok
 * Date: 03.03.17
 * Time: 18:53
 */

namespace Drupal\dranks_sync;


use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Logger\LoggerChannelInterface;

class DrupalProxy implements DrupalProxyInterface{


  /**
   * Max allowed updates per run.
   */
  const PROXY_UPDATE_PER_RUN = 100;

  /**
   * Max children for single parent entity.
   */
  const MAX_CHILD_WARNING = 100;

  /**
   * Entity load chunk.
   */
  const ENTITY_LOAD_CHUNK = 100;

  /**
   * @var \Drupal\dranks_sync\Wrapper
   */
  protected $dataWrapper;

  /**
   * Timezone object.
   *
   * @var \DateTimeZone
   */
  protected $timezone;

  /**
   * Query factory.
   *
   * @var QueryFactory
   */
  protected $queryFactory;

  /**
   * Logger.
   *
   * @var LoggerChannelInterface
   */
  protected $logger;

  /**
   * @var \Drupal\dranks_sync\Fetcher
   */
  protected $fetcher;

  /**
   * Entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The cache storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $cacheStorage;

  public function __construct(Wrapper $data_wrapper, QueryFactory $query_factory, LoggerChannelInterface $logger, Fetcher $fetcher, EntityTypeManagerInterface $entity_type_manager) {
    $this->dataWrapper = $data_wrapper;
    $this->queryFactory = $query_factory;
    $this->logger = $logger;
    $this->fetcher = $fetcher;
    $this->entityTypeManager = $entity_type_manager;

    $this->cacheStorage = $this->entityTypeManager->getStorage(Wrapper::ENTITY_TYPE);
  }

  /**
   * {@inheritdoc}
   */
  public function saveEntities() {
    // TODO: Implement saveEntities() method.
    $this->logger->error($this->t('Implement saveEntities method'));
  }

}