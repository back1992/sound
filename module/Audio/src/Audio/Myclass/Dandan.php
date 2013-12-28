<?php
namespace Audio\Myclass;
use Neutron\TemporaryFilesystem\TemporaryFilesystem;
class Dandan {
	const DIR = './';
	const SDIR = './public/audiodata/';
	const RAWDIR = './public/audiodata/raw/';
	const RESDIR = './public/audiodata/result/';
	const MP3DIR = './public/audiodata/mp3/';
	const OGGDIR = './public/audiodata/ogg/';
	const VDIR = './public/videodata/';
	const DOCDIR = './data/cet4/';
	const TMPDIR = './data/tmp/';

	// const PATTERN = '/(\d{1,2})\.?\s+[A-D]\)([^)]*)\s+[A-D]\)([^)]*)\s+[A-D]\)([^)]*)\s+[A-D]\)([^\n]*)/';
	// const PATTERN = '/(\d{1,2})\.?\s*\(?[A-D]\)([^)]*)\s+\(?[A-D]\)([^)]*)\s+\(?[A-D]\)([^)]*)\s+\(?[A-D]\)([^\n]*)/';
	const PATTERN = '/(\d{1,2})\.?\s*\(?[A-D]\)([^)]*)\s+\(?[A-D]\)([^)]*)\s+\(?[A-D]\)([^)]*)\s+\(?[A-D]\s?\)([^\n]*)/';

public  static	function dirToArray($dir, $path = false) { 
	$dir = (substr($dir, -1) == '/')? $dir : $dir.DIRECTORY_SEPARATOR;
	$result = array(); 
	$cdir = scandir($dir); 
	foreach ($cdir as $key => $value) 
	{ 
		if (!in_array($value,array(".",".."))) 
		{ 
			if (is_dir($dir  . $value)) 
			{ 
				$result[$value] = self::dirToArray($dir  . $value, $path); 
			} 
			else 
			{ 
					// (!$path) ? $result[] =  $value : $result[] = $dir  . $value; 
					// var_dump($path);
				$result[] =  (!$path) ? $value : $dir  . $value; 
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
function read_doc_file($filename, $begin = 'Listening Comprehension', $end = 'Reading Comprehension') {
		//use antiword
	$cmd = "/usr/local/bin/antiword -m UTF-8.txt  $filename";
	$content = htmlspecialchars(shell_exec($cmd));
		// $begin = array('Listening Comprehension', 'Listing Comprehension');
	$begin = 'Listening Comprehension';
	$end = 	'Reading Comprehension';
// echo "$begin --------$end";
	$content = self::slice($content, $begin, $end);
	return $content;
}
function read_file($filename) {
		//use antiword
	$cmd = "/usr/local/bin/antiword -m UTF-8.txt  $filename";
	$content = htmlspecialchars(shell_exec($cmd));
	return $content;
}
function readWhole($filename) {
		//use antiword
	$cmd = "/usr/local/bin/antiword -m UTF-8.txt  $filename";
	$content = htmlspecialchars(shell_exec($cmd));
	return $content;
}
function reReadDoc($filename) {
		//use antiword
	// $cmd = "/usr/local/bin/antiword -m UTF-8.txt  $filename";
	$cmd = "/usr/local/bin/antiword  $filename";
	$content =shell_exec($cmd);
	$begin = 'Listing Comprehension';
	$end = 	'Reading Comprehension';
	$content = self::slice($content, $begin, $end);
	return $content;
}

function read_pdf_file($filename) {
	$pdffile = $filename;
	$textfile = $filename.'.txt';
    //  var_dump(pathinfo($filename, PATHINFO_EXTENSION));
	if(pathinfo($filename, PATHINFO_EXTENSION) == 'doc')
	{
		$pdffile = $filename.'.pdf';
    //  var_dump(shell_exec("wvPDF $filename $pdffile")); 
	}
    //  var_dump(shell_exec("pdftotext $pdffile $textfile"));
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
		$tam = strlen($thisline);
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

public function slice($string, $begin = null, $end = null){
	if(stripos($string, $begin)) $string = mb_stristr($string, $begin);
		// echo $string;
		// if(stripos($string, $begin)) echo "<br />begin yes----------<br />";
	if(stripos($string, $end)) $string = mb_stristr($string, $end, true);
		// if(stripos($string, $end))  echo "end yes----------.<br />";
		// echo $string;
	return $string;
}
public function  savequestion($quizfile, $collection = null)
{
	$quizname = pathinfo($quizfile, PATHINFO_FILENAME);
	$subject = self::read_doc_file($quizfile);
		// $pattern = '/(\d{1,2})\.?\s+[A-D]\)([^)]*)\s+[A-D]\)([^)]*)\s+[A-D]\)([^)]*)\s+[A-D]\)([^\n]*)/';
	$pattern = self::PATTERN;
	preg_match_all($pattern, $subject, $match);
// var_dump($match);
	for ($i=0; $i < count($match['0']) ; $i++) {
		$select[$i]['no'] = $match['1'][$i];
		$select[$i]['quiz'] = $quizname;
		$select[$i]['A']= trim($match['2'][$i]);
		$select[$i]['B']= trim($match['3'][$i]);
		$select[$i]['C'] = trim($match['4'][$i]);
		$select[$i]['D'] = trim($match['5'][$i]);
		if($collection) $collection->insert($select[$i]);
	}
	return true;
}
public function  readquestion($content, $collection = null, $quizname)
{
	// $pattern = '/(\d{1,2})\.\s*[A-D]\)([^)]*)\s*[A-D]\)([^)]*)\s*[A-D]\)([^)]*)\s*[A-D]\)([^\.]*)/';
	$pattern = self::PATTERN;
	preg_match_all($pattern, $content, $match);
	// var_dump($match['1']);
	// var_dump($collection);
	$select = array();
	
	for ($i=0; $i < count($match['0']) ; $i++) {
		$select[$i]['no'] = $match['1'][$i];
		$select[$i]['quiz'] = $quizname;
		$select[$i]['A']= trim($match['2'][$i]);
		$select[$i]['B']= trim($match['3'][$i]);
		$select[$i]['C'] = trim($match['4'][$i]);
		$select[$i]['D'] = trim($match['5'][$i]);
		if($collection) $collection->insert($select[$i]);
		// $collection->insert($select[$i]);
	}
	return $select;
// return true;
/*	if($select) {
		return $select;
	}else {
		return false;
	}*/

}
}