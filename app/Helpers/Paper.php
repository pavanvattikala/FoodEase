<?php


namespace App\Helpers;

class Paper
{
    private $width = 283.6;
    private $height = 20;

    public function toArray()
    {
        return array(0, 0, $this->width, $this->height);
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public static function getPaper()
    {
        return new Paper();
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }
}
