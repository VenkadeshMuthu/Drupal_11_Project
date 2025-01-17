<?php

namespace Drupal\statistics;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure statistics settings for this site.
 *
 * @internal
 *
 * @phpstan-consistent-constructor
 */
class StatisticsSettingsForm extends ConfigFormBase {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected ModuleHandlerInterface $moduleHandler;

  /**
   * The optional block plugin manager.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface|null
   */
  protected ?PluginManagerInterface $blockPluginManager;

  /**
   * Constructs a \Drupal\statistics\StatisticsSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Config\TypedConfigManagerInterface $typedConfigManager
   *   The typed config manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Component\Plugin\PluginManagerInterface|null $blockPluginManager
   *   The block plugin manager, if block module is enabled.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typedConfigManager,
    ModuleHandlerInterface $module_handler,
    ?PluginManagerInterface $blockPluginManager,
  ) {
    parent::__construct($config_factory, $typedConfigManager);

    $this->moduleHandler = $module_handler;
    $this->blockPluginManager = $blockPluginManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed'),
      $container->get('module_handler'),
      $container->get('plugin.manager.block', ContainerInterface::NULL_ON_INVALID_REFERENCE)
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'statistics_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('statistics.settings');

    // Content counter settings.
    $form['content'] = [
      '#type' => 'details',
      '#title' => $this->t('Content viewing counter settings'),
      '#open' => TRUE,
    ];
    $form['content']['statistics_count_content_views'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Count content views'),
      '#default_value' => $config->get('count_content_views'),
      '#description' => $this->t('Increment a counter each time content is viewed.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('statistics.settings')
      ->set('count_content_views', $form_state->getValue('statistics_count_content_views'))
      ->save();

    // The popular statistics block is dependent on these settings, so clear the
    // block plugin definitions cache.
    if (method_exists($this->blockPluginManager, "clearCachedDefinitions")) {
      $this->blockPluginManager->clearCachedDefinitions();
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['statistics.settings'];
  }

}
