<?php

namespace Drupal\social_file_private;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\StreamWrapper\PrivateStream;

/**
 * Class SocialFilePrivateTextEditorConfigOverride.
 *
 * Override the text editor configuration and set private scheme for files.
 *
 * @package Drupal\social_file_private
 */
class SocialFilePrivateTextEditorConfigOverride implements ConfigFactoryOverrideInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs the configuration override.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Drupal configuration factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Get all the editors/input formats we need to protect.
   *
   * @todo Retrieve the input formats programmatically.
   *
   * Note: this list is now fixed, but an error will be shown in the status
   * report when there are text editors using public scheme.
   *
   * @return array
   *   Returns an array containing config_names.
   */
  public function getTextEditorsToProtect() {
    $config_names = [
      'editor.editor.basic_html',
      'editor.editor.full_html',
    ];
    return $config_names;
  }

  /**
   * {@inheritdoc}
   */
  public function loadOverrides($names) {
    $overrides = [];
    if (PrivateStream::basePath()) {
      $config_names = $this->getTextEditorsToProtect();
      foreach ($config_names as $config_name) {
        if (in_array($config_name, $names)) {
          $scheme = $this->configFactory
            ->getEditable($config_name)
            ->get('image_upload.scheme');
          if ($scheme == 'public') {
            $overrides[$config_name]['image_upload']['scheme'] = 'private';
          }
        }
      }
    }
    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'SocialFilePrivateTextEditorConfigOverride';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

}
