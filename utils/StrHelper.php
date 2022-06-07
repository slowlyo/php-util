<?php

class StrHelper
{
    private $str;

    public function __construct($str)
    {
        $this->str = $str;
    }

    /**
     * 获取首字母
     */
    public function firstChar()
    {
        $str = $this->str;
        if (empty($str)) {
            return '';
        }
        //取出参数字符串中的首个字符
        $temp_str = substr($str, 0, 1);
        if (ord($temp_str) > 127) {
            $str = substr($str, 0, 3);
        } else {
            $str   = $temp_str;
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
        $s   = $s2 == $str ? $s1 : $str;
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

        return $this->rare_words($asc);
    }

    //百家姓中的生僻字
    private function rare_words($asc = '')
    {
        $rare_arr = [
            -3652  => ['word' => "窦", 'first_char' => 'D'],
            -8503  => ['word' => "奚", 'first_char' => 'X'],
            -9286  => ['word' => "酆", 'first_char' => 'F'],
            -7761  => ['word' => "岑", 'first_char' => 'C'],
            -5128  => ['word' => "滕", 'first_char' => 'T'],
            -9479  => ['word' => "邬", 'first_char' => 'W'],
            -5456  => ['word' => "臧", 'first_char' => 'Z'],
            -7223  => ['word' => "闵", 'first_char' => 'M'],
            -2877  => ['word' => "裘", 'first_char' => 'Q'],
            -6191  => ['word' => "缪", 'first_char' => 'M'],
            -5414  => ['word' => "贲", 'first_char' => 'B'],
            -4102  => ['word' => "嵇", 'first_char' => 'J'],
            -8969  => ['word' => "荀", 'first_char' => 'X'],
            -4938  => ['word' => "於", 'first_char' => 'Y'],
            -9017  => ['word' => "芮", 'first_char' => 'R'],
            -2848  => ['word' => "羿", 'first_char' => 'Y'],
            -9477  => ['word' => "邴", 'first_char' => 'B'],
            -9485  => ['word' => "隗", 'first_char' => 'K'],
            -6731  => ['word' => "宓", 'first_char' => 'M'],
            -9299  => ['word' => "郗", 'first_char' => 'X'],
            -5905  => ['word' => "栾", 'first_char' => 'L'],
            -4393  => ['word' => "钭", 'first_char' => 'T'],
            -9300  => ['word' => "郜", 'first_char' => 'G'],
            -8706  => ['word' => "蔺", 'first_char' => 'L'],
            -3613  => ['word' => "胥", 'first_char' => 'X'],
            -8777  => ['word' => "莘", 'first_char' => 'S'],
            -6708  => ['word' => "逄", 'first_char' => 'P'],
            -9302  => ['word' => "郦", 'first_char' => 'L'],
            -5965  => ['word' => "璩", 'first_char' => 'Q'],
            -6745  => ['word' => "濮", 'first_char' => 'P'],
            -4888  => ['word' => "扈", 'first_char' => 'H'],
            -9309  => ['word' => "郏", 'first_char' => 'J'],
            -5428  => ['word' => "晏", 'first_char' => 'Y'],
            -2849  => ['word' => "暨", 'first_char' => 'J'],
            -7206  => ['word' => "阙", 'first_char' => 'Q'],
            -4945  => ['word' => "殳", 'first_char' => 'S'],
            -9753  => ['word' => "夔", 'first_char' => 'K'],
            -10041 => ['word' => "厍", 'first_char' => 'S'],
            -5429  => ['word' => "晁", 'first_char' => 'C'],
            -2396  => ['word' => "訾", 'first_char' => 'Z'],
            -7205  => ['word' => "阚", 'first_char' => 'K'],
            -10049 => ['word' => "乜", 'first_char' => 'N'],
            -10015 => ['word' => "蒯", 'first_char' => 'K'],
            -3133  => ['word' => "竺", 'first_char' => 'Z'],
            -6698  => ['word' => "逯", 'first_char' => 'L'],
            -9799  => ['word' => "俟", 'first_char' => 'Q'],
            -6749  => ['word' => "澹", 'first_char' => 'T'],
            -7220  => ['word' => "闾", 'first_char' => 'L'],
            -10047 => ['word' => "亓", 'first_char' => 'Q'],
            -10005 => ['word' => "仉", 'first_char' => 'Z'],
            -3417  => ['word' => "颛", 'first_char' => 'Z'],
            -6431  => ['word' => "驷", 'first_char' => 'S'],
            -7226  => ['word' => "闫", 'first_char' => 'Y'],
            -9293  => ['word' => "鄢", 'first_char' => 'Y'],
            -6205  => ['word' => "缑", 'first_char' => 'G'],
            -9764  => ['word' => "佘", 'first_char' => 'S'],
            -9818  => ['word' => "佴", 'first_char' => 'N'],
            -9509  => ['word' => "谯", 'first_char' => 'Q'],
            -3122  => ['word' => "笪", 'first_char' => 'D'],
            -9823  => ['word' => "佟", 'first_char' => 'T'],
        ];
        if (array_key_exists($asc, $rare_arr) && $rare_arr[$asc]['first_char']) {
            return $rare_arr[$asc]['first_char'];
        } else {
            return null;
        }
    }

    /**
     * 加密字符串
     *
     * @return string
     */
    public function encode()
    {
        return base64_encode(openssl_encrypt($this->str, 'DES-ECB', md5('encrypt_and_decode')));
    }

    /**
     * 解密字符串
     *
     * @return false|string
     */
    public function decode()
    {
        $str = openssl_decrypt(base64_decode($this->str), 'DES-ECB', md5('encrypt_and_decode'));

        return $str ?: '';
    }

    /**
     * 字符替换
     *
     * @param string   $new_str 用来替换的字符
     * @param int      $start   开始位置
     * @param int|null $length  替换长度
     *
     */
    public function replace(string $new_str, int $start, int $length = null)
    {
        if ($this->str == '') {
            return '';
        }

        if (!$length) {
            $length = mb_strlen($this->str) - $start;
        }
        $new_str = sprintf("%'$new_str{$length}s", $new_str);

        $this->str = substr_replace($this->str, $new_str, $start, $length);

        return $this;
    }

    /**
     * 隐藏手机号
     *
     * @param string $str 用来替换的字符
     */
    public function hide_phone_number(string $str = '*')
    {
        return $this->replace($str, 3, 4)->get();
    }

    /**
     * 隐藏身份证号
     *
     * @param string $str 用来替换的字符
     *
     * @return string|string[]
     */
    public function hide_id_number(string $str = '*')
    {
        return $this->replace($str, 4, 12)->get();
    }

    /**
     * 将字符串以指定长度进行截断
     *
     * @param        $limit
     * @param string $end
     *
     * @return StrHelper
     */
    public function limit($limit, string $end = '...')
    {
        if (mb_strwidth($this->str, 'UTF-8') <= $limit) {
            return $this;
        }

        $this->str = rtrim(mb_strimwidth($this->str, 0, $limit, '', 'UTF-8')) . $end;

        return $this;
    }

    /**
     * 字符串长度
     *
     * @param null $encoding
     */
    public function length($encoding = null)
    {
        if ($encoding) {
            return mb_strlen($this->str, $encoding);
        }

        return mb_strlen($this->str);
    }

    /**
     * 获取指定长度的指定字符串
     *
     * @param int $length
     *
     * @return string
     */
    public function random(int $length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size   = $length - $len;
            $bytes  = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * 移除html标签
     *
     * @return $this
     */
    public function removeHtml()
    {
        if (!$this->str) {
            return $this;
        }

        $this->str = strip_tags(str_replace("&nbsp;", "", htmlspecialchars_decode($this->str)));

        return $this;
    }

    /**
     * 处理距离
     *
     * @param int $unit 0:m/km 1:米/千米 2:米/公里
     * @param int $precision
     *
     * @return $this
     */
    public function distance(int $unit = 0, int $precision = 2)
    {
        $units = [
            ['m', 'km'],
            ['米', '千米'],
            ['米', '公里'],
        ];
        if ($this->str < 1000) {
            $this->str = round($this->str, $precision) . $units[$unit][0];
        } else {
            $this->str = round($this->str / 1000, $precision) . $units[$unit][1];
        }

        return $this;
    }

    public function get()
    {
        return $this->str;
    }
}
