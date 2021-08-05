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
    for ($i = 0; $i < count($result); $i++) {
      foreach ($result[$i] as $item => $value) {
        if ($value == NULL) {
          $result[$i][$item] = '';
        }
      }
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $def = $this->load();
    if (empty($def)) {
      $def[0]['jan'] = "";
      $def[0]['feb'] = "";
      $def[0]['mar'] = "";
      $def[0]['apr'] = "";
      $def[0]['may'] = "";
      $def[0]['jun'] = "";
      $def[0]['jul'] = "";
      $def[0]['aug'] = "";
      $def[0]['sep'] = "";
      $def[0]['oct'] = "";
      $def[0]['nov'] = "";
      $def[0]['dec'] = "";
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
    $form['system_messages'] = [
      '#markup' => '<div id="form-system-messages"></div>',
      '#weight' => -100,
    ];
    $form['wrapper']['table'] = [
      '#type' => 'table',
      '#header' => $headers,
    ];
    $lastYear = 0;
    for ($i = count($def) - 1; $i != -1; $i--) {
      $lastYear++;
      $form['wrapper']["Year$i"] = [
        '#type' => 'number',
        '#value' => $i == 0 ? date('Y', time()) : $def[$i]['year'],
        '#disabled' => TRUE,
        '#attributes' => ['class' => ['firstField']],
      ];
      $form['wrapper']["Jan$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['jan'],
      ];
      $form['wrapper']["Feb$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['feb'],
      ];
      $form['wrapper']["Mar$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['mar'],
      ];
      $fQuarter = [
        $def[$i]['jan'] == "" ? 0 : round($def[$i]['jan'], 2),
        $def[$i]['feb'] == "" ? 0 : round($def[$i]['feb'], 2),
        $def[$i]['mar'] == "" ? 0 : round($def[$i]['mar'], 2),
      ];
      $form['wrapper']["Qfirst$i"] = [
        '#type' => 'textfield',
        '#value' => $fQuarter[0] == 0 && $fQuarter[1] == 0 && $fQuarter[2] == 0 ? "" : round((($fQuarter[0] + $fQuarter[1] + $fQuarter[2]) + 1) / 3, 2),
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Apr$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['apr'],
      ];
      $form['wrapper']["May$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['may'],
      ];
      $form['wrapper']["Jun$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['jun'],
      ];
      $sQuarter = [
        $def[$i]['apr'] == "" ? 0 : round($def[$i]['apr'], 2),
        $def[$i]['jun'] == "" ? 0 : round($def[$i]['jun'], 2),
        $def[$i]['may'] == "" ? 0 : round($def[$i]['may'], 2),
      ];
      $form['wrapper']["Qsecond$i"] = [
        '#type' => 'textfield',
        '#value' => $sQuarter[0] == 0 && $sQuarter[1] == 0 && $sQuarter[2] == 0 ? "" : round((($sQuarter[0] + $sQuarter[1] + $sQuarter[2]) + 1) / 3, 2),
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Jul$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['jul'],
      ];
      $form['wrapper']["Aug$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['aug'],
      ];
      $form['wrapper']["Sep$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['sep'],
      ];
      $tQuarter = [
        $def[$i]['jul'] == "" ? 0 : round($def[$i]['jul'], 2),
        $def[$i]['aug'] == "" ? 0 : round($def[$i]['aug'], 2),
        $def[$i]['sep'] == "" ? 0 : round($def[$i]['sep'], 2),
      ];
      $form['wrapper']["Qthird$i"] = [
        '#type' => 'textfield',
        '#value' => $tQuarter[0] == 0 && $tQuarter[1] == 0 && $tQuarter[2] == 0 ? "" : round((($tQuarter[0] + $tQuarter[1] + $tQuarter[2]) + 1) / 3, 2),
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Oct$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['oct'],
      ];
      $form['wrapper']["Nov$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['nov'],
      ];
      $form['wrapper']["Dec$i"] = [
        '#type' => 'number',
        '#default_value' => $def[$i]['dec'],
      ];
      $fQuarter = [
        $def[$i]['oct'] == "" ? 0 : round($def[$i]['oct'], 2),
        $def[$i]['nov'] == "" ? 0 : round($def[$i]['nov'], 2),
        $def[$i]['dec'] == "" ? 0 : round($def[$i]['dec'], 2),
      ];
      $form['wrapper']["Qfourth$i"] = [
        '#type' => 'textfield',
        '#value' => $fQuarter[0] == 0 && $fQuarter[1] == 0 && $fQuarter[2] == 0 ? "" : round((($fQuarter[0] + $fQuarter[1] + $fQuarter[2]) + 1) / 3, 2),
        '#disabled' => TRUE,
      ];
      $yearly = [
        $form['wrapper']["Qfirst$i"]['#value'] == "" ? 0 : round($form['wrapper']["Qfirst$i"]['#value'], 2),
        $form['wrapper']["Qsecond$i"]['#value'] == "" ? 0 : round($form['wrapper']["Qsecond$i"]['#value'], 2),
        $form['wrapper']["Qthird$i"]['#value'] == "" ? 0 : round($form['wrapper']["Qthird$i"]['#value'], 2),
        $form['wrapper']["Qfourth$i"]['#value'] == "" ? 0 : round($form['wrapper']["Qfourth$i"]['#value'], 2),
      ];
      $form['wrapper']["YTD$i"] = [
        '#type' => 'textfield',
        '#value' => $form['wrapper']["Qfirst$i"]['#value'] == 0 && $form['wrapper']["Qsecond$i"]['#value'] == 0 && $form['wrapper']["Qthird$i"]['#value'] == 0 && $form['wrapper']["Qfourth$i"]['#value'] == 0 ? "" : round((($yearly[0] + $yearly[1] + $yearly[2] + $yearly[3]) + 1) / 4, 2),
        '#disabled' => TRUE,
      ];
      $form['wrapper']['hidden'] = [
        '#type' => 'hidden',
        '#value' => $lastYear,
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
    $def = $this->load();
    $monthsVal = [];
    for ($i = count($def) - 1; $i > -1; $i--) {
      $monthsVal["Jan$i"] = $form_state->getValue("Jan$i");
      $monthsVal["Feb$i"] = $form_state->getValue("Feb$i");
      $monthsVal["Mar$i"] = $form_state->getValue("Mar$i");
      $monthsVal["Apr$i"] = $form_state->getValue("Apr$i");
      $monthsVal["May$i"] = $form_state->getValue("May$i");
      $monthsVal["Jun$i"] = $form_state->getValue("Jun$i");
      $monthsVal["Jul$i"] = $form_state->getValue("Jul$i");
      $monthsVal["Aug$i"] = $form_state->getValue("Aug$i");
      $monthsVal["Sep$i"] = $form_state->getValue("Sep$i");
      $monthsVal["Oct$i"] = $form_state->getValue("Oct$i");
      $monthsVal["Nov$i"] = $form_state->getValue("Nov$i");
      $monthsVal["Dec$i"] = $form_state->getValue("Dec$i");
    }
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
        $form_state->setErrorByName($name, t('There is a space in report, you need to fix'));
      }
    }
    return $form;
  }

  /**
   * Adding new year table to the report.
   */
  public function addYear(array &$form, FormStateInterface $form_state) {
    $def = $this->load();
    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#form-system-messages', '<div class="alert alert-dismissible fade show col-12 alert-success">' . t('Year added successfully.') . '</div>'));
    $connection = \Drupal::service('database');
    if (empty($def)) {
      $result = $connection->insert('anzy')
        ->fields([
          'jan' => $form_state->getValue('Jan0') != '' ? $form_state->getValue('Jan0') : NULL,
          'feb' => $form_state->getValue('Feb0') != '' ? $form_state->getValue('Feb0') : NULL,
          'mar' => $form_state->getValue('Mar0') != '' ? $form_state->getValue('Mar0') : NULL,
          'apr' => $form_state->getValue('Apr0') != '' ? $form_state->getValue('Apr0') : NULL,
          'may' => $form_state->getValue('May0') != '' ? $form_state->getValue('May0') : NULL,
          'jun' => $form_state->getValue('Jun0') != '' ? $form_state->getValue('Jun0') : NULL,
          'jul' => $form_state->getValue('Jul0') != '' ? $form_state->getValue('Jul0') : NULL,
          'aug' => $form_state->getValue('Aug0') != '' ? $form_state->getValue('Aug0') : NULL,
          'sep' => $form_state->getValue('Sep0') != '' ? $form_state->getValue('Sep0') : NULL,
          'oct' => $form_state->getValue('Oct0') != '' ? $form_state->getValue('Oct0') : NULL,
          'nov' => $form_state->getValue('Nov0') != '' ? $form_state->getValue('Nov0') : NULL,
          'dec' => $form_state->getValue('Dec0') != '' ? $form_state->getValue('Dec0') : NULL,
          'reptable' => 0,
          'year' => date('Y', time()),
        ])
        ->execute();
    }
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
        'year' => date('Y', time()) - $form_state->getValue('hidden'),
      ])
      ->execute();
    $index = count($def);
    $form['wrapper']["Year$index"] = [
      '#type' => 'number',
      '#value' => date('Y', time()) - $form_state->getValue('hidden'),
      '#disabled' => TRUE,
      '#attributes' => ['class' => ['firstField']],
    ];
    $form['wrapper']["Jan$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Feb$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Mar$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Qfirst$index"] = [
      '#type' => 'textfield',
      '#value' => "",
      '#disabled' => TRUE,
    ];
    $form['wrapper']["Apr$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["May$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Jun$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Qsecond$index"] = [
      '#type' => 'textfield',
      '#value' => "",
      '#disabled' => TRUE,
    ];
    $form['wrapper']["Jul$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Aug$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Sep$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Qthird$index"] = [
      '#type' => 'textfield',
      '#value' => "",
      '#disabled' => TRUE,
    ];
    $form['wrapper']["Oct$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Nov$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Dec$index"] = [
      '#type' => 'number',
      '#default_value' => "",
    ];
    $form['wrapper']["Qfourth$index"] = [
      '#type' => 'textfield',
      '#value' => "",
      '#disabled' => TRUE,
    ];
    $form['wrapper']["YTD$index"] = [
      '#type' => 'textfield',
      '#value' => "",
      '#disabled' => TRUE,
    ];
    $form_state->setRebuild(TRUE);
    return $form['wrapper'];
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = \Drupal::service('database');
    $def = $this->load();
    if (!empty($def)) {
      for ($i = 0; $i < count($def); $i++) {
        $test = $form_state->getValue("Sep$i") != '' ? $form_state->getValue("Sep$i") : NULL;
        $result = $connection->update('anzy')
          ->condition('year', $form_state->getValue("Year$i"))
          ->fields([
            'jan' => $form_state->getValue("Jan$i") != '' ? $form_state->getValue("Jan$i") : NULL,
            'feb' => $form_state->getValue("Feb$i") != '' ? $form_state->getValue("Feb$i") : NULL,
            'mar' => $form_state->getValue("Mar$i") != '' ? $form_state->getValue("Mar$i") : NULL,
            'apr' => $form_state->getValue("Apr$i") != '' ? $form_state->getValue("Apr$i") : NULL,
            'may' => $form_state->getValue("May$i") != '' ? $form_state->getValue("May$i") : NULL,
            'jun' => $form_state->getValue("Jun$i") != '' ? $form_state->getValue("Jun$i") : NULL,
            'jul' => $form_state->getValue("Jul$i") != '' ? $form_state->getValue("Jul$i") : NULL,
            'aug' => $form_state->getValue("Aug$i") != '' ? $form_state->getValue("Aug$i") : NULL,
            'sep' => $form_state->getValue("Sep$i") != '' ? $form_state->getValue("Sep$i") : NULL,
            'oct' => $form_state->getValue("Oct$i") != '' ? $form_state->getValue("Oct$i") : NULL,
            'nov' => $form_state->getValue("Nov$i") != '' ? $form_state->getValue("Nov$i") : NULL,
            'dec' => $form_state->getValue("Dec$i") != '' ? $form_state->getValue("Dec$i") : NULL,
            'reptable' => 0,
          ])
          ->execute();
      }
    }
    else {
      $result = $connection->insert('anzy')
        ->fields([
          'jan' => $form_state->getValue('Jan0') != '' ? $form_state->getValue('Jan0') : NULL,
          'feb' => $form_state->getValue('Feb0') != '' ? $form_state->getValue('Feb0') : NULL,
          'mar' => $form_state->getValue('Mar0') != '' ? $form_state->getValue('Mar0') : NULL,
          'apr' => $form_state->getValue('Apr0') != '' ? $form_state->getValue('Apr0') : NULL,
          'may' => $form_state->getValue('May0') != '' ? $form_state->getValue('May0') : NULL,
          'jun' => $form_state->getValue('Jun0') != '' ? $form_state->getValue('Jun0') : NULL,
          'jul' => $form_state->getValue('Jul0') != '' ? $form_state->getValue('Jul0') : NULL,
          'aug' => $form_state->getValue('Aug0') != '' ? $form_state->getValue('Aug0') : NULL,
          'sep' => $form_state->getValue('Sep0') != '' ? $form_state->getValue('Sep0') : NULL,
          'oct' => $form_state->getValue('Oct0') != '' ? $form_state->getValue('Oct0') : NULL,
          'nov' => $form_state->getValue('Nov0') != '' ? $form_state->getValue('Nov0') : NULL,
          'dec' => $form_state->getValue('Dec0') != '' ? $form_state->getValue('Dec0') : NULL,
          'reptable' => 0,
          'year' => date('Y', time()),
        ])
        ->execute();
    }
    \Drupal::messenger()->addMessage($this->t('Report updated successfully'), 'status', TRUE);
  }

}
