<?php

namespace Drupal\phpunit_tdd;

use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\Serializer\SerializerInterface;

/**
 *
 */
class PhpunitTddMockerFactory extends \PHPUnit_Framework_TestCase {

  /**
   * @param $service
   *   Original service's object.
   * @param \Drupal\Core\Config\ConfigFactory $config
   *   Config Factory to work with configs.
   * @param \Drupal\Component\Serialization\Json $serializer
   *   Serializer to store data serialized into config.
   *
   * @return object
   *   Returns mocked object.
   */
  public function testMe(
    $service,
    ConfigFactory $config,
    SerializerInterface $serializer
  ) {
    $original_class = get_class($service);
    $config = $config->getEditable('phpunit_tdd.config');
    $services = $config->get('services');
    $decorated = $config->get('decorated');
    $services = [];
    foreach ($decorated as $key => $data) {
      if (isset($data['methods'])) {
        $services[$data['source']] = $data['methods'];
      }
      else {
        $services[$data['source']] = NULL;
      }
    }

    /* @var array of methods to be mocked $mock_methods */
    $mock_methods = [];
    // Check if class exists in the config.
    if (array_key_exists($service->_serviceId, $services)) {
      foreach ($services[$service->_serviceId] as $method => $m_config) {
        if (isset($m_config['status']) && $m_config['status'] == TRUE) {
          if (isset($m_config['type'])) {
            $data = isset($m_config['data']) ? $m_config['data'] : 'Return data not yet provided within config.';
            $mock_methods[$method] = ['type' => $m_config['type'], 'data' => $data];
          }
          else {
            $mock_methods[$method] = isset($m_config['data']) ? $m_config['data'] : "Return data not yet provided within config.\r\n";
          }

        }
      }

      $testcase = new PhpunitTddMockerFactory(__METHOD__, [], __METHOD__);

      // __call() method can be mocked as well if you provide a magic name to
      // array of method to be mocked.
      // @see http://stackoverflow.com/a/6210784/3027445
      $mock = $testcase->getMock(
        $original_class,
        array_unique(array_merge(get_class_methods($original_class), array_keys($mock_methods), ['__mockedServiceId'])),
        [],
        '',
        FALSE,
        TRUE,
        TRUE,
        TRUE,
        FALSE,
        $service
      );

      foreach (get_class_methods($original_class) as $original_method) {
        if (!array_key_exists($original_method, $mock_methods)) {
          $mock->expects($testcase->any())
            ->method($original_method)
            ->will($testcase->returnCallback([$service, $original_method]));
        }
      }

      foreach ($mock_methods as $method => $return) {
        if (is_array($return)) {
          $mock->expects($testcase->any())
            ->method($method)
            ->will($testcase->returnValue($serializer->deserialize($return['data'], $return['type'], 'json')));
        }
        else {
          // @todo check serializer is working for huge arrays.
          // @todo replace serializer with better one to ensure objects are working.
          $mock->expects($testcase->any())
            ->method($method)
            ->will($testcase->returnValue($return));
        }
      }
      $mock->expects($testcase->any())
        ->method('__mockedServiceId')
        ->will($testcase->returnValue($service->_serviceId));
      return $mock;
    }
  }

}
