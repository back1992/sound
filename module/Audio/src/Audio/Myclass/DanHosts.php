<?php
namespace Audio\Myclass;
class DanHosts {
	public $hosts;    
	public $urls;    
	public static $wall = array(
		'google.com',
		'google.com.hk',
		'drive.google.com',
		'appengine.google.com',
		'apps.google.com',
		'chrome.google.com',
		'code.google.com',
		'developers.google.com',
		'ditu.google.com',
		'encrypted.google.com',
		'finance.google.com',
		'googleapis.l.google.com',
		'mail.google.com',
		'play.google.com',
		// 'news.google.com',
		// 'docs.google.com',
		// 'spreadsheets.google.com',
		'0.drive.google.com',
		'accounts.google.com',  
		'clients3.google.com',  
		'upload.drive.google.com',
		'github.com',
		);
	public function insertHosts($hosts, $urls)
	{
		// $this->hosts
		$mongo = DBConnection::instantiate();
		$collection = $mongo->getCollection('hosts');
		// foreach ($this->hosts as $myHost) {
		// 	$collection->insert($myHost);
		// }
		for ($i=0; $i < count($hosts); $i++) { 
			# code...
			$collection->insert( array('IP' => $hosts[$i],  'URL' => $urls[$i]));
		}
		return true;
	}
	public function freshUrls()
	{
		$this->urls = $this->getUrls();
		$mongo = DBConnection::instantiate();
		$collection = $mongo->getCollection('urls');
		foreach ($this->urls as $myUrl) {
			$collection->insert(array('url' => $myUrl));
		}
		return true;
	}
	public function getHosts($hosts)
	{
		$mongo = DBConnection::instantiate();
		$collection = $mongo->getCollection($hosts);
		$this->hosts = $collection->find();
		return $this->hosts;
	}
}