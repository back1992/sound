<?php
namespace Snoopy\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Audio\Myclass\DanSnoopy;
use Audio\Myclass\DanAudio;
use Audio\Myclass\Dandan;
use Audio\Myclass\DanCurl;
use Audio\Myclass\Snoopy;
class SnoopyController extends AbstractActionController
{
	public function indexAction()
	{
		$snoopy = new Snoopy;

		$snoopy->fetchtext("http://www.php.net/");
		print $snoopy->results;

		$snoopy->fetchlinks("http://www.phpbuilder.com/");
		print $snoopy->results;

		$submit_url = "http://lnk.ispi.net/texis/scripts/msearch/netsearch.html";

		$submit_vars["q"] = "amiga";
		$submit_vars["submit"] = "Search!";
		$submit_vars["searchhost"] = "Altavista";
		
		$snoopy->submit($submit_url,$submit_vars);
		print $snoopy->results;

		$snoopy->maxframes=5;
		$snoopy->fetch("http://www.ispi.net/");
		echo "<PRE>\n";
		echo htmlentities($snoopy->results[0]); 
		echo htmlentities($snoopy->results[1]); 
		echo htmlentities($snoopy->results[2]); 
		echo "</PRE>\n";
		$snoopy->fetchform("http://www.altavista.com");
		print $snoopy->results;
		return FALSE;
	}
	public function fetchlinksAction(){
		// $url = "http://www.cet4v.com/Ch3_Kind3_zttl.asp";
		$url = "http://www.tingclass.net/show-5406-3632-1.html";
		// $res = DanSnoopy::fetchlinks($url);
		// var_dump($res);
		$snoopy = new Snoopy;
		$res = $snoopy->fetchlinks($url);
		var_dump($res);	
		return false;
	}
	public function fetchtextAction(){
		// $url = "http://www.cet4v.com/exam/10975.asp";
		$url = "http://www.tingclass.net/show-5406-3632-1.html";
		var_dump(DanSnoopy::fetchlinks($url));
		$res = DanSnoopy::fetchtext($url);
		print $res;
		return false;
	}
	public function fetchlistenAction(){
		$url = "http://www.cet4v.com/exam/10975.asp";
		$content = DanSnoopy::fetchtext($url);
		// print $content;
		$begin = 'Listening Comprehension' ;
		$end = '答案：'; 
		$res = DanAudio::slice($content, $begin, $end);
		$listen = Dandan::readquestion($res);
		// var_dump($listen);
		// print $res;
		return array('listen' => $listen);
	}
	public function curlAction(){
		$ch = curl_init();
		// $url = "http://www.cet4v.com/exam/10975.asp";
		$url = "http://www.tingclass.net/show-5406-3632-1.html";
		curl_setopt_array(
			$ch, array( 
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true
				));

		$output = curl_exec($ch);
		echo $output;
		return false;
		return array('listen' => $listen);
	}
	public function addquizAction() {
        //set the $url and $refurl
		$url = "http://localhost/ticool/administrator/index.php?option=com_ariquizlite&task=question_add&quizId=1";
		$refurl = "http://localhost/ticool/administrator/index.php";
//        $url = "http://highschool3.local/administrator/index.php?option=com_ariquizlite&task=question_add&quizId=1";
//        $refurl = "	http://highschool3.local/administrator/index.php";
		DanCurl::curl3($url, $refurl);
		return False;
	}
	public function addquiz2Action() {
        //set the $url and $refurl
		$url = "http://localhost/ticool/administrator/index.php?option=com_ariquizlite&task=question_add&quizId=1";
		$refurl = "http://localhost/ticool/administrator/index.php";
//        $url = "http://highschool3.local/administrator/index.php?option=com_ariquizlite&task=question_add&quizId=1";
//        $refurl = "	http://highschool3.local/administrator/index.php";
		DanCurl::newcurl3($url, $refurl);
		return False;
	}
}
