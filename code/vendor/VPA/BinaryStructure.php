<?php


namespace VPA;


class BinaryStructure
{
	private string $fileName;
	private int $fileLength;
	private $fileDescriptor;

	function __construct (string $filename)
	{

		if (!file_exists($filename)) {
			throw new Exception("File not exists", 1);
		}
		$this->fileName = $filename;
		$this->fileLength = filesize($this->fileName);
		$this->fileDescriptor = fopen($this->fileName,'rb');
	}

	private function fseek (int $offset):bool
	{
		$return  = fseek ($this->fileDescriptor,$offset);
		return $return != -1;
	}

	protected function offsetValid(int $offset):bool
	{
		return $offset < $this->fileLength;
	}

	protected function getLine(int $offset,int $length):string
	{
		if (!$this->fseek($offset)) {
			throw new Exception("File offset is incorrect",1);
		}
		$str = '';
		for ($i=0; $i<$length;$i++) {
			$str .= fgetc($this->fileDescriptor);
		}
		return $str;
	}

	protected function toLong(string $data):int
	{
		return reset(unpack('N',$data));
	}

	protected function toChars(string $data):array
	{
		return unpack('C'.strlen($data),$data);
	}
}