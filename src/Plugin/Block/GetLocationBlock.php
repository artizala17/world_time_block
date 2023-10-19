<?php

namespace Drupal\world_time_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\world_time_block\GetTimeService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a user current location block that display current time.
 *
 * @Block(
 *   id = "world_time_view_block",
 *   admin_label = @Translation("Location Block")
 * )
 */
class GetLocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\world_time_block\GetTimeService definition.
   *
   * @var \Drupal\world_time_block\GetTimeService
   */
  protected $getTime;

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a Drupalist object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\world_time_block\GetTimeService $get_time_service
   *   The get time service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, GetTimeService $get_time_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->getTime = $get_time_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('world_time_block.get_time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $worldtime = $this->configFactory->get('world_time_block.admin_settings');
    $city = $worldtime->get('city');
    $country = $worldtime->get('country');
    $timezone = $worldtime->get('timezone');
    // Service will return date format 19th Sep 2023 - 11:15 AM
    // $dateformat = $this->getTime->getTimezone();
    $build['#theme'] = 'get_world_time';
    $build['#country'] = $country;
    $build['#city'] = $city;
    $build['#attached']['drupalSettings']['timezone'] = $timezone;
    $build['#attached']['library'] = ['world_time_block/world_time_js_example'];
    $build['#cache'] = [
      'tags' => ['timezone_tag'],
      'max-age' => 0,
    ];
    return $build;
  }

  /**
   * Clear cache max-age.
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
