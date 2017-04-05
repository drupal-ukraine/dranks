<?php

namespace Drupal\phpunit_tdd;

use Drupal\Core\Config\BootstrapConfigStorageFactory;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class PhpunitTddServiceProvider.
 *
 * @package Drupal\phpunit_tdd
 */
class PhpunitTddServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container) {
    $decorated = [];
    $modules = $container->getParameter('container.modules');

    // Add this only if phpunit_tdd module is enabled.
    if (!isset($modules['phpunit_tdd'])) {
      throw new \Exception('Module is not enabled');
    }
    try {
      $config_storage = BootstrapConfigStorageFactory::get();
      $phpunit_tdd = $config_storage->read('phpunit_tdd.config');
      if (!$phpunit_tdd) {
        // Module or config not yet available.
        // Current class runs before module/config enabled.
        return;
      }
      $decorated = $phpunit_tdd['decorated'];
    }
    catch (\Exception $e) {
      // config_clean.sh is not working here because of drush.
      // @todo fix this for drush somehow.
    }

    $container->register('phpunit_tdd.tester', PhpunitTddMockerFactory::class);
    foreach ($decorated as $key => $data) {
      if (isset($data['status']) && $data['status'] == TRUE) {
        // Set original class as decorated with a new one from the config.
        // Original service still is available via $service_name.inner.
        $container->register(
          $data['replacement'],
          PhpunitTddMockerFactory::class
        )
          ->setDecoratedService($data['source'])->setShared(TRUE)
          ->setFactory([new Reference('phpunit_tdd.tester'), 'testMe'])
          ->setArguments(
            [
              new Reference($data['replacement'] . '.inner'),
              new Reference('config.factory'),
              new Reference('serializer'),
            ]
          );
      }
    }

    parent::register($container);

  }

}
