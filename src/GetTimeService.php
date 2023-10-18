<?php

namespace Drupal\world_time_block;

use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Class GetTimeService.
 *
 */
class GetTimeService {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   * 
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new GetTimeService object.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   *
   */
  public function getCurrentTime() {
    $worldtime = $this->configFactory->get('world_time_block.admin_settings');
    $selectedTimezone = $worldtime->get('timezone');
    $date = new DrupalDateTime();
    $date->setTimezone(new \DateTimeZone($selectedTimezone));
    return $date->format('dS M Y - H:i A');
  }

}
