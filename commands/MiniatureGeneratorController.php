<?php


namespace app\commands;


use app\services\Image;
use app\services\Miniature;
use yii\console\Controller;

class MiniatureGeneratorController extends Controller
{
    public function actionRun(string $sizes, bool $watermarked = false, bool $catalogOnly)
    {
        try {
            echo (new Miniature($sizes, $watermarked, $catalogOnly))->generate();
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
}