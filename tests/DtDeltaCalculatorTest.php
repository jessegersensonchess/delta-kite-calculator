<?php
namespace Kite\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Kite\Calculator;

// Manually include the Calculator class if no autoload is set up
require_once __DIR__ . '/../src/Calculator.php';

class DtDeltaCalculatorTest extends TestCase
{
    // ─────────────────────────────────────────────────────────────────────────────
    // Unit Conversion Tests
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Tests conversion from cm to inches for a known value.
     */
    public function testCmToInches()
    {
        $this->assertEqualsWithDelta(39.37, Calculator::convertCmToInches(100), 0.01);
    }

    /**
     * Tests that 0 cm converts to 0 inches.
     */
    public function testConvertCmToInchesZero()
    {
        $this->assertEquals(0, Calculator::convertCmToInches(0));
    }

    /**
     * Tests that a negative cm value results in negative inches.
     */
    public function testConvertCmToInchesNegative()
    {
        $this->assertLessThan(0, Calculator::convertCmToInches(-10));
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Dimension Calculations
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Tests height calculation from scale.
     */
    public function testCalculateHeight()
    {
        $this->assertEqualsWithDelta(78.7, Calculator::calculateHeight(1.0), 0.1);
        $this->assertEqualsWithDelta(39.4, Calculator::calculateHeight(0.5), 0.1);
    }

    /**
     * Tests leading edge calculation at scale 1.0.
     */
    public function testLeadingEdgeCalculation()
    {
        $this->assertEqualsWithDelta(111.0, Calculator::calculateLeadingEdge(1.0), 0.1);
    }

    /**
     * Tests that leading edge calculation returns 0 for 0 scale.
     */
    public function testCalculateLeadingEdgeZero()
    {
        $this->assertEquals(0, Calculator::calculateLeadingEdge(0));
    }

    /**
     * Tests that leading edge uses height and LE factor.
     */
    public function testLeadingEdgeUsesScaleLeFactor()
    {
        $scalefactor = 1.0;
        $expected = round(Calculator::calculateHeight($scalefactor) * Calculator::SCALE_LE_FACTOR, 1);
        $this->assertEquals($expected, Calculator::calculateLeadingEdge($scalefactor));
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Area + Line Strength Tests
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Tests area calculation with realistic inputs.
     */
    public function testAreaCalculation()
    {
        $area = Calculator::calculateArea(100, 33.02, 39.5);
        $this->assertIsFloat($area);
        $this->assertGreaterThan(0, $area);
    }

    /**
     * Tests area calculation with zero input.
     */
    public function testAreaCalculationZeroInputs()
    {
        $this->assertEquals(0, Calculator::calculateArea(0, 0, 0));
    }

    /**
     * Tests recommended line strength from area.
     */
    public function testRecommendedLine()
    {
        $this->assertEquals(200, Calculator::recommendedLine(100));
    }

    /**
     * Tests rounding behavior of recommended line strength.
     */
    public function testRecommendedLineRounding()
    {
        $this->assertEquals(6, Calculator::recommendedLine(3.1));
        $this->assertEquals(7, Calculator::recommendedLine(3.5));
        $this->assertEquals(8, Calculator::recommendedLine(3.9));
    }

    /**
     * Tests that a negative scale still returns an array structure for spar deflection.
     */
    public function testNegativeScaleHandledGracefully()
    {
        $result = Calculator::getSparDeflectionFactors(-1.0);
        $this->assertIsArray($result);
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Get Measurements Tests
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Tests getMeasurements method with a scale factor.
     */
    public function testGetMeasurements()
    {
        $scaleFactor = 1.0;
        $measurements = Calculator::getMeasurements($scaleFactor);

        // Check if heightCm is properly calculated and part of the result
        $this->assertArrayHasKey('heightCm', $measurements);
        $this->assertEquals(78.7, $measurements['heightCm']['value'], "HeightCm does not match expected value.");

        // Check for the presence of other keys as needed
        $this->assertArrayHasKey('wingWidthCm', $measurements);
        $this->assertArrayHasKey('cellWidthCm', $measurements);
    }

    /**
     * Tests getHeightCm helper function
     */
    public function testGetHeightCm()
    {
        $scaleFactor = 1.0;
        $heightCm = Calculator::getHeightCm($scaleFactor);
        $this->assertEquals(78.7, $heightCm, "HeightCm calculation is incorrect.");
    }

    /**
     * Tests getBoxBackCm helper function
     */
    public function testGetBoxBackCm()
    {
        $scaleFactor = 1.0;
        $boxBackCm = Calculator::getBoxBackCm($scaleFactor);
        $this->assertEquals(33.0, $boxBackCm, "BoxBackCm calculation is incorrect.");
    }
}


