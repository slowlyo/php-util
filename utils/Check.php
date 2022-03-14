<?php

class Check
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * 检查手机号:等级1-3
     *
     * @param int $grade
     *
     * @return false|int|void
     */
    public function isPhoneNumber(int $grade = 2)
    {
        switch ($grade) {
            case 1:
                return preg_match('/^(?:(?:\+|00)86)?1\d{10}$/', $this->data);
            case 2:
                return preg_match('/^(?:(?:\+|00)86)?1[3-9]\d{9}$/', $this->data);
            case 3:
                return preg_match('/^(?:(?:\+|00)86)?1(?:(?:3[\d])|(?:4[5-7|9])|(?:5[0-3|5-9])|(?:6[5-7])|(?:7[0-8])|(?:8[\d])|(?:9[1|8|9]))\d{8}$/', $this->data);
        }
    }


    /**
     * 检查身份证号
     *
     * @return false|int
     */
    public function isIdNumber()
    {
        return preg_match('/(^\d{8}(0\d|10|11|12)([0-2]\d|30|31)\d{3}$)|(^\d{6}(18|19|20)\d{2}(0\d|10|11|12)([0-2]\d|30|31)\d{3}(\d|X|x)$)/', $this->data);
    }


    /**
     * 检查邮箱
     *
     * @return false|int
     */
    public function is_email()
    {
        return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/', $this->data);
    }


    public function miss()
    {
        return !isset($this->data) || empty($this->data);
    }

    public function ok()
    {
        return isset($this->data) && !empty($this->data);
    }


    /**
     * 是否电话
     *
     * @return bool
     */
    public function isTel()
    {
        $val = trim($this->data);
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
