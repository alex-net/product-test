<?php 

namespace app\modules\admin\controllers;

use Yii;

class ProductController extends AdminBaseController
{    
    /**
     * загрузка товава по idу ..
     * @param  int $id Идентификатор товара ..
     * @return [type]     [description]
     */
    protected function getProduct($id)
    {
        $prod = $id ? \app\modules\admin\models\ProductCfg::getById($id) : new \app\modules\admin\models\ProductCfg;
        if(!$prod) {
            throw new \yii\web\NotFoundHttpException("Товар не найден");
        }
        return $prod;
    }

    /**
     * список товаров .. .
     * @return [type] [description]
     */
    public function actionList()
    {
        return $this->render('list', [
            'dp' => \app\modules\admin\models\ProductCfg::getAll(),
        ]);
    }

    /**
     * редактирование товара
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function actionEdit($id = 0)
    {
        $prod = $this->getProduct($id); 

        $ret = $this->postOperations($prod, 'Данные сохранены', 'Товар %s удален');
        if ($ret) {
            return $ret;
        }
            
        return $this->render('edit', [
            'prod' => $prod,
        ]);
    }

    public function actionView($id)
    {
        $prod = $this->getProduct($id);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            switch(true) {
                // убийсво товара...
                case isset($post['kill']) && $prod->kill():
                    return $this->redirect(['list']);
                // загрузка катинок на сервак ... 
                case !empty($post['file-uploader']):
                    $files = \yii\web\UploadedFile::getInstancesByName('files');
                    if (!$files) {
                        return $this->asJson(['ok' => false]);
                    }
                        
                    $prod->scenario = $prod::SCENARIO_IMAGElOADER;
                    $prod->imgs = $files;
                    return $this->asJson(['ok' => $prod->save()]);
                // полнучение списка картинок .. 
                case isset($post['get-imgs-for']):
                    return $this->asJson([
                        'list' => $prod->images,
                    ]);
                // обновление статуса картинки ...
                case !empty($post['set-img-status']):
                    $prod->updateImgStatus(substr(basename($post['set-img-status']), 0, 32), $post['curst'] ?? false);
                    return $this->asJson(['ok' => true]);
                // убийство картинки ..
                case !empty($post['kill-img']):
                    $prod->killImg(substr(basename($post['kill-img']), 0, 32));
                    return $this->asJson(['ok' => true]);
            }
        }
        return $this->render('view', ['prod' => $prod]);
    }
}