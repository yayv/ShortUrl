<?php
include_once('commoncontroller.php');
include_once("cls.ShortURL.php");

class t extends  CommonController
{
	public function index()
	{
		/*
```
URL:http://x0a.top/t
MOTHOD:POST
FORM:JSON/POST_FORM
REQUEST:{
	"url":"*url"
}

RESPONSE:{
	"code":"*retCode",
	"url":"*url//shorted url",
	"qrcode":"inlineImage//shorted url image"
}
```
		*/
	
		$params = json_decode(file_get_contents("php://input"));
		if(!isset($params->url))
		{
			header('location:/t/page');
		}

		$md5 = md5($params->url);

		parent::initDb(Core::getInstance()->getConfig('database'));
		$ret = $this->getModel('mu')->checkSame($params->url);

		header('Content-Type:application/json');

		if($ret)
		{
			$str = ShortURL::encode($ret['id']);
			echo json_encode(['code'=>'ok','shorturl'=>'http://'.$_SERVER['HTTP_HOST'].'/'.$str]);
			return ;
		}
		else
		{
			$id = $this->getModel('mu')->add($params->url);
			
			if($id>0)
			{

				$str = ShortURL::encode($id);
				#print_r([$str,$id,$params,1]);
				echo json_encode(['code'=>'ok', 'shorturl'=>'http://'.$_SERVER['HTTP_HOST']].'/'.$str);
				return ;
			}
			else
			{
				echo json_encode(['code'=>'fail','message'=>'create short url failed']);
				return ;
			}
			return ;
		}

	}

	public function page()
	{
		echo "这里进行介绍和进行页面操作";
	}

	public function a()
	{

	}

}

