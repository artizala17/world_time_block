<?php

namespace Drupal\world_time_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class WorldTimeForm.
 */
class WorldTimeForm extends ConfigFormBase {

  /**
   * The cache tags invalidator
   * 
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * Constructs a CacheTagsInvalidatorInterface object
   * 
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   * The cache tags invalidator
   */
  public function _construct(ConfigFactoryInterface $config_factory, CacheTagsInvalidatorInterface $cache_tags_invalidator) {
    parent::__construct($config_factory);
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('cache_tags.invalidator')
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
      '#options' => ["America/Chicago" => $this->t("America/Chicago"), "America/New_York" => $this->t("America/New_York"), "Asia/Tokyo" => $this->t("Asia/Tokyo"), "Asia/Dubai" => $this->t("Asia/Dubai"), "Asia/Kolkata" => $this->t("Asia/Kolkata"), "Europe/Amsterdam" => $this->t("Europe/Amsterdam"), "Europe/Oslo" => $this->t("Europe/Oslo"), "Europe/London" => $this->t("Europe/London")],
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
    $this->cacheTagsInvalidator->invalidateTags(['time_zone_tag']);
    \Drupal::service('cache.render')->invalidateAll();
  }

}
