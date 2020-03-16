<?php
declare(strict_types=1);

set_time_limit(0);

require_once './vendor/autoload.php';

use VPA\FANN\TrainBase as TrainBase;
use VPA\FANN\TrainLabel as TrainLabel;
use VPA\NeuronNetworks\Utils as Utils;

$ann = createNet();

$parts = 10;

$epochs = 100;
$desired_error = 0.05;

//$test_data = fann_read_train_from_file("test_100.data");

fann_set_callback($ann, function ($ann,$data,$max_epochs,$epochs_between_reports,$desired_error,$epochs) {
	global $start_time,$test_data;
	$end_time = microtime(true);
	//$mse = fann_test_data($ann,$test_data);
	printf("[%.4f] Epoch %d/%d MSE result: %.5f \n",($end_time-$start_time),$epochs,$max_epochs,fann_get_MSE($ann));
	$start_time = $end_time;
	return true;
});


$train_data = fann_read_train_from_file("train.data");
//$ret = fann_set_input_scaling_params($ann,$train_data,0,1);
fann_scale_input_train_data($train_data,0,1);
//$mse_result = fann_train_epoch ($ann , $train_data );
$start_time = microtime(true);
if (fann_train_on_data($ann,$train_data,$epochs,1,$desired_error)) {
	fann_save($ann,'minst.net');	
}
printf("Epoch %d/%d MSE result: %.2f\n",$e,$epochs,fann_get_MSE($ann));

fann_save($ann,'minst.net');
fann_destroy($ann);


function createNet() {
	$ann = fann_create_standard( 4,784,800,400,10 );
	fann_set_training_algorithm ( $ann , FANN_TRAIN_INCREMENTAL );
	fann_set_activation_function_hidden( $ann,  FANN_SIGMOID );
	fann_set_activation_function_output( $ann, FANN_SIGMOID_STEPWISE );
	fann_set_train_error_function($ann, FANN_ERRORFUNC_LINEAR);
	fann_set_learning_rate( $ann, 0.0001 );
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