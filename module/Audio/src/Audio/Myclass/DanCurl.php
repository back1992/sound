<?php
namespace Audio\Myclass;
class DanCurl {

	const USER = 'admin';
	const PASSWD = 'Joomla8';

	public function newcurl3($url, $refurl, $quiz) {
	// $url = "http://localhost/ticool/administrator/";
		$cookie = "./data/tmpuploads/cookie".time().".txt";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_URL, $url);
		ob_start(); 
		$page = curl_exec ($ch);
		curl_close ($ch);
		unset($ch);

//get hidden inputs
		preg_match_all("(<input type=\"hidden\" name=\"return\" value=\"(.*)\" />)siU", $page,                     $matches1);
		preg_match_all("(<input type=\"hidden\" name=\"(.*)\" value=\"1\" />)iU", $page,     $matches2);
		$return = trim($matches1[1][0]);
		$key = trim($matches2[1][0]);

		$param = 'username='.urlencode("admin")."&passwd=".urlencode("Joomla8")."&lang=&option=com_login&task=login&return=".urlencode($return)."&".urlencode($key)."=1";

		$fp = fopen($cookie,"w");
		fclose($fp);

//login
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		ob_start();      
		$page2 = curl_exec ($ch);

//        echo $ret;



/*		$crawl_str = file_get_contents($file_seri);
$crawl_arr = unserialize($crawl_str);*/
$mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
$collection = $mongo->getCollection('question');

// $quiz = 'cet4_200006';
$audioDir = Dandan::RESDIR.$quiz.DIRECTORY_SEPARATOR;
$audioFiles = Dandan::dirToArray($audioDir);
$audioNames = array();
foreach ($audioFiles as $audioFile) {
	# code...
	$audioNames[] = pathinfo($audioFile, PATHINFO_FILENAME);
}
$audioNames_uni = array_unique($audioNames);

$questions = $collection->find(array('quiz' => $quiz))->sort(array('no' => 1));

        //set the CONST of this quiz
$questionCategoryId = 1;
$templateId = 0;
$questionTypeId = 1;
$score = 5;
//        $chkSQRandomizeOrder = 0;
//        $ddlSQView = 0;
$questionId = '';
//        $hidemainmenu = 1;
//        $boxchecked = '';
$option = 'com_ariquizlite';
$task = 'question_add$save';
$quizId = DanMysql::fetchQuizId($quiz);

		// foreach ($crawl_arr as $quiz_data) {
while($quiz_data = $questions->getNext()) {
	$postquestion = array();
	$postquestion['zQuiz[QuestionCategoryId]'] = $questionCategoryId;
	$postquestion['templateId'] = $templateId;
	$postquestion['questionTypeId'] = $questionTypeId;
	$postquestion['zQuiz[Score]'] = $score;
	// $postquestion['zQuiz[Question]'] = $quiz_data['quiz'];
	$postquestion['zQuiz[Question]'] = "<p>No. ".$quiz_data['no']."</p><p>
	<audio controls='' autoplay='true'>
		<source src='images/result/".$quiz."/".current($audioNames_uni).".mp3' type='audio/mpeg'>
			<source src='images/result/".$quiz."/".current($audioNames_uni).".ogg' type='audio/ogg'>
			</audio></p>";

// /ticool/images/result/cet4_200006/cet4_200006_silence_02.ogg
//            $postquestion['zQuiz[Note]'] = $quiz_data['note'];
//            $postquestion['chkSQRandomizeOrder'] = $chkSQRandomizeOrder;
//            $postquestion['ddlSQView'] = $ddlSQView;

			$postquestion['tbxAnswer_0'] = $quiz_data['A'];
			$postquestion['hidQueId_0'] = '';
			$postquestion['hidCorrect_0'] = ($quiz_data['answer'] == 'A') ? TRUE : '';
//            $postquestion['tbxScore_0'] = '';
			$postquestion['tblQueContainer_hdnstatus_0'] = '';
			$postquestion['rbCorrect'] = true;
			$postquestion['tbxAnswer_1'] = $quiz_data['B'];
			$postquestion['hidQueId_1'] = '';
			$postquestion['hidCorrect_1'] = ($quiz_data['answer'] == 'B') ? TRUE : '';
//            $postquestion['tbxScore_1'] = '';
			$postquestion['tblQueContainer_hdnstatus_1'] = '';
			$postquestion['tbxAnswer_2'] = $quiz_data['C'];
			$postquestion['hidQueId_2'] = '';
			$postquestion['hidCorrect_2'] = ($quiz_data['answer'] == 'C') ? TRUE : '';
//            $postquestion['tbxScore_2'] = '';
			$postquestion['tblQueContainer_hdnstatus_2'] = '';

			$postquestion['tbxAnswer_3'] = $quiz_data['D'];
			$postquestion['hidQueId_3'] = '';
			$postquestion['hidCorrect_3'] = ($quiz_data['answer'] == 'D') ? TRUE : '';
//            $postquestion['tbxScore_3'] = '';
			$postquestion['tblQueContainer_hdnstatus_3'] = '';


			$postquestion['questionId'] = $questionId;
//            $postquestion['hidemainmenu'] = $hidemainmenu;
//            $postquestion['boxchecked'] = $boxchecked;
			$postquestion['option'] = $option;
			$postquestion['task'] = $task;
			$postquestion['quizId'] = $quizId;


			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_REFERER, $refurl);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postquestion);
			next($audioNames_uni);
// $ret = curl_exec($ch);
			if (curl_exec($ch) === false) {
				echo 'Curl error: ' . curl_error($ch);
			} else {
				echo "报告首长！ 第" . $quiz_data['no'] . "号试题添加操作完成，没有任何错误</br>\n";
			}
		}
	}
	public function addquizs($url, $refurl) {
	// $url = "http://localhost/ticool/administrator/";
		$cookie = "./data/tmpuploads/cookie".time().".txt";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_URL, $url);
		ob_start(); 
		$page = curl_exec ($ch);
		var_dump($page);
		curl_close ($ch);
		unset($ch);

//get hidden inputs
		preg_match_all("(<input type=\"hidden\" name=\"return\" value=\"(.*)\" />)siU", $page,                     $matches1);
		preg_match_all("(<input type=\"hidden\" name=\"(.*)\" value=\"1\" />)iU", $page,     $matches2);
		$return = trim($matches1[1][0]);
		$key = trim($matches2[1][0]);

		$param = 'username='.urlencode("admin")."&passwd=".urlencode("Joomla8")."&lang=&option=com_login&task=login&return=".urlencode($return)."&".urlencode($key)."=1";

		$fp = fopen($cookie,"w");
		fclose($fp);

//login
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		ob_start();      
		$page2 = curl_exec ($ch);

		echo $page2;
		$mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
		$collection = $mongo->getCollection('question');


		$quizs = $collection->distinct('quiz');
		sort($quizs);
		var_dump($quizs);

		foreach ($quizs as $quiz) {
// while($quiz = $quizs->getNext()) {
			$postquestion = array();
			$postquestion["AccessGroup[]"]="0";
			$postquestion["Category[]"]="1";
			$postquestion["act"]="";
			$postquestion["option"]="com_ariquizlite";
			$postquestion["quizId"]="";
			$postquestion["task"]='quiz_add$apply';
			$postquestion["zQuiz[Active]"]="1";
			$postquestion["zQuiz[AdminEmail]"]="back1992@gmail.com";
			$postquestion["zQuiz[AttemptCount]"]="0";
			$postquestion["zQuiz[CanSkip]"]="1";
			$postquestion["zQuiz[CssTemplateId]"]="3";
			$postquestion["zQuiz[Description]"]=$quiz;
			$postquestion["zQuiz[LagTime]"]="0";
			$postquestion["zQuiz[PassedScore]"]="60";
			$postquestion["zQuiz[QuestionCount]"]="20";
			$postquestion["zQuiz[QuestionTime]"]="300";
			$postquestion["zQuiz[QuizName]"]=$quiz;
			$postquestion["zQuiz[TotalTime]"]="3000";
			$postquestion["zTextTemplate[QuizAdminEmail]"]="1";
			$postquestion["zTextTemplate[QuizFailedEmail]"]="1";
			$postquestion["zTextTemplate[QuizFailedPrint]"]="1";
			$postquestion["zTextTemplate[QuizFailed]"]="1";
			$postquestion["zTextTemplate[QuizSuccessfulEmail]"]="1";
			$postquestion["zTextTemplate[QuizSuccessfulPrint]"]="1";
			$postquestion["zTextTemplate[QuizSuccessful]"]=1;

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_REFERER, $refurl);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postquestion);
// $ret = curl_exec($ch);
			if (curl_exec($ch) === false) {
				echo 'Curl error: ' . curl_error($ch);
			} else {
				echo "报告首长！ 第号试题添加操作完成，没有任何错误</br>\n";
			}
		}
	}
}