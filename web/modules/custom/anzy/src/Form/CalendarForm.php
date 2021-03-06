<?php

namespace Drupal\anzy\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
   * Contain number of tables to display.
   *
   * @var tableCount
   */
  protected $tableCount = 1;

  /**
   * Contain number of rows to display.
   *
   * @var rowsCount
   */
  protected $rowsCount = 0;

  /**
   * Contain table number from which validation starts.
   *
   * @var rowsIndex
   */
  protected $rowIndex = 0;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'Calendar_form';
  }

  /**
   * Selects from which table is validate first.
   */
  public function validateFilter(array $monthsFilter) {
    // $count used to correct $theLastOne and $offset, so it would be precise.
    $count = 0;
    // $theLastOne Count how many elements blank after all checked months.
    $theLastOne = 0;
    // $offset is counting how many element blank after first checked month.
    $offset = 0;
    $sortIndex = 0;
    $fieldsName = [];
    foreach ($monthsFilter as $month => $number) {
      if ($number != "") {
        if ($count == 0) {
          $sortIndex = $month[3];
          $this->rowIndex = $sortIndex;
        }
        if ($month[3] == $sortIndex) {
          $fieldsName[$offset] = $month;
        }
        elseif ($month[3] != $sortIndex) {
          break;
        }
        $count++;
        $offset++;
        if ($theLastOne != 0) {
          $theLastOne = 0;
        }
      }
      elseif ($count != 0) {
        if ($month[3] == $sortIndex) {
          $fieldsName[$offset] = $month;
        }
        $theLastOne++;
        $offset++;
      }
    }
    $fieldsName = array_slice($fieldsName, 0, $offset - $theLastOne, TRUE);
    return $fieldsName;
  }

  /**
   * Selects from which table is validate first.
   */
  public function getFilterValues(array $newVal, array $inputes) {
    $allMonthsTable = [];
    for ($c = 0; $c < count($newVal); $c++) {
      $monthName = substr($newVal[$c], 0, 3);
      $monthIndex = $newVal[$c];
      $monthIndex = $monthIndex[4];
      for ($i = 0; $i != $this->tableCount; $i++) {
        $allMonthsTable[$monthName . $i . $monthIndex] = $inputes[$monthName . $i . $monthIndex];
      }
    }
    return $allMonthsTable;
  }

  /**
   * Validate fields and return a list of fields names which not valid.
   */
  public function validateMonths(array $monthsValidate, array $inputes) {
    $fieldsName = [];
    $inputes = array_filter($inputes);
    $unnecessaryValues = [];
    foreach ($inputes as $month => $value) {
      $counter = 0;
      foreach ($monthsValidate as $newMonth => $newValue) {
        if ($month == $newMonth && $newValue != "") {
          $counter++;
        }
      }
      if ($counter != 1) {
        $unnecessaryValues[$month] = $month;
      }
    }
    foreach ($monthsValidate as $month => $value) {
      if ($value == "") {
        $fieldsName[$month] = $month;
      }
    }
    return array_merge($unnecessaryValues, $fieldsName);
  }

  /**
   * Provides quartal report for fields.
   */
  public function quartalReport(array $monthsVal) {
    for ($j = 0; $j < $this->tableCount; $j++) {
      for ($i = $this->rowsCount; $i > -1; $i--) {
        $monthsVal["Qfirst$j$i"] = [
          $monthsVal["Jan$j$i"] == NULL ? 0 : round($monthsVal["Jan$j$i"], 2),
          $monthsVal["Feb$j$i"] == NULL ? 0 : round($monthsVal["Feb$j$i"], 2),
          $monthsVal["Mar$j$i"] == NULL ? 0 : round($monthsVal["Mar$j$i"], 2),
        ];
        $monthsVal["Qfirst$j$i"] = $monthsVal["Qfirst$j$i"][0] == 0 && $monthsVal["Qfirst$j$i"][1] == 0 && $monthsVal["Qfirst$j$i"][2] == 0 ? "" : round((($monthsVal["Qfirst$j$i"][0] + $monthsVal["Qfirst$j$i"][1] + $monthsVal["Qfirst$j$i"][2]) + 1) / 3, 2);
        $monthsVal["Qsecond$j$i"] = [
          $monthsVal["Apr$j$i"] == NULL ? 0 : round($monthsVal["Apr$j$i"], 2),
          $monthsVal["May$j$i"] == NULL ? 0 : round($monthsVal["May$j$i"], 2),
          $monthsVal["Jun$j$i"] == NULL ? 0 : round($monthsVal["Jun$j$i"], 2),
        ];
        $monthsVal["Qsecond$j$i"] = $monthsVal["Qsecond$j$i"][0] == 0 && $monthsVal["Qsecond$j$i"][1] == 0 && $monthsVal["Qsecond$j$i"][2] == 0 ? "" : round((($monthsVal["Qsecond$j$i"][0] + $monthsVal["Qsecond$j$i"][1] + $monthsVal["Qsecond$j$i"][2]) + 1) / 3, 2);
        $monthsVal["Qthird$j$i"] = [
          $monthsVal["Jul$j$i"] == NULL ? 0 : round($monthsVal["Jul$j$i"], 2),
          $monthsVal["Aug$j$i"] == NULL ? 0 : round($monthsVal["Aug$j$i"], 2),
          $monthsVal["Sep$j$i"] == NULL ? 0 : round($monthsVal["Sep$j$i"], 2),
        ];
        $monthsVal["Qthird$j$i"] = $monthsVal["Qthird$j$i"][0] == 0 && $monthsVal["Qthird$j$i"][1] == 0 && $monthsVal["Qthird$j$i"][2] == 0 ? "" : round((($monthsVal["Qthird$j$i"][0] + $monthsVal["Qthird$j$i"][1] + $monthsVal["Qthird$j$i"][2]) + 1) / 3, 2);
        $monthsVal["Qfourth$j$i"] = [
          $monthsVal["Oct$j$i"] == NULL ? 0 : round($monthsVal["Oct$j$i"], 2),
          $monthsVal["Nov$j$i"] == NULL ? 0 : round($monthsVal["Nov$j$i"], 2),
          $monthsVal["Dec$j$i"] == NULL ? 0 : round($monthsVal["Dec$j$i"], 2),
        ];
        $monthsVal["Qfourth$j$i"] = $monthsVal["Qfourth$j$i"][0] == 0 && $monthsVal["Qfourth$j$i"][1] == 0 && $monthsVal["Qfourth$j$i"][2] == 0 ? "" : round((($monthsVal["Qfourth$j$i"][0] + $monthsVal["Qfourth$j$i"][1] + $monthsVal["Qfourth$j$i"][2]) + 1) / 3, 2);
        $monthsVal["YTD$j$i"] = [
          $monthsVal["Qfirst$j$i"] == "" ? 0 : round($monthsVal["Qfirst$j$i"], 2),
          $monthsVal["Qsecond$j$i"] == "" ? 0 : round($monthsVal["Qsecond$j$i"], 2),
          $monthsVal["Qthird$j$i"] == "" ? 0 : round($monthsVal["Qthird$j$i"], 2),
          $monthsVal["Qfourth$j$i"] == "" ? 0 : round($monthsVal["Qfourth$j$i"], 2),
        ];
        $monthsVal["YTD$j$i"] = $monthsVal["Qfirst$j$i"] == 0 && $monthsVal["Qsecond$j$i"] == 0 && $monthsVal["Qthird$j$i"] == 0 && $monthsVal["Qfourth$j$i"] == 0 ? "" : round((($monthsVal["YTD$j$i"][0] + $monthsVal["YTD$j$i"][1] + $monthsVal["YTD$j$i"][2] + $monthsVal["YTD$j$i"][3]) + 1) / 4, 2);
      }
    }
    return $monthsVal;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $inputes = $form_state->getUserInput();
    $quartalsVal = [];
    if (!empty($inputes) && $inputes["_triggering_element_value"] == "Add Table") {
      $this->rowsCount = $inputes["hidden"];
      $this->tableCount = $inputes['hiddenTable'];
      $this->tableCount++;
    }
    if (!empty($inputes) && $inputes["_triggering_element_value"] == "Add year") {
      $this->rowsCount = $inputes["hidden"];
      $this->tableCount = $inputes['hiddenTable'];
      $this->rowsCount++;
    }
    elseif (!empty($inputes) && $inputes["_triggering_element_value"] == "Submit") {
      $this->rowsCount = $inputes["hidden"];
      $this->tableCount = $inputes['hiddenTable'];
    }
    $monthsVal = [];
    if (!empty($inputes)) {
      for ($j = 0; $j < $this->tableCount; $j++) {
        for ($i = $this->rowsCount; $i > -1; $i--) {
          $monthsVal["Jan$j$i"] = $inputes["Jan$j$i"];
          $monthsVal["Feb$j$i"] = $inputes["Feb$j$i"];
          $monthsVal["Mar$j$i"] = $inputes["Mar$j$i"];
          $monthsVal["Apr$j$i"] = $inputes["Apr$j$i"];
          $monthsVal["May$j$i"] = $inputes["May$j$i"];
          $monthsVal["Jun$j$i"] = $inputes["Jun$j$i"];
          $monthsVal["Jul$j$i"] = $inputes["Jul$j$i"];
          $monthsVal["Aug$j$i"] = $inputes["Aug$j$i"];
          $monthsVal["Sep$j$i"] = $inputes["Sep$j$i"];
          $monthsVal["Oct$j$i"] = $inputes["Oct$j$i"];
          $monthsVal["Nov$j$i"] = $inputes["Nov$j$i"];
          $monthsVal["Dec$j$i"] = $inputes["Dec$j$i"];
        }
      }
      $newVal = $this->validateFilter($monthsVal);
      $newVal = $this->getFilterValues($newVal, $inputes);
      $monthsVal = $this->validateMonths($newVal, $monthsVal);
    }
    if (isset($inputes["_triggering_element_value"]) && empty($monthsVal)) {
      if ($inputes["_triggering_element_value"] == "Submit") {
        $quartalsVal = $this->quartalReport($inputes);
      }
    }
    $form['system_messages'] = [
      '#markup' => '<div id="form-system-messages"></div>',
      '#weight' => -100,
    ];
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
    $form["wrapper"] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'data-wrapper'],
    ];
    for ($j = 0; $j < $this->tableCount; $j++) {
      $form["wrapper"]["table$j"] = [
        '#type' => 'table',
        '#header' => $headers,
      ];
      for ($i = $this->rowsCount; $i != -1; $i--) {
        $form["wrapper"]["Year$j$i"] = [
          '#type' => 'number',
          '#value' => $i == 0 ? date('Y', time()) : date('Y', time()) - $i,
          '#disabled' => TRUE,
          '#attributes' => ['class' => ['firstField']],
        ];
        $form["wrapper"]["Jan$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Feb$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Mar$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Qfirst$j$i"] = [
          '#type' => 'textfield',
          '#value' => $quartalsVal["Qfirst$j$i"] ?? '',
          '#disabled' => TRUE,
        ];
        $form["wrapper"]["Apr$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["May$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Jun$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Qsecond$j$i"] = [
          '#type' => 'textfield',
          '#value' => $quartalsVal["Qsecond$j$i"] ?? '',
          '#disabled' => TRUE,
        ];
        $form["wrapper"]["Jul$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Aug$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Sep$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Qthird$j$i"] = [
          '#type' => 'textfield',
          '#value' => $quartalsVal["Qthird$j$i"] ?? '',
          '#disabled' => TRUE,
        ];
        $form["wrapper"]["Oct$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Nov$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Dec$j$i"] = [
          '#type' => 'number',
          '#default_value' => "",
        ];
        $form["wrapper"]["Qfourth$j$i"] = [
          '#type' => 'textfield',
          '#value' => $quartalsVal["Qfourth$j$i"] ?? '',
          '#disabled' => TRUE,
        ];
        $form["wrapper"]["YTD$j$i"] = [
          '#type' => 'textfield',
          '#value' => $quartalsVal["YTD$j$i"] ?? '',
          '#disabled' => TRUE,
        ];
      }
    }
    $form['wrapper']['hidden'] = [
      '#type' => 'hidden',
      '#value' => $this->rowsCount,
    ];
    $form['wrapper']['hiddenTable'] = [
      '#type' => 'hidden',
      '#value' => $this->tableCount,
    ];
    $form["wrapper"]['add'] = [
      '#type' => 'submit',
      '#value' => t('Add year'),
      '#ajax' => [
        'callback' => '::submitCall',
        'wrapper'    => 'data-wrapper',
      ],
    ];
    $form["wrapper"]['addTable'] = [
      '#type' => 'submit',
      '#value' => t('Add Table'),
      '#ajax' => [
        'callback' => '::submitCall',
        'wrapper'    => 'data-wrapper',
      ],
    ];
    $form["wrapper"]['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
      '#description' => t('Submit, #type = submit'),
      '#ajax' => [
        'callback' => '::submitCall',
        'wrapper'    => 'data-wrapper',
      ],
    ];
    $form['#attached']['library'][] = 'anzy/my-lib';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $inputes = $form_state->getUserInput();
    $monthsVal = [];
    for ($j = 0; $j < $this->tableCount; $j++) {
      for ($i = $this->rowsCount; $i > -1; $i--) {
        $monthsVal["Jan$j$i"] = $form_state->getValue("Jan$j$i");
        $monthsVal["Feb$j$i"] = $form_state->getValue("Feb$j$i");
        $monthsVal["Mar$j$i"] = $form_state->getValue("Mar$j$i");
        $monthsVal["Apr$j$i"] = $form_state->getValue("Apr$j$i");
        $monthsVal["May$j$i"] = $form_state->getValue("May$j$i");
        $monthsVal["Jun$j$i"] = $form_state->getValue("Jun$j$i");
        $monthsVal["Jul$j$i"] = $form_state->getValue("Jul$j$i");
        $monthsVal["Aug$j$i"] = $form_state->getValue("Aug$j$i");
        $monthsVal["Sep$j$i"] = $form_state->getValue("Sep$j$i");
        $monthsVal["Oct$j$i"] = $form_state->getValue("Oct$j$i");
        $monthsVal["Nov$j$i"] = $form_state->getValue("Nov$j$i");
        $monthsVal["Dec$j$i"] = $form_state->getValue("Dec$j$i");
      }
    }
    $newVal = $this->validateFilter($monthsVal);
    $newVal = $this->getFilterValues($newVal, $monthsVal);
    $monthsVal = $this->validateMonths($newVal, $monthsVal);
    if (!empty($monthsVal) && $inputes["_triggering_element_value"] == "Submit") {
      foreach ($monthsVal as $name) {
        $form_state->setErrorByName($name, t('There is a mistake in report, you need to fix it.'));
      }
    }
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage($this->t('Report updated successfully'), 'status', TRUE);
  }

  /**
   * Triggers validation and quarterly report.
   */
  public function submitCall(array &$form, FormStateInterface $form_state) {
    return $form['wrapper'];
  }

}
