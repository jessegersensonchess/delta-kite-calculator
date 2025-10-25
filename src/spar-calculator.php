<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head><meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
   <title>Carbon and Fiberglass spar scaling calculator</title>
	<style type="text/css">
	body {margin:0;width:900px;margin-left:auto;margin-right:auto;}
	h1 {text-align:center;margin-bottom:5px;margin-top:0}
	h3 {}
	input {width:50px;}
	input:focus {border:2px solid green;background:pink}
	table { border-collapse:collapse; }
	table,th, td {border: 1px solid black;padding:5px;}
	table {}
	#comparedspars {margin-top:1em;}


.spar-table {
    display: flex;
    flex-direction: column;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 8px;
    overflow: hidden;
    font-family: Arial, sans-serif;
}

.spar-header, .spar-row {
    display: flex;
    padding: 10px;
    border-bottom: 1px solid #f1f1f1;
}

.spar-header {
    font-weight: bold;
    background-color: #f9f9f9;
}

.spar-cell {
    flex: 1;
    padding: 5px 10px;
    text-align: left;
    overflow: hidden;
    text-overflow: ellipsis;
}

.spar-row:last-child {
    border-bottom: none;
}

.spar-cell:first-child {
    font-weight: normal;
}

	</style>

<?php
// Show all errors during development
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Include the Spar class
require_once __DIR__ . '/src/Spar.php';

use Kite\Spar;

// Function to calculate relative stiffness
function calculateRelativeStiffness($sparDeflection, $passedInDeflection)
{
    if ($passedInDeflection == 0) {
        return 0; // Avoid division by zero
    }

    // Relative stiffness formula
    return $sparDeflection / $passedInDeflection;
}

// Function to calculate scale factor from relative stiffness
function calculateScaleFactor($relativeStiffness)
{
    if ($relativeStiffness == 0) {
        return 0; // If relative stiffness is zero, scale factor is zero
    }

    // Scale factor formula (0.25th power of relative stiffness)
    return number_format(pow($relativeStiffness, 0.25), 2, '.', '');
}

function calculateDeflection($material_elasticity, $outside_diameter, $inside_diameter)
{
    // Perform the calculation based on the provided formula
    $deflection = abs(
        $material_elasticity /
            (
                (
                    0.049087385 *
                            (
                                (pow($outside_diameter, 4)) -
                                    (pow($inside_diameter, 4))
                            )
                )
            ) / 48
    );

    // Return the calculated deflection
    return $deflection;
}

// Default passed-in deflection from URL or form
$deflection = isset($_POST['deflection']) && is_numeric($_POST['deflection'])
    ? (float) $_POST['deflection']
    : 1.0; // or whatever default makes sense




$deflection = $_GET['wingspar'];
if (isset($_POST['submit'])) {
    $deflection = $_POST['deflection'];
    // todo: refactor
    $carbonOneWeight = number_format(4.5 * (pow(.5 * $_POST['carbonOneOD'], 2) - pow(.5 * $_POST['carbonOneID'], 2)), 1, '.', '');
    $carbonTwoWeight = number_format(4.5 * (pow(.5 * $_POST['carbonTwoOD'], 2) - pow(.5 * $_POST['carbonTwoID'], 2)), 1, '.', '');

    $fiberglassOneWeight = number_format(6.277 * (pow(.5 * $_POST['fiberglassOneOD'], 2) - pow(.5 * $_POST['fiberglassOneID'], 2)), 1, '.', '');
    $fiberglassTwoWeight = number_format(6.277 * (pow(.5 * $_POST['fiberglassTwoOD'], 2) - pow(.5 * $_POST['fiberglassTwoID'], 2)), 1, '.', '');

    $carbonOneID = $_POST['carbonOneID'] / 25.4;
    $carbonOneOD = $_POST['carbonOneOD'] / 25.4;
    $carbonTwoID = $_POST['carbonTwoID'] / 25.4;
    $carbonTwoOD = $_POST['carbonTwoOD'] / 25.4;

    $fiberglassOneID = $_POST['fiberglassOneID'] / 25.4;
    $fiberglassOneOD = $_POST['fiberglassOneOD'] / 25.4;
    $fiberglassTwoID = $_POST['fiberglassTwoID'] / 25.4;
    $fiberglassTwoOD = $_POST['fiberglassTwoOD'] / 25.4;
    $fiveid = $_POST['fiveid'] / 25.4;
    $fiveod = $_POST['fiveod'] / 25.4;

    // Create an instance of the Spar class
    if ($deflection != null) {
        $spars = [
            new Spar('2mm carbon rod', 21.85, $deflection, 1, 1, 5, 1.6),
            new Spar('2.5mm carbon rod', 9.1, $deflection, 1, 1, 5, 1.6),
            new Spar('3mm carbon rod', 4.316, $deflection, 1, 1, 5, 1.6),
            new Spar('4mm carbon rod', 1.366, $deflection, 1, 1, 5, 1.6),
            new Spar('4/2.5mm carbon', 1.711, $deflection, 1, 1, 5, 1.6),
            new Spar('5/3mm carbon tube', 0.64, $deflection, 1, 1, 5, 1.6),
            new Spar('6/4mm carbon tube', 0.34, $deflection, 1, 1, 5, 1.6),
            new Spar('Skyshark P 90', 0.565, $deflection, 1, 1, 5, 1.6),
            new Spar('Skyshark P 100', 0.413, $deflection, 1, 1, 5, 1.6),
            new Spar('Skyshark P 400', 0.302, $deflection, 1, 1, 5, 1.6),
            new Spar('Excel 6 fg tube blue', 1.079, $deflection, 1, 1, 5, 1.6),
            new Spar('Excel 8 fg tube grey', 0.272, $deflection, 1, 1, 5, 1.6),
            new Spar('Excel 9 fg tube red', 0.118, $deflection, 1, 1, 5, 1.6),
            new Spar('Excel 10 fg tube white', 0.047, $deflection, 1, 1, 5, 1.6),
            new Spar('3mm Wood', 35.3288, $deflection, 1, 1, 5, 1.6),
            new Spar('5mm Wood', 4.5786, $deflection, 1, 1, 5, 1.6),
            new Spar('6mm Wood', 2.2081, $deflection, 1, 1, 5, 1.6),
            new Spar('8mm Wood', 0.6986, $deflection, 1, 1, 5, 1.6),
            new Spar('1/8" Wood', 28.1604, $deflection, 1, 1, 5, 1.6),
            new Spar('3/16" Wood', 5.5626, $deflection, 1, 1, 5, 1.6),
            new Spar('1/4" wood', 1.7600, $deflection, 1, 1, 5, 1.6),
            new Spar('5/16" wood', 0.7209, $deflection, 1, 1, 5, 1.6),
        ];


        $carbon_elasticity = 0.00197889;
        $fiberglass_elasticity = 0.004836093;
        $wood_elasticity = 0.016199078;

        // carbon, first row of input in html form
        if ($carbonOneOD != null) {
            $carbonOneDeflection = calculateDeflection($carbon_elasticity, $carbonOneOD, $carbonOneID);
            $carbonOneRelativeStiffness = $deflection / $carbonOneDeflection;
        }

        // carbon, second row of input in html form
        if ($carbonTwoOD != null) {
            $carbonTwoDeflection = calculateDeflection($carbon_elasticity, $carbonTwoOD, $carbonTwoID);
            $carbonTwoRelativeStiffness = $deflection / $carbonTwoDeflection;
        }

        // fiberglass, third row of input in html form
        if ($fiberglassOneOD != null) {
            $fiberglassOneDeflection = calculateDeflection($fiberglass_elasticity, $fiberglassOneOD, $fiberglassOneID);
            $fiberglassOneRelativeStiffness = $deflection / $fiberglassOneDeflection;
        }

        // fiberglass, fourth row of input in html form
        if ($fiberglassTwoOD != null) {
            $fiberglassTwoDeflection = calculateDeflection($fiberglass_elasticity, $fiberglassTwoOD, $fiberglassTwoID);
            $fiberglassTwoRelativeStiffness = $deflection / $fiberglassTwoDeflection;
        }
        // ramen wood, fifth row of input in html form
        if ($fiveod != null) {
            $fivedeflection = calculateDeflection($wood_elasticity, $fiveod, $fiveid);
            $relstiffnessfive = $deflection / $fivedeflection;
        }


    }


    if ($deflection != null) {
        $carbonOneScaleFactor = calculateScaleFactor($carbonOneRelativeStiffness);
        $carbonTwoScaleFactor = calculateScaleFactor($carbonTwoRelativeStiffness);
        $fiberglassOneScaleFactor = calculateScaleFactor($fiberglassOneRelativeStiffness);
        $fiberglassTwoScaleFactor = calculateScaleFactor($fiberglassTwoRelativeStiffness);
        $scalefactorfive = calculateScaleFactor($relstiffnessfive);

    }
}

?>
	</head><body>
	<h1>Carbon and Fiberglass spar scaling calculator</h1>
<p>This calculator helps compare relative flexibility of spars. To use it, enter the distance, in inches, a 908-gram weight deflects when hung from the center of a 26-inch (66 cm) spar.</p>

<p>To begin, enter a deflection value and click Calculate. You can also optionally enter the outside diameter (O.D.) and inside diameter (I.D.) of the spars being compared. For solid rods, enter 0 for the inside diameter.</p>

<p>Note: The elasticity moduli used for "carbon" (17.7634892268) and "fiberglass" (7.2686776573) are not fixed physical constants. This calculator is intended as a guide and should be used in conjunction with real-world testing.</p>

<p>This page is written in PHP by Jesse Gersenson (see <a href="http://www.jesseo.com/kites/">kite calculators</a>) based on equations from <a href="http://www.nic.fi/~sos/spars/spars.htm">Dave Lord's Scale Factor</a> and a <a href="http://www.jesseo.com/kites/Spar-Deflection-and-Comparison-Chart-simon-p-craft%20.xls">spreadsheet by Simon Craft</a></p>

	<div style="width:35%;margin-right:2em;text-align:center;float:left;">
	<h3>Spar deflection</h3><div style="background:#ffc;border:1px #ddd dashed">
	<form style="margin:0;padding:1em;" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	Enter deflection <input type="text" value="<?php echo htmlentities($deflection);?>" name="deflection" />
	
<table id="comparedspars" align="center"><tr><td>Comparison spar</td><td>O.D mm</td><td>I.D mm</td></tr>
<tr><td>Carbon</td><td><input type="text" value="<?php echo htmlentities($carbonOneOD * 25.4);?>" name="carbonOneOD" /></td><td><input value="<?php echo htmlentities($carbonOneID * 25.4);?>" type="text" name="carbonOneID" /></td></tr>
<tr><td>Carbon</td><td><input type="text" value="<?php echo htmlentities($carbonTwoOD * 25.4);?>" name="carbonTwoOD" /></td><td><input value="<?php echo htmlentities($carbonTwoID * 25.4);?>" type="text" name="carbonTwoID" /></td></tr>
<tr><td>Fiberglass</td><td><input type="text" value="<?php echo htmlentities($fiberglassOneOD * 25.4);?>" name="fiberglassOneOD" /></td><td><input value="<?php echo htmlentities($fiberglassOneID * 25.4);?>" type="text" name="fiberglassOneID" /></td></tr>
<tr><td>Fiberglass</td><td><input type="text" value="<?php echo htmlentities($fiberglassTwoOD * 25.4);?>" name="fiberglassTwoOD" /></td><td><input value="<?php echo htmlentities($fiberglassTwoID * 25.4);?>" type="text" name="fiberglassTwoID" /></td></tr>
<tr><td>Ramen wood</td><td><input type="text" value="<?php echo htmlentities($fiveod * 25.4);?>" name="fiveod" /></td><td><input value="<?php echo htmlentities($fiveid * 25.4);?>" type="text" name="fiveid" /></td></tr>
</table>

<input type="submit" name="submit" style="width:auto;font-size:1em;margin:1em;" value="calculate" />
	</form>
	</div>	</div>

<div style="float:left;width:60%;">
<h3>Calculated scaling factors of comparision spars</h3>
<table>
    <tr><td>spar (O.D/I.D)</td><td>weight (g/m)</td><td>deflection (in.)</td><td><b>scale factor</b></td></tr>
    <tr><td nowrap><?php echo htmlentities($_POST['carbonOneOD'] . " mm / " . $_POST['carbonOneID'] . " mm"); ?> carbon</td><td><?php echo htmlentities($carbonOneWeight . "");?></td><td><?php echo htmlentities(number_format($carbonOneDeflection, 2, '.', '') . "");?></td><td><b><?php echo htmlentities($carbonOneScaleFactor);?></b></td></tr>
    <tr><td><?php echo htmlentities($_POST['carbonTwoOD'] . " mm / " . $_POST['carbonTwoID'] . " mm"); ?> carbon</td><td><?php echo htmlentities($carbonTwoWeight . "");?></td><td><?php echo htmlentities(number_format($carbonTwoDeflection, 2, '.', '') . "");?></td><td><b><?php echo htmlentities($carbonTwoScaleFactor);?></b></td></tr>
    <tr><td><?php echo htmlentities($_POST['fiberglassOneOD'] . " mm / " . $_POST['fiberglassOneID'] . " mm"); ?> fiberglass</td><td><?php echo htmlentities($fiberglassOneWeight . "");?></td><td><?php echo htmlentities(number_format($fiberglassOneDeflection, 2, '.', '') . "");?></td><td><b><?php echo htmlentities($fiberglassOneScaleFactor);?></b></td></tr>
    <tr><td><?php echo htmlentities($_POST['fiberglassTwoOD'] . " mm / " . $_POST['fiberglassTwoID'] . " mm"); ?> fiberglass</td><td><?php echo htmlentities($fiberglassTwoWeight . "");?></td><td><?php echo htmlentities(number_format($fiberglassTwoDeflection, 2, '.', '') . "");?></td><td><b><?php echo htmlentities($fiberglassTwoScaleFactor);?></b></td></tr>
    <tr><td><?php echo htmlentities($_POST['fiveod'] . " mm / " . $_POST['fiveid'] . " mm"); ?> ramen</td><td><?php echo "";?></td><td><?php echo htmlentities(number_format($fivedeflection, 2, '.', '') . "");?></td><td><b><?php echo htmlentities($scalefactorfive);?></b></td></tr>
</table>

</div>

<?php

// Output each spar's details
foreach ($spars as $spar) {
    // Display row header only if it's the first row (i.e., when row_count == 0)
    if ($spar === reset($spars)) {
        echo "<div class='spar-table'>";
        echo "<h2>Compare deflection known reference spars</h2>";
        echo "<div class='spar-header'>";
        echo "<div class='spar-cell'>name</div>";
        echo "<div class='spar-cell'>scale factor</div>";
        echo "<div class='spar-cell'>spar deflection</div>";
        echo "</div>";
    }
    echo "<div class='spar-row'>";
    echo "<div class='spar-cell'>" . htmlentities($spar->name) . "</div>";
    echo "<div class='spar-cell'>" . htmlentities($spar->scalefactor) . "</div>";
    echo "<div class='spar-cell'>" . htmlentities($spar->sparDeflection) . "</div>";
    echo "</div>";
}

echo "</div>";


?>

  </body></html>
