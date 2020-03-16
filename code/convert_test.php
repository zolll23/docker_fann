<?php
declare(strict_types=1);

set_time_limit(0);

require_once './vendor/autoload.php';

use VPA\FANN\TrainBase as TrainBase;
use VPA\FANN\TrainLabel as TrainLabel;
use VPA\NeuronNetworks\Utils as Utils;

$testSet = new TrainBase('t10k-images-idx3-ubyte');
$testLabels = new TrainLabel('t10k-labels-idx1-ubyte');


$items = $testSet->getItems();
$points = $testSet[0];

$labels = $testLabels->getItems();


$num_train_data = 100;
$num_input = 784;
$num_output = 10;
$parts = 40;

	$fd = fopen('test_100.data','w');
	fputs($fd,sprintf("%d %d %d\n", $num_train_data,$num_input,$num_output));
	for ($i=0; $i<$num_train_data; $i++) {
		$points = $testSet[$i];
		$value = reset($testLabels[$i]);
		fputs($fd,sprintf("%s\n",implode(' ',$points)));
		$out = implode(" ",Utils::toCategoretical($value,10));
		fputs($fd,sprintf("%s\n",$out));
	}
	fclose($fd);
die();

//for ($part = 0;$part<$parts;$part++) {
	$fd = fopen('train_'.$part.'.data','w');
	fputs($fd,sprintf("%d %d %d\n", $num_train_data,$num_input,$num_output));
	for ($i=0; $i<$num_train_data; $i++) {
		$points = $trainSet[$part*$num_train_data+$i];
		fputs($fd,sprintf("%s\n",implode(' ',$points)));
		$out = implode(" ",Utils::toCategoretical($value,10));
		fputs($fd,sprintf("%s\n",$out));
	}
	fclose($fd);
//}



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