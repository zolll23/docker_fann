<?php
declare(strict_types=1);

require_once './vendor/autoload.php';

use VPA\FANN\TrainBase as TrainBase;
use VPA\FANN\TrainLabel as TrainLabel;
use VPA\NeuronNetworks\Utils as Utils;

$trainSet = new TrainBase('train-images-idx3-ubyte');
$trainLabels = new TrainLabel('train-labels-idx1-ubyte');

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


function createNet($trainSet,$trainLabels) {
	$ann = fann_create_standard(4,784,800,400,10);

}


function show_image($points) {
	$gd = imagecreatetruecolor(28,28);
	for ($x=0; $x<28; $x++) {
		for ($y=0;$y<28;$y++) {
			$offset = $y * 28 + $x + 1;
			$gray = $points[$offset];
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