<?php

/**
 * 身份证信息获取
 */
class IdCard
{
    private $id_number;

    public function __construct($id_number)
    {
        $this->id_number = $id_number;
    }

    /**
     * 获取身份证信息
     *
     * @return array
     */
    public function all()
    {
        // 性别
        $gender = $this->getGender();

        // 生日
        $birthday = $this->getBirthday();

        // 年龄
        $age = $this->getAge();

        // 生肖
        $zodiac = $this->getZodiac();

        // 星座
        $constellation = $this->getConstellation();

        // 地区
        $area = $this->getArea();

        // 籍贯
        $hometown = $this->getHometown();


        return compact('gender', 'birthday', 'age', 'zodiac', 'constellation', 'area', 'hometown');
    }

    /**
     * 获取身份证 性别
     *
     * @return string
     */
    public function getGender()
    {
        return (int)substr($this->id_number, 16, 1) % 2 === 0 ? '女' : '男';
    }

    /**
     * 获取身份证 生日
     *
     * @return false|string
     */
    public function getBirthday()
    {
        return date('Y-m-d', strtotime(substr($this->id_number, 6, 8)));
    }

    /**
     * 获取身份证 年龄
     *
     * @return false|int|string
     */
    public function getAge()
    {
        return helper_date($this->getBirthday())->getAge();
    }

    /**
     * 获取身份证 生肖
     *
     * @return string
     */
    public function getZodiac()
    {
        return helper_date($this->getBirthday())->getZodiac();
    }

    /**
     * 获取身份证 星座
     *
     * @return string
     */
    public function getConstellation()
    {
        return helper_date($this->getBirthday())->getConstellation();
    }

    /**
     * 获取身份证 区域
     *
     * @return mixed|null
     */
    public function getArea()
    {
        $area = [
            11 => "北京",
            12 => "天津",
            13 => "河北",
            14 => "山西",
            15 => "内蒙古",
            21 => "辽宁",
            22 => "吉林",
            23 => "黑龙江",
            31 => "上海",
            32 => "江苏",
            33 => "浙江",
            34 => "安徽",
            35 => "福建",
            36 => "江西",
            37 => "山东",
            41 => "河南",
            42 => "湖北",
            43 => "湖南",
            44 => "广东",
            45 => "广西",
            46 => "海南",
            50 => "重庆",
            51 => "四川",
            52 => "贵州",
            53 => "云南",
            54 => "西藏",
            61 => "陕西",
            62 => "甘肃",
            63 => "青海",
            64 => "宁夏",
            65 => "新疆",
            71 => "台湾",
            81 => "香港",
            82 => "澳门",
            91 => "国外",
        ];

        return helper_array($area)->get(substr($this->id_number, 0, 2), '');
    }

    /**
     * 获取身份证 籍贯
     *
     * @return mixed|null
     */
    public function getHometown()
    {
        if (!file_exists(__DIR__ . '/address-code.php')) {
            return '';
        }
        $code = substr($this->id_number, 0, 6);
        $addresses = require 'address-code.php';

        return helper_array($addresses)->get($code);
    }
}
