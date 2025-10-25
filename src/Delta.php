<?php
 namespace Kite;

/**
 * Class Delta
 * Handles calculations for a kite's dimensions, wing spars, and other related metrics.
 */
class Delta
{
    /**
     * @var float $noseAngle The angle of the kite's nose.
     * @var float $lengthAtCenterline The length of the kite at the centerline.
     * @var string $units The units of measurement (e.g., 'cm' or 'in').
     */
    private $noseAngle;
    private $lengthAtCenterline;
    private $units;

    /**
     * Delta constructor.
     *
     * @param float $noseAngle Initial nose angle (default: 96.0 degrees).
     * @param float $lengthAtCenterline Initial length at centerline (default: 100.0 cm).
     * @param string $units Units of measurement (default: 'cm').
     */
    public function __construct($noseAngle = 96.0, $lengthAtCenterline = 100.0, $units = 'cm')
    {
        $this->noseAngle = $noseAngle;
        $this->lengthAtCenterline = $lengthAtCenterline;
        $this->units = $units;

        // Get input only if POST is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $this->handlePostData();
        }
    }

    /**
     * Get the kite's nose angle.
     *
     * @return float The nose angle.
     */
    public function getNoseAngle()
    {
        return $this->noseAngle;
    }

    /**
     * Get the kite's length at centerline.
     *
     * @return float The length at centerline.
     */
    public function getLengthAtCenterline()
    {
        return $this->lengthAtCenterline;
    }

    /**
     * Get the unit of measurement.
     *
     * @return string The unit of measurement ('cm' or 'in').
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Handles POST data to update the object's properties.
     */
    private function handlePostData()
    {
        $inputNoseAngle = filter_input(INPUT_POST, 'noseangle', FILTER_VALIDATE_FLOAT);
        $inputLength = filter_input(INPUT_POST, 'lengthatcenterline', FILTER_VALIDATE_FLOAT);
        $inputUnits = filter_input(INPUT_POST, 'units', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($inputNoseAngle !== false && $inputNoseAngle > 0 && $inputNoseAngle < 170) {
            $this->noseAngle = $inputNoseAngle;
        }

        if ($inputLength !== false && $inputLength > 0) {
            $this->lengthAtCenterline = $inputLength;
        }

        if (!empty($inputUnits)) {
            $this->units = $inputUnits;
        }
    }

    // --- CALCULATIONS ---

    /**
     * Calculate the half span of the kite.
     *
     * @return float The half span.
     */
    public function getHalfSpan()
    {
        return $this->calculateHalfSpan($this->noseAngle, $this->lengthAtCenterline);
    }

    /**
     * Get the nominal span of the kite (double the half span).
     *
     * @return float The nominal span.
     */
    public function getNominalSpan()
    {
        return $this->getHalfSpan() * 2;
    }

    /**
     * Calculate the length of the fin.
     *
     * @return float The length of the fin.
     */
    public function getLengthOfFin()
    {
        $finLength = 0.75 * $this->lengthAtCenterline;
        return round($finLength, 1);
    }

    /**
     * Calculate the length of the leading edge.
     *
     * @return float The length of the leading edge.
     */
    public function getLengthOfLeadingEdge()
    {
        $cosHalfAngle = cos(deg2rad($this->noseAngle / 2));
        return ($cosHalfAngle == 0) ? 0 : $this->lengthAtCenterline / $cosHalfAngle;

    }

    /**
     * Calculate the nominal length of the wing spars.
     *
     * @return float The nominal length of the wing spars.
     */
    public function getNominalLengthOfWingSpars()
    {
        $wingSparLength = $this->getLengthOfLeadingEdge() * 0.75;
        return round($wingSparLength, 1);
    }

    /**
     * Calculate the distance from the nose to the leading edge spar.
     *
     * @return float The distance from the nose to the leading edge spar.
     */
    public function getNoseToLEspar()
    {
        return $this->getLengthOfLeadingEdge() - $this->getNominalLengthOfWingSpars();
    }

    /**
     * Calculate the depth of the fin.
     *
     * @return float The depth of the fin.
     */
    public function getDepthOfFin()
    {
        $finDepth = 0.333 * $this->getHalfSpan();
        return round($finDepth, 1);
    }

    /**
     * Calculate the spreader strut attachment point.
     *
     * @return float The spreader strut attachment point.
     */
    public function getSpreaderStrutAttachmentPoint()
    {
        return (7 / 9) * $this->getNominalLengthOfWingSpars();
    }

    /**
     * Calculate the distance from the nose to the spreader.
     *
     * @return float The distance from the nose to the spreader.
     */
    public function getNoseToSpreader()
    {
        $cosHalfAngle = cos(deg2rad($this->noseAngle / 2));
        return $cosHalfAngle * ($this->getLengthOfLeadingEdge() - $this->getSpreaderStrutAttachmentPoint());
    }

    /**
     * Calculate the spreader length.
     *
     * @return string|float The spreader length, or 'N/A' if not applicable.
     */
    public function getSpreaderLength()
    {
        $cosHalfAngle = cos(deg2rad($this->noseAngle / 2));
        $noseToSpreader = $this->getNoseToSpreader();

        if ($cosHalfAngle == 0 || $noseToSpreader == 0) {
            return 'N/A';
        } else {
            return number_format(2 / ($cosHalfAngle / $noseToSpreader), 1, '.', '');
        }
    }

    /**
     * Calculate a second spreader length based on specific ratios.
     *
     * @return string|float The second spreader length, or 'N/A' if not applicable.
     */
    public function getSpreaderLength2()
    {
        $noseToSpreader = $this->getNoseToSpreader();
        if ($this->lengthAtCenterline == 0) {
            return 'N/A';
        } else {
            return number_format(($noseToSpreader / $this->lengthAtCenterline) * $this->getNominalSpan(), 1, '.', '');
        }
    }

    /**
     * Calculate the area of the kite.
     *
     * @return float The area of the kite.
     */
    public function getArea()
    {
        return $this->calculateArea($this->getNominalSpan(), $this->lengthAtCenterline, $this->units);
    }

    /**
     * Calculate the line strength for light winds.
     *
     * @return int The line strength for light winds.
     */
    public function getLineStrengthLightWinds()
    {
        return round($this->getArea() * 1, 0);
    }

    /**
     * Calculate the line strength for moderate winds.
     *
     * @return int The line strength for moderate winds.
     */
    public function getLineStrengthModerateWinds()
    {
        return round($this->getArea() * 2.2, 0);
    }

    /**
     * Calculate the line strength for strong winds.
     *
     * @return int The line strength for strong winds.
     */
    public function getLineStrengthStrongWinds()
    {
        return round($this->getArea() * 4, 0);
    }

    /**
     * Calculate the front leg of the keel.
     *
     * @return float The front leg of the keel.
     */
    public function getKeelFrontLeg()
    {
        return $this->calculateKeelLeg($this->getDepthOfFin(), $this->getLengthOfFin(), $this->lengthAtCenterline);
    }

    /**
     * Calculate the rear leg of the keel.
     *
     * @return float The rear leg of the keel.
     */
    public function getKeelRearLeg()
    {
        return $this->calculateKeelLeg($this->getDepthOfFin(), $this->getLengthOfFin(), $this->lengthAtCenterline, true);
    }

    /**
     * Calculate the total sewing length.
     *
     * @return float The total sewing length.
     */
    public function getSewingLength()
    {
        return $this->calculateSewing($this->lengthAtCenterline, $this->getNominalSpan(), $this->getLengthOfLeadingEdge(), $this->getNoseToLEspar(), $this->getKeelRearLeg(), $this->getKeelFrontLeg());
    }

    /**
     * Get the unit of weight measurement.
     *
     * @return string 'lb.' or 'kg' depending on the unit.
     */
    public function getWeightUnit()
    {
        return ($this->units === 'in') ? 'lb.' : 'kg';
    }

    // --- INTERNAL CALCULATION METHODS ---

    /**
     * Calculate the half span of the kite.
     *
     * @param float $noseAngle The nose angle.
     * @param float $lengthAtCenterline The length at centerline.
     * @return float The half span.
     */
    private function calculateHalfSpan(float $noseAngle, float $lengthAtCenterline): float
    {
        return tan(deg2rad($noseAngle / 2)) * $lengthAtCenterline;
    }

    /**
     * Calculate the area of the kite.
     *
     * @param float $span The span of the kite.
     * @param float $lengthAtCenterline The length at centerline.
     * @param string $units The units of measurement.
     * @return float The area.
     */
    public function calculateArea(float $span, float $lengthAtCenterline, string $units): float
    {
        $precision = 1;
        $areaM2 = 0.5 * $span * $lengthAtCenterline / 10000; // Convert cm² to m²

        if ($units === 'in') {
            $spanFt = $span / 12;
            $lengthAtCenterlineFt = $lengthAtCenterline / 12;
            return round($spanFt * $lengthAtCenterlineFt * 0.5, $precision);
        }

        return round($areaM2, $precision);
    }

    /**
     * Calculate the keel leg length.
     *
     * @param float $depthOfFin The depth of the fin.
     * @param float $lengthOfFin The length of the fin.
     * @param float $lengthAtCenterline The length at centerline.
     * @param bool $isRear Whether it's the rear leg (default: false).
     * @return float The length of the keel leg.
     */
    private function calculateKeelLeg(float $depthOfFin, float $lengthOfFin, float $lengthAtCenterline, bool $isRear = false): float
    {
        $distance = $lengthOfFin - (0.5 * $lengthAtCenterline);
        if ($isRear) {
            $distance -= $lengthOfFin;
        }
        $result = sqrt(($depthOfFin ** 2) + ($distance ** 2));
        return round($result, 1);
    }

    /**
     * Calculate the total sewing length.
     *
     * @param float $lengthAtCenterline The length at centerline.
     * @param float $nominalSpan The nominal span.
     * @param float $lengthOfLeadingEdge The length of the leading edge.
     * @param float $noseToLEspar The distance from nose to leading edge spar.
     * @param float $keelRearLeg The rear leg of the keel.
     * @param float $keelFrontLeg The front leg of the keel.
     * @return float The total sewing length.
     */
    private function calculateSewing(float $lengthAtCenterline, float $nominalSpan, float $lengthOfLeadingEdge, float $noseToLEspar, float $keelRearLeg, float $keelFrontLeg): float
    {
        return (
            (2 * $lengthOfLeadingEdge) +
            (2 * $noseToLEspar) +
            $nominalSpan +
            (4.5 * $lengthAtCenterline) +
            (2 * $keelRearLeg) +
            (2 * $keelFrontLeg)
        );
    }
}
