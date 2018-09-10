<?php

namespace Drupal\math_field;

/**
 * Class ReversePolishNotation.
 */
class ReversePolishNotation {

  /**
   * Allowed operations with precedences.
   *
   * @var array
   */
  protected $operations = [
    '-' => 2,
    '+' => 2,
    '*' => 3,
    '/' => 3,
  ];

  /**
   * Parse an infix notation string.
   *
   * @param string $expression
   *   Infix notation expression.
   *
   * @return array
   *   Postfix notation expression in array format.
   */
  public function parseInfix($expression) {
    $expr = trim(strtolower($expression));
    $index = 0;
    $output = [];
    $filo = [];
    $current_number = '';

    while ($index < strlen($expr)) {
      $char = substr($expr, $index, 1);

      // Skip spaces.
      if ($char == ' ') {
        $index++;
        continue;
      }
      if (!array_key_exists($char, $this->operations) && (intval($char) >= 0)) {
        $current_number .= $char;
      }
      else {

        // Push number to output.
        array_push($output, (int) $current_number);
        $current_number = '';

        // Push operations to output from operations stack.
        while (count($filo)
          && $this->operations[$char] <= $this->operations[$filo[count($filo) - 1]]) {
          array_push($output, array_pop($filo));
        }
        array_push($filo, $char);
      }
      $index++;
    }
    if (!empty($current_number)) {
      array_push($output, (int) $current_number);
    }
    if (count($filo)) {
      while (count($filo) && !empty($filo[count($filo) - 1])) {
        array_push($output, array_pop($filo));
      }
    }
    return $output;
  }

  /**
   * Calculate postfix notation expression.
   *
   * @return float|int
   *   Calculation result.
   */
  public function calculatePostfix($tokens) {
    $stack = [];
    foreach ($tokens as $item) {
      if (array_key_exists($item, $this->operations)) {
        $op_2 = array_pop($stack);
        $op_1 = array_pop($stack);
        switch ($item) {

          case '+':
            array_push($stack, $op_1 + $op_2);
            break;

          case '-':
            array_push($stack, $op_1 - $op_2);
            break;

          case '*':
            array_push($stack, $op_1 * $op_2);
            break;

          case '/':
            array_push($stack, $op_1 / $op_2);
            break;

          default:
            break;
        }
      }
      else {
        array_push($stack, $item);
      }
    }
    return reset($stack);
  }

  /**
   * Calculate infix notaion string.
   *
   * @param string $expression
   *   Infix notation expression.
   *
   * @return float|int
   *   Calculation result.
   */
  public function calculateInfix($expression) {
    $stack = $this->parseInfix($expression);
    return $this->calculatePostfix($stack);
  }

}
