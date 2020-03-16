<?php
declare(strict_types=1);

set_time_limit(0);

require_once './vendor/autoload.php';

use VPA\FANN\TrainBase as TrainBase;
use VPA\FANN\TrainLabel as TrainLabel;
use VPA\NeuronNetworks\Utils as Utils;

$trainSet = new TrainBase('train-images-idx3-ubyte');
$trainLabels = new TrainLabel('train-labels-idx1-ubyte');

$items = $trainSet->getItems();
$points = $trainSet[0];

$labels = $trainLabels->getItems();

/*echo "<table border=1><tr>";
for ($i=0;$i<20;$i++) {
	$points = $trainSet[$i];
	$value = reset($trainLabels[$i]);
	printf('<td>%s</td><td>%d</td><td>%s</td>',show_image($points),$value,implode(",",Utils::toCategoretical($value,10)));
	if (($i+1)%4==0) {
		printf("</tr><tr>");
	}
}
echo "</tr></table>";
*/

$num_train_data = 100;
$num_input = 784;
$num_output = 10;
$parts = 40;

	$fd = fopen('train_100.data','w');
	fputs($fd,sprintf("%d %d %d\n", $num_train_data,$num_input,$num_output));
	for ($i=0; $i<$num_train_data; $i++) {
		$points = $trainSet[$i];
		$value = reset($trainLabels[$i]);
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