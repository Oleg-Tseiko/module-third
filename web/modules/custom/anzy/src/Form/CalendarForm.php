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
    if (empty($def)) {
      $def[0]['jan'] = 0;
      $def[0]['feb'] = 0;
      $def[0]['mar'] = 0;
      $def[0]['apr'] = 0;
      $def[0]['may'] = 0;
      $def[0]['jun'] = 0;
      $def[0]['jul'] = 0;
      $def[0]['aug'] = 0;
      $def[0]['sep'] = 0;
      $def[0]['oct'] = 0;
      $def[0]['nov'] = 0;
      $def[0]['dec'] = 0;
    }
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
    $monthsVal = [
      'Jan' => $form_state->getValue('Jan'),
      'Feb' => $form_state->getValue('Feb'),
      'Mar' => $form_state->getValue('Mar'),
      'Apr' => $form_state->getValue('Apr'),
      'May' => $form_state->getValue('May'),
      'Jun' => $form_state->getValue('Jun'),
      'Jul' => $form_state->getValue('Jul'),
      'Aug' => $form_state->getValue('Aug'),
      'Sep' => $form_state->getValue('Sep'),
      'Oct' => $form_state->getValue('Oct'),
      'Nov' => $form_state->getValue('Nov'),
      'Dec' => $form_state->getValue('Dec'),
    ];
    $count = 0;
    $values = 0;
    $theLastOne = 0;
    $offset = 0;
    $fieldsName = [];
    foreach ($monthsVal as $month => $number) {
      if ($number != "") {
        $count++;
        $values++;
        if ($theLastOne != 0) {
          $count--;
          $theLastOne = 0;
        }
      }
      elseif ($count != 0) {
        $fieldsName[$offset] = $month;
        $theLastOne++;
        $offset++;
      }
    }
    $fieldsName = array_slice($fieldsName, 0, $offset - $theLastOne, TRUE);
    if ($count != $values) {
      foreach ($fieldsName as $name) {
        $form_state->setErrorByName($name, t('There is a space in report, you need to fix') . " " . $name);
      }
    }
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
          'jan' => $form_state->getValue('Jan') != '' ? $form_state->getValue('Jan') : NULL,
          'feb' => $form_state->getValue('Feb') != '' ? $form_state->getValue('Feb') : NULL,
          'mar' => $form_state->getValue('Mar') != '' ? $form_state->getValue('Mar') : NULL,
          'apr' => $form_state->getValue('Apr') != '' ? $form_state->getValue('Apr') : NULL,
          'may' => $form_state->getValue('May') != '' ? $form_state->getValue('May') : NULL,
          'jun' => $form_state->getValue('Jun') != '' ? $form_state->getValue('Jun') : NULL,
          'jul' => $form_state->getValue('Jul') != '' ? $form_state->getValue('Jul') : NULL,
          'aug' => $form_state->getValue('Aug') != '' ? $form_state->getValue('Aug') : NULL,
          'sep' => $form_state->getValue('Sep') != '' ? $form_state->getValue('Sep') : NULL,
          'oct' => $form_state->getValue('Oct') != '' ? $form_state->getValue('Oct') : NULL,
          'nov' => $form_state->getValue('Nov') != '' ? $form_state->getValue('Nov') : NULL,
          'dec' => $form_state->getValue('Dec') != '' ? $form_state->getValue('Dec') : NULL,
          'reptable' => 0,
          'year' => 0,
        ])
        ->execute();
    }
    else {
      $result = $connection->update('anzy')
        ->condition('id', 1)
        ->fields([
          'jan' => $form_state->getValue('Jan') != '' ? $form_state->getValue('Jan') : NULL,
          'feb' => $form_state->getValue('Feb') != '' ? $form_state->getValue('Feb') : NULL,
          'mar' => $form_state->getValue('Mar') != '' ? $form_state->getValue('Mar') : NULL,
          'apr' => $form_state->getValue('Apr') != '' ? $form_state->getValue('Apr') : NULL,
          'may' => $form_state->getValue('May') != '' ? $form_state->getValue('May') : NULL,
          'jun' => $form_state->getValue('Jun') != '' ? $form_state->getValue('Jun') : NULL,
          'jul' => $form_state->getValue('Jul') != '' ? $form_state->getValue('Jul') : NULL,
          'aug' => $form_state->getValue('Aug') != '' ? $form_state->getValue('Aug') : NULL,
          'sep' => $form_state->getValue('Sep') != '' ? $form_state->getValue('Sep') : NULL,
          'oct' => $form_state->getValue('Oct') != '' ? $form_state->getValue('Oct') : NULL,
          'nov' => $form_state->getValue('Nov') != '' ? $form_state->getValue('Nov') : NULL,
          'dec' => $form_state->getValue('Dec') != '' ? $form_state->getValue('Dec') : NULL,
          'reptable' => 0,
          'year' => 0,
        ])
        ->execute();
    }
    \Drupal::messenger()->addMessage($this->t('Report updated successfully'), 'status', TRUE);
  }

}
