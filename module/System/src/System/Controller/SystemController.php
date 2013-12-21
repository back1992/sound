<?php

namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Audio\Myclass\DanHosts;
use Audio\Myclass\DBConnection;
use MongoId;

class SystemController extends AbstractActionController
{

  public function indexAction()
  {
    return new ViewModel();
  }

  public function formDbAdapterAction()
  {
    $vm = new ViewModel();
    $vm->setTemplate('form-dependencies/form/form-db-adapter.phtml');

    $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    $form      = new DbAdapterForm($dbAdapter);

    return $vm->setVariables(array(
      'form' => $form
      ));
  }

  public function formTableAction()
  {
    $vm = new ViewModel();
    $vm->setTemplate('form-dependencies/form/form-table.phtml');

    $tableGateway = $this->getServiceLocator()->get('formdependencies-model-selecttable');
    $form         = new TableForm($tableGateway);

    return $vm->setVariables(array(
      'form' => $form
      ));
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
    $hosts = '/etc/hosts';
    $content = file_get_contents($hosts);
    // echo $content;

    $pattern = "/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\s+([^\s]+)/";
    $resMatch = preg_match_all($pattern, $content, $match);

    $myHosts = $match[1];
    $myUrls = $match[2];

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
        //fresh hosts data in db 
/*        $hosts->freshHosts();
$hosts->freshUrls();*/
$fileRes = '/etc/hosts';
$fileTar = './data/hosts';
copy($fileRes, $fileTar);
$fp = fopen($fileTar, 'w');

$myHosts = $hosts->getHosts('hosts');
$hostsArr = iterator_to_array($myHosts);
return array(
  'myHosts' => $hostsArr,
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

