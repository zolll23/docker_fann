<?php
/**
[offset] [type]          [value]          [description]
0000     32 bit integer  0x00000803(2051) magic number
0004     32 bit integer  60000            number of images
0008     32 bit integer  28               number of rows
0012     32 bit integer  28               number of columns
0016     unsigned byte   ??               pixel
0017     unsigned byte   ??               pixel
........
xxxx     unsigned byte   ??               pixel
xxxx     unsigned byte   ??               label
**/

namespace VPA\FANN;

class TrainBase extends \VPA\BinaryStructure implements \ArrayAccess
{
	private int $numItems;
	private array $images;
	private int $startOffset = 16;
	private int $blockSize = 784;
	private int $numRows;
	private int $numColumns;

	function __construct (string $filename)
	{
		parent::__construct($filename);
		$magicNumber = $this->toLong($this->getLine(0,4));
		if ($magicNumber != 2051) {
			throw new \Exception("Magic number is invalid");
		}
		$this->numItems = $this->toLong($this->getLine(4,4));
		$this->numRows = $this->toLong($this->getLine(8,4));
		$this->numColumns = $this->toLong($this->getLine(12,4));
		$this->images = [];
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
        return isset($this->images[$offset]) || $this->offsetValid($binaryOffset);
    }

    public function offsetUnset($offset):bool
    {
    	throw new Exception ('File opened as readonly');
    	return false;
    }

    public function offsetGet($offset):array
    {
    	if (!isset($this->images[$offset])) {
    		$binaryOffset = $this->startOffset + $this->blockSize * $offset;
    		$binaryString = $this->getLine($binaryOffset,$this->blockSize);
    		$this->images[$offset] = $this->toChars($binaryString);
    	}
    	return $this->images[$offset];

    }

}