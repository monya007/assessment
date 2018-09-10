<?php

namespace Drupal\Tests\math_field\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\math_field\ReversePolishNotation;

/**
 * Tests the Reverse Polish Notation converter.
 *
 * @coversDefaultClass \Drupal\math_field\ReversePolishNotation
 *
 * @group math_field
 */
class PolishNotationTest extends UnitTestCase {

  /**
   * @covers ::parseInfix
   * @dataProvider parseInfixDataProvider
   */
  public function testParseInfix($value, $expected) {
    $converter = new ReversePolishNotation();
    $value = $converter->parseInfix($value);
    $this->assertSame($expected, $value);
  }

  /**
   * @covers ::calculatePostfix
   * @dataProvider calculatePostfixDataProvider
   */
  public function testCalculatePostfix($value, $expected) {
    $converter = new ReversePolishNotation();
    $value = $converter->calculatePostfix($value);
    $this->assertSame($expected, $value);
  }

  /**
   * Data provider for testParseInfix().
   *
   * @return array
   *   Expressions to check.
   */
  public function parseInfixDataProvider() {
    return [
      '2+3*4' => [
        '2+3*4',
        [2, 3, 4, '*', '+'],
      ],
      '10 + 20 - 30 + 15 * 5' => [
        '10 + 20 - 30 + 15 * 5',
        [10, 20, '+', 30, '-', 15, 5, '*', '+'],
      ],
      '2+2*2/5-2' => [
        '2+2*2/5-2',
        [2, 2, 2, '*', 5, '/', '+', 2, '-'],
      ],
    ];
  }

  /**
   * Data provider for testParseInfix().
   *
   * @return array
   *   Expressions to check.
   */
  public function calculatePostfixDataProvider() {
    return [
      '2+3*4' => [
        [2, 3, 4, '*', '+'],
        14,
      ],
      '10 + 20 - 30 + 15 * 5' => [
        [10, 20, '+', 30, '-', 15, 5, '*', '+'],
        75,
      ],
      '2+2*2/5-2' => [
        [2, 2, 2, '*', 5, '/', '+', 2, '-'],
        0.8,
      ],
    ];
  }

}
