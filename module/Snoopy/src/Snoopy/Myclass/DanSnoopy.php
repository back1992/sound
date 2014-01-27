<?php
namespace Snoopy\Myclass;
use Neutron\TemporaryFilesystem\TemporaryFilesystem;
// use Audio\Myclass\Snoopy;
class DanSnoopy {
	const PATTERN = '/(\d{1,2})\.?\s*\(?[A-D]\)([^)]*)\s+\(?[A-D]\)([^)]*)\s+\(?[A-D]\)([^)]*)\s+\(?[A-D]\s?\)([^\n]*)/';
const PATTERN_ANS = '/(\d{1,2})\.\s*([A-D])/';
function pageCount($content, $start='', $end='', $pattern='') {
	if(!$pattern) $pattern = '/href\s*=\s*"([^#"]+#?[^"]*)"/';
	if(!$start) $start = '<div class="pagesBox">';
	if(!$end) $end = '</div>';
	$content = strstr(strstr($content, $start), $end, true);
	preg_match_all($pattern, $content, $out);
	$page = array_unique($out[1]);
	// var_dump($page);
	return $page;
}
function content($url, $start='', $end='') {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, $url);
	$content = curl_exec($curl);
	curl_close($curl);
	// $content =file_get_contents($url);
		// var_dump($start);
		// var_dump($end);
	// echo $content;
		// echo strip_tags($content);
		// if(!$start) $start = '<div id="share_con">';
		// if(!$end) $end = '<div id="dezhi" class="mt10">';
	$doc = new \DOMDocument();
	@$doc->loadHTML($content);
	$nodes = $doc->getElementsByTagName('title');
	$nodeTitle = $nodes->item(0)->nodeValue;
	$pieces = explode("-", $nodeTitle);
	$title = trim($pieces[0]);
	// echo $pieces[0]; 
	// var_dump($title);
	if($start !=='') $part1 = strstr($content, $start);
	if($end !=='') $content = $part2 = strstr($part1, $end, true);
		// var_dump($part1);
		// var_dump($part2);
	return array(
		'title' => $title,
		'content' => $content,
		);
}
function links($url, $start='', $end='') {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, $url);
	$content = curl_exec($curl);
	curl_close($curl);
	// $content =file_get_contents($url);
	if(!$start) $start = '<div id="share_con">';
	if(!$end) $end = '<div id="dezhi" class="mt10">';
	$content = strstr(strstr($content, $start), $end, true);
			// var_dump($content);
			// echo $content;
	$patten = '/href\s*=\s*"(http[^#"]+#?[^"]*)"/';
	preg_match_all($patten, $content, $out);
	$res = $out[1]; 
	$key = array_search("http://i.qq.com/", $res);
	unset($res[$key]);
	return $res;
}
function audio($url, $start='', $end='') {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, $url);
	$content = curl_exec($curl);
	curl_close($curl);
	// $content =file_get_contents($url);
	if(!$start) $start = '<div id="share_con">';
	if(!$end) $end = '<div id="dezhi" class="mt10">';
	$content = strstr(strstr($content, $start), $end, true);
			// var_dump($content);
			// echo $content;
	$patten = '/href\s*=\s*"(http[^#"]+#?[^"]*)"/';
	preg_match_all($patten, $content, $out);
	return $out[1];
}
public function  fetchQuestion($subject)
{
	$pattern = '/(\d{1,2})\.\s*([A-D]\).*[^\n]*\n){4}/';
$subject_strip = str_replace('&nbsp;', '', htmlspecialchars_decode(strip_tags($subject)));
preg_match_all($pattern, $subject_strip, $match);
// var_dump($match);
// echo $subject_strip;
return array_combine($match[1], $match[0]);
}
public function  saveQuestion($subject, $collection = null)
{
	$pattern = '/([A-D])\)\s*([^\n]*)/';
$pattern_ans = '/\（([A-D])\）/';
$subject_strip = preg_replace($pattern_ans, '', $subject);
preg_match_all($pattern, $subject_strip, $match);
// var_dump($match);
preg_match_all($pattern_ans, $subject, $match_ans);
// var_dump($match_ans);

/*$quizname = $title;
// for ($i=0; $i < count($match['0']) ; $i++) {
	$select['no'] = $no;
	$select['quiz'] = $quizname;*/
	$select['A']= trim($match['2'][0]);
	$select['B']= trim($match['2'][1]);
	$select['C'] = trim($match['2'][2]);
	$select['D'] = trim($match['2'][2]);
	$select['ANS'] = trim($match_ans['1'][0]);
	// $collection->insert($select);
	// echo $i.'--------------------------<br />';
	var_dump($select);
// }

return $select;
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