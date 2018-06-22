<?php

namespace Drupal\d8_panels\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Demo condition' condition.
 *
 * @Condition(
 *   id = "demo_condition",
 *   label = @Translation("Demo condition"),
 *   context = {
 *     "user" = @ContextDefinition("entity:user", label = @Translation("User"))
 *   }
 * )
 */
class DemoCondition extends ConditionPluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['username_string'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username contains string'),
      '#description' => $this->t('Enter a string that should be contained in the username of the active user.'),
      '#default_value' => $this->configuration['username_string'],
    ];
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'username_string' => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['username_string'] = $form_state->getValue('username_string');
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * Provides a human readable summary of the condition's configuration.
   */
  public function summary() {
    // Entity Type.
    $username_string = $this->configuration['username_string'];
    $message = t('Will not be shown, if username contains !username_string', ['!username_string' => $username_string]);

    return $message;
  }

  /**
   * Evaluates the condition and returns TRUE or FALSE accordingly.
   *
   * @return bool
   *   TRUE if the condition has been met, FALSE otherwise.
   */
  public function evaluate() {
    if (empty($this->configuration['username_string']) && !$this->isNegated()) {
      return TRUE;
    }

    $user = $this->getContextValue('user');

    return strpos($user->getUsername(), $this->configuration['username_string']) !== FALSE;
  }

}
