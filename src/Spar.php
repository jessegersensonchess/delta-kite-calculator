<?php

namespace Kite;

// Class to represent a Spar
class Spar
{
    public $name;
    public $sparDeflection; // The spar's deflection (e.g., 0.047 for Excel 10)
    public $passedInDeflection; // The deflection value passed via URL or form
    public $weight;
    public $scalefactor;
    // Constants
    public const LOAD = 908; // Load in grams
    public const LENGTH = 60.96; // Length in cm
    //
    // Properties of the Spar class
    public $innerDiameter; // Inner diameter in mm
    public $outerDiameter; // Outer diameter in mm
    public $density;       // Density in g/cm³ (grams per cubic centimeter)

    /**
     * Constructor to initialize a Spar object with its properties.
     *
     * @param string $name            The name of the spar.
     * @param float $sparDeflection   The spar's deflection in mm.
     * @param float $passedInDeflection The deflection value passed from the form or URL.
     * @param float $weight           The weight in g/m.
     * @param float $innerDiameter    The inner diameter in mm.
     * @param float $outerDiameter    The outer diameter in mm.
     * @param float $density          The density in g/cm³.
     */
    public function __construct($name, $sparDeflection, $passedInDeflection, $weight, $innerDiameter, $outerDiameter, $density)
    {
        $this->name = $name;
        $this->sparDeflection = $sparDeflection;
        $this->passedInDeflection = $passedInDeflection;
        $this->weight = $weight;
        $this->scalefactor = $this->calculateScaleFactor();
        $this->innerDiameter = $innerDiameter;
        $this->outerDiameter = $outerDiameter;
        $this->density = $density;
    }

    // Function to calculate the relative stiffness
    public function calculateRelativeStiffness()
    {
        // Calculate the relative stiffness ratio (deflection of the reference spar / the passed deflection)
        return $this->passedInDeflection / $this->sparDeflection;
    }

    // Function to calculate scale factor based on spar deflection and passed-in deflection
    public function calculateScaleFactor()
    {
        // Get the relative stiffness
        $relativeStiffness = $this->calculateRelativeStiffness();

        // Calculate the scale factor as the 0.25th power of the relative stiffness
        $scalefactor = pow($relativeStiffness, 0.25);

        // Format the scale factor to 2 decimal places and return as a percentage
        return number_format($scalefactor, 2, '.', ''); // Return as a percentage
    }


    /**
     * Calculate the weight of the spar tube for a 1-meter length.
     *
     * @return float The weight of the spar in grams per meter.
     */
    public function calculateRodWeight(): float
    {
        // Convert diameters from mm to meters
        $idInMeters = $this->innerDiameter / 1000;
        $odInMeters = $this->outerDiameter / 1000;

        // Calculate the radii
        $rInner = $idInMeters / 2;
        $rOuter = $odInMeters / 2;

        // Calculate the cross-sectional areas (π * r^2)
        $areaOuter = pi() * pow($rOuter, 2); // Area of the outer cylinder
        $areaInner = pi() * pow($rInner, 2); // Area of the inner cylinder

        // Calculate the volume of the material (1 meter length)
        $volume = $areaOuter - $areaInner;

        // Calculate the weight in grams (density in g/cm³, so multiply by 1000 to convert g/m³)
        $weight = $this->density * $volume * 1000000; // Result in grams per meter

        return $weight;
    }

    // Getters for the Spar properties (if needed)
    public function getInnerDiameter(): float
    {
        return $this->innerDiameter;
    }

    public function getOuterDiameter(): float
    {
        return $this->outerDiameter;
    }

    public function getDensity(): float
    {
        return $this->density;
    }



    /**
     * Calculate the second moment of area (I) for the spar's cross section.
     *
     * For a circular tube, this is calculated as:
     * I = π/64 * (D^4 - d^4)
     *
     * @return float The second moment of area (I) in mm⁴
     */
    public function calculateMomentOfArea(): float
    {
        $outerRadius = $this->outerDiameter / 2;
        $innerRadius = $this->innerDiameter / 2;

        // Second moment of area for a hollow circular section
        return (pi() / 64) * (pow($outerRadius, 4) - pow($innerRadius, 4));
    }




    /**
     * Calculate the deflection of the spar based on its load, length, and material properties.
     *
     * @return float The deflection in millimeters.
     */
    public function calculateDeflection(): float
    {
        // Constants
        $P = self::LOAD; // Load in grams
        $L = self::LENGTH; // Length in cm
        $E = 23000000; //23710000; // Young's modulus for composite carbon fiber in [units unknown :)]

        // Convert length from cm to mm (for consistency)
        $L = $L * 10; // Now in mm

        // Calculate the second moment of area (I) in mm⁴
        $I = $this->calculateMomentOfArea();

        // Convert load to N (1 gram = 0.00981 Newtons)
        $P = $P * 0.00981; // Now in Newtons (N)

        // Deflection formula: δ = (P * L^3) / (3 * E * I)
        $deflection = ($P * pow($L, 3)) / (3 * $E * $I);

        return $deflection; // Return deflection in mm
    }

}
