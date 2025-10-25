<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
// Show all errors during development
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/src/Calculator.php';

use Kite\Calculator;

$calculator = new Calculator();

$scaleFactor = isset($_POST['scaleFactor']) && is_numeric($_POST['scaleFactor'])
    ? (float) $_POST['scaleFactor']
    : 1.0; // or whatever default makes sense

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head><meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> <title>DT Delta kite calculator</title>
	<style type="text/css">
	body {margin:0;width:900px;margin-left:auto;margin-right:auto;}
	h1 {text-align:center;margin-bottom:5px;font-size:14px;display:inline;}
	h3 {margin-bottom:3px;margin-top:15px;}
	
	table
	 {
	 border-collapse:collapse;
	 }
	 table,th, td
	 {
	 border: 1px solid black;
	 }
	
	table {width:300px;}</style>

	
		<style type="text/css">#atscale {width: <?php echo htmlentities($scaleFactor * 100);?>%}</style>
	<?php
    if (isset($_POST['submit'])) {
        $scaleFactor = $_POST['scaleFactor'];
    }
?>

	</head><body>	
	
	<div style="padding:5px;text-align:center;background:#ffc"><h1>DT Delta kite calculator</h1> enter a number and click 'calculate'. <form style="margin:0;padding:0;" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	   <input type="text" name="scaleFactor" />
	   <input type="submit" name="submit" value="calculate" />
	</form>
	</div>
	

<?php

$getMeasurements = Calculator::getMeasurements($scaleFactor);
$getSpars = Calculator::getSpars($scaleFactor);
$getSparDeflection = Calculator::getSparDeflectionFactors($scaleFactor);

// Default scale factor
$scaleFactor = 1.0;

// Optional spar deflection values
if (isset($_POST['scaleFactor']) && is_numeric($_POST['scaleFactor'])) {
    $scaleFactor = (float) $_POST['scaleFactor'];
}

$sparData = $calculator->getSparDeflectionFactors($scaleFactor);
$lineData = $calculator->getLineRecommendation($scaleFactor);
$area = $calculator->getArea($scaleFactor);

?>
	
	<div style="width:100%">
	<div style="width:40%;margin-left:2em;margin-right:20px;float:left;">

	<h3>Dimensions (scaled to <?php echo htmlentities($scaleFactor * 100); ?>%)</h3>
<table>
    <tr><td width="200"></td><td>cm</td><td>in</td></tr>
<?php
foreach ($getMeasurements as $row) {
    $label     = htmlspecialchars($row['label']);
    $cm        = $row['value'];
    $inch      = $cm / Calculator::CM_PER_INCH;
    $precision = $row['precision'];
    $inchFmt   = number_format($inch, $precision);
    echo "<tr><td>" . htmlentities($label) . "</td><td>" . htmlentities($cm) . "</td><td>" . htmlentities($inchFmt) . "</td></tr>\n";
}
?>
</table>

<h3>Spars</h3>
<table>
    <tr><td width="200"></td><td>cm</td><td>in</td></tr>
<?php
foreach ($getSpars as $row) {
    $label     = htmlspecialchars($row['label']);
    $precision = $row['precision'];
    $cm        = number_format($row['value'], $precision);
    $inch      = $cm / Calculator::CM_PER_INCH;
    $inchFmt   = number_format($inch, $precision);
    echo "<tr><td>" . htmlentities($label) . "</td><td>" . htmlentities($cm) . "</td><td>" . htmlentities($inchFmt) . "</td></tr>\n";

}
?>
</table>


<h3>Spar deflection</h3>
<table>
    <tr><td width="200"></td><td>deflection</td></tr>
<?php
foreach ($getSparDeflection as $row) {
    $label     = htmlspecialchars($row['label']);
    $value       = $row['value'];
    $precision = $row['precision'];
    $valueFmt   = number_format($value, $precision);

    echo "<tr><td>" . htmlentities($label) . "</td><td><a href='/kites/spar-calculator.php?wingspar=" . urlencode(htmlentities($value)) . "'>" . htmlentities($value) . "</a></td></tr>\n";

}
?>
</table>

</div>

<div style="float:left;width:50%">
    <h3>Relative size</h3>
    'full scale'<br/>
    <img alt="kite reference size" height="100" src="/kites/dtdeltareference-t.jpg" /><br/><br/>
    scaled <?php echo htmlentities($scaleFactor * 100);?>%<br/>
	<img alt="your kites size" height="<?php echo htmlentities(100 * $scaleFactor);?>" src="/kites/dtdeltareference-t.jpg" />

    <h3>Sail area <span style="font-size:10px;font-weight:normal;">= (wing width x height)+2(box width * height)+2(central rectangle)</span></h3>
<?php echo htmlentities($area);?> sq ft.

    <h3>Recommended line</h3>
    <?php echo htmlentities($lineData['recommendedLineStrength']);?> <?= $units === 'cm' ? 'kg' : 'lb.' ?>


	<h3>Plan</h3>
	<a href="http://www.richmondairforce.com/Files/DTplans.pdf">http://www.richmondairforce.com/Files/DTplans.pdf</a>
	<h3>This page's source code </h3>Written by Jesse Gersenson, it's free (<a href="https://github.com/jessegersensonchess/delta-kite-calculator">source code</a>)
	<h3>Other resources</h3> <a href="http://www.jesseo.com/kites/">Spar calculator and Delta kite calculator</a>
	</div>

  </div>
  </body></html>


