<?php

namespace Drupal\world_time_block;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatter;

/**
 * Class GetTimeService is used to call current time.
 */
class GetTimeService {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The datetime.time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $timeService;

  /**
   * The datetime.time service.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * Constructs a new GetTimeService object.
   */
  public function __construct(ConfigFactoryInterface $config_factory, TimeInterface $time_service, DateFormatter $date_formatter) {
    $this->configFactory = $config_factory;
    $this->timeService = $time_service;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Get timezone from service.
   */
  public function getTimezone() {
    $worldtime = $this->configFactory->get('world_time_block.admin_settings');
    $timezone = $worldtime->get('timezone');
    $timestamp = $this->timeService->getCurrentTime();
    $dateformat = $this->dateFormatter->format($timestamp, 'custom', 'dS M Y - H:i A', $timezone);
    return $dateformat;
  }

}
