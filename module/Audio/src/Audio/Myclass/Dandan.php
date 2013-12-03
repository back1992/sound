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
	function deleteDirectory($dir) {
		if (!file_exists($dir)) return true;
		if (!is_dir($dir)) return unlink($dir);
		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			if (!self::deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
		}
		return rmdir($dir);
	}
	 // When the directory is not empty:
	function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
	function removeDir($path) {
    // Normalise $path.
		$path = rtrim($path, '/') . '/';
    // Remove all child files and directories.
		$items = glob($path . '*');
		foreach($items as $item) {
			is_dir($item) ? self::removeDir($item) : unlink($item);
		}
    // Remove directory.
		rmdir($path);
	}
}