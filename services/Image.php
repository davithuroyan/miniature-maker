<?php

namespace app\services;

use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use yii\imagine\Image as Imagine;

class Image
{
    protected $url;
    protected $width;
    protected $height;
    protected $watermarked = false;
    
    public function __construct($path, $width, $height)
    {
        $this->path = $path;
        $this->width = $width;
        $this->height = $height;
    }
    
    /**
     * @param bool $isWatermarked
     */
    public function setWatermarked(bool $isWatermarked)
    {
        $this->watermarked = $isWatermarked;
    }
    
    /**
     * @return string
     */
    public function generateMiniature(): string
    {
        return $this->generate();
    }
    
    /**
     * @return string
     */
    public function generateWatermarkedMiniature(): string
    {
        $this->setWatermarked(true);
        
        return $this->generate();
    }
    
    /**
     * @return string
     */
    public function generate()
    {
        $image = Imagine::thumbnail($this->path, $this->width, $this->height, ManipulatorInterface::THUMBNAIL_INSET);
        
        if ($this->watermarked) {
            $watermarkWidth = $this->width / 2;
            $watermarkHeight = $this->height / 2;
            $watermarkPath = \Yii::getAlias('@webroot') . \Yii::$app->params['watermark_path'];
            $watermark = Imagine::thumbnail($watermarkPath, $watermarkWidth, $watermarkHeight);
            
            Imagine::watermark($image, $watermark, $this->getWatermarkCoordinates($watermark));
        }
        
        $imageSavePath = \Yii::getAlias('@webroot') . \Yii::$app->params['save_path'];
        
        if (!is_dir($imageSavePath)) {
            mkdir($imageSavePath);
        }
        
        $newMiniaturePath = $imageSavePath . DIRECTORY_SEPARATOR . $this->generateFileName();
        $image->save($newMiniaturePath);
        
        return $newMiniaturePath;
    }
    
    /**
     * @param ImageInterface $watermark
     * @return array
     */
    protected function getWatermarkCoordinates(ImageInterface $watermark): array
    {
        $x = ($this->width - $watermark->getSize()->getWidth()) / 2;
        $y = ($this->height - $watermark->getSize()->getHeight()) / 2;
        
        return [$x, $y];
    }
    
    /**
     * @return string
     */
    protected function generateFileName(): string
    {
        $filePathInfo = pathinfo($this->path);
        
        return \Yii::getAlias(
            ($this->watermarked ? 'W_' : '')
            . $filePathInfo['filename']
            . $this->width . 'x' . $this->height
            . '.' . $filePathInfo['extension']
        );
    }
}