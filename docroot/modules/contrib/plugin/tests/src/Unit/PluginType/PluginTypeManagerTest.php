<?php

namespace Drupal\Tests\plugin\Unit\PluginType;

use Drupal\Component\FileCache\FileCacheFactory;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\DependencyInjection\ClassResolverInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\plugin\PluginType\PluginTypeInterface;
use Drupal\plugin\PluginType\PluginTypeManager;
use Drupal\Tests\UnitTestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @coversDefaultClass \Drupal\plugin\PluginType\PluginTypeManager
 *
 * @group Plugin
 */
class PluginTypeManagerTest extends UnitTestCase {

  /**
   * The service container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $container;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $moduleHandler;

  /**
   * The plugin type's plugin managers.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface[]
   *   Keys are plugin type IDs.
   */
  protected $pluginManagers = [];

  /**
   * The plugin type definitions.
   *
   * @var array[]
   */
  protected $pluginTypeDefinitions = [];

  /**
   * The class under test.
   *
   * @var \Drupal\plugin\PluginType\PluginTypeManager
   */
  protected $sut;

  /**
   * Builds a plugin type definition file.
   *
   * @param string $id
   *
   * @return string
   */
  protected function buildPluginDefinitionYaml($id, $label, $description, $provider, $plugin_manager_service_id) {
    return <<<EOT
$id:
  label: "$label"
  description: "$description"
  provider: $provider
  plugin_manager_service_id: $plugin_manager_service_id
EOT;

  }

  public function setUp() {
    FileCacheFactory::setPrefix($this->randomMachineName());

    $plugin_type_id_a = $this->randomMachineName();
    $this->pluginTypeDefinitions[$plugin_type_id_a] = [
      'label' => $this->randomMachineName(),
      'description' => $this->randomMachineName(),
      'provider' => $this->randomMachineName(),
      'plugin_manager_service_id' => $this->randomMachineName(),
    ];
    $plugin_type_id_b = $this->randomMachineName();
    $this->pluginTypeDefinitions[$plugin_type_id_b] = [
      'label' => $this->randomMachineName(),
      'description' => $this->randomMachineName(),
      'provider' => $this->randomMachineName(),
      'plugin_manager_service_id' => $this->randomMachineName(),
    ];

    $this->pluginManagers = [
      $plugin_type_id_a => $this->getMock(PluginManagerInterface::class),
      $plugin_type_id_b => $this->getMock(PluginManagerInterface::class),
    ];

    vfsStreamWrapper::register();
    $root = new vfsStreamDirectory('modules');
    vfsStreamWrapper::setRoot($root);

    $this->moduleHandler = $this->getMock(ModuleHandlerInterface::class);
    $this->moduleHandler->expects($this->any())
      ->method('getModuleDirectories')
      ->willReturn(array(
        'module_a' => vfsStream::url('modules/module_a'),
        'module_b' => vfsStream::url('modules/module_b'),
      ));

    $class_resolver = $this->getMock(ClassResolverInterface::class);

    $this->container = $this->getMock(ContainerInterface::class);
    $map = [
      ['class_resolver', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $class_resolver],
      ['string_translation', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->getStringTranslationStub()],
      [$this->pluginTypeDefinitions[$plugin_type_id_a]['plugin_manager_service_id'], ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->pluginManagers[$plugin_type_id_a]],
      [$this->pluginTypeDefinitions[$plugin_type_id_b]['plugin_manager_service_id'], ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->pluginManagers[$plugin_type_id_b]],
    ];
    $this->container->expects($this->any())
      ->method('get')
      ->willReturnMap($map);

    $url = vfsStream::url('modules');
    mkdir($url . '/module_a');
    file_put_contents($url . '/module_a/module_a.plugin_type.yml', $this->buildPluginDefinitionYaml($plugin_type_id_a, $this->pluginTypeDefinitions[$plugin_type_id_a]['label'], $this->pluginTypeDefinitions[$plugin_type_id_a]['description'], $this->pluginTypeDefinitions[$plugin_type_id_a]['provider'], $this->pluginTypeDefinitions[$plugin_type_id_a]['plugin_manager_service_id']));
    mkdir($url . '/module_b');
    file_put_contents($url . '/module_b/module_b.plugin_type.yml', $this->buildPluginDefinitionYaml($plugin_type_id_b, $this->pluginTypeDefinitions[$plugin_type_id_b]['label'], $this->pluginTypeDefinitions[$plugin_type_id_b]['description'], $this->pluginTypeDefinitions[$plugin_type_id_b]['provider'], $this->pluginTypeDefinitions[$plugin_type_id_b]['plugin_manager_service_id']));

    $this->sut = new PluginTypeManager($this->container, $this->moduleHandler);
  }

  /**
   * @covers ::__construct
   */
  public function testConstruct() {
    $this->sut = new PluginTypeManager($this->container, $this->moduleHandler);
  }

  /**
   * @covers ::hasPluginType
   *
   * @dataProvider providerHasPluginType
   */
  public function testHasPluginType($expected, $plugin_type_id, $module_exists) {
    $this->moduleHandler->expects($this->atLeastOnce())
      ->method('moduleExists')
      ->willReturn(isset($this->pluginTypeDefinitions[$plugin_type_id]) && $module_exists);

    $this->assertSame($expected, $this->sut->hasPluginType($plugin_type_id));
  }

  /**
   * Provides data to self::testHasPluginType().
   */
  public function providerHasPluginType () {
    $data = [];

    foreach ($this->pluginTypeDefinitions as $plugin_type_definition) {
      $data[] = [TRUE, $plugin_type_definition['id'], TRUE];
      $data[] = [FALSE, $plugin_type_definition['id'], FALSE];
    }
    $data[] = [FALSE, $this->randomMachineName(), TRUE];
    $data[] = [FALSE, $this->randomMachineName(), FALSE];

    return $data;
  }

  /**
   * @covers ::getPluginType
   *
   * @dataProvider providerGetPluginType
   */
  public function testGetPluginType($expected_success, $plugin_type_id, $module_exists) {
    $this->moduleHandler->expects($this->atLeastOnce())
      ->method('moduleExists')
      ->willReturn(isset($this->pluginTypeDefinitions[$plugin_type_id]) && $module_exists);

    if ($expected_success) {
      $this->assertInstanceOf(PluginTypeInterface::class, $this->sut->getPluginType($plugin_type_id));
    }
    else {
      $this->setExpectedException('\InvalidArgumentException');
      $this->sut->getPluginType($plugin_type_id);
    }
  }

  /**
   * Provides data to self::testGetPluginType().
   */
  public function providerGetPluginType () {
    $data = [];

    foreach ($this->pluginTypeDefinitions as $plugin_type_definition) {
      $data[] = [TRUE, $plugin_type_definition['id'], TRUE];
      $data[] = [FALSE, $plugin_type_definition['id'], FALSE];
    }
    $data[] = [FALSE, $this->randomMachineName(), TRUE];
    $data[] = [FALSE, $this->randomMachineName(), FALSE];

    return $data;
  }

  /**
   * @covers ::getPluginType
   *
   * @expectedException \InvalidArgumentException
   */
  public function testGetPluginTypeWithInvalidPluginTypeId() {
    $this->sut->getPluginType($this->randomMachineName());
  }

  /**
   * @covers ::getPluginTypes
   */
  public function testGetPluginTypes() {
    foreach ($this->sut->getPluginTypes() as $plugin_type) {
      $this->assertPluginTypeIntegrity($plugin_type->getId(), $this->pluginTypeDefinitions[$plugin_type->getId()], $this->pluginManagers[$plugin_type->getId()], $plugin_type);
    }
  }

  /**
   * Asserts the integrity of a plugin type based on its definition.
   *
   * @param string $plugin_type_id
   * @param mixed[] $plugin_type_definition
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   * @param mixed $plugin_type
   */
  protected function assertPluginTypeIntegrity($plugin_type_id, $plugin_type_definition, PluginManagerInterface $plugin_manager, $plugin_type) {
    $this->assertInstanceOf(PluginTypeInterface::class, $plugin_type);
    $this->assertSame($plugin_type_id, $plugin_type->getId());
    $this->assertSame($plugin_type_definition['label'], $plugin_type->getLabel()->getUntranslatedString());
    $this->assertSame($plugin_type_definition['description'], $plugin_type->getDescription()->getUntranslatedString());
    $this->assertSame($plugin_type_definition['provider'], $plugin_type->getProvider());
    $this->assertSame($plugin_manager, $plugin_type->getPluginManager());
  }

}
