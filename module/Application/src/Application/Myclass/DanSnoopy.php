<?php
namespace Application\Myclass;
use Neutron\TemporaryFilesystem\TemporaryFilesystem;
// use Audio\Myclass\Snoopy;
class DanSnoopy {
	function pageCount($content, $start, $end, $pattern) {
		$content = strstr(strstr($content, $start), $end, true);
		if(!$pattern) $pattern = '/href\s*=\s*"([^#"]+#?[^"]*)"/';
		if(!$start) $start = '<div class="pagesBox">';
		if(!$end) $end = '</div>';
		preg_match_all($patten, $content, $out);
		var_dump($out[1]);
		return $out[1];
	}
	function fetchlinks($url, $filter = false) {
		$snoopy = new Snoopy;
		$links = $snoopy->fetchlinks($url);
	// var_dump($snoopy->results);
		if($filter) $links = array_filter($snoopy->results, "self::myfunction");
		sort($links);
		var_dump($links);
		return $links;
	}
	function fetchtext($url) {
		$snoopy = new Snoopy;


		$snoopy->fetchtext($url);
	// $res = $snoopy->results;
	// var_dump($res);
		$content = iconv("gb2312", "utf-8",$snoopy->results);
		// print $content;
	// print $res;
		return $content;
	}
	function getImage($id,$url) {
		$filename = $id . ".jpg";
		$temp = new Snoopy;
		$temp -> fetch($url);
		if($temp->results != "") {
			$handle = fopen("images/" . $filename, "w");
                        //写入抓得内容
			fwrite($handle, $temp->results);
			fclose($handle);
		}
		return $filename;
	}


	protected function myfunction($v) 
	{
		$pattern = '/http:\/\/www\.cet4v\.com\/exam\/\d{4,5}\.asp/';
		if (preg_match($pattern, $v))
		{
			return true;
		}
		return false;
	}
}