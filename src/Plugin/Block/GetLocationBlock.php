<?php

namespace Drupal\world_time_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\world_time_block\GetTimeService;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides a user current location block that display user location with current time.
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
  protected $gettime;

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * 
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->gettime = $container->get('world_time_block.get_time');
    return $instance;
  }

  /**
   *
   */
  public function build() {  
    $build['#theme'] = 'get_world_time';
    $build['#country'] = $this->gettime->getCountry();
    $build['#city'] = $this->gettime->getCity();
    $build['#time'] = $this->gettime->getDatetimeFormat()['time'];
    $build['#date'] = $this->gettime->getDatetimeFormat()['date'];
    $build['#timezone'] = $this->gettime->getTimezone();
    $build['#current_time'] = $this->gettime->getDatetimeFormat()['dateformat'];
    $build['#cache'] = [
      'max-age' => 0
    ];
    return $build;
  }

  /**
   *
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
