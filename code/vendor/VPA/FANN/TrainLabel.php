<?php
/**
[offset] [type]          [value]          [description]
0000     32 bit integer  0x00000801(2049) magic number (MSB first)
0004     32 bit integer  60000            number of items
0008     unsigned byte   ??               label
0009     unsigned byte   ??               label
........
xxxx     unsigned byte   ??               label
**/

namespace VPA\FANN;

class TrainLabel extends \VPA\BinaryStructure implements \ArrayAccess
{
	private int $numItems;
	private array $items;
	private int $startOffset = 8;
	private int $blockSize = 1;
	private int $numRows;
	private int $numColumns;

	function __construct (string $filename)
	{
		parent::__construct($filename);
		$magicNumber = $this->toLong($this->getLine(0,4));
		if ($magicNumber != 2049) {
			throw new Exception("Magic number is invalid");
		}
		$this->numItems = $this->toLong($this->getLine(4,4));
		$this->items = [];
	}

	public function getItems():int
	{
		return $this->numItems;
	}

	public function offsetSet($offset, $value)
	{
		throw new Exception ('File opened as readonly');
    }

    public function offsetExists($offset):bool
    {
    	$binaryOffset = $this->startOffset + $this->blockSize * $offset;
        return isset($this->items[$offset]) || $this->offsetValid($binaryOffset);
    }

    public function offsetUnset($offset):bool
    {
    	throw new Exception ('File opened as readonly');
    	return false;
    }

    public function offsetGet($offset):array
    {
    	if (!isset($this->items[$offset])) {
    		$binaryOffset = $this->startOffset + $this->blockSize * $offset;
    		$binaryString = $this->getLine($binaryOffset,$this->blockSize);
    		$this->items[$offset] = $this->toChars($binaryString);
    	}
    	return $this->items[$offset];

    }

}