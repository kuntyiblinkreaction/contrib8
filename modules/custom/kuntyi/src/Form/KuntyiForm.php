<?php

/**
 * @file
 * Contains \Drupal\demo\Form\DemoForm.
 */

namespace Drupal\kuntyi\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class KuntyiForm extends ConfigFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'demo_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $form['dependee'] = array(
      '#type' => 'select',
      '#options' => array(
        'a' => 'Option A',
        'b' => 'Option B',
        'c' => 'Option C',
      ),
      '#multiple' => TRUE,
    );

    $form['dependent'] = array(
      '#type' => 'textfield',
      '#states' => array(
        'visible' => array(
          'select[name="dependee[]"]' => array('value' => array('a')),
        ),
      ),
    );
    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Your .com email address.'),
      '#default_value' => $config->get('demo.email_address')
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    if (strpos($form_state->getValue('email'), '.com') === FALSE ) {
      $form_state->setErrorByName('email', $this->t('This is not a .com email address.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->config('demo.settings');
    $config->set('demo.email_address', $form_state->getValue('email'));
    $config->save();

    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'demo.settings',
    ];
  }
}
