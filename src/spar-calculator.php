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
	</style>

<?php
    $deflection = $_GET['wingspar'];
if (isset($_POST['submit'])) {
    $deflection = $_POST['deflection'];
    $oneid = $_POST['oneid'] / 25.4;
    $oneod = $_POST['oneod'] / 25.4;
    $oneweight = number_format(4.5 * (pow(.5 * $_POST['oneod'], 2) - pow(.5 * $_POST['oneid'], 2)), 1, '.', '');
    $twoweight = number_format(6.277 * (pow(.5 * $_POST['twood'], 2) - pow(.5 * $_POST['twoid'], 2)), 1, '.', '');
    $threeweight = number_format(4.5 * (pow(.5 * $_POST['threeod'], 2) - pow(.5 * $_POST['threeid'], 2)), 1, '.', '');
    $fourweight = number_format(6.277 * (pow(.5 * $_POST['fourod'], 2) - pow(.5 * $_POST['fourid'], 2)), 1, '.', '');
    $twoid = $_POST['twoid'] / 25.4;
    $twood = $_POST['twood'] / 25.4;
    $fourid = $_POST['fourid'] / 25.4;
    $fourod = $_POST['fourod'] / 25.4;
    $fiveid = $_POST['fiveid'] / 25.4;
    $fiveod = $_POST['fiveod'] / 25.4;
    $threeid = $_POST['threeid'] / 25.4;
    $threeod = $_POST['threeod'] / 25.4;
    if ($oneod != null) {
        $onedeflection = abs(
            0.00197889 /
            (
                (
                    0.049087385 *
                    (
                        (pow($oneod, 4)) -
                        (pow($oneid, 4))
                    )
                )
            ) / 48
        );
        $relstiffnessone = $deflection / $onedeflection;
    }

    if ($threeod != null) {
        $threedeflection = abs(
            0.00197889 /
                    (
                        (
                            0.049087385 *
                            (
                                (pow($threeod, 4)) -
                                (pow($threeid, 4))
                            )
                        )
                    ) / 48
        );
        $relstiffnessthree = $deflection / $threedeflection;
    }

    if ($fourod != null) {
        $fourdeflection = abs(
            0.004836093 /
                    (
                        (
                            0.049087385 *
                            (
                                (pow($fourod, 4)) -
                                (pow($fourid, 4))
                            )
                        )
                    ) / 48
        );
        $relstiffnessfour = $deflection / $fourdeflection;
    }

    if ($fiveod != null) {
        $fivedeflection = abs(
            0.016199078 /
                    (
                        (
                            0.049087385 *
                            (
                                (pow($fiveod, 4)) -
                                (pow($fiveid, 4))
                            )
                        )
                    ) / 48
        );
        $relstiffnessfive = $deflection / $fivedeflection;
    }
    if ($twood != null) {
        $twodeflection = abs(
            0.004836093 /
                    (
                        (
                            0.049087385 *
                            (
                                (pow($twood, 4)) -
                                (pow($twoid, 4))
                            )
                        )
                    ) / 48
        );
        $relstiffnesstwo = $deflection / $twodeflection;
    }
}

if ($deflection != null) {
    $scalefactorone = number_format(pow($relstiffnessone, 0.25), 2, '.', '');
    $scalefactortwo = number_format(pow($relstiffnesstwo, 0.25), 2, '.', '');
    $scalefactorthree = number_format(pow($relstiffnessthree, 0.25), 2, '.', '');
    $scalefactorfour = number_format(pow($relstiffnessfour, 0.25), 2, '.', '');
    $scalefactorfive = number_format(pow($relstiffnessfive, 0.25), 2, '.', '');
}
if ($deflection != null) {
}

$scalefactorP90 = (number_format(pow(($deflection / 0.565), 0.25), 2, '.', '') - 1) * 100;

$scalefactor53carbon = (number_format(pow(($deflection / 0.64), 0.25), 2, '.', '') - 1) * 100;
$scalefactor40carbon = (number_format(pow(($deflection / 1.37), 0.25), 2, '.', '') - 1) * 100;
$scalefactor425carbon = (number_format(pow(($deflection / 1.61), 0.25), 2, '.', '') - 1) * 100;
$scalefactor20carbon = (number_format(pow(($deflection / 21.85), 0.25), 2, '.', '') - 1) * 100;
$scalefactor64carbon = (number_format(pow(($deflection / 0.34), 0.25), 2, '.', '') - 1) * 100;
$scalefactorP100 = (number_format(pow(($deflection / 0.413), 0.25), 2, '.', '') - 1) * 100;
$scalefactorP400 = (number_format(pow(($deflection / 0.302), 0.25), 2, '.', '') - 1) * 100;
$scalefactor30carbon = (number_format(pow(($deflection / 4.316), 0.25), 2, '.', '') - 1) * 100;
$scalefactor25carbon = (number_format(pow(($deflection / 9.106), 0.25), 2, '.', '') - 1) * 100;

$scalefactorexcel10 = (number_format(pow(($deflection / 0.047), 0.25), 2, '.', '') - 1) * 100;
$scalefactorexcel6 = (number_format(pow(($deflection / 1.079), 0.25), 2, '.', '') - 1) * 100;
$scalefactorexcel8 = (number_format(pow(($deflection / 0.272), 0.25), 2, '.', '') - 1) * 100;
$scalefactorexcel9 = (number_format(pow(($deflection / 0.118), 0.25), 2, '.', '') - 1) * 100;

?>
	</head><body>
	<h1>Carbon and Fiberglass spar scaling calculator</h1>
<p>This calculator helps kite builders compare spars and determine their relative scaling factors, particularly when scaling a design. To use it, measure a spar's deflection by suspending a 908-gram weight from the center of a 26-inch (66 cm) segment and recording how far it bends (deflects). For example, a 2.5 mm carbon rod deflects 9.106 inches under this setup.</p>

<p>To begin, enter a deflection value and click Calculate. You can also optionally enter the outside diameter (O.D.) and inside diameter (I.D.) of the spars being compared. For solid rods, enter 0 for the inside diameter.</p>

<p>Note: The elasticity moduli used for "carbon" (17.7634892268) and "fiberglass" (7.2686776573) are not fixed physical constants. This calculator is intended as a guide and should be used in conjunction with real-world testing.</p>

<p>This page is written in PHP by Jesse Gersenson (see <a href="http://www.jesseo.com/kites/">kite calculators</a>) based on equations from <a href="http://www.nic.fi/~sos/spars/spars.htm">Dave Lord's Scale Factor</a> and a <a href="http://www.jesseo.com/kites/Spar-Deflection-and-Comparison-Chart-simon-p-craft%20.xls">spreadsheet by Simon Craft</a></p>

	<div style="width:35%;margin-right:2em;text-align:center;float:left;">
	<h3>Reference spar deflection</h3><div style="background:#ffc;border:1px #ddd dashed">
	<form style="margin:0;padding:1em;" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	Enter deflection <input type="text" value="<?php echo $deflection;?>" name="deflection" />
	
<table id="comparedspars" align="center"><tr><td>Comparison spar</td><td>O.D mm</td><td>I.D mm</td></tr>
<tr><td>Carbon spar</td><td><input type="text" value="<?php echo $oneod * 25.4;?>" name="oneod" /></td><td><input value="<?php echo $oneid * 25.4;?>" type="text" name="oneid" /></td></tr>
<tr><td>Carbon spar</td><td><input type="text" value="<?php echo $threeod * 25.4;?>" name="threeod" /></td><td><input value="<?php echo $threeid * 25.4;?>" type="text" name="threeid" /></td></tr>
<tr><td>Fiberglass spar</td><td><input type="text" value="<?php echo $twood * 25.4;?>" name="twood" /></td><td><input value="<?php echo $twoid * 25.4;?>" type="text" name="twoid" /></td></tr>
<tr><td>Fiberglass spar</td><td><input type="text" value="<?php echo $fourod * 25.4;?>" name="fourod" /></td><td><input value="<?php echo $fourid * 25.4;?>" type="text" name="fourid" /></td></tr>
<tr><td>Ramen wood spar (experimental)</td><td><input type="text" value="<?php echo $fiveod * 25.4;?>" name="fiveod" /></td><td><input value="<?php echo $fiveid * 25.4;?>" type="text" name="fiveid" /></td></tr>


</table>
<input type="submit" name="submit" style="width:auto;font-size:1em;margin:1em;" value="calculate" />
	</form>
	</div>	</div>

<div style="float:left;width:60%;">
<h3>Scaling factors of comparision spars</h3><table>
			<tr><td>Calculated spar (O.D/I.D)</td><td>weight (g/m)</td><td>deflection (in.)</td><td><b>scale factor</b></td></tr>
			<tr><td nowrap><?php echo  $_POST['oneod'] . " mm / " . $_POST['oneid'] . " mm"; ?> carbon</td><td><?php echo $oneweight . "";?></td><td><?php echo number_format($onedeflection, 2, '.', '') . "";?></td><td><b><?php echo $scalefactorone;?></b></td></tr>
			<tr><td><?php echo  $_POST['threeod'] . " mm / " . $_POST['threeid'] . " mm"; ?> carbon</td><td><?php echo $threeweight . "";?></td><td><?php echo number_format($threedeflection, 2, '.', '') . "";?></td><td><b><?php echo $scalefactorthree;?></b></td></tr>
			<tr><td><?php echo  $_POST['twood'] . " mm / " . $_POST['twoid'] . " mm"; ?> fiberglass</td><td><?php echo $twoweight . "";?></td><td><?php echo number_format($twodeflection, 2, '.', '') . "";?></td><td><b><?php echo $scalefactortwo;?></b></td></tr>
			<tr><td><?php echo  $_POST['fourod'] . " mm / " . $_POST['fourid'] . " mm"; ?> fiberglass</td><td><?php echo $fourweight . "";?></td><td><?php echo number_format($fourdeflection, 2, '.', '') . "";?></td><td><b><?php echo $scalefactorfour;?></b></td></tr>
			<tr><td><?php echo  $_POST['fiveod'] . " mm / " . $_POST['fiveid'] . " mm"; ?> ramen</td><td><?php echo "";?></td><td><?php echo number_format($fivedeflection, 2, '.', '') . "";?></td><td><b><?php echo $scalefactorfive;?></b></td></tr>
		</table>

	
<pre>
2mm carbon rod	 	21.85 <?php echo $scalefactor20carbon ."%"; ?>

2.5mm carbon rod 	(9.1) <?php echo $scalefactor25carbon ."%"; ?>

3mm carbon rod		4.316 <?php echo $scalefactor30carbon ."%"; ?>

4mm carbon rod	 	1.366 <?php echo $scalefactor40carbon ."%"; ?>

4/2.5mm carbon (jino)	1.711 <?php echo $scalefactor425carbon ."%"; ?>

5/3mm carbon tube	<?php echo $scalefactor53carbon ."%"; ?>

6/4mm carbon tube 	<?php echo $scalefactor64carbon ."%"; ?>

Skyshark P 90 		<?php echo $scalefactorP90 ."%"; ?> (deflection: 0.565)
Skyshark P 100		<?php echo $scalefactorP100 ."%"; ?> (deflection: 0.413)
Skyshark P 400		<?php echo $scalefactorP400 ."%"; ?> (deflection: 0.302)
Excel 6 fg tube blue 	<?php echo $scalefactorexcel6 ."%"; ?> 	(1.079) 24.67 g/m
Excel 8 fg tube grey 	<?php echo $scalefactorexcel8 ."%"; ?> 	(0.272) 38.18 g/m
Excel 9 fg tube red 	<?php echo $scalefactorexcel9 ."%"; ?> 	(0.118) 51.2 g/m
Excel 10 fg tube white 	<?php echo $scalefactorexcel10 ."%"; ?> 	(0.047) 54 g/m

3mm Wood			(35.3288)
5mm Wood			(4.5786)
6mm Wood			(2.2081)
8mm Wood			(0.6986)
1/8" Wood			(28.1604)
3/16" Wood			(5.5626)
1/4" wood			(1.7600)
5/16" wood			(0.7209)


</pre>

</div>
  </body></html>
