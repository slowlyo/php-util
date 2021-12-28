<?php

/**
 * 图像处理
 */
class Image
{
    public $file;

    public $old_resource;

    public $new_resource;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * 把正方形图片裁剪为圆形
     *
     * @return bool|Image 如果返回值为false 则裁剪图片失败否则裁剪完成，$save_path为空则返回二进制数据，否则为true
     */
    public function round()
    {
        if (@is_file($this->file)) {
            // 如果传入的是文件路径则打开文件再创建图片
            $src = imagecreatefromstring(file_get_contents($this->file));
        } else {
            // 通过二进制字符串创建图片对象
            $src = @imagecreatefromstring($this->file);
        }
        if (!$src) {
            return false;
        }
        $w = imagesx($src);
        $h = imagesy($src);
        // 新建一个图像
        $new_pic = imagecreatetruecolor($w, $h);
        // 关闭图像混色
        imagealphablending($new_pic, false);
        // 设置图片保存透明通道
        imagesavealpha($new_pic, true);
        // 设置图片透明底色
        $transparent = imagecolorallocatealpha($new_pic, 0, 0, 0, 127);
        // 获取圆形半径
        $r = $w / 2;
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                // 获取原图片指定坐标点的像素值
                $c = imagecolorat($src, $x, $y);
                $_x = $x - $w / 2;
                $_y = $y - $h / 2;
                if ((($_x * $_x) + ($_y * $_y)) < ($r * $r)) {
                    // 设置像素颜色值为原图片像素的颜色值
                    imagesetpixel($new_pic, $x, $y, $c);
                } else {
                    // 设置像素颜色值为透明
                    imagesetpixel($new_pic, $x, $y, $transparent);
                }
            }
        }

        $this->old_resource = $src;
        $this->new_resource = $new_pic;

        return $this;
    }

    /**
     * 修改图片大小
     *
     * @param int $w 图片宽度
     * @param int $h 图片高度
     *
     * @return bool|Image
     */
    public function changeSize(int $w, int $h)
    {
        if (@is_file($this->file)) {
            // 如果传入的是文件路径则打开文件再创建图片
            $this->file = file_get_contents($this->file);
        }
        if (!$w || !$h) {
            return $this->file;
        }
        // 通过二进制字符串创建图片对象
        $image = @imagecreatefromstring($this->file);
        if (!$image) {
            return false;
        }
        $new_pic = imagecreatetruecolor($w, $h);
        $s_w = imagesx($image);
        $s_h = imagesy($image);
        // 关闭图像混色
        imagealphablending($new_pic, false);
        // 设置图片保存透明通道
        imagesavealpha($new_pic, true);
        imagecopyresampled($new_pic, $image, 0, 0, 0, 0, $w, $h, $s_w, $s_h);

        $this->old_resource = $image;
        $this->new_resource = $new_pic;

        return $this;
    }

    /**
     * @param string $water_path 水印图片或路径
     * @param int $x 水印放置X轴位置
     * @param int $y 水印放置Y轴位置
     * @param int $water_width 水印图片宽
     * @param int $water_height 水印图片高
     *
     * @return bool|Image
     */
    public function addImageWater(string $water_path, int $x, int $y, int $water_width, int $water_height)
    {
        if (@is_file($this->file)) {
            // 如果传入的是文件路径则打开文件再创建图片
            $dist_image = imagecreatefromstring(file_get_contents($this->file));
        } else {
            // 通过二进制字符串创建图片对象
            $dist_image = @imagecreatefromstring($this->file);
        }
        if (!$dist_image) {
            return false;
        }
        $water_image_str = (new self($water_path))->changeSize($water_width, $water_height)->save(null, false);
        if (!$water_image_str) {
            return false;
        }
        $water_image = imagecreatefromstring($water_image_str);
        if (!$water_image) {
            return false;
        }
        $water_width = imagesx($water_image);
        $water_height = imagesy($water_image);
        imagecopy($dist_image, $water_image, $x, $y, 0, 0, $water_width, $water_height);

        $this->old_resource = $water_image;
        $this->new_resource = $dist_image;

        return $this;
    }

    /**
     * 修改小程序二维码的logo
     *
     * @param string $logo_path logo图片地址或者二进制数据
     *
     * @return bool
     */
    public function changeMiniProgramLogo(string $logo_path, $save_path)
    {
        if (@is_file($this->file)) {
            [$w, $h, $type] = getimagesize($this->file);
        } else {
            [$w, $h, $type] = getimagesizefromstring($this->file);
        }
        if (!$w) {
            return false;
        }
        // 计算logo占二维码的宽度和高度
        $logo_w = $w / 2.2;
        // 计算logo在图片上的位置
        $x = ($w - $logo_w) / 2;
        $logo = (new self($logo_path))->round()->save();

        return (new self($this->file))->addImageWater($logo, $x, $x, $logo_w, $logo_w)->save($save_path);
    }

    /**
     * @param string $font 字体路径
     * @param string $text 水印文字
     * @param int $x 水印放置X轴位置
     * @param int $y 水印放置Y轴位置
     * @param null|array $option 水印文字的颜色 字体大小 倾斜角度 文字锚点等设置
     *                           ext_align=center 时以给定坐标为文字水印的放置中心点
     * @return bool|Image
     */
    public function addTextWater($font, $text, $x, $y, $option = null)
    {
        if (!$this->file || !$font || !$text || !$x || !$y) {
            return false;
        }
        if (@!is_file($font)) {
            return false;
        }
        if (@is_file($this->file)) {
            // 如果传入的是文件路径则打开文件再创建图片
            $dist_image = imagecreatefromstring(file_get_contents($this->file));
        } else {
            // 通过二进制字符串创建图片对象
            $dist_image = @imagecreatefromstring($this->file);
        }
        if (!$dist_image) {
            return false;
        }
        // 关闭图像混色
        imagealphablending($dist_image, false);
        // 设置图片保存透明通道
        imagesavealpha($dist_image, true);
        $option = $option ?: [];
        if (empty($option['color'])) {
            $option['color'] = ['red' => 255, 'green' => 255, 'blue' => 255];
        }
        if (empty($option['size'])) {
            $option['size'] = 14;
        }
        if (empty($option['angle'])) {
            $option['angle'] = 0;
        }
        if (empty($option['text_align'])) {
            $option['text_align'] = '';
        }
        // 已给定点为锚点进行文字居中
        if ($option['text_align'] == 'center') {
            // 获取文本所占像素
            $text_image_pos = imagettfbbox($option['size'], $option['angle'], $font, $text);
            if (!$text_image_pos) {
                return false;
            }
            // 获取文本所占高度
            $t_h = $text_image_pos[1];
            // 获取文本所占宽度
            $t_w = $text_image_pos[2];
            // 计算文字居中的X和Y坐标
            $x = $x - $t_w / 2;
            $y = $y - $t_h / 2;
        }
        $color = imagecolorallocate($dist_image, $option['color']['red'], $option['color']['green'], $option['color']['blue']);
        imagettftext($dist_image, $option['size'], $option['angle'], $x, $y, $color, $font, $text);

        $this->new_resource = $dist_image;

        return $this;
    }

    public function save($save_path = null, $need_destroy = true)
    {
        $data = false;
        if (!$save_path) {
            // 如果保存路径为空则创建临时文件
            $save_path = tempnam(sys_get_temp_dir(), 'picture_processing');
            if (imagepng($this->new_resource, $save_path)) {
                // 如果图片保存到临时文件成功则读取图片的二进制数据
                $data = file_get_contents($save_path);
                // 删除临时文件
                @unlink($save_path);
            }
        } else {
            // 保存图片到指定路径
            $data = imagepng($this->new_resource, $save_path);
        }
        // 销毁裁剪为的图片对象
        if ($this->new_resource && $need_destroy) {
            imagedestroy($this->new_resource);
        }
        // 销毁原图片对象
        if ($this->old_resource && $need_destroy) {
            imagedestroy($this->old_resource);
        }

        return $data;
    }
}
