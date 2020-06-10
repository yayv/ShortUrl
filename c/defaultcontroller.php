<?php 
include_once('commoncontroller.php');
include_once("cls.ShortURL.php");

class defaultcontroller extends CommonController
{
	public function index()
	{
		$uri = $_SERVER['REQUEST_URI'];

		$id = ShortURL::decode($uri);		

		parent::initDb(Core::getInstance()->getConfig('database'));
		$ret = $this->getModel('mu')->query($id);

		if($ret)
			header('location:'.$ret['url']);	
		else
			header('location:/t/page');

		return ;
	}

	
}


