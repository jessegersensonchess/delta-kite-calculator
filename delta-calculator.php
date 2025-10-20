<?php
declare(strict_types=1);

// Show all errors during development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Default values
$noseangle = 96.0;
$lengthatcenterline = 100.0;
$units = 'cm';

// Get input only if POST is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $input_noseangle = filter_input(INPUT_POST, 'noseangle', FILTER_VALIDATE_FLOAT);
    $input_length = filter_input(INPUT_POST, 'lengthatcenterline', FILTER_VALIDATE_FLOAT);
    $input_units = filter_input(INPUT_POST, 'units', FILTER_SANITIZE_STRING);

    if ($input_noseangle !== false && $input_noseangle > 0 && $input_noseangle < 170) {
        $noseangle = $input_noseangle;
    }

    if ($input_length !== false && $input_length > 0) {
        $lengthatcenterline = $input_length;
    }

    if (!empty($input_units)) {
        $units = $input_units;
    }
}

// --- CALCULATIONS ---
$halfspan = calculateHalfSpan($noseangle, $lengthatcenterline);
$nominalspan = $halfspan * 2;

$lengthoffin = 0.75 * $lengthatcenterline;
$cos_half_angle = cos(deg2rad($noseangle / 2));
$lengthofleadingedge = ($cos_half_angle == 0) ? 0 : $lengthatcenterline / $cos_half_angle;

$nominallengthofwingspars = $lengthofleadingedge * 0.75;
$nosetoLEspar = $lengthofleadingedge - $nominallengthofwingspars;
$depthoffin = 0.333 * $halfspan;
$spreaderstrutattachmentpoint = (7 / 9) * $nominallengthofwingspars;

$nosetospreader = $cos_half_angle * ($lengthofleadingedge - $spreaderstrutattachmentpoint);

// Protect division
if ($cos_half_angle == 0 || $nosetospreader == 0) {
    $spreaderlength = 'N/A';
} else {
    $spreaderlength = number_format(2 / ($cos_half_angle / $nosetospreader), 1, '.', '');
}

if ($lengthatcenterline == 0) {
    $spreaderlength2 = 'N/A';
} else {
    $spreaderlength2 = number_format(($nosetospreader / $lengthatcenterline) * $nominalspan, 1, '.', '');
}

// Area (sq ft or m^2)
$area = calculateArea($halfspan, $lengthatcenterline, $units);

// Line strengths (lbs)
$line_strength_light_winds = number_format($area * 1, 0, '.', '');
$line_strength_moderate_winds = number_format($area * 2.2, 0, '.', '');
$line_strength_strong_winds = number_format($area * 4, 0, '.', '');

// Keel legs
$keelfrontleg = calculateKeelLeg($depthoffin, $lengthoffin, $lengthatcenterline);
$keelrearleg = calculateKeelLeg($depthoffin, $lengthoffin, $lengthatcenterline, true);

// Total sewing length
$sewing = calculateSewing($lengthatcenterline, $nominalspan, $lengthofleadingedge, $nosetoLEspar, $keelrearleg, $keelfrontleg);

// Format keel lengths for display
$keelfrontleg = number_format($keelfrontleg, 1, '.', '');
$keelrearleg = number_format($keelrearleg, 1, '.', '');

// --- FUNCTIONS ---
function calculateHalfSpan(float $noseangle, float $lengthatcenterline): float {
    // half-span = tan(θ/2) × length at centerline
    return tan(deg2rad($noseangle / 2)) * $lengthatcenterline;
}

function calculateArea(float $halfspan, float $lengthatcenterline, string $units): float {
    return match ($units) {
        'in' => round(($halfspan / 12) * ($lengthatcenterline / 12), 1),
        default => round(($halfspan / 30.48) * ($lengthatcenterline / 30.48), 1),
    };
}

function calculateKeelLeg(float $depthoffin, float $lengthoffin, float $lengthatcenterline, bool $isRear = false): float {
    $distance = $lengthoffin - (0.5 * $lengthatcenterline);
    if ($isRear) {
        $distance -= $lengthoffin;
    }
    return sqrt(($depthoffin ** 2) + ($distance ** 2));
}

function calculateSewing(float $lengthatcenterline, float $nominalspan, float $lengthofleadingedge, float $nosetoLEspar, float $keelrearleg, float $keelfrontleg): float {
    return (
        (2 * $lengthofleadingedge) +
        (2 * $nosetoLEspar) +
        $nominalspan +
        (4.5 * $lengthatcenterline) +
        (2 * $keelrearleg) +
        (2 * $keelfrontleg)
    );
}
?>








<html><body><title>Delta kite calculator</title>
<style>
	p {text-align:center}
	body {margin:0;width:900px;margin-left:auto;margin-right:auto}
	h1 {text-align:center;margin-bottom:5px}
	h3 {margin-bottom:3px;margin-top:15px}
	table{border-collapse:collapse}
	table,th, td {border: 1px solid #99661E}
	form {margin:0;padding:0}
	table {width:300px}
	input:focus {background:#ffc}
	.tdlabel {text-align:center}
	#lengthatcenterline {width:50px;float:left}
	#submitbutton {padding:2px 10px;margin:5px;font-size:1.1em}
	#selectunits {padding:0 1em;font-size:1em}
	#dimensions {width:410px;margin-left:2em;margin-right:10px;float:left}
	#plan {float:left;width:400px}
	input {}
	#wrapper {width:100%}
	#form {padding:5px;text-align:center;background:#ffc}
	#form table {background:#FFEEFF}
</style>
<body>
<h1>Delta kite calculator</h1>
<p>Calculates a <a href="http://www.deltakites.com/plan.html">Dan Leigh standard delta</a>. Nose angles range from 86 to 120 degrees, typical is 96.</p>
<p>How to use this form: Enter a nose angle and a center line length, then click 'calculate'.</p>

<div id="form">
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <table align="center">
            <tr>
                <td>Nose angle</td>
                <td>
                    <input id="noseangle" type="text" name="noseangle" value="<?= htmlspecialchars((string)$noseangle) ?>">
                </td>
            </tr>
            <tr>
                <td nowrap>Length at centerline (C)</td>
                <td nowrap>
                    <input type="text" id="lengthatcenterline" name="lengthatcenterline" value="<?= htmlspecialchars((string)$lengthatcenterline) ?>">

                    <select id="selectunits" name="units">
                        <option value="cm" <?= $units === 'cm' ? 'selected' : '' ?>>cm</option>
                        <option value="in" <?= $units === 'in' ? 'selected' : '' ?>>in.</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <input id="submitbutton" type="submit" name="submit" value="Calculate">
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="wrapper">
    <div id="dimensions">
        <h3>Dimensions</h3>
        <table width="100%">
            <tr><td class="tdlabel">C</td><td>Length at centerline</td><td><?= htmlspecialchars((string)$lengthatcenterline) ?></td></tr>
            <tr><td class="tdlabel">S</td><td>Nominal span</td><td><?= number_format($nominalspan, 1) ?></td></tr>
            <tr><td class="tdlabel">S/2</td><td>Half span</td><td><?= number_format($halfspan, 1) ?></td></tr>
            <tr><td class="tdlabel">F</td><td>Length of fin</td><td><?= number_format($lengthoffin, 1) ?></td></tr>
            <tr><td class="tdlabel">D</td><td>Depth of fin</td><td><?= number_format($depthoffin, 1) ?></td></tr>
            <tr><td class="tdlabel"></td><td>Length of fin side (keel front leg)</td><td><?= htmlspecialchars($keelfrontleg) ?></td></tr>
            <tr><td class="tdlabel"></td><td>Keel rear leg</td><td><?= htmlspecialchars($keelrearleg) ?></td></tr>
            <tr><td class="tdlabel">B</td><td>Towing point</td><td><?= number_format(0.5 * $lengthatcenterline, 1) ?></td></tr>
            <tr><td class="tdlabel">LE</td><td>Length of leading edge</td><td><?= number_format($lengthofleadingedge, 1) ?></td></tr>
            <tr><td class="tdlabel">L</td><td>Length of wing spars</td><td><?= number_format($nominallengthofwingspars, 1) ?></td></tr>
            <tr><td class="tdlabel"></td><td>Nose to LE spar</td><td><?= number_format($nosetoLEspar, 1) ?></td></tr>
            <tr><td class="tdlabel" nowrap>to SA</td><td>Spreader attachment point</td><td><?= number_format($spreaderstrutattachmentpoint, 1) ?></td></tr>
        </table>

        <h3>Sail area</h3>
        <p>
            <?= htmlspecialchars((string)$area) ?> <?= $units === 'in' ? 'sq ft' : 'm²' ?>
        </p>

        <h3>Recommended line strength</h3>
        <p>Light winds = <?= $line_strength_light_winds ?> lb. line</p>
        <p>Moderate winds = <?= $line_strength_moderate_winds ?> lb. line</p>
        <p>Strong winds = <?= $line_strength_strong_winds ?> lb. line</p>
    </div>
</div>



		<h3>Resources</h3>
		<a href="http://www.deltakites.com/plan.html">Plan and assembly instructions</a>
		<br/>
		<a href="http://www.jesseo.com/kites/">Other kites calculators</a>
		<br/>
		<a href="https://github.com/jessegersensonchess/delta-kite-calculator">Source code</a>
		<br/>
		<br/>
	</div>
	<div id="plan">
		<img src="dplan86.gif">
	</div>
</div>
</body></html>
