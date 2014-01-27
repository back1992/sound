<?php
namespace Snoopy\Myclass;
class DanFun {
	function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") self::rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
	function mkdir_p($dir)  
	{  
		if(!is_dir($dir))  
		{  
			if(!self::mkdir_p(dirname($dir))){  
				return false;  
			}  
			if(!mkdir($dir,0777)){  
				return false;  
			}  
		}  
		return true;  
	}  
function flatten_array($mArray) {
	$sArray = array();
	foreach ($mArray as $row) {
		if ( !(is_array($row)) ) {
			if($sArray[] = $row){
			}
		} else {
			$sArray = array_merge($sArray, self::flatten_array($row));
		}
	}
	return $sArray;
}
	}