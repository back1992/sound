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
	function read_doc_file($filename) {
		$content = shell_exec('/usr/bin/antiword -f '.$filename);
		return $content;
		// return shell_exec('/usr/local/bin/antiword '.$filename);
	}
	
	function read_pdf_file($filename) {
		$pdffile = $filename;
		$textfile = $filename.'.txt';
		var_dump(pathinfo($filename, PATHINFO_EXTENSION));
		if(pathinfo($filename, PATHINFO_EXTENSION) == 'doc')
		{
			$pdffile = $filename.'.pdf';
			var_dump(shell_exec("wvPDF $filename $pdffile")); 
		}
		var_dump(shell_exec("pdftotext $pdffile $textfile"));
		$content = file_get_contents($textfile);
		return $content;
		// return shell_exec('/usr/local/bin/antiword '.$filename);
	}
	
	function parseWord($userDoc) 
	{
		$fileHandle = fopen($userDoc, "r");
		// $word_text = @fread($fileHandle, filesize($userDoc));
		$word_text = fread($fileHandle, filesize($userDoc));
		$line = "";
		$tam = filesize($userDoc);
		$nulos = 0;
		$caracteres = 0;
		for($i=1536; $i<$tam; $i++)
		{
			$line .= $word_text[$i];
			if( $word_text[$i] == 0)
			{
				$nulos++;
			}
			else
			{
				$nulos=0;
				$caracteres++;
			}
			if( $nulos>1996)
			{   
				break;  
			}
		}
    //echo $caracteres;
		$lines = explode(chr(0x0D),$line);
    //$outtext = "<pre>";
		$outtext = "";
		foreach($lines as $thisline)
		{
			$tam = mb_strlen($thisline);
			if( !$tam )
			{
				continue;
			}
			$new_line = ""; 
			for($i=0; $i<$tam; $i++)
			{
				$onechar = $thisline[$i];
				if( $onechar > chr(240) )
				{
					continue;
				}
				if( $onechar >= chr(0x20) )
				{
					$caracteres++;
					$new_line .= $onechar;
				}
				if( $onechar == chr(0x14) )
				{
					$new_line .= "</a>";
				}
				if( $onechar == chr(0x07) )
				{
					$new_line .= "\t";
					if( isset($thisline[$i+1]) )
					{
						if( $thisline[$i+1] == chr(0x07) )
						{
							$new_line .= "\n";
						}
					}
				}
			}
        //troca por hiperlink
			$new_line = str_replace("HYPERLINK" ,"<a href=",$new_line); 
			$new_line = str_replace("\o" ,">",$new_line); 
			$new_line .= "\n";
        //link de imagens
			$new_line = str_replace("INCLUDEPICTURE" ,"<br><img src=",$new_line); 
			$new_line = str_replace("\*" ,"><br>",$new_line); 
			$new_line = str_replace("MERGEFORMATINET" ,"",$new_line); 
			$outtext .= nl2br($new_line);
		}
		return $outtext;
	} 
}