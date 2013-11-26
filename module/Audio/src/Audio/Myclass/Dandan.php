<?php

namespace Audio\Myclass;

class Dandan {
	const SDIR = './public/audiodata/';
	const RAWDIR = './public/audiodata/raw/';
	const MP3DIR = './public/audiodata/mp3/';
	const OGGDIR = './public/audiodata/ogg/';
	const VDIR = './public/videodata/';


	
	public  static	function dirToArray($dir, $path = false) { 
		$result = array(); 
		$cdir = scandir($dir); 
		foreach ($cdir as $key => $value) 
		{ 
			if (!in_array($value,array(".",".."))) 
			{ 
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
				{ 
					$result[$value] = self::dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
				} 
				else 
				{ 
					// (!$path) ? $result[] =  $value : $result[] = $dir . DIRECTORY_SEPARATOR . $value; 
					$result[] =  (!$path) ? $value : $dir . DIRECTORY_SEPARATOR . $value; 
				} 
			} 
		} 
		return $result; 
	}
}