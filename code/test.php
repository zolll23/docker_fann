<?php
declare(strict_types=1);

set_time_limit(0);

require_once './vendor/autoload.php';

use VPA\FANN\TrainBase as TrainBase;
use VPA\FANN\TrainLabel as TrainLabel;
use VPA\NeuronNetworks\Utils as Utils;

$ann = fann_create_from_file('minst.net');

$trainSet = new TrainBase('train-images-idx3-ubyte');
$trainLabels = new TrainLabel('train-labels-idx1-ubyte');

$testSet = new TrainBase('t10k-images-idx3-ubyte');
$testLabels = new TrainLabel('t10k-labels-idx1-ubyte');


$items = $trainSet->getItems();
$points = $trainSet[0];

$labels = $trainLabels->getItems();

echo "<table border=1><tr>";
for ($i=0;$i<20;$i++) {
	$points = $trainSet[$i];
	$value = reset($trainLabels[$i]);
	printf('<td>%s</td><td>%d</td><td>%s</td>',show_image($points),$value,implode(",",Utils::toCategoretical($value,10)));
	if (($i+1)%4==0) {
		printf("</tr><tr>");
	}
}
echo "</tr></table>";

$success = 0;
$tests = 10000;

//echo "<table border=1><tr>";
for ($i=0;$i<$tests;$i++) {
	$points = $testSet[$i];
	$value = reset($testLabels[$i]);
	$float_points = $points;
	$net_value = Utils::argMax(fann_run($ann,$float_points));
	//var_dump(Utils::argMax($net_value));
	//var_dump($points);die();
	if ($value==$net_value) {
		$success++;
	}
	//printf('<td>%s</td><td>%d</td><td>%d</td>',show_image($points),$value,$net_value);
	if (($i+1)%16==0) {
		printf("</tr><tr>");
	}
}
//echo "</tr></table>";

printf("Results: %.2f %% passed",$success/$tests*100);

function show_image($points) {
	$gd = imagecreatetruecolor(28,28);
	for ($x=0; $x<28; $x++) {
		for ($y=0;$y<28;$y++) {
			$offset = $y * 28 + $x + 1;
			$gray = intval($points[$offset]);
			$color = imagecolorallocate($gd, $gray, $gray, $gray); 
			imagesetpixel($gd, $x, $y, $color);
		}
	}
	
	//header('Content-Type: image/png');
	ob_start();
	imagepng($gd);
	$png = ob_get_contents();
	ob_clean();
	return '<img src="data:image/png;base64,'.base64_encode($png).'">';
}