<?php
namespace Audio\Myclass;
class DanHosts {
	public $hosts;    
	public $urls;    

	public function freshHosts($hosts, $urls)
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
	// public function getUrls()
	// {
	// 	$this->urls = array(
	// 		'google.com',
	// 		'drive.google.com',
	// 		'appengine.google.com',
	// 		'apps.google.com',
	// 		'chrome.google.com',
	// 		'code.google.com',
	// 		'developers.google.com',
	// 		'ditu.google.com',
	// 		'encrypted.google.com',
	// 		'finance.google.com',
	// 		'googleapis.l.google.com',
	// 		'google-public-dns-a.google.com',
	// 		'mail.google.com',
	// 		'play.google.com',
	// 		'news.google.com',
	// 		);
	// 	return $this->urls;
	// }
	public function getHosts($hosts)
	{
		$mongo = DBConnection::instantiate();
		$collection = $mongo->getCollection($hosts);
		$this->hosts = $collection->find();
		return $this->hosts;
	}
	public function getUrls()
	{
		$this->urls = array(
			'google.com',
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
			'google-public-dns-a.google.com',
			'mail.google.com',
			'play.google.com',
			'news.google.com',
			);
		return $this->urls;
	}
}