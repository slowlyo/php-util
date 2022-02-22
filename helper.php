<?php

spl_autoload_register(function ($class_name) {
    require_once "utils/$class_name.php";
});

class Helper
{
    /**
     * 锁
     * @return RedisLock
     */
    public static function redisLock()
    {
        return RedisLock::getInstance();
    }

    /**
     * 身份证
     * @param $id
     * @return IdCard
     */
    public static function idCard($id)
    {
        return new IdCard($id);
    }

    /**
     * 图像处理
     * @param $file
     * @return Image
     */
    public static function image($file)
    {
        return new Image($file);
    }

    /**
     * 概率算法
     * @param $arr
     * @return Lottery
     */
    public static function lottery($arr)
    {
        return new Lottery($arr);
    }

    /**
     * 图形验证码
     * @param $width
     * @param $height
     * @param $codeNum
     * @return Captcha
     */
    public static function captcha($width = 80, $height = 20, $codeNum = 4)
    {
        return new Captcha($width, $height, $codeNum);
    }

    /**
     * 链式调用PHP自带函数
     * @param $data
     * @return Chain
     */
    public static function chain($data)
    {
        return new Chain($data);
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

if (!function_exists('_array_get')) {
    function _array_get(array $array, $key, $default = null)
    {
        return $array[$key] ?? $default;
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

if (!function_exists('first_char')) {
    /**
     * 获取首字母
     */
    function first_char($str)
    {
        if (empty($str)) {
            return '';
        }
        //取出参数字符串中的首个字符
        $temp_str = substr($str, 0, 1);
        if (ord($temp_str) > 127) {
            $str = substr($str, 0, 3);
        } else {
            $str = $temp_str;
            $fchar = ord($str[0]);
            if ($fchar >= ord('A') && $fchar <= ord('z')) {
                return strtoupper($temp_str);
            } else {
                return null;
            }
        }
        $s1 = iconv('UTF-8', 'gb2312//IGNORE', $str);
        if (empty($s1)) {
            return null;
        }
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        if (empty($s2)) {
            return null;
        }
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        if ($str == '哒') {
            return 'D';
        }

        return rare_words($asc);
    }

    //百家姓中的生僻字
    function rare_words($asc = '')
    {
        $rare_arr = [
            -3652 => ['word' => "窦", 'first_char' => 'D'],
            -8503 => ['word' => "奚", 'first_char' => 'X'],
            -9286 => ['word' => "酆", 'first_char' => 'F'],
            -7761 => ['word' => "岑", 'first_char' => 'C'],
            -5128 => ['word' => "滕", 'first_char' => 'T'],
            -9479 => ['word' => "邬", 'first_char' => 'W'],
            -5456 => ['word' => "臧", 'first_char' => 'Z'],
            -7223 => ['word' => "闵", 'first_char' => 'M'],
            -2877 => ['word' => "裘", 'first_char' => 'Q'],
            -6191 => ['word' => "缪", 'first_char' => 'M'],
            -5414 => ['word' => "贲", 'first_char' => 'B'],
            -4102 => ['word' => "嵇", 'first_char' => 'J'],
            -8969 => ['word' => "荀", 'first_char' => 'X'],
            -4938 => ['word' => "於", 'first_char' => 'Y'],
            -9017 => ['word' => "芮", 'first_char' => 'R'],
            -2848 => ['word' => "羿", 'first_char' => 'Y'],
            -9477 => ['word' => "邴", 'first_char' => 'B'],
            -9485 => ['word' => "隗", 'first_char' => 'K'],
            -6731 => ['word' => "宓", 'first_char' => 'M'],
            -9299 => ['word' => "郗", 'first_char' => 'X'],
            -5905 => ['word' => "栾", 'first_char' => 'L'],
            -4393 => ['word' => "钭", 'first_char' => 'T'],
            -9300 => ['word' => "郜", 'first_char' => 'G'],
            -8706 => ['word' => "蔺", 'first_char' => 'L'],
            -3613 => ['word' => "胥", 'first_char' => 'X'],
            -8777 => ['word' => "莘", 'first_char' => 'S'],
            -6708 => ['word' => "逄", 'first_char' => 'P'],
            -9302 => ['word' => "郦", 'first_char' => 'L'],
            -5965 => ['word' => "璩", 'first_char' => 'Q'],
            -6745 => ['word' => "濮", 'first_char' => 'P'],
            -4888 => ['word' => "扈", 'first_char' => 'H'],
            -9309 => ['word' => "郏", 'first_char' => 'J'],
            -5428 => ['word' => "晏", 'first_char' => 'Y'],
            -2849 => ['word' => "暨", 'first_char' => 'J'],
            -7206 => ['word' => "阙", 'first_char' => 'Q'],
            -4945 => ['word' => "殳", 'first_char' => 'S'],
            -9753 => ['word' => "夔", 'first_char' => 'K'],
            -10041 => ['word' => "厍", 'first_char' => 'S'],
            -5429 => ['word' => "晁", 'first_char' => 'C'],
            -2396 => ['word' => "訾", 'first_char' => 'Z'],
            -7205 => ['word' => "阚", 'first_char' => 'K'],
            -10049 => ['word' => "乜", 'first_char' => 'N'],
            -10015 => ['word' => "蒯", 'first_char' => 'K'],
            -3133 => ['word' => "竺", 'first_char' => 'Z'],
            -6698 => ['word' => "逯", 'first_char' => 'L'],
            -9799 => ['word' => "俟", 'first_char' => 'Q'],
            -6749 => ['word' => "澹", 'first_char' => 'T'],
            -7220 => ['word' => "闾", 'first_char' => 'L'],
            -10047 => ['word' => "亓", 'first_char' => 'Q'],
            -10005 => ['word' => "仉", 'first_char' => 'Z'],
            -3417 => ['word' => "颛", 'first_char' => 'Z'],
            -6431 => ['word' => "驷", 'first_char' => 'S'],
            -7226 => ['word' => "闫", 'first_char' => 'Y'],
            -9293 => ['word' => "鄢", 'first_char' => 'Y'],
            -6205 => ['word' => "缑", 'first_char' => 'G'],
            -9764 => ['word' => "佘", 'first_char' => 'S'],
            -9818 => ['word' => "佴", 'first_char' => 'N'],
            -9509 => ['word' => "谯", 'first_char' => 'Q'],
            -3122 => ['word' => "笪", 'first_char' => 'D'],
            -9823 => ['word' => "佟", 'first_char' => 'T'],
        ];
        if (array_key_exists($asc, $rare_arr) && $rare_arr[$asc]['first_char']) {
            return $rare_arr[$asc]['first_char'];
        } else {
            return null;
        }
    }
}

if (!function_exists('str_encode')) {
    /**
     * 加密字符串
     *
     * @param string $data 要加密的字符串
     *
     * @return string
     */
    function str_encode(string $data)
    {
        return base64_encode(openssl_encrypt($data, 'DES-ECB', md5('encrypt_and_decode')));
    }
}

if (!function_exists('str_decode')) {
    /**
     * 解密字符串
     *
     * @param string $data 要解密的字符串
     *
     * @return false|string
     */
    function str_decode(string $data)
    {
        $str = openssl_decrypt(base64_decode($data), 'DES-ECB', md5('encrypt_and_decode'));

        return $str ?: '';
    }
}

if (!function_exists('_str_replace')) {
    /**
     * 字符替换
     *
     * @param null $str 要替换的字符
     * @param string $new_str 用来替换的字符
     * @param int $start 开始位置
     * @param int|null $length 替换长度
     *
     * @return string|string[]
     */
    function _str_replace($str, string $new_str, int $start, int $length = null)
    {
        if ($str == '') {
            return '';
        }

        if (!$length) {
            $length = mb_strlen($str) - $start;
        }
        $new_str = sprintf("%'$new_str{$length}s", $new_str);

        return substr_replace($str, $new_str, $start, $length);
    }
}

if (!function_exists('hide_phone_number')) {
    /**
     * 隐藏手机号
     *
     * @param null $phone 手机号
     * @param string $str 用来替换的字符
     *
     * @return string|string[]
     */
    function hide_phone_number($phone, string $str = '*')
    {
        return _str_replace($phone, $str, 3, 4);
    }
}

if (!function_exists('hide_id_number')) {
    /**
     * 隐藏身份证号
     *
     * @param null $id_number 身份证号
     * @param string $str 用来替换的字符
     *
     * @return string|string[]
     */
    function hide_id_number($id_number, string $str = '*')
    {
        return _str_replace($id_number, $str, 6, 8);
    }
}

if (!function_exists('is_phone_number')) {
    /**
     * 检查手机号:等级1-3
     *
     * @param     $number
     * @param int $grade
     *
     * @return false|int|void
     */
    function is_phone_number($number, int $grade = 2)
    {
        switch ($grade) {
            case 1:
                return preg_match('/^(?:(?:\+|00)86)?1\d{10}$/', $number);
            case 2:
                return preg_match('/^(?:(?:\+|00)86)?1[3-9]\d{9}$/', $number);
            case 3:
                return preg_match('/^(?:(?:\+|00)86)?1(?:(?:3[\d])|(?:4[5-7|9])|(?:5[0-3|5-9])|(?:6[5-7])|(?:7[0-8])|(?:8[\d])|(?:9[1|8|9]))\d{8}$/', $number);
        }
    }
}

if (!function_exists('is_id_number')) {
    /**
     * 检查身份证号
     *
     * @param $id_number
     *
     * @return false|int
     */
    function is_id_number($id_number)
    {
        return preg_match('/(^\d{8}(0\d|10|11|12)([0-2]\d|30|31)\d{3}$)|(^\d{6}(18|19|20)\d{2}(0\d|10|11|12)([0-2]\d|30|31)\d{3}(\d|X|x)$)/', $id_number);
    }
}

if (!function_exists('is_email')) {
    /**
     * 检查邮箱
     *
     * @param $email
     *
     * @return false|int
     */
    function is_email($email)
    {
        return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/', $email);
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

if (!function_exists('get_age')) {
    /**
     * 计算年龄
     *
     * @param $birthday
     *
     * @return false|int|string
     */
    function get_age($birthday)
    {
        $birthday = strtotime($birthday);
        //格式化出生时间年月日
        $byear = date('Y', $birthday);
        $bmonth = date('m', $birthday);
        $bday = date('d', $birthday);

        //格式化当前时间年月日
        $tyear = date('Y');
        $tmonth = date('m');
        $tday = date('d');

        //开始计算年龄
        $age = $tyear - $byear;
        if ($bmonth > $tmonth || $bmonth == $tmonth && $bday > $tday) {
            $age--;
        }

        return $age;
    }
}

if (!function_exists('get_zodiac')) {
    /**
     * 获取生肖
     *
     * @param $birthday
     *
     * @return string
     */
    function get_zodiac($birthday)
    {
        $animals = ['子鼠', '丑牛', '寅虎', '卯兔', '辰龙', '巳蛇', '午马', '未羊', '申猴', '酉鸡', '戌狗', '亥猪'];
        $key = (date('Y', strtotime($birthday)) - 1900) % 12;

        return $animals[$key];
    }
}

if (!function_exists('get_constellation')) {
    /**
     * 获取星座
     *
     * @param $date
     *
     * @return string
     */
    function get_constellation($date)
    {
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
            $constellation = "水瓶座";
        } else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
            $constellation = "双鱼座";
        } else if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
            $constellation = "白羊座";
        } else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
            $constellation = "金牛座";
        } else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {
            $constellation = "双子座";
        } else if (($month == 6 && $day >= 22) || ($month == 7 && $day <= 22)) {
            $constellation = "巨蟹座";
        } else if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
            $constellation = "狮子座";
        } else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
            $constellation = "处女座";
        } else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {
            $constellation = "天秤座";
        } else if (($month == 10 && $day >= 24) || ($month == 11 && $day <= 22)) {
            $constellation = "天蝎座";
        } else if (($month == 11 && $day >= 23) || ($month == 12 && $day <= 21)) {
            $constellation = "射手座";
        } else if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
            $constellation = "摩羯座";
        }

        return $constellation;
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

if (!function_exists('date_array')) {
    /**
     * 生成日期数组
     *
     * @param string $start 开始时间
     * @param string $end 结束时间
     * @param string $format 生成的时间格式
     * @param int $interval 生成的时间间隔(默认一天)
     *
     * @return array|false[]|string[]
     */
    function date_array(string $start, string $end, string $format = 'Y-m-d', int $interval = 86400)
    {
        $dates = range(strtotime($start), strtotime($end), $interval);

        return array_map(function ($_date) use ($format) {
            return date($format, $_date);
        }, $dates);
    }
}

if (!function_exists('millisecond')) {
    /**
     * 获取当前毫秒
     *
     * @return int
     */
    function millisecond()
    {
        return intval(microtime(true) * 1000);
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
        $ci = curl_init();

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
        $response = curl_exec($ci);
        $request_info = curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);

        curl_close($ci);

        return compact('response', 'request_info', 'http_code');
    }
}

if (!function_exists('_miss')) {
    function _miss($value)
    {
        return !isset($value) || empty($value);
    }

}

if (!function_exists('_ok')) {
    function _ok($value)
    {
        return isset($value) && !empty($value);
    }
}

if (!function_exists('encode_quotes')) {
    /**
     * 把引号字符串转义为html转义字符
     * @param $val
     * @return string|string[]
     */
    function encode_quotes($val) {
        return str_replace("'", '&#39;', str_replace('"', '&#34;', $val));
    }
}

if (!function_exists('decode_quotes')) {
    /**
     * 把html转义引号转换为正常的引号字符串
     * @param $val
     * @return string|string[]
     */
    function decode_quotes($val) {
        return str_replace('&#39;', "'", str_replace('&#34;', '"', $val));
    }
}

if (!function_exists('encode_quotes')) {
    /**
     * 把引号字符串转义为html转义字符
     * @param $val
     * @return string|string[]
     */
    function encode_quotes($val) {
        return str_replace("'", '&#39;', str_replace('"', '&#34;', $val));
    }
}

if (!function_exists('decode_quotes')) {
    /**
     * 把html转义引号转换为正常的引号字符串
     * @param $val
     * @return string|string[]
     */
    function decode_quotes($val) {
        return str_replace('&#39;', "'", str_replace('&#34;', '"', $val));
    }
}

if (!function_exists('_str_limit')) {
    /**
     * 将字符串以指定长度进行截断
     *
     * @param $value
     * @param $limit
     * @param $end
     *
     * @return mixed|string
     */
    function _str_limit($value, $limit, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }
}

if (!function_exists('_str_length')) {
    /**
     * 字符串长度
     *
     * @param $value
     * @param null $encoding
     *
     * @return false|int
     */
    function _str_length($value, $encoding = null)
    {
        if ($encoding) {
            return mb_strlen($value, $encoding);
        }

        return mb_strlen($value);
    }
}

if (!function_exists('_str_random')) {
    /**
     * 获取指定长度的指定字符串
     *
     * @param int $length
     *
     * @return string
     */
    function _str_random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size   = $length - $len;
            $bytes  = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}

if (!function_exists('_str_random')) {
    /**
     * 获取指定长度的指定字符串
     *
     * @param int $length
     *
     * @return string
     */
    function _str_random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size   = $length - $len;
            $bytes  = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}

if (!function_exists('is_tel')) {
    /**
     * 是否电话
     *
     * @param $val
     *
     * @return bool
     */
    function is_tel($val)
    {
        $val = trim($val);
        if (!$val) {
            return false;
        }
        if (preg_match('/^(13[0-9]|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[0-9]|19[0-35-9])\d{8}$/', $val)
            || preg_match('/^\d{3,4}-[1-9]{1}\d{6,7}$/', $val)
            || preg_match('/^[1-9]{1}\d{6,7}$/', $val)) {
            return $val;
        }
        return false;
    }
}

if (!function_exists('is_lng_or_lat')) {
    /**
     * 判断是否经纬度
     *
     * @param $val
     *
     * @return false|int
     */
    function is_lng_or_lat($val)
    {
        $val = trim($val);
        if (!$val) {
            return false;
        }
        if (preg_match('/^(:?[1-9]\d{0,2}|0)\.\d{4,18}$/', $val)) {
            return $val;
        }
        return false;
    }
}

if (!function_exists('is_ids')) {
    /**
     * 是否ID集合
     *
     * @param $val
     *
     * @return bool
     */
    function is_ids($val)
    {
        $val = trim($val, " \t\n\r \v,");
        if (!$val) {
            return false;
        }
        if (preg_match('/^(?:[1-9]\d{0,},{0,})+$/', $val)) {
            return $val;
        }
        return false;
    }
}
