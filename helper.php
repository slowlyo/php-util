<?php

require_once __DIR__ . "/utils/Captcha.php";
require_once __DIR__ . "/utils/Chain.php";
require_once __DIR__ . "/utils/IdCard.php";
require_once __DIR__ . "/utils/Lottery.php";
require_once __DIR__ . "/utils/ArrHelper.php";
require_once __DIR__ . "/utils/StrHelper.php";
require_once __DIR__ . "/utils/DateHelper.php";
require_once __DIR__ . "/utils/Check.php";
require_once __DIR__ . "/utils/phpqrcode.php";

if (!function_exists('util_chain')) {
    /**
     * 链式调用
     *
     * @param $data
     *
     * @return Chain
     */
    function util_chain($data)
    {
        return new Chain($data);
    }
}

if (!function_exists('util_idcard')) {
    /**
     * 身份证相关
     *
     * @param $id_number
     *
     * @return IdCard
     */
    function util_idcard($id_number)
    {
        return new IdCard($id_number);
    }
}

if (!function_exists('util_lottery')) {
    /**
     * 概率算法
     *
     * @param array $arr
     *
     * @return Lottery
     */
    function util_lottery(array $arr = [])
    {
        return new Lottery($arr);
    }
}

if (!function_exists('util_captcha')) {
    /**
     * 图像验证码
     *
     * @param int $width
     * @param int $height
     * @param int $codeNum
     *
     * @return Captcha
     */
    function util_captcha($width = 80, $height = 20, $codeNum = 4)
    {
        return new Captcha($width, $height, $codeNum);
    }
}

if (!function_exists('util_array')) {
    /**
     * 数组处理
     *
     * @param $arr
     *
     * @return ArrHelper
     */
    function util_array($arr)
    {
        return new ArrHelper($arr);
    }
}

if (!function_exists('util_str')) {
    /**
     * 处理字符串
     *
     * @param $str
     *
     * @return StrHelper
     */
    function util_str($str)
    {
        return new StrHelper($str);
    }
}

if (!function_exists('util_check')) {
    /**
     * 检查数据
     *
     * @param $data
     *
     * @return Check
     */
    function util_check($data)
    {
        return new Check($data);
    }
}

if (!function_exists('util_date')) {
    /**
     * 处理日期
     *
     * @param $date
     *
     * @return DateHelper
     */
    function util_date($date = null)
    {
        return new DateHelper($date);
    }
}

if (!function_exists('_dump')) {
    /**
     * 比 var_dump() 要好看的打印
     *
     * @param $data
     */
    function _dump($data)
    {
        highlight_string("<?php \n\n" . var_export($data, true) . ";\n\n?>");
    }
}


if (!function_exists('_dd')) {
    function _dd($data)
    {
        _dump($data);
        die;
    }
}

if (!function_exists('get_ip')) {
    /**
     * 获取 IP 地址
     *
     * @return mixed|string
     */
    function get_ip()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = "";
        }

        return $cip;
    }
}

if (!function_exists('domain_name')) {
    /**
     * 获取域名
     *
     * @return string
     */
    function domain_name()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

        return $http_type . $_SERVER['HTTP_HOST'];
    }
}

if (!function_exists('_redirect')) {
    /**
     * 重定向
     *
     * @param $url
     */
    function _redirect($url)
    {
        header("Location: $url");
        exit();
    }
}

if (!function_exists('file_to_line')) {
    /**
     * 一行行读取文件
     *
     * @param $path
     *
     * @return array|false
     */
    function file_to_line($path)
    {
        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}

if (!function_exists('_http')) {
    /**
     * http 请求
     *
     * @param       $url
     * @param       $method
     * @param null $postfields
     * @param array $headers
     *
     * @return array
     */
    function _http($url, $method, $postfields = null, array $headers = [])
    {
        $method = strtoupper($method);
        $ci     = curl_init();

        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }

        $ssl = preg_match('/^https:\/\//i', $url);
        curl_setopt($ci, CURLOPT_URL, $url);

        if ($ssl) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false); // 不从证书中检查SSL加密算法是否存在
        }

        //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);

        /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response     = curl_exec($ci);
        $request_info = curl_getinfo($ci);
        $http_code    = curl_getinfo($ci, CURLINFO_HTTP_CODE);

        curl_close($ci);

        return compact('response', 'request_info', 'http_code');
    }
}


if (!function_exists('sn')) {
    /**
     * 生成sn
     *
     * @param string $prefix 前缀
     *
     * @return string
     */
    function sn(string $prefix = '')
    {
        return $prefix . date('YmdHis') . mt_rand(10000, 100000);
    }
}
