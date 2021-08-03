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
    $form['system_messages'] = [
      '#markup' => '<div id="form-system-messages"></div>',
      '#weight' => -100,
    ];
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
    $form['wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'data-wrapper'],
    ];
    $form['wrapper']['table'] = [
      '#type' => 'table',
      '#header' => $headers,
    ];
    for ($i = count($def) - 1; $i != -1; $i--) {
      $form['wrapper'][$i]['Year'] = [
        '#type' => 'number',
        '#value' => $i == 0 ? date('Y', time()) : $def[$i]['year'],
        '#disabled' => TRUE,
        '#attributes' => ['class' => ['firstField']],
      ];
      $form['wrapper'][$i]['Jan'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['jan'] == 0 ? '' : $def[$i]['jan'],
        '#size' => 9999,
      ];
      $form['wrapper'][$i]['Feb'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['feb'] == 0 ? '' : $def[$i]['feb'],
      ];
      $form['wrapper'][$i]['Mar'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['mar'] == 0 ? '' : $def[$i]['mar'],
      ];
      $fQuarter = [
        $def[$i]['jan'] == NULL ? 0 : round($def[$i]['jan'], 2),
        $def[$i]['feb'] == NULL ? 0 : round($def[$i]['feb'], 2),
        $def[$i]['mar'] == NULL ? 0 : round($def[$i]['mar'], 2),
      ];
      $form['wrapper'][$i]['Qfirst'] = [
        '#type' => 'textfield',
        '#value' => $fQuarter[0] == 0 && $fQuarter[1] == 0 && $fQuarter[2] == 0 ? "" : round((($fQuarter[0] + $fQuarter[1] + $fQuarter[2]) + 1) / 3, 2),
        '#disabled' => TRUE,
      ];
      $form['wrapper'][$i]['Apr'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['apr'] == 0 ? '' : $def[$i]['apr'],
      ];
      $form['wrapper'][$i]['May'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['may'] == 0 ? '' : $def[$i]['may'],
      ];
      $form['wrapper'][$i]['Jun'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['jun'] == 0 ? '' : $def[$i]['jun'],
      ];
      $sQuarter = [
        $def[$i]['apr'] == NULL ? 0 : round($def[$i]['apr'], 2),
        $def[$i]['jun'] == NULL ? 0 : round($def[$i]['jun'], 2),
        $def[$i]['may'] == NULL ? 0 : round($def[$i]['may'], 2),
      ];
      $form['wrapper'][$i]['Qsecond'] = [
        '#type' => 'textfield',
        '#value' => $sQuarter[0] == 0 && $sQuarter[1] == 0 && $sQuarter[2] == 0 ? "" : round((($sQuarter[0] + $sQuarter[1] + $sQuarter[2]) + 1) / 3, 2),
        '#disabled' => TRUE,
      ];
      $form['wrapper'][$i]['Jul'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['jul'] == 0 ? '' : $def[$i]['jul'],
      ];
      $form['wrapper'][$i]['Aug'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['aug'] == 0 ? '' : $def[$i]['aug'],
      ];
      $form['wrapper'][$i]['Sep'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['sep'] == 0 ? '' : $def[$i]['sep'],
      ];
      $tQuarter = [
        $def[$i]['jul'] == NULL ? 0 : round($def[$i]['jul'], 2),
        $def[$i]['aug'] == NULL ? 0 : round($def[$i]['aug'], 2),
        $def[$i]['sep'] == NULL ? 0 : round($def[$i]['sep'], 2),
      ];
      $form['wrapper'][$i]['Qthird'] = [
        '#type' => 'textfield',
        '#value' => $tQuarter[0] == 0 && $tQuarter[1] == 0 && $tQuarter[2] == 0 ? "" : round((($tQuarter[0] + $tQuarter[1] + $tQuarter[2]) + 1) / 3, 2),
        '#disabled' => TRUE,
      ];
      $form['wrapper'][$i]['Oct'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['oct'] == 0 ? '' : $def[$i]['oct'],
      ];
      $form['wrapper'][$i]['Nov'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['nov'] == 0 ? '' : $def[$i]['nov'],
      ];
      $form['wrapper'][$i]['Dec'] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['dec'] == 0 ? '' : $def[$i]['dec'],
      ];
      $fQuarter = [
        $def[$i]['oct'] == NULL ? 0 : round($def[$i]['oct'], 2),
        $def[$i]['nov'] == NULL ? 0 : round($def[$i]['nov'], 2),
        $def[$i]['dec'] == NULL ? 0 : round($def[$i]['dec'], 2),
      ];
      $form['wrapper'][$i]['Qfourth'] = [
        '#type' => 'textfield',
        '#value' => $fQuarter[0] == 0 && $fQuarter[1] == 0 && $fQuarter[2] == 0 ? "" : round((($fQuarter[0] + $fQuarter[1] + $fQuarter[2]) + 1) / 3, 2),
        '#disabled' => TRUE,
      ];
      $yearly = [
        $form['wrapper'][$i]["Qfirst"]['#value'] == "" ? 0 : round($form['wrapper'][$i]["Qfirst"]['#value'], 2),
        $form['wrapper'][$i]["Qsecond"]['#value'] == "" ? 0 : round($form['wrapper'][$i]["Qsecond"]['#value'], 2),
        $form['wrapper'][$i]["Qthird"]['#value'] == "" ? 0 : round($form['wrapper'][$i]["Qthird"]['#value'], 2),
        $form['wrapper'][$i]["Qfourth"]['#value'] == "" ? 0 : round($form['wrapper'][$i]["Qfourth"]['#value'], 2),
      ];
      $form['wrapper'][$i]['YTD'] = [
        '#type' => 'textfield',
        '#value' => $form['wrapper'][$i]['Qfirst']['#value'] == 0 && $form['wrapper'][$i]['Qsecond']['#value'] == 0 && $form['wrapper'][$i]['Qthird']['#value'] == 0 && $form['wrapper'][$i]['Qfourth']['#value'] == 0 ? "" : round((($yearly[0] + $yearly[1] + $yearly[2] + $yearly[3]) + 1) / 4, 2),
        '#disabled' => TRUE,
      ];
    }
    $form['wrapper']['add'] = [
      '#type' => 'submit',
      '#value' => 'Add year',
      '#ajax' => [
        'callback' => '::addYear',
        'wrapper'    => 'data-wrapper',
      ],
    ];
    $form['wrapper']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#description' => $this->t('Submit, #type = submit'),
    ];
    $form['#attached']['library'][] = 'anzy/my-lib';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
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
   * Adding new year table to the report.
   */
  public function addYear(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#form-system-messages', '<div class="alert alert-dismissible fade show col-12 alert-success">' . t('Year added successfully.') . '</div>'));
    $year = $form_state->getValue('Year');
    $year--;
    $connection = \Drupal::service('database');
    $update = $connection->insert('anzy')
      ->fields([
        'jan' => NULL,
        'feb' => NULL,
        'mar' => NULL,
        'apr' => NULL,
        'may' => NULL,
        'jun' => NULL,
        'jul' => NULL,
        'aug' => NULL,
        'sep' => NULL,
        'oct' => NULL,
        'nov' => NULL,
        'dec' => NULL,
        'reptable' => 0,
        'year' => $year,
      ])
      ->execute();
    return $form['wrapper'];
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
          'year' => $form_state->getValue('Year'),
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
          'year' => $form_state->getValue('Year'),
        ])
        ->execute();
    }
    \Drupal::messenger()->addMessage($this->t('Report updated successfully'), 'status', TRUE);
  }

}
