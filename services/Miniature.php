<?php


namespace app\services;

use Yii;
use app\exceptions\InvalidParamsException;
use app\models\Product;
use yii\base\DynamicModel;

class Miniature
{
    protected $sizes;
    protected $isWatermarked;
    protected $isCatalogOnly;
    
    public function __construct(string $sizes, bool $isWatermarked, bool $isCatalogOnly)
    {
        $this->isWatermarked = $isWatermarked;
        $this->isCatalogOnly = $isCatalogOnly;
        $this->sizes = $sizes;
        $this->validate();
    }
    
    
    /**
     * @return string
     */
    public function generate()
    {
        $generatedMiniaturesCount = 0;
        $failedMiniaturesCount = 0;
        $sizes = $this->parseSize();
        $products = Product::find()->where('is_deleted=0')->select('image');
        if ($this->isCatalogOnly) {
            $products->innerJoin('store_product');
        }
        
        foreach ($products->all() as $product) {
            $imagePath = Yii::getAlias('@webroot/' . $product->image);
            foreach ($sizes as $size) {
                try {
                    if ($this->isWatermarked) {
                        (new Image($imagePath, $size['width'], $size['height']))->generateWatermarkedMiniature(
                            $imagePath,
                            $size
                        );
                    } else {
                        (new Image($imagePath, $size['width'], $size['height']))->generateMiniature($imagePath, $size);
                    }
                    
                    $generatedMiniaturesCount++;
                } catch (\Exception $ex) {
                    $failedMiniaturesCount++;
                }
            }
        }
        
        return "Generated Miniature(s) :" . $generatedMiniaturesCount . PHP_EOL .
            "Failed to Generate :" . $failedMiniaturesCount . PHP_EOL;
    }
    
    /**
     * @return array
     */
    protected function parseSize()
    {
        $sizesList = explode(',', $this->sizes);
        $sizes = [];
        
        if (!empty($sizesList)) {
            foreach ($sizesList as $sizeItem) {
                $sizesArray = explode('x', $sizeItem);
                if (count($sizesArray) == 1) {
                    $sizes[] = ['width' => $sizesArray[0], 'height' => $sizesArray[0]];
                } else {
                    $sizes[] = ['width' => $sizesArray[0], 'height' => $sizesArray[1]];
                }
            }
        }
        
        return $sizes;
    }
    
    /**
     * @return bool
     * @throws InvalidParamsException
     */
    protected function validate()
    {
        $varsToValidate = [
            'sizes' => $this->sizes,
            'isWatermarked' => $this->isWatermarked,
            'isCatalogOnly' => $this->isCatalogOnly
        ];
        
        $model = new DynamicModel($varsToValidate);
        $model->addRule(['sizes', 'isWatermarked', 'isCatalogOnly'], 'required')
            ->addRule(['isWatermarked', 'isCatalogOnly'], 'boolean')
            ->addRule(['sizes'], 'match', ['pattern' => '/(^((\d*)?,?(\d*[x]\d*)?)*$)/'])
            ->validate();
      
        if ($model->hasErrors()) {
            $errorMessage = implode(
                "\r\n",
                array_map(
                    function ($entry) {
                        return implode(', ', $entry);
                    },
                    $model->getErrors()
                )
            );
            
            throw new InvalidParamsException($errorMessage);
        }
        return true;
    }
}