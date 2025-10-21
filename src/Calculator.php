<?php

namespace Kite;

class Calculator
{
    public const BOX_BACK_CM = 33.02;
    public const BOX_CUTOUT_CM = 12.7;
    public const BOX_LE_HALF_CM = 28.4;
    public const CELL_CM = 66.04;
    public const CENTRAL_RECTANGLE_CM = 39.5;
    public const CM_PER_INCH = 2.54;
    public const LE_SPAR_RATIO = 0.823;
    public const LONGERON_CM = 78.74;
    public const NOSE_TO_SPREADER_CM = 43.18;
    public const SCALE_BOX_WIDTH_CM = 50.8;
    public const SCALE_CELL_WIDTH_CM = 30.48;
    public const SCALE_HEIGHT_CM = 78.74;
    public const SCALE_LE_FACTOR = 1.41;
    public const SCALE_WING_WIDTH_CM = 78.74;
    public const SEAM_ALLOWANCE_CM = 2.54;
    public const SPREADER_CM = 91.44;

    // Helper method to calculate heightCm
    public static function getHeightCm(float $scaleFactor): float
    {
        return self::calculateHeight($scaleFactor);
    }

    // Helper method to calculate boxBackCm
    public static function getBoxBackCm(float $scaleFactor): float
    {
        return round($scaleFactor * self::BOX_BACK_CM, 1);
    }


    public static function convertCmToInches(float $cm): float
    {
        return $cm / self::CM_PER_INCH;
    }

    public static function calculateHeight(float $scalefactor): float
    {
        return round($scalefactor * self::SCALE_HEIGHT_CM, 1);
    }

    public static function calculateLeadingEdge(float $scalefactor): float
    {
        $height = self::calculateHeight($scalefactor);
        return round($height * self::SCALE_LE_FACTOR, 1);
    }

    public static function getSpars(float $scaleFactor): array
    {
        $leadingEdgeCm = self::calculateLeadingEdge($scaleFactor);
        $leadingEdgeSparCm = round(($leadingEdgeCm * self::LE_SPAR_RATIO) - ($scaleFactor * self::BOX_CUTOUT_CM), 1);
        $spreaderCm = $scaleFactor * self::SPREADER_CM;
        $longeronsCm = $scaleFactor * self::LONGERON_CM;
        $cellCm = $scaleFactor * self::CELL_CM;

        return [
            [        'label'     => 'leading edge',        'value'     => $leadingEdgeSparCm,        'precision' => 1,    ],
            [        'label'     => 'spreader',        'value'     => $spreaderCm,        'precision' => 1,    ],
            [        'label'     => 'longerons',        'value'     => $longeronsCm,        'precision' => 1,    ],
            [        'label'     => 'cell',        'value'     => $cellCm,        'precision' => 1,    ],
        ];
    }


    public static function getMeasurements(float $scaleFactor): array
    {
        $heightCm = self::getHeightCm($scaleFactor);
        $boxBackCm = self::getBoxBackCm($scaleFactor);

        $wingWidthCm = round($scaleFactor * self::SCALE_WING_WIDTH_CM, 1);
        $cellWidthCm = round($scaleFactor * self::SCALE_CELL_WIDTH_CM, 1);

        $totalWidthCm = $wingWidthCm * 2 + $cellWidthCm;

        $leadingEdgeCm = self::calculateLeadingEdge($scaleFactor);
        $distanceToLESparCm = round($leadingEdgeCm * (1 - self::LE_SPAR_RATIO), 2);
        $noseToSpreaderCm = round($scaleFactor * self::NOSE_TO_SPREADER_CM, 1);

        $boxWidthCm = round($scaleFactor * self::SCALE_BOX_WIDTH_CM + self::SEAM_ALLOWANCE_CM, 1);
        $boxCutoutCm = round($scaleFactor * self::BOX_CUTOUT_CM, 1);

        $boxWidthNoSewingCm = $scaleFactor * self::SCALE_BOX_WIDTH_CM;
        $boxLEHalfCm = round($scaleFactor * self::BOX_LE_HALF_CM, 2);
        $cellSideCm = round($boxWidthNoSewingCm / 2, 1);


        return [
    'heightCm' => [
        'label' => 'A height',
        'value' => $heightCm,
        'precision' => 1,
    ],
    'wingWidthCm' => [
        'label' => 'B width of wing*',
        'value' => $wingWidthCm,
        'precision' => 1,
    ],
    'cellWidthCm' => [
        'label' => 'C width of cell',
        'value' => $cellWidthCm,
        'precision' => 1,
    ],
    'totalWidthCm' => [
        'label' => 'H width of kite',
        'value' => $totalWidthCm,
        'precision' => 1,
    ],
    'leadingEdgeCm' => [
        'label' => 'D Leading edge (LE)',
        'value' => $leadingEdgeCm,
        'precision' => 1,
    ],
    'distanceToLESparCm' => [
        'label' => 'E nose to LE spar',
        'value' => $distanceToLESparCm,
        'precision' => 2,
    ],
    'noseToSpreaderCm' => [
        'label' => 'F nose to spreader',
        'value' => $noseToSpreaderCm,
        'precision' => 1,
    ],
    'boxWidthCm' => [
        'label' => 'J box width',
        'value' => $boxWidthCm,
        'precision' => 1,
    ],
    'boxCutoutCm' => [
        'label' => 'K box cut out depth',
        'value' => $boxCutoutCm,
        'precision' => 1,
    ],
    'boxLEHalfCm' => [
        'label' => 'box LE half',
        'value' => $boxLEHalfCm,
        'precision' => 2,
    ],
    'cellSideCm' => [
        'label' => 'cell side',
        'value' => $cellSideCm,
        'precision' => 1,
    ],
    'boxBackCm' => [
        'label' => 'width of box back',
        'value' => $boxBackCm,
        'precision' => 1,
    ],
];

    }

    public static function calculateArea(float $height, float $boxBack, float $centralRectangle): float
    {
        // Adjust formula based on your original code
        $heightMeters = $height;
        $boxBackMeters = $boxBack;
        $centralRectMeters = $centralRectangle;
        $precision = 1;

        $area = (($heightMeters * $heightMeters) + ($heightMeters * $boxBackMeters) + $centralRectMeters) / 929.03;
        return number_format($area, $precision);
    }

    public static function recommendedLine(float $area): int
    {
        return (int) round($area * 2);
    }

    public static function getSparDeflectionFactors(float $scale): array
    {
        return [
            ['label' => 'wing', 	'value'   	=> round(9.106 / pow($scale, 4), 3),	'precision' => 2],
            ['label' => 'spreader','value'	=> round(3.44 / pow($scale, 4), 3),	'precision' => 2],
            ['label' => 'wing (recommended)','value'		=> round(4.316 / pow($scale, 4), 3),	'precision' => 2],
            ['label' => 'spreader (recommended)', 	'value' 	=> round(1.711 / pow($scale, 4), 3),	'precision' => 2],
        ];
    }

    // getArea to use the helper method
    public static function getArea(float $scaleFactor): float
    {
        $heightCm = self::getHeightCm($scaleFactor);
        $boxBackCm = self::getBoxBackCm($scaleFactor);
        $areaSqFt = self::calculateArea($heightCm, $boxBackCm, self::CENTRAL_RECTANGLE_CM);

        return $areaSqFt;
    }


    // getLineRecommendation to use the helper method
    public static function getLineRecommendation(float $scaleFactor): array
    {
        $areaSqFt = self::getArea($scaleFactor);

        // Return the recommended line strength based on the area
        return [
            'recommendedLineStrength' => round($areaSqFt * 2),
        ];
    }

}
