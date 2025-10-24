<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/src/Delta.php';

use Kite\Delta;

$delta = new Delta();

// Default values
$noseangle = 96.0;
$lengthatcenterline = 50.0;
$units = 'cm';

// Get input only if POST is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $input_noseangle = filter_input(INPUT_POST, 'noseangle', FILTER_VALIDATE_FLOAT);
    $input_length = filter_input(INPUT_POST, 'lengthatcenterline', FILTER_VALIDATE_FLOAT);
    $input_units = filter_input(INPUT_POST, 'units', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Initialize and validate input
    if ($input_noseangle !== false && $input_noseangle > 0 && $input_noseangle < 170) {
        $noseangle = $input_noseangle;
    }

    if ($input_length !== false && $input_length > 0) {
        $lengthatcenterline = $input_length;
    }

    if (!empty($input_units)) {
        $units = $input_units;
    }

    // Instantiate the Delta class with user input values
    $delta = new Delta($noseangle, $lengthatcenterline, $units);
    
    // Extract the calculated values from the Delta class
    $halfspan = $delta->getHalfSpan();
    $nominalspan = $delta->getNominalSpan();
    $lengthoffin = $delta->getLengthOfFin();
    $depthoffin = $delta->getDepthOfFin();
    $keelfrontleg = round($delta->getKeelFrontLeg(), 1);
    $keelrearleg = $delta->getKeelRearLeg();
    $lengthofleadingedge = $delta->getLengthOfLeadingEdge();
    $nominallengthofwingspars = $delta->getNominalLengthOfWingSpars();
    $nosetoLEspar = $delta->getNoseToLEspar();
    $spreaderstrutattachmentpoint = $delta->getSpreaderStrutAttachmentPoint();
    $area = $delta->getArea();
    $line_strength_light_winds = $delta->getLineStrengthLightWinds();
    $line_strength_moderate_winds = $delta->getLineStrengthModerateWinds();
    $line_strength_strong_winds = $delta->getLineStrengthStrongWinds();
    $weight_unit = $delta->getWeightUnit();
} else {
    // Default values
    $noseangle = 96.0;
    $lengthatcenterline = 50.0;
    $units = 'cm';
    $delta = new Delta($noseangle, $lengthatcenterline, $units);
    $halfspan = $delta->getHalfSpan();
    $nominalspan = $delta->getNominalSpan();
    $lengthoffin = round($delta->getLengthOfFin(), 1);
    $depthoffin = $delta->getDepthOfFin();
    $keelfrontleg = round($delta->getKeelFrontLeg(), 1);
    $keelrearleg = round($delta->getKeelRearLeg(), 1);
    $lengthofleadingedge = $delta->getLengthOfLeadingEdge();
    $nominallengthofwingspars = $delta->getNominalLengthOfWingSpars();
    $nosetoLEspar = $delta->getNoseToLEspar();
    $spreaderstrutattachmentpoint = $delta->getSpreaderStrutAttachmentPoint();
    $area = $delta->getArea();
    $line_strength_light_winds = $delta->getLineStrengthLightWinds();
    $line_strength_moderate_winds = $delta->getLineStrengthModerateWinds();
    $line_strength_strong_winds = $delta->getLineStrengthStrongWinds();
    $weight_unit = $delta->getWeightUnit();
}
?>

<html><body><title>Delta kite calculator</title>
<style>
/*	p {text-align:center; margin:0.5em 0;}
	body {margin:0;width:900px;margin-left:auto;margin-right:auto}
	h1 {text-align:center;margin-top:0em; margin-bottom:0.0em}
	h3 {margin-bottom:0.2em;margin-top:1em}
	table{border-collapse:collapse; padding 1em; width 300px}
	table,th, td {border: 1px solid #99661E;}
	form {margin:0;padding:0}
	input:focus {background:#ffc}
	.tdlabel {text-align:center}
	#lengthatcenterline {width:50px;float:left}
	#submitbutton {padding:2px 10px;margin:5px;font-size:1.1em}
	#selectunits {padding:0 1em;font-size:1em}
	input {}
	#form {padding:5px;text-align:center;background:#ffc}
	#form table {background:#FFEEFF}
	*/
	#plan {float:left;width:400px}
	#dimensions {width:410px;margin-left:2em;margin-right:10px;float:left}
	#wrapper {width:100%}
</style>
<style>
	body {margin:0;width:900px;margin-left:auto;margin-right:auto}
body {
    font-family: 'Arial', sans-serif; /* Cleaner and modern font */
    margin: 0;
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
    background-color: #f9f9f9;
    color: #000;
}

/* Header Styling */
h1 {
    text-align: center;
    margin-top: 0;
    margin-bottom: 0.5em;
    font-size: 2.5em;
    color: #000;
}

h3 {
    margin-top: 0.5em;
    margin-bottom: 0.5em;
    font-size: 1.2em;
    color: #000;
    color: #000;
}

/* Paragraph Styling */
p {
    text-align: center;
    margin: 0.2em 0;
    font-size: 1.1em;
}

/* Form Layout */
form {
    background-color: #fff;
    margin: 0em auto;
}

input[type="text"] {
    padding: 2px;
    margin: 0.2em 0;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 5px;
}

input:focus {
    background-color: #fff7d8;
    border-color: #ffa500;
    outline: none;
}

select {
    padding: 4px;
    margin: 0.5em 0;
    font-size: 1.1em;
    border: 1px solid #ddd;
    border-radius: 5px;
}

input[type="submit"] {
    padding: 12px 20px;
    margin-top: 10px;
    font-size: 1.2em;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

/* Label Styling */
.tdlabel {
    font-weight: bold;
    text-align: center;
}

/* Form Section Styling */
#form {
    padding: 1em;
    background-color: #FFFAF0;
    border-radius: 8px;
    text-align: center;
}

#form table {
    background-color: #F8F8F8;
    border-radius: 8px;
    border-spacing: 0;
}

/* Miscellaneous */
input[type="text"],
select {
    box-sizing: border-box;
}
</style>
<body>
<h1>Delta kite calculator</h1>
<p>Calculates a <a href="http://www.deltakites.com/plan.html">Dan Leigh standard delta</a>. Enter a nose angle and a center line length, then click 'calculate'.</p>

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
        <table>
            <tr><td class="tdlabel">C</td><td>Length at centerline</td><td><?= htmlspecialchars((string)$lengthatcenterline) ?></td></tr>
            <tr><td class="tdlabel">S</td><td>Nominal span</td><td><?= number_format($nominalspan, 1) ?></td></tr>
            <tr><td class="tdlabel">S/2</td><td>Half span</td><td><?= number_format($halfspan, 1) ?></td></tr>
            <tr><td class="tdlabel">F</td><td>Length of fin</td><td><?= number_format($lengthoffin, 1) ?></td></tr>
            <tr><td class="tdlabel">D</td><td>Depth of fin</td><td><?= number_format($depthoffin, 1) ?></td></tr>
            <tr><td class="tdlabel"></td><td>Keel front leg</td><td><?= htmlspecialchars($keelfrontleg) ?></td></tr>
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
	<p>Light winds = <?= $line_strength_light_winds ?> <?= $weight_unit ?> line</p>
        <p>Moderate winds = <?= $line_strength_moderate_winds ?> <?= $weight_unit ?> line</p>
        <p>Strong winds = <?= $line_strength_strong_winds ?> <?= $weight_unit ?> line</p>
    </div>
</div>

	<h3>Resources</h3>
	<a href="http://www.deltakites.com/plan.html">Plan and assembly instructions</a>
	<br/>
	<a href="http://www.jesseo.com/kites/">Other kites deltas</a>
	<br/>
	<a href="https://github.com/jessegersensonchess/delta-kite-delta">Source code</a>
	<br/>
	<br/>
	</div>
	<div id="plan">
		<img src="dplan86.gif">
	</div>
</div>
</body></html>
