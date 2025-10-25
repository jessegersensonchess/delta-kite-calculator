<?php

namespace Kite\Tests;

use PHPUnit\Framework\TestCase;
use Kite\Delta;

require_once __DIR__ . '/../src/Delta.php';

/**
 * Class DeltaTest
 * Unit tests for the Kite\Delta class to ensure proper calculation and functionality.
 */
class DeltaTest extends TestCase
{
    /**
     * @var Delta $delta Instance of the Delta class.
     */
    private $delta;

    /**
     * Set up the environment for each test.
     * Mock the $_SERVER global variable and initialize the Delta object.
     */
    protected function setUp(): void
    {
        // Mock the $_SERVER global variable
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['submit'] = true;
        $_POST['noseangle'] = 100.0;
        $_POST['lengthatcenterline'] = 150.0;
        $_POST['units'] = 'in';

        // Use default values (e.g., noseangle=96, lengthatcenterline=100, units='cm')
        $this->delta = new Delta();
    }

    // --- TEST CASES ---

    /**
     * Test the initial values for nose angle, length at centerline, and units.
     */
    public function testInitialValues()
    {
        $this->assertEquals(96.0, $this->delta->getNoseAngle());
        $this->assertEquals(100.0, $this->delta->getLengthAtCenterline());
        $this->assertEquals('cm', $this->delta->getUnits());
    }

    /**
     * Test the calculation of the half span.
     */
    public function testCalculateHalfSpan()
    {
        $halfSpan = $this->delta->getHalfSpan();
        $expected = tan(deg2rad(96.0 / 2)) * 100.0;
        $this->assertEquals($expected, $halfSpan, '', 0.01);  // Allow a small delta of 0.01
    }

    /**
     * Test the calculation of the nominal span (double the half span).
     */
    public function testCalculateNominalSpan()
    {
        $nominalSpan = $this->delta->getNominalSpan();
        $expected = 2 * $this->delta->getHalfSpan();
        $this->assertEquals($expected, $nominalSpan, '', 0.01);
    }

    /**
     * Test the calculation of the length of the fin.
     */
    public function testCalculateLengthOfFin()
    {
        $lengthOfFin = $this->delta->getLengthOfFin();
        $expected = 0.75 * $this->delta->getLengthAtCenterline();
        $this->assertEquals($expected, $lengthOfFin);
    }

    /**
     * Test the calculation of the length of the leading edge.
     */
    public function testCalculateLengthOfLeadingEdge()
    {
        $lengthOfLeadingEdge = $this->delta->getLengthOfLeadingEdge();
        $cosHalfAngle = cos(deg2rad($this->delta->getNoseAngle() / 2));
        $expected = ($cosHalfAngle == 0) ? 0 : $this->delta->getLengthAtCenterline() / $cosHalfAngle;
        $this->assertEquals($expected, $lengthOfLeadingEdge, '', 0.01);
    }

    /**
     * Test the calculation of the nominal length of the wing spars.
     */
    public function testCalculateNominalLengthOfWingSpars()
    {
        $nominalLengthOfWingSpars = $this->delta->getNominalLengthOfWingSpars();
        $expected = $this->delta->getLengthOfLeadingEdge() * 0.75;
        $expected = round($expected, 1);
        $this->assertEquals($expected, $nominalLengthOfWingSpars, '', 0.01);
    }

    /**
     * Test the calculation of the nose to leading edge spar distance.
     */
    public function testCalculateNoseToLEspar()
    {
        $noseToLEspar = $this->delta->getNoseToLEspar();
        $expected = $this->delta->getLengthOfLeadingEdge() - $this->delta->getNominalLengthOfWingSpars();
        $this->assertEquals($expected, $noseToLEspar, '', 0.01);
    }

    /**
     * Test the calculation of the depth of the fin.
     */
    public function testCalculateDepthOfFin()
    {
        $depthOfFin = $this->delta->getDepthOfFin();
        $expected = 0.333 * $this->delta->getHalfSpan();
        $expected = round($expected, 1);
        $this->assertEquals($expected, $depthOfFin, '', 0.01);
    }

    /**
     * Test the calculation of the spreader strut attachment point.
     */
    public function testCalculateSpreaderStrutAttachmentPoint()
    {
        $spreaderStrutAttachmentPoint = $this->delta->getSpreaderStrutAttachmentPoint();
        $expected = (7 / 9) * $this->delta->getNominalLengthOfWingSpars();
        $this->assertEquals($expected, $spreaderStrutAttachmentPoint, '', 0.01);
    }

    /**
     * Test the calculation of the nose to spreader distance.
     */
    public function testCalculateNoseToSpreader()
    {
        $noseToSpreader = $this->delta->getNoseToSpreader();
        $cosHalfAngle = cos(deg2rad($this->delta->getNoseAngle() / 2));
        $expected = $cosHalfAngle * ($this->delta->getLengthOfLeadingEdge() - $this->delta->getSpreaderStrutAttachmentPoint());
        $this->assertEquals($expected, $noseToSpreader, '', 0.01);
    }

    /**
     * Test the calculation of the spreader length.
     */
    public function testCalculateSpreaderLength()
    {
        $spreaderLength = $this->delta->getSpreaderLength();
        $cosHalfAngle = cos(deg2rad($this->delta->getNoseAngle() / 2));
        $noseToSpreader = $this->delta->getNoseToSpreader();
        if ($cosHalfAngle == 0 || $noseToSpreader == 0) {
            $this->assertEquals('N/A', $spreaderLength);
        } else {
            $expected = number_format(2 / ($cosHalfAngle / $noseToSpreader), 1, '.', '');
            $this->assertEquals($expected, $spreaderLength);
        }
    }

    /**
     * Test the calculation of the second spreader length.
     */
    public function testCalculateSpreaderLength2()
    {
        $spreaderLength2 = $this->delta->getSpreaderLength2();
        $noseToSpreader = $this->delta->getNoseToSpreader();
        if ($this->delta->getLengthAtCenterline() == 0) {
            $this->assertEquals('N/A', $spreaderLength2);
        } else {
            $expected = number_format(($noseToSpreader / $this->delta->getLengthAtCenterline()) * $this->delta->getNominalSpan(), 1, '.', '');
            $this->assertEquals($expected, $spreaderLength2);
        }
    }

    /**
     * Test the area calculation in square centimeters.
     */
    public function testCalculateAreaInCm()
    {
        $area = $this->delta->getArea();
        $expected = 0.5 * $this->delta->getNominalSpan() * $this->delta->getLengthAtCenterline() / 10000; // Convert to m²
        $expected = round($expected, 1);
        $this->assertEquals($expected, $area, '', 0.01);
    }

    /**
     * Test the area calculation when units are in inches.
     */
    public function testCalculateAreaInInches()
    {
        $this->delta = new Delta(96.0, 100.0, 'in'); // Set units to inches
        $area = $this->delta->getArea();
        $expected = 0.5 * ($this->delta->getNominalSpan() / 12) * ($this->delta->getLengthAtCenterline() / 12); // Convert to ft²
        $expected = round($expected, 1);
        $this->assertEquals($expected, $area, '', 0.1);
    }

    /**
     * Test the line strength calculation for light winds.
     */
    public function testLineStrengthLightWinds()
    {
        $lineStrength = $this->delta->getLineStrengthLightWinds();
        $area = $this->delta->getArea();
        $expected = $area * 1;
        $expected = round($expected, 0);
        $this->assertEquals($expected, $lineStrength);
    }

    /**
     * Test the line strength calculation for moderate winds.
     */
    public function testLineStrengthModerateWinds()
    {
        $lineStrength = $this->delta->getLineStrengthModerateWinds();
        $area = $this->delta->getArea();
        $expected = $area * 2.2;
        $expected = round($expected, 0);
        $this->assertEquals($expected, $lineStrength);
    }

    /**
     * Test the line strength calculation for strong winds.
     */
    public function testLineStrengthStrongWinds()
    {
        $lineStrength = $this->delta->getLineStrengthStrongWinds();
        $area = $this->delta->getArea();
        $expected = $area * 4;
        $expected = round($expected, 0);
        $this->assertEquals($expected, $lineStrength);
    }

    /**
     * Test the keel front leg calculation.
     */
    public function testKeelFrontLeg()
    {
        $keelFrontLeg = $this->delta->getKeelFrontLeg();
        $depthOfFin = $this->delta->getDepthOfFin();
        $lengthOfFin = $this->delta->getLengthOfFin();
        $lengthAtCenterline = $this->delta->getLengthAtCenterline();
        $expected = sqrt(($depthOfFin ** 2) + (($lengthOfFin - (0.5 * $lengthAtCenterline)) ** 2));
        $expected = round($expected, 1);
        $this->assertEquals($expected, $keelFrontLeg, '', 0.01);
    }

    /**
     * Test the keel rear leg calculation.
     */
    public function testKeelRearLeg()
    {
        $keelRearLeg = $this->delta->getKeelRearLeg();
        $depthOfFin = $this->delta->getDepthOfFin();
        $lengthOfFin = $this->delta->getLengthOfFin();
        $lengthAtCenterline = $this->delta->getLengthAtCenterline();
        $expected = sqrt(($depthOfFin ** 2) + (($lengthOfFin - (0.5 * $lengthAtCenterline) - $lengthOfFin) ** 2));
        $expected = round($expected, 1);
        $this->assertEquals($expected, $keelRearLeg, '', 0.01);
    }

    /**
     * Test the calculation of sewing length.
     */
    public function testSewingLength()
    {
        $sewingLength = $this->delta->getSewingLength();
        $expected = (2 * $this->delta->getLengthOfLeadingEdge()) +
                    (2 * $this->delta->getNoseToLEspar()) +
                    $this->delta->getNominalSpan() +
                    (4.5 * $this->delta->getLengthAtCenterline()) +
                    (2 * $this->delta->getKeelRearLeg()) +
                    (2 * $this->delta->getKeelFrontLeg());
        $this->assertEquals($expected, $sewingLength);
    }

    /**
     * Test the weight unit (should default to 'kg').
     */
    public function testWeightUnit()
    {
        $weightUnit = $this->delta->getWeightUnit();
        $this->assertEquals('kg', $weightUnit); // Default unit is 'cm', so should return 'kg'
    }
}
