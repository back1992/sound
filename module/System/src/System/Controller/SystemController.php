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
    $hosts = '/etc/hosts';
    $url = 'google.com';
    $cmd =  "nslookup $url 8.8.8.8";
    $res = shell_exec($cmd);
    var_dump($res);
    $pattern = "/Name:\s+[a-z\.]+\s+Address: (\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/";
    $resMatch = preg_match($pattern, $res, $match);
    var_dump($resMatch);
    var_dump($match);
    $hoststring = "$match[1]    $url";
    var_dump($hoststring);
    $handle = @fopen($hosts, "r");
    if ($handle) {
      while (($buffer = fgets($handle, 4096)) !== false) {
        echo $buffer.'<br />';
                // echo $buffer;
      }
      if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
      }
      fclose($handle);
    }
    return FALSE;
    return new ViewModel();
  }
  public function hostsAction()
  {
    $hosts =  new DanHosts();
        //fresh hosts data in db 
/*        $hosts->freshHosts();
$hosts->freshUrls();*/
$fp = fopen('./data/hosts', 'w');

$myHosts = $hosts->getHosts('hosts');
$myUrls = $hosts->getHosts('urls');
$hostsArr = iterator_to_array($myHosts);
// fwrite($fp, "############################### \n");
// fwrite($fp, "###########---my hosts- ---######### \n");
// foreach ($hostsArr as $myhost) {
//   fwrite($fp, "$myhost[ip]         $myhost[url]  \n");
// }
// fwrite($fp, "\n\n\n###########---my wall- ---######### \n");
$urlsArr = iterator_to_array($myUrls);
// foreach ($urlsArr as $url) {
//            # code...
//         // var_dump($url['url']);
//   $urltmp = $url['url'];
//   $cmd =  "nslookup $urltmp 8.8.8.8";


//   $res = shell_exec($cmd);
//   sleep(1);
//         // var_dump($res);
//   $pattern = "/Name:\s+[^\s]+\s+Address: (\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/";
//   $resMatch = preg_match($pattern, $res, $match);
//         // var_dump($match[1]);
//         // array_push($url, array('ip' => $match[1]));
//         // $url['ip'] = $match[1];
//   fwrite($fp, "$match[1]         $urltmp  \n");
//   $matchIP[] = $match[1];
// }
//        // var_dump($urlsArr);
// fclose($fp);
       // $cmd_cp = "cp data.txt  /etc/hosts";
       // $rescp = exec($cmd_cp);
       // $rescp = system('echo "Joomla8" | sudo -u root -S "cp data.txt  /etc/hosts"');
       // $rescp = system('echo "Joomla8" | " sudo  cp data.txt  /etc/hosts"');
       // $rescp = exec('sudo -u root -S  cp ./data.txt  /etc/hosts  < ~/.sudopass/sudopass.secret');
       // $rescp = shell_exec('sudo -u root -S cp ./data.txt  /etc/hosts  < ~/.sudopass/sudopass.secret');
       // var_dump($rescp);
       // return false;
return array(
  'myHosts' => $hostsArr,
  'myUrls' => $urlsArr,
  // 'matchIP' => $matchIP,
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

