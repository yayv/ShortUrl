<?php
date_default_timezone_set('PRC');
include_once("cls.getoptw.php");

abstract class CommonController extends Controller
{
    public function __construct()
    {
        $this->paramsParseErrors = '';
    }

    /**
     * 初始化数据库对象，考虑增加一层，用于实现用户的基类
     *
     */
    public function initDb($dbsrv)
    {
        include_once 'cls.mysql.php';
        // override it , if you want own database object
        //assign the object for db,tpl
        /*
        $server['port']        = isset($server['port'])?$server['port']:'3306';
        $server['charset']    = $server['charset']?$server['charset']:'utf8';
        $this->logfile  = isset($server['logfile'])?$server['logfile']:'php://output';
        $this->db        = $server;
        $this->prefix    = $server['prefix'];
        $this->count     = 0;
         */
        $this->_db = new mysql($dbsrv);
    }

    /*
    usage:
    $params = $this->getJSONParams($format);
    if(null==$params) return $this->echoParamsErrorMessage('JSON');
     */
    public function getUrlParams($url)
    {
        if(is_file("configs/api.php"))
            include_once("configs/api.php");
        else
            return false;

        $format = $apis[$url];

        return $this->getJSONParams($format);
    }

    /*
    usage:
    $params = $this->getJSONParams($format);
    if(null==$params) return $this->echoParamsErrorMessage('JSON');
     */
    public function getJSONParams($format)
    {
        // TODO: 1. 检查 format 语法是否符合要求
        // TODO: 2. 获取参数
        // TODO: 3. 检查参数是否符合 format 要求
       
        $opt = new GetOptW();
        $ret = $opt->isFormatOK($format);

        if(!$ret)
        {
            $this->paramsParseErrors = 'Params Format Error';
            return false;
        }

        $strInput = file_get_contents("php://input");
        if($strInput==""){
            $params = new stdClass();
        }
        else{
            $params = json_decode($strInput,false);
        }
      
        $str = json_last_error_msg();

        if('No error'==$str)
        {
            $ret = $opt->parseParams($params);

            if( count($opt->all_errors)<1 )
            {
            
                return json_decode(json_encode($params),true);
            }
            else
            {
                $this->paramsParseErrors = implode("\n",$opt->all_errors);
                return false;
            }
        }
        else
        {
            $this->paramsParseErrors = $str ;
            return false;
        }

        return false;
    }
    public function getPOSTParams($format)
    {
        return $_POST;
    }
    public function getGETParams($format)
    {
        return $_GET;
    }
    public function echoParamsErrorMessage($type)
    {
        if (strcmp('json', $type) == 0) {
            header('Content-Type: application/json');
            echo '{"code":"fail","message":"' . $this->paramsParseErrors . '"}';
        } else {
            header('Content-Type: text/html');
            echo '参数解析失败:' . $this->paramsParseErrors;
        }
        return;
    }
    public function isLogined()
    {
        if (empty($_SESSION['customerId'])) {
            header("Content-Type:application/json");
            echo json_encode(["code" => "fail", "message" => "请先登录"]);
            return false;
        }
        else
            return true;
    }
    
    /*
    生成指定长度的随机数，目前用于短信验证，默认5位
     */
    public function generate_code($length = 5)
    {
        $min = pow(10, ($length - 1));
        $max = pow(10, $length) - 1;
        return rand($min, $max);
    }
    /*
    检查手机号
     */
    public function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return 0;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? 1 : 0;
    }
    /*
    检查1分钟内同一手机号发送短信次数
     */
    public function renum($array, $get)
    {
        $n = 0;
        foreach ($array as $val) {
            if ($val == $get) {
                $n++;
            }
        }
        return $n;
    }
    /*
    验证身份证号
     */
    public function checkIdCard($idcard)
    {
        // 只能是18位
        if (strlen($idcard) != 18) {
            return false;
        }
        // 取出本体码
        $idcard_base = substr($idcard, 0, 17);
        // 取出校验码
        $verify_code = substr($idcard, 17, 1);
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        // 根据前17位计算校验码
        $total = 0;
        for ($i = 0; $i < 17; $i++) {
            $total += substr($idcard_base, $i, 1) * $factor[$i];
        }
        // 取模
        $mod = $total % 11;
        // 比较校验码
        if ($verify_code == $verify_code_list[$mod]) {
            return true;
        } else {
            return false;
        }
    }

    public function isCurrentCustomer($customerId)
    {
        if(isset($_SESSION['customerId']) && $_SESSION['customerId']==$customerId)
            return true;
        else
            return false;
    }
}
