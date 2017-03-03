<?php

/**
 * @file
 * Drush command.
 */

/**
 * Implements hook_drush_command().
 */
function phpunit_tdd_drush_command() {
  $items = [];
  $items['tdd_run'] = array(
    'description' => 'Run tests.',
    'options' => array(),
    'arguments' => array(),
    'core' => array('8+'),
    'aliases' => array('tr'),
  );
  $items['tdd_capture_method'] = array(
    'description' => 'Set phpunit_tdd into capture mode for capturing method call for service, provided as arguments.',
    'options' => array(),
    'arguments' => array(
      'service' => dt(
        'Service name string from core.services.yml for example.'
      ),
      'method' => dt('Method name string to be captured, case sensitive.'),
    ),
    'core' => array('8+'),
    'aliases' => array('tcm'),
  );
  $items['tdd_disable_method'] = array(
    'description' => 'Disable specified method of the service from being mocked.',
    'options' => array(),
    'arguments' => array(
      'service' => dt(
        'Service name string from core.services.yml for example.'
      ),
      'method' => dt('Method name string to be disabled, case sensitive.'),
    ),
    'core' => array('8+'),
    'aliases' => array('tdm'),
  );
  $items['tdd_enable_method'] = array(
    'description' => 'Enable specified method of the service for being mocked.',
    'options' => array(),
    'arguments' => array(
      'service' => dt(
        'Service name string from core.services.yml for example.'
      ),
      'method' => dt('Method name string to be disabled, case sensitive.'),
    ),
    'core' => array('8+'),
    'aliases' => array('tem'),
  );
  $items['tdd_set_method_data'] = array(
    'description' => 'Set mocked data for specified method of service.',
    'options' => array(),
    'arguments' => array(
      'service' => dt(
        'Service name string from core.services.yml for example.'
      ),
      'method' => dt('Method name string to be disabled, case sensitive.'),
      'data' => dt('json encoded string of data to be stored.'),
    ),
    'core' => array('8+'),
    'aliases' => array('tsmd'),
  );
  return $items;
}

/**
 * Set method into capture mode.
 */
function drush_phpunit_tdd_tdd_capture_method() {
  $config = \Drupal::service('config.factory')->getEditable(
    'phpunit_tdd.config'
  );
  $decorated = $config->get('decorated');
  $args = func_get_args();
  if (count($args) == 2) {
    $source = $args[0];
    $mock_method = $args[1];
    foreach ($decorated as $key => $data) {
      if ($data['source'] == $source) {
        if (isset($data['methods'])) {
          $decorated[$key]['methods'][$mock_method] = [];
          $config->set('decorated', $decorated);
        }
        elseif (!isset($data['methods'])) {
          $decorated[$key]['methods'] = [];
          $decorated[$key]['methods'][$mock_method] = [];
          $config->set('decorated', $decorated);
        }
      }
    }
    $config->save();
  }
}

/**
 * Set method into disabled mode.
 */
function drush_phpunit_tdd_tdd_disable_method() {
  $config = \Drupal::service('config.factory')->getEditable(
    'phpunit_tdd.config'
  );
  $decorated = $config->get('decorated');
  $args = func_get_args();
  if (count($args) == 2) {
    $source = $args[0];
    $mock_method = $args[1];
    foreach ($decorated as $key => $data) {
      if ($data['source'] == $source) {
        if (isset($data['methods']) && isset($data['methods'][$mock_method])) {
          $decorated[$key]['methods'][$mock_method]['status'] = FALSE;
          $config->set('decorated', $decorated);
        }
        elseif (isset($data['methods']) && !isset($data['methods'][$mock_method])) {
          $decorated[$key]['methods'][$mock_method] = ['status' => FALSE];
          $config->set('decorated', $decorated);
        }
        elseif (!isset($data['methods'])) {
          $decorated[$key]['methods'] = [];
          $decorated[$key]['methods'][$mock_method] = ['status' => FALSE];
          $config->set('decorated', $decorated);
        }
      }
    }
    $config->save();
  }
}

/**
 * Set method into enabled mode.
 */
function drush_phpunit_tdd_tdd_enable_method() {
  $config = \Drupal::service('config.factory')->getEditable(
    'phpunit_tdd.config'
  );
  $decorated = $config->get('decorated');
  $args = func_get_args();
  if (count($args) == 2) {
    $source = $args[0];
    $mock_method = $args[1];
    foreach ($decorated as $key => $data) {
      if ($data['source'] == $source) {
        if (isset($data['methods']) && isset($data['methods'][$mock_method])) {
          $decorated[$key]['methods'][$mock_method]['status'] = TRUE;
          $config->set('decorated', $decorated);
        }
        elseif (isset($data['methods']) && !isset($data['methods'][$mock_method])) {
          $decorated[$key]['methods'][$mock_method] = ['status' => TRUE];
          $config->set('decorated', $decorated);
        }
        elseif (!isset($data['methods'])) {
          $decorated[$key]['methods'] = [];
          $decorated[$key]['methods'][$mock_method] = ['status' => TRUE];
          $config->set('decorated', $decorated);
        }
      }
    }
    $config->save();
  }
}

/**
 * Set method into enabled mode.
 */
function drush_phpunit_tdd_tdd_set_method_data() {
  $config = \Drupal::service('config.factory')->getEditable(
    'phpunit_tdd.config'
  );
  $decorated = $config->get('decorated');
  $args = func_get_args();
  if (count($args) == 3) {
    $source = $args[0];
    $mock_method = $args[1];
    $mock_data = json_decode($args[2]);
    foreach ($decorated as $key => $data) {
      if ($data['source'] == $source) {
        if (isset($data['methods']) && isset($data['methods'][$mock_method])) {
          $decorated[$key]['methods'][$mock_method]['data'] = $mock_data;
          $config->set('decorated', $decorated);
        }
        elseif (isset($data['methods']) && !isset($data['methods'][$mock_method])) {
          $decorated[$key]['methods'][$mock_method] = ['status' => TRUE, 'data' => $mock_data];
          $config->set('decorated', $decorated);
        }
        elseif (!isset($data['methods'])) {
          $decorated[$key]['methods'] = [];
          $decorated[$key]['methods'][$mock_method] = ['status' => TRUE, 'data' => $mock_data];
          $config->set('decorated', $decorated);
        }
      }
    }
    $config->save();
  }
}

/**
 * Rebuild a Drupal 8 site in a quick way.
 */
function drush_phpunit_tdd_tdd_run() {
  $name = 'Drupal\\Tests\\ygh_programs_search\\Unit\\StorageTest';
  $path = DRUPAL_ROOT . '/modules/custom/ygh_programs_search/tests/src/Unit/StorageTest.php';
  \Drupal::service('phpunit_tdd.runner')->run($name, $path);
}
