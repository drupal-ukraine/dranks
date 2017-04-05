<?php
/**
 * Created by PhpStorm.
 * User: podarok
 * Date: 03.03.17
 * Time: 18:52
 */

namespace Drupal\dranks_sync;

use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use GuzzleHttp\Client;

class Fetcher implements FetcherInterface{
  use StringTranslationTrait;

  /**
   * Wrapper to be used for sharing data between steps.
   *
   * @var \Drupal\dranks_sync\WrapperInterface
   */
  private $wrapper;

  /**
   * Predefined logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  private $logger;

  /**
   * HTTP client to work with.
   *
   * @var \GuzzleHttp\Client
   */
  private $client;

  public function __construct(WrapperInterface $wrapper, LoggerChannelInterface $loggerChannel, Client $client) {
    $this->wrapper = $wrapper;
    $this->logger = $loggerChannel;
    $this->client = $client;

  }

  /**
   * {@inheritdoc}
   */
  public function fetch(array $args) {
    // TODO: Implement fetch() method.
    $this->logger->error($this->t('Implement fetch method'));
  }
}