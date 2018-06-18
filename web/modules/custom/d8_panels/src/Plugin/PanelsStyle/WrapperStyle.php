<?php

namespace Drupal\d8_panels\Plugin\PanelsStyle;

use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\panels\Plugin\DisplayVariant\PanelsDisplayVariant;
use Drupal\panels\Plugin\PanelsStyle\PanelsStyleBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the wrapper panels style plugin.
 *
 * @PanelsStyle(
 *   id = "wrapper_style",
 *   title = @Translation("Wrapper"),
 *   description = @Translation("Wrap regions and panes with HTML elements.")
 * )
 */
class WrapperStyle extends PanelsStyleBase implements ContainerFactoryPluginInterface {

  use DependencySerializationTrait;

  /**
   * Request.
   *
   * @var CurrentRouteMatch
   */
  private $routeMatch;

  /**
   * Renderer.
   *
   * @var Renderer
   */
  private $renderer;

  /**
   * RedpillElementStyle constructor.
   *
   * @param array $configuration
   *   Config.
   * @param string $plugin_id
   *   Plugin ID.
   * @param mixed $plugin_definition
   *   Definition of plugin.
   * @param CurrentRouteMatch $routeMatch
   *   Current route.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Renderer.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ModuleHandlerInterface $moduleHandler,  CurrentRouteMatch $routeMatch, Renderer $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $moduleHandler);
    $this->routeMatch = $routeMatch;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'region' => [
        'content' => [
          'element' => '',
          'id' => '',
          'class' => '',
        ],
      ],
      'pane' => [
        'title' => [
          'element' => '',
          'id' => '',
          'class' => '',
        ],
        'content' => [
          'element' => '',
          'id' => '',
          'class' => '',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildRegion(PanelsDisplayVariant $display, array $build, $region, array $blocks) {
    $config = $this->getConfiguration();
    $config_element = $config['region']['element'];
    $config_element_id = $config['region']['id'];
    $config_element_class = $config['region']['class'];

    if (!empty($config_element)) {
      $content = $this->renderer->render($build);
      $build['#theme'] = 'wrapper';
      $build['#tag'] = $config_element;
      $build['#content'] = $content;

      if ($config_element_id || $config_element_class) {
        $attributes = [];
        $attributes['id'] = $config_element_id ?: NULL;
        $attributes['class'] = $config_element_class ?: NULL;
        $build['#attributes'] = new Attribute($attributes);
      }
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  public function buildBlock(PanelsDisplayVariant $display, BlockPluginInterface $block) {
    $config = $block->getConfiguration();

    $title_element = $config['style']['configuration']['pane']['title']['element'];
    $title_element_id = $config['style']['configuration']['pane']['title']['id'];
    $title_element_class = $config['style']['configuration']['pane']['title']['class'];

    $content_element = $config['style']['configuration']['pane']['content']['element'];
    $content_element_id = $config['style']['configuration']['pane']['content']['id'];
    $content_element_class = $config['style']['configuration']['pane']['content']['class'];

    $render_array = $block->build() ?: [];
    $content = $this->renderer->render($render_array);

    if (!empty($content_element)) {
      $build['content'] = [
        '#theme' => 'wrapper',
        '#content' => $content,
        '#tag' => $content_element,
      ];
      if ($content_element_id || $content_element_class) {
        $attributes = [];
        $attributes['id'] = $content_element_id ?: NULL;
        $attributes['class'] = $content_element_class ?: NULL;
        $build['content']['#attributes'] = new Attribute($attributes);
      }
    }
    elseif ($content_element == 0) {
      $build['content'] = [
        '#markup' => $content,
      ];
    }

    if ($config['label_display'] === 'visible') {
      $content = $config['label'];
      $build['title'] = [
        '#theme' => 'wrapper',
        '#content' => $content,
        '#tag' => $title_element,
        '#weight' => -1,
      ];
      if ($title_element_id || $title_element_class) {
        $attributes = [];
        $attributes['id'] = $title_element_id ?: NULL;
        $attributes['class'] = $title_element_class ?: NULL;
        $build['title']['#attributes'] = new Attribute($attributes);
      }
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    $route_name = $this->routeMatch->getRouteName();
    if ($route_name === NULL) {
      $form_state->setRebuild();
    }
    if ($route_name === 'panels.region_edit_style') {
      $form['region']['element'] = [
        '#type' => 'textfield',
        '#title' => t('HTML Tag of the region wrapper element'),
        '#description' => t('You can define a custom html tag of the wrapping element for the region. If left blank there will be none.'),
        '#default_value' => $form_state->getValue('element') ?: $config['region']['element'],
      ];
      $form['region']['id'] = [
        '#type' => 'textfield',
        '#title' => t('id'),
        '#description' => t('CSS id to apply to the element, without the hash.'),
        '#default_value' => $form_state->getValue('id') ?: $config['region']['id'],
      ];
      $form['region']['class'] = [
        '#type' => 'textfield',
        '#title' => t('class'),
        '#description' => t('CSS classes to apply to the element, separated by spaces.'),
        '#default_value' => $form_state->getValue('class') ?: $config['region']['class'],
      ];
    }
    else {
      $form['pane']['title']['element'] = [
        '#type' => 'textfield',
        '#title' => t('HTML Tag of the title wrapper element'),
        '#description' => t('You can define a custom html tag of the wrapping element for the title. If left blank there will be none.'),
        '#default_value' => $form_state->getValue('element') ?: $config['pane']['title']['element'],
      ];
      $form['pane']['title']['id'] = [
        '#type' => 'textfield',
        '#title' => t('id'),
        '#description' => t('CSS id to apply to the element, without the hash.'),
        '#default_value' => $form_state->getValue('id') ?: $config['pane']['title']['id'],
      ];
      $form['pane']['title']['class'] = [
        '#type' => 'textfield',
        '#title' => t('class'),
        '#description' => t('CSS classes to apply to the element, separated by spaces.'),
        '#default_value' => $form_state->getValue('class') ?: $config['pane']['title']['class'],
      ];
      $form['pane']['content']['element'] = [
        '#type' => 'textfield',
        '#title' => t('HTML Tag of the content wrapper element'),
        '#description' => t('You can define a custom html tag of the wrapping element for the content. If left blank there will be none.'),
        '#default_value' => $form_state->getValue('element') ?: $config['pane']['content']['element'],
      ];
      $form['pane']['content']['id'] = [
        '#type' => 'textfield',
        '#title' => t('id'),
        '#description' => t('CSS id to apply to the element, without the hash.'),
        '#default_value' => $form_state->getValue('id') ?: $config['pane']['content']['id'],
      ];
      $form['pane']['content']['class'] = [
        '#type' => 'textfield',
        '#title' => t('class'),
        '#description' => t('CSS classes to apply to the element, separated by spaces.'),
        '#default_value' => $form_state->getValue('class') ?: $config['pane']['content']['class'],
      ];
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   *
   * @throws \LogicException
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration = $form_state->getValues();
    $form_state->setCached(FALSE);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('module_handler'), $container->get('current_route_match'), $container->get('renderer'));
  }

}
