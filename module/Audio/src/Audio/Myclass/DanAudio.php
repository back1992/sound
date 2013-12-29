<?php
namespace Audio\Myclass;
use Neutron\TemporaryFilesystem\TemporaryFilesystem;
class DanAudio {
	const DIR = './';
	const SDIR = './public/audiodata/';
	const RAWDIR = './public/audiodata/raw/';
	const RESDIR = './public/audiodata/result/';
	const MP3DIR = './public/audiodata/mp3/';
	const OGGDIR = './public/audiodata/ogg/';
	const VDIR = './public/videodata/';
	const DOCDIR = './data/cet4/';
	const TMPDIR = './data/tmp/';


	function mp3splt($audioFile, $audioTDir, $th='-48', $min = '2.4', $off = '0.6') {
		$cmd_splt = " mp3splt -s -p th=$th,min=$min,off=$off  $audioFile  -d $audioTDir ";
		var_dump($cmd_splt);
		$spltlog = shell_exec($cmd_splt);
		if(shell_exec($cmd_splt)){
			echo "well done in $audioFile ! <br />";
		} else {
			echo "something wrong in $audioFile! <br /> ";
		}
	}
	public function slice($string, $begin = null, $end = null){
		if(stripos($string, $begin)) $string = stristr($string, $begin);
		if(stripos($string, $end)) $string = stristr($string, $end, true);
		return $string;
	}
	public function mp32ogg($sourceFile, $targetFile = null, $options = '  -acodec libvorbis  '){
		// $targetFile = ($targetFile) ? $targetFile: dirname($sourceFile). DIRECTORY_SEPARATOR. pathinfo($sourceFile, PATHINFO_FILENAME).'.ogg';
		$cmd_conv = 'ffmpeg -i ' . $sourceFile . $options . $targetFile;
				// var_dump($cmd_conv);
		shell_exec($cmd_conv);
		return true;
	}
	public function mp32oggDir($sDir, $options = '  -acodec libvorbis  ')  {
		$tDir = $sDir;
		if (is_dir($sDir)) {
			$sFiles = Dandan::dirToArray($sDir, true);
			$cmd_rm = 'rm ' . $sDir . '/*.ogg';
			shell_exec($cmd_rm);
		}
		else {
			echo "$sDir must be a directory! ";
		}
			// $cmd_conv = 'ffmpeg -i '. $sourceFile .'  -acodec libvorbis  '. $targetFile;
			// var_dump($cmd_conv);
			// shell_exec($cmd_conv);
			// var_dump($request->getPost());
			// var_dump($sDir);
			// var_dump($sFiles);

		foreach ($sFiles as $sFile) {
			$sFile_parts = pathinfo($sFile);
			$sourceFile = $sFile;
			$targetFile = $sFile_parts['dirname'] . '/' . $sFile_parts['filename'] . '.ogg';
			$cmd_conv = 'ffmpeg -i ' . $sourceFile . '  -acodec libvorbis  ' . $targetFile;
				// var_dump($cmd_conv);
			shell_exec($cmd_conv);
				// shell_exec('ffmpeg -i '. $sDir . '/'.$sFile .' '. $sDir. '/ogg/'.$sFile_parts['basename'].'.ogg' );

		}
		return true;
	}
	public function  savequestion($quizfile, $collection)
	{
		$quizname = pathinfo($quizfile, PATHINFO_FILENAME);
		$subject = Dandan::read_doc_file($quizfile);
		$pattern = '/(\d{1,2})\.\s+A\)([^)]*)\s+B\)([^)]*)\s+C\)([^)]*)\s+D\)([^.)]*)/';
preg_match_all($pattern, $subject, $match);
for ($i=0; $i < count($match['0']) ; $i++) {
	$select[$i]['no'] = $match['1'][$i];
	$select[$i]['quiz'] = $quizname;
	$select[$i]['A']= trim($match['2'][$i]);
	$select[$i]['B']= trim($match['3'][$i]);
	$select[$i]['C'] = trim($match['4'][$i]);
	$select[$i]['D'] = trim($match['5'][$i]);
	$collection->insert($select[$i]);
}
return true;
}
}