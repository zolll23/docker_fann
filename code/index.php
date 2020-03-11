<?php
declare(strict_types=1);

set_time_limit(0);

require_once './vendor/autoload.php';

use VPA\FANN\TrainBase as TrainBase;
use VPA\FANN\TrainLabel as TrainLabel;
use VPA\NeuronNetworks\Utils as Utils;

$ann = createNet();

$parts = 10;

$epochs = 5;
for ($e = 0; $e<$epochs; $e++) {
		$train_data = fann_read_train_from_file("train.data");
		$mse_result = fann_train_epoch ($ann , $train_data );
		printf("Epoch %d/%d MSE result: %.2f\n",$e,$epochs,$mse_result);
}

fann_save($ann,'minst.net');

var_dump($mse_result);

function createNet() {
	$ann = fann_create_standard( 4,784,800,400,10 );
	fann_set_training_algorithm ( $ann , FANN_TRAIN_BATCH );
	fann_set_activation_function_hidden( $ann, FANN_SIGMOID );
	fann_set_activation_function_output( $ann, FANN_ERRORFUNC_TANH );
	return $ann;
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