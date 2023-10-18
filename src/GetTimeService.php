<?php

namespace Drupal\world_time_block;

use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatter;

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

  public function getTimezone() {
    $worldtime = $this->configFactory->get('world_time_block.admin_settings');
    return $worldtime->get('timezone');
  }

  public function getCity() {
    $worldtime = $this->configFactory->get('world_time_block.admin_settings');
    return $worldtime->get('city');
  }

  public function getCountry() {
    $worldtime = $this->configFactory->get('world_time_block.admin_settings');
    return $worldtime->get('country');
  }

  /**
   * Get current timestamp.
   */
  public function getCurrentTimestamp() {
    $timestamp = $this->timeService->getCurrentTime();
    return $timestamp;
  }

  /**
   * Datetime format as per timezone.
   */
  public function getDatetimeFormat() {
    $selectedTimezone = $this->getTimezone();
    $timestamp = $this->getCurrentTimestamp();
    $dateformat = $this->dateFormatter->format($timestamp, 'custom', 'dS M Y - H:i A', $this->getTimezone());
    $time = $this->dateFormatter->format($timestamp, 'custom', 'H:i a', $this->getTimezone());
    $date = $this->dateFormatter->format($timestamp, 'custom', 'l, d F Y', $this->getTimezone());
    return ['dateformat' => $dateformat, 'time' => $time, 'date' => $date];
  }

}
