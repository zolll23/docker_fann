<?php

namespace VPA\NeuronNetworks;

class Utils
{
	static function toCategoretical(int $value,int $length):array
	{
		$binValue = str_repeat('0',$length);
		$binValue = substr_replace($binValue,'1',$value,1);
		return str_split($binValue);
	}

	static function argMax(array $value):int
	{
		$index = 0;
		$max = 0;
		foreach ($value as $i => $v) {
			if ($v>$max) {
				$max=$v;
				$index = $i;
			}
		}
		return $index;
	}
}