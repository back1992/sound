<?php

namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Audio\Myclass\DanHosts;
use Audio\Myclass\DanDb;
use Audio\Myclass\DBConnection;
use System\Form\HostsForm;
use MongoId;

class SystemController extends AbstractActionController
{

  public function indexAction()
  {
    return new ViewModel();
  }

  public function phpinfoAction()
  {
    phpinfo();
    return new ViewModel();
  }
  public function urlsAction()
  {
    $fileRes = '/etc/hosts';
    $hosts = './data/hosts';
    copy($fileRes, $hosts);
    $content = file_get_contents($hosts);
    // echo $content;
    $pattern = "/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\s+([^\s]+)/";
    $resMatch = preg_match_all($pattern, $content, $match);

    $myHosts = $match[1];
    $myUrls = $match[2];
    DanDb::removeDoc('hosts');
    DanHosts::insertHosts($myHosts, $myUrls);
    // var_dump($resMatch);
    // var_dump($match);

    return array(
      'myHosts' => $match[1],
      'myUrls' => $match[2],
  // 'matchIP' => $matchIP,
      );
  }
  public function addhostAction()
  {
    $myurl = $this->getEvent()->getRouteMatch()->getParam('id');

    $cmd =  "nslookup $myurl 8.8.8.8";
    $res = shell_exec($cmd);
    sleep(1);
    $pattern = "/Name:\s+[^\s]+\s+Address: (\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/";
    $resMatch = preg_match($pattern, $res, $match);

    DanHosts::insertHosts($match[1], $myUrl);
    // var_dump($resMatch);
    // var_dump($match);

    return array(
      'myHosts' => $match[1],
      'myUrls' => $match[2],
  // 'matchIP' => $matchIP,
      );
  }
  public function hostsAction()
  {
    $hosts =  new DanHosts();
    $form = new HostsForm();
    $fileRes = '/etc/hosts';
    $fileTar = './data/hosts';
    copy($fileRes, $fileTar);
    $fp = fopen($fileTar, 'w');
    $content = file_get_contents($fileRes);
    $sep = "###########---my wall- ---#########";

    $mySelf = strstr($content, $sep, true) ? strstr($content, $sep, true) : rtrim($content);
    $myWall = strstr($content, $sep);

    // $pattern = "/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\s+([^\s]+)/";
    // $resSelfMatch = preg_match_all($pattern, $mySelf, $selfMatch);
    // $resWallMatch = $myWall ? preg_match_all($pattern, $myWall, $wallMatch) : DanHosts::getWall();
    // $resWallMatch = $myWall ? preg_match_all($pattern, $myWall, $wallMatch) : DanHosts::$wall;
    // preg_match_all($pattern, $myWall, $wallMatch);
    // var_dump($selfMatch);
    // var_dump($wallMatch);
    $resMatch = DanHosts::$wall;
    // fwrite($fp, "############################### \n");
    // fwrite($fp, "###########---my hosts- ---######### \n");
    fwrite($fp, "$mySelf ");


/*    for ($i=0; $i < count($selfMatch[0]) ; $i++) { 
   # code...
      $selfip = $selfMatch[1][$i];
      $selfUrl = $selfMatch[2][$i];

  // fwrite($fp, "(string)$selfMatch[1][$i]         (string)$selfMatch[2][$i]  \n");
      fwrite($fp, "$selfip         $selfUrl  \n");
    }*/
    fwrite($fp, "###########---my wall- ---######### \n");
    for ($i=0; $i < count($resMatch) ; $i++) { 
      $wallUrl = $resMatch[$i];
      $cmd =  "nslookup $wallUrl 8.8.8.8";
      $res = shell_exec($cmd);
      sleep(1);
      $pattern = "/Name:\s+[^\s]+\s+Address: (\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/";
      preg_match($pattern, $res, $match);
      $wallip = $match[1];

      fwrite($fp, "$wallip         $wallUrl  \n");
    }
    fclose($fp);
        // exec("sudo -u root -S {{ cp  $fileTar  $fileRes }} < ~/.sudopass/sudopass.secret");
        // exec("sudo -u root -S  cp  $fileTar  $fileRes  < ~/.sudopass/sudopass.secret", $info);
        // var_dump($info);
    copy($fileTar, $fileRes);
    $myHosts = $hosts->getHosts('hosts');

    $request = $this->getRequest();
    if ($request->isPost()) {
      // Make certain to merge the files info!
      $post = $request->getPost();
      $form->setData($post);
      // var_dump($data);
      $title = $post['title'];
    }
    return array(
      'myHosts' => $myHosts,
      'form' => $form,
      );
  }

  public function deletehostAction(){
    $id = $this->getEvent()->getRouteMatch()->getParam('id');
  // $url = 'play.google.com';
    $mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
    $collection = $mongo->getCollection('urls');
    $res = $collection->remove(array('_id' => new MongoId($id)));
  // var_dump($res);
    return FALSE;
  }
  public function genhostAction(){
   $hosts =  new DanHosts();
   $fp = fopen('./data/hosts', 'w');
   $myHosts = $hosts->getHosts('hosts');
   $myUrls = $hosts->getHosts('urls');
   $hostsArr = iterator_to_array($myHosts);
   fwrite($fp, "############################### \n");
   fwrite($fp, "###########---my hosts- ---######### \n");
   foreach ($hostsArr as $myhost) {
    fwrite($fp, "$myhost[ip]         $myhost[url]  \n");
  }
  fwrite($fp, "\n\n\n###########---my wall- ---######### \n");
  $urlsArr = iterator_to_array($myUrls);
  foreach ($urlsArr as $url) {
    $urltmp = $url['url'];
    $cmd =  "nslookup $urltmp 8.8.8.8";
    $res = shell_exec($cmd);
    sleep(1);
    $pattern = "/Name:\s+[^\s]+\s+Address: (\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/";
    $resMatch = preg_match($pattern, $res, $match);
    fwrite($fp, "$match[1]         $urltmp  \n");
  }
  fclose($fp);
}

}

