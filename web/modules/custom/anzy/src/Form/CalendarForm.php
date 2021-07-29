<?php

namespace Drupal\anzy\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Contains \Drupal\anzy\Form\CalendarForm for building form on report.
 *
 * @file
 */

/**
 * Inherit FormBase for build our form.
 */
class CalendarForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'Calendar_form';
  }

  /**
   * Get all month report fields for page.
   *
   * @return array
   *   A simple array.
   */
  public function load() {
    $connection = \Drupal::service('database');
    $query = $connection->select('anzy', 'a');
    $query->fields('a',
      [
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec',
        'reptable',
        'year',
      ]
    );
    $result = $query->execute()->fetchAll();
    $result = json_decode(json_encode($result), TRUE);
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $def = $this->load();
    $headers = [
      t('Year'),
      t('Jan'),
      t('Feb'),
      t('Mar'),
      t('Q1'),
      t('Apr'),
      t('May'),
      t('Jun'),
      t('Q2'),
      t('Jul'),
      t('Aug'),
      t('Sep'),
      t('Q3'),
      t('Oct'),
      t('Nov'),
      t('Dec'),
      t('Q4'),
      t('YTD'),
    ];
    $form['table'] = [
      '#type' => 'table',
      '#header' => $headers,
    ];
    $form['Year'] = [
      '#type' => 'number',
      '#value' => 2021,
      '#disabled' => TRUE,
    ];
    $form['Jan'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['jan'] == 0 ? '' : $def[0]['jan'],
    ];
    $form['Feb'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['feb'] == 0 ? '' : $def[0]['feb'],
    ];
    $form['Mar'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['mar'] == 0 ? '' : $def[0]['mar'],
    ];
    $form['Qfirst'] = [
      '#type' => 'number',
      '#value' => 4,
      '#disabled' => TRUE,
    ];
    $form['Apr'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['apr'] == 0 ? '' : $def[0]['apr'],
    ];
    $form['May'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['may'] == 0 ? '' : $def[0]['may'],
    ];
    $form['Jun'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['jun'] == 0 ? '' : $def[0]['jun'],
    ];
    $form['Qsecond'] = [
      '#type' => 'number',
      '#value' => 2,
      '#disabled' => TRUE,
    ];
    $form['Jul'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['jul'] == 0 ? '' : $def[0]['jul'],
    ];
    $form['Aug'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['aug'] == 0 ? '' : $def[0]['aug'],
    ];
    $form['Sep'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['sep'] == 0 ? '' : $def[0]['sep'],
    ];
    $form['Qthird'] = [
      '#type' => 'number',
      '#value' => 4,
      '#disabled' => TRUE,
    ];
    $form['Oct'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['oct'] == 0 ? '' : $def[0]['oct'],
    ];
    $form['Nov'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['nov'] == 0 ? '' : $def[0]['nov'],
    ];
    $form['Dec'] = [
      '#type' => 'number',
      '#default_value' => $def[0]['dec'] == 0 ? '' : $def[0]['dec'],
    ];
    $form['Qfourth'] = [
      '#type' => 'number',
      '#value' => 3,
      '#disabled' => TRUE,
    ];
    $form['YTD'] = [
      '#type' => 'number',
      '#value' => 8,
      '#disabled' => TRUE,
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#description' => $this->t('Submit, #type = submit'),
    ];
    $form['#attached']['library'][] = 'anzy/my-lib';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = \Drupal::service('database');
    if (empty($this->load())) {
      $result = $connection->insert('anzy')
        ->fields([
          'jan' => $form_state->getValue('Jan') != '' ? $form_state->getValue('Jan') : 0,
          'feb' => $form_state->getValue('Feb') != '' ? $form_state->getValue('Feb') : 0,
          'mar' => $form_state->getValue('Mar') != '' ? $form_state->getValue('Mar') : 0,
          'apr' => $form_state->getValue('Apr') != '' ? $form_state->getValue('Apr') : 0,
          'may' => $form_state->getValue('May') != '' ? $form_state->getValue('May') : 0,
          'jun' => $form_state->getValue('Jun') != '' ? $form_state->getValue('Jun') : 0,
          'jul' => $form_state->getValue('Jul') != '' ? $form_state->getValue('Jul') : 0,
          'aug' => $form_state->getValue('Aug') != '' ? $form_state->getValue('Aug') : 0,
          'sep' => $form_state->getValue('Sep') != '' ? $form_state->getValue('Sep') : 0,
          'oct' => $form_state->getValue('Oct') != '' ? $form_state->getValue('Oct') : 0,
          'nov' => $form_state->getValue('Nov') != '' ? $form_state->getValue('Nov') : 0,
          'dec' => $form_state->getValue('Dec') != '' ? $form_state->getValue('Dec') : 0,
          'reptable' => 0,
          'year' => 0,
        ])
        ->execute();
    }
    else {
      $result = $connection->update('anzy')
        ->condition('id', 1)
        ->fields([
          'jan' => $form_state->getValue('Jan') != '' ? $form_state->getValue('Jan') : 0,
          'feb' => $form_state->getValue('Feb') != '' ? $form_state->getValue('Feb') : 0,
          'mar' => $form_state->getValue('Mar') != '' ? $form_state->getValue('Mar') : 0,
          'apr' => $form_state->getValue('Apr') != '' ? $form_state->getValue('Apr') : 0,
          'may' => $form_state->getValue('May') != '' ? $form_state->getValue('May') : 0,
          'jun' => $form_state->getValue('Jun') != '' ? $form_state->getValue('Jun') : 0,
          'jul' => $form_state->getValue('Jul') != '' ? $form_state->getValue('Jul') : 0,
          'aug' => $form_state->getValue('Aug') != '' ? $form_state->getValue('Aug') : 0,
          'sep' => $form_state->getValue('Sep') != '' ? $form_state->getValue('Sep') : 0,
          'oct' => $form_state->getValue('Oct') != '' ? $form_state->getValue('Oct') : 0,
          'nov' => $form_state->getValue('Nov') != '' ? $form_state->getValue('Nov') : 0,
          'dec' => $form_state->getValue('Dec') != '' ? $form_state->getValue('Dec') : 0,
          'reptable' => 0,
          'year' => 0,
        ])
        ->execute();
    }
    \Drupal::messenger()->addMessage($this->t('Report updated successfully'), 'status', TRUE);
  }

}
