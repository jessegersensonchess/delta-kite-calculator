<?php
error_reporting(0);
if(isset($_POST['submit']))
	{
	    $noseangle = $_POST['noseangle'];
	    $lengthatcenterline = $_POST['lengthatcenterline'];
	    $units = $_POST['units'];
	}

	$nominalspan= number_format((tan(deg2rad($noseangle/2))*($lengthatcenterline*2)), 1, '.', '');
	$halfspan=number_format(($nominalspan/2), 1, '.', '');
	$lengthoffin= number_format((.75*$lengthatcenterline), 1, '.', '');
	$lengthofleadingedge= number_format(($lengthatcenterline/(cos(deg2rad($noseangle/2)))), 1, '.', '');
	$nominallengthofwingspars=number_format(($lengthofleadingedge*.75), 1, '.', '');
	$nosetoLEspar=$lengthofleadingedge-$nominallengthofwingspars;
	$depthoffin=number_format((.333*$halfspan), 1, '.', '');
	$spreaderstrutattachmentpoint=number_format(((7/9)*$nominallengthofwingspars), 1, '.', '');

$nosetospreader=number_format(cos(deg2rad($noseangle/2))*($lengthofleadingedge-$spreaderstrutattachmentpoint),1,'.','');
	$spreaderlength = number_format(2/(cos(deg2rad($noseangle/2))/$nosetospreader),1,'.','');

$spreaderlength2=number_format(($nosetospreader/$lengthatcenterline)*$nominalspan,1,'.','');
	/*


		$keellongedge=
		$keelshortedge=
		$keelpartperimeter=
		$towingpointposition=
	*/

	if ($units =="in"){
		$area = number_format((($halfspan/12)*($lengthatcenterline/12)), 1, '.', '');
	}
	
	else {
		$area = number_format((($halfspan/30.48)*($lengthatcenterline/30.48)), 1, '.', '');
	}
$keelfrontleg = sqrt(
($depthoffin * $depthoffin) + (($lengthoffin-(0.5*$lengthatcenterline))*($lengthoffin-(0.5*$lengthatcenterline)))
);

$keelrearleg = sqrt
(
($depthoffin * $depthoffin) + 
(
	(
		($lengthoffin-(0.5*$lengthatcenterline))-$lengthoffin
	)
* 
	(
		($lengthoffin-(0.5*$lengthatcenterline))-$lengthoffin
	)

));
	$sewing = (2 * $lengthofleadingedge) + (2 * $nosetoLEspar) + ($nominalspan) + (4.5 * $lengthatcenterline) + (2 * $keelrearleg) + (2 * $keelfrontleg);

$keelfrontleg = number_format($keelfrontleg, 1, '.', '');
$keelrearleg = number_format($keelrearleg, 1, '.', '');

?> 


<html><body><title>Delta kite calculator</title>
<style>
.warning {color:red;text-decoration: line-through}	body {margin:0;width:900px;margin-left:auto;margin-right:auto;}
	h1 {text-align:center;margin-bottom:5px;}
	h3 {margin-bottom:3px;margin-top:15px;}
	table{border-collapse:collapse;}
	table,th, td {border: 1px solid #99661E;}
	form {margin:0;padding:0;}
	table {width:300px;}
	input:focus {background:#ffc;}
.tdlabel {text-align:center}
#lengthatcenterline {width:50px;float:left;}
#selectunits {padding:0 1em;font-size:1em;}
input {}
</style>
<body>

<h1>Delta kite calculator</h1>
<p style="text-align:center">Calculates a <a href="http://www.deltas.freeserve.co.uk/plan.html">Dan Leigh standard delta</a>. Nose angles range from 86 to 120 degrees, typical is 96.</p>


<div style="padding:5px;text-align:center;background:#ffc">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table style="background:#FFEEFF" align="center" >
			<tr><td>nose angle</td><td><input type="text" value="<? if ($noseangle) {echo $noseangle; } else {echo '96';} ?>" name="noseangle"></td></tr>
			<tr><td nowrap>length at centerline (C)&nbsp;</td>
			<td nowrap><input type="text" value="<? echo $lengthatcenterline; ?>" id="lengthatcenterline" name="lengthatcenterline"> 		
<!--<INPUT TYPE="RADIO" VALUE="cm">cm<BR>
<INPUT TYPE="RADIO" <? if ($units =="in"){echo "selected";} ?> value="in" >in.<BR>-->
<select id="selectunits" name="units">
   					<option value="cm">cm</option>
					<option <? if ($units =="in"){echo "selected";} ?> value="in">in.</option>
				</select>
</td></tr>
			<tr><td align="center" colspan="2">
				<input style="padding:2px 10px;margin:5px;font-size:1.1em" type="submit" name="submit" value="calculate"></td>
			</tr>
		</table>
	</form>
</div>



<div style="width:100%">
	<div style="width:410px;margin-left:2em;margin-right:10px;float:left;">
		<h3>Dimensions </h3>

<table width="100%">
	<?
		echo "<tr><td class='tdlabel'>C</td><td>length at centerline</td><td>" . $lengthatcenterline . "</td></tr>";
		echo "<tr><td class='tdlabel'>S</td><td>nominal span</td><td>" . $nominalspan . "</td></tr>";
		echo "<tr><td class='tdlabel'>S/2</td><td>nominal span (half)</td><td>" . $halfspan . "</td></tr>";
		echo "<tr><td class='tdlabel'>F</td><td>length of fin</td><td>" .$lengthoffin ."</td></tr>";
		echo "<tr><td class='tdlabel'>D</td><td>depth of fin</td><td>" . $depthoffin . "</td></tr>";
		echo "<tr><td class='tdlabel'></td><td>length of fin side</td><td>" . $keelfrontleg ."</td></tr>";
		echo "<tr><td class='tdlabel'></td><td>keel rear leg</td><td>" . $keelrearleg ."</td></tr>";

		echo "<tr><td class='tdlabel'>B</td><td>towing point</td><td nowrap>" .(0.5*$lengthatcenterline) ."</td></tr>";


		echo "<tr><td class='tdlabel'>LE</td><td>length of Leading Edge</td><td>" . $lengthofleadingedge . "</td></tr>";
		echo "<tr><td class='tdlabel'>L</td><td>length of wing spars</td><td>" . $nominallengthofwingspars . "</td></tr>";
		echo "<tr><td class='tdlabel'></td><td>nose to LE spar</td><td>" . $nosetoLEspar . "</td></tr>";
		echo "<tr><td nowrap class='tdlabel'>to SA</td><td>to spreader</td><td>" . $spreaderstrutattachmentpoint . "</td></tr>";
		echo "<tr><td nowrap class='tdlabel'></td><td class='warning'>spreader length (??)</td><td class='warning'>" . $spreaderlength2 . " or ".$spreaderlength . " or ". $nosetospreader*2 ."??</td></tr>";

	?>

</table>
	<h3>Sail area</h3>
		<? echo $area ." sq ft"?>
	<h3>Recommended line</h3>
		<? 
		echo "light winds = " . 	number_format(($area * 1) , 0, '.', ''). " lb. line<br/>";
		echo "moderate winds = " . 	number_format(($area * 2.2) , 0, '.', ''). " lb. line<br/>";
		echo "strong winds = " . 	number_format(($area * 4) , 0, '.', ''). " lb. line<br/>";
		?>
<h3>Resources</h3><a href="http://www.deltas.freeserve.co.uk/plan.html">Plan and assembly instructions</a><br/>
<a href="http://www.jesseo.com/kites/">Other kites calculators</a>
<br/>
	<a href="delta-calculator.txt">Source code</a>
</div>

<div style="float:left;width:400px">	

	<img src="dplan86.gif">
</div></div>
