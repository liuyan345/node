<?php
/**
 * Created by PhpStorm.
 * User: zxf
 * Date: 2017/5/19 0019
 * Time: 下午 7:52
 */
header("Content-type:text/html;charset=utf-8");

class Hook
{

    public $ip = array();       //定义IP地址
    public $token = "";        //眼前token
    public $path = "";         //路径
    public $power = array();  //0:ip 开关 1:token开关  3:是否保存日志
    private $time = '';       //时间
    private $_content;
    private $_gitToken;      //gitLab 的私密授权码
    public static $success = "验证成功";
    public static $error = "验证失败";
    public static $status = "未开启验证";  //换行
    public static $htmlchars = PHP_EOL;  //换行


    public function __construct($ip = array(), $token = '', $path = '', $power = array(), $gitToken = '')
    {
        $this->ip        = empty($ip) ? '' : $ip;
        $this->token     = empty($token) ? '' : $token;
        $this->_gitToken = $gitToken;
        $this->path      = empty($path) ? './hooks.log' : $path;
        $this->power     = empty($power) ? array(1, 1, 1, 1) : $power;
        $this->time      = time();
    }

    public function main()
    {
        $flagArr        = $this->power;
        $this->_content = self::fileGetContent();

        $Flag['verifyIpFlag']  = ($flagArr[0]) ? $this->vaifyIp() : '';             //ip验证
        $Flag['verifyToken']   = ($flagArr[1]) ? $this->vaifyToken() : '';           //token 验证
        $Flag['verifyContent'] = ($flagArr[2]) ? $this->verifyContent() : '';       //私密验证

        $this->hookLog('3', $this->_content);
        $result = self::makeFun($flagArr, $Flag);
      
        $str    = self::result($result);
        error_log(print_r($str), 3, "./test.log");
        return $str;
    }

    /**
     * ip 验证
     * @return boolean
     */
    private function vaifyIp()
    {

        $client_ip = self::getIP();

        $this->hookLog(0, '本次更新的IP:' . $client_ip . self::$htmlchars);

        if (in_array($client_ip, $this->ip)) {
            $this->hookLog(1, $client_ip . self::$success);
            return true;
        } else {
            $this->hookLog(1, $client_ip . self::$error);
            return false;
        }
    }

    /**
     * token 验证
     * @return boolean
     */
    private function vaifyToken()
    {
        $token      = trim($this->token);
        $vaifyToken = $_GET['token'];
        if ($token == $vaifyToken) {
            $this->hookLog(2, self::$success);
            return true;
        } else {
            $this->hookLog(2, self::$error);
            return false;
        }
    }

    /**
     * git 私密验证
     * @return bool
     */
    private function verifyContent()
    {
        $putContent = $_SERVER['HTTP_X_GITLAB_TOKEN'];
        if ($putContent == $this->_gitToken) {
            $this->hookLog(4, self::$success);
            return true;
        } else {
            $this->hookLog(4, self::$error);
            return false;
        }
    }

    /*
     * 验证内部信息
     */

    public static function fileGetContent()
    {

        $PutContent = file_get_contents("php://input");
        return $PutContent;
    }

    /*
     * 获取IP 的方法
     */

    public static function makeFun($flagArr, $Flag)
    {
        $str  = "";
        $flag = 1;
        foreach ($flagArr as $key => $val) {
            switch ($key) {
                case 0:
                    //ip 验证
                    $str  .= ($val == 1) ? ($Flag['verifyIpFlag']) ? self::$success : self::$error : "IP" . self::$status . self::$htmlchars;
                    $flag = ($val == 1) ? ($Flag['verifyIpFlag']) ? $flag * 1 : $flag * 0 : 2;
                    break;
                case 1:
                    //token 验证
                    $str  .= ($val == 1) ? ($Flag['verifyToken']) ? self::$success : self::$error : "Token" . self::$status . self::$htmlchars;
                    $flag = ($val == 1) ? ($Flag['verifyToken']) ? $flag * 1 : $flag * 0 : 2;
                    break;
                //sha 验证
                case 2:
                    $str  .= ($val == 1) ? ($Flag['verifyContent']) ? self::$success : self::$error : "sha" . self::$status . self::$htmlchars;
                    $flag = ($val == 1) ? ($Flag['verifyContent']) ? $flag * 1 : $flag * 0 : 2;
                    break;
                case 3:
                    //留一个分区
                    break;
            }
        }
        $result = array("str" => $str, "flag" => $flag);
        return $result;
    }

    /**
     * 强制执行结果
     * @param $Flag
     * @return string
     */
    public static function result($Flag)
    {
        $str = $Flag['str'];
        if ($Flag['flag']) {
            $str .= self::$htmlchars;
            $a = shell_exec('cd /data/www/node;git stash; git pull;');
            error_log(print_r($a), 3, "./test.log");
            $str .= PHP_EOL . "git" . self::$success;
        } else {
            $str .= PHP_EOL . "git" . self::$error;
        }
        return $str;
    }

    /**
     * 获取用户IP
     * @return array|false|string
     */

    public static function getIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $returnIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $returnIp = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $returnIp = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $returnIp = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $returnIp = getenv("HTTP_CLIENT_IP");
            } else {
                $returnIp = getenv("REMOTE_ADDR");
            }
        }
        return $returnIp;
    }

    /**
     * 日志打印
     * @param type $type
     * @param type $contentLogs
     */
    private function hookLog($type = '', $contentLogs = '')
    {
        $times = date("Y-m-d H:i:s", $this->time);
        switch ($type) {
            case 1:
                error_log(print_r('当前时间' . $times . '  ' . $this->time . '   本次IP:' . $contentLogs . self::$htmlchars, 1), 3, $this->path);
                break;
            case 2:
                error_log(print_r('当前时间' . $times . '  ' . $this->time . '  token验签：' . $contentLogs . self::$htmlchars, 1), 3, $this->path);
                break;
            case 3:
                error_log(print_r($contentLogs . '验证结束' . self::$htmlchars, 1), 3, $this->path);
                break;
            case 4:
                error_log(print_r('当前时间' . $times . '  ' . $this->time . '  本次私密验证:' . $contentLogs . self::$htmlchars, 1), 3, $this->path);
                break;
            default :
                error_log(print_r($contentLogs, 1), 3, $this->path);
                break;
        }
    }

}

//参数配置
$ip       = array('127.0.0.1', '192.168.1.1', '::1', '120.79.166.44');       //允许IP访问
$token    = '68abaad53bfc926a62d4d498cf9eac42aaxf4ed1026effd32a29300';    //GET 验签KEY
$gitToken = 'liuyanTestNode';                                                      //gitlab 私密授权吗
$path     = "./hooks.log";                                                //日志配置路径
$power    = array(0, 1, 0, 0);                                           //0:ip 开关 1:token开关 2:内容验证  （4 待定）
//方法调用
$hooksClass = new Hook($ip, $token, $path, $power, $gitToken);
$Logs       = $hooksClass->main();
