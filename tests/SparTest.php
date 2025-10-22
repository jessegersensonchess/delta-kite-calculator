<?php

namespace Kite\Tests;

use PHPUnit\Framework\TestCase;
use Kite\Spar;

// Manually include the Calculator class if no autoload is set up
require_once __DIR__ . '/../src/Spar.php';


class SparTest extends TestCase
{
    /**
     * Test constructor and basic properties.
     */
    public function testConstructor()
    {
        $spar = new Spar("Test Spar", 10, 5, 250, 50, 60, 1.6);

        $this->assertEquals("Test Spar", $spar->name);
        $this->assertEquals(10, $spar->sparDeflection);
        $this->assertEquals(5, $spar->passedInDeflection);
        $this->assertEquals(250, $spar->weight);
        $this->assertEquals(50, $spar->innerDiameter);
        $this->assertEquals(60, $spar->outerDiameter);
        $this->assertEquals(1.6, $spar->density);
        $this->assertNotEmpty($spar->scalefactor);
    }

    /**
     * Test scale factor calculation.
     */
    public function testScaleFactorCalculation()
    {
        $spar = new Spar("Test Spar", 1.3655431273772, 9.106, 250, 50, 60, 1.6);

        // We expect the scale factor to be a formatted string, check the expected value
        $relstiffness = 9.106 / 1.3655431273772;
        $expectedScaleFactor = number_format(pow($relstiffness, 0.25), 2, '.', '');
        $this->assertEquals(number_format($expectedScaleFactor, 2, '.', ''), $spar->scalefactor);
    }

    public function testRelativeStiffnessCalculation()
    {
        $spar = new Spar("Test Spar", 1.3655431273772, 9.106, 250, 50, 60, 1.6);

        // Expected relative stiffness value
        $expectedRelativeStiffness = 9.106 / 1.3655431273772;

        // Check that the relative stiffness is calculated correctly
        $this->assertEqualsWithDelta($expectedRelativeStiffness, $spar->calculateRelativeStiffness(), 0.0001);
    }

    /**
     * Test rod weight calculation.
     */
    public function testRodWeightCalculation()
    {
        $spar = new Spar("Test Spar", 0.047, 0.045, 250, 50, 60, 1.6);

        // Expected weight in grams per meter for a given diameter, density, etc.
        $idInMeters = 50 / 1000;
        $odInMeters = 60 / 1000;
        $rInner = $idInMeters / 2;
        $rOuter = $odInMeters / 2;
        $areaOuter = pi() * pow($rOuter, 2);
        $areaInner = pi() * pow($rInner, 2);
        $volume = $areaOuter - $areaInner;
        $expectedWeight = 1.6 * $volume * 1000000; // g/m

        $this->assertEqualsWithDelta($expectedWeight, $spar->calculateRodWeight(), 0.1);
    }

    /**
     * Test moment of area calculation.
     */
    public function testMomentOfAreaCalculation()
    {
        $spar = new Spar("Test Spar", 0.047, 0.045, 250, 50, 60, 1.6);

        $outerRadius = 60 / 2;
        $innerRadius = 50 / 2;
        $expectedMomentOfArea = (pi() / 64) * (pow($outerRadius, 4) - pow($innerRadius, 4));

        $this->assertEqualsWithDelta($expectedMomentOfArea, $spar->calculateMomentOfArea(), 0.1);
    }

    /**
     * Test deflection calculation.
     */
    public function testDeflectionCalculation()
    {
        $spar = new Spar("Test Spar", 0.047, 0.045, 250, 50, 60, 1.6);

        // Constants
        $P = Spar::LOAD * 0.00981;  // Load in Newtons (conversion from grams to Newtons)
        $L = Spar::LENGTH * 10;      // Length in mm (conversion from cm to mm)
        $E = 23000000;               // Young's modulus for composite carbon fiber
        $I = $spar->calculateMomentOfArea();

        // Deflection formula: δ = (P * L^3) / (3 * E * I)
        $expectedDeflection = ($P * pow($L, 3)) / (3 * $E * $I);

        $this->assertEqualsWithDelta($expectedDeflection, $spar->calculateDeflection(), 0.1);
    }


}
?>

