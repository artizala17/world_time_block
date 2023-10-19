<?php

namespace Drupal\world_time_block\Form;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure world_time_block settings to get current time as per timezone.
 */
class WorldTimeForm extends ConfigFormBase {

  /**
   * The cache tags invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * Drupal\Core\Cache\CacheBackendInterface definition.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheRender;

  /**
   * Constructs a CacheTagsInvalidatorInterface object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   The cache tags invalidator.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_render
   *   The cache render.
   */
  public function __construct(ConfigFactoryInterface $config_factory, CacheTagsInvalidatorInterface $cache_tags_invalidator, CacheBackendInterface $cache_render) {
    parent::__construct($config_factory);
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
    $this->cacheRender = $cache_render;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('cache_tags.invalidator'),
      $container->get('cache.render')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'world_time_block.admin_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'world_time_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('world_time_block.admin_settings');

    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#options' => [
        "America/Chicago" => $this->t("America/Chicago"),
        "America/New_York" => $this->t("America/New_York"),
        "Asia/Tokyo" => $this->t("Asia/Tokyo"),
        "Asia/Dubai" => $this->t("Asia/Dubai"),
        "Asia/Kolkata" => $this->t("Asia/Kolkata"),
        "Europe/Amsterdam" => $this->t("Europe/Amsterdam"),
        "Europe/Oslo" => $this->t("Europe/Oslo"),
        "Europe/London" => $this->t("Europe/London"),
      ],
      '#empty_option' => '-- Select Timezone --',
      '#empty_value' => '_none_',
      '#default_value' => $config->get('timezone'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('world_time_block.admin_settings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();

    $this->cacheTagsInvalidator->invalidateTags(['timezone_tag']);
    $this->cacheRender->invalidateAll();
  }

}
