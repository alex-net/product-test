<?php 

namespace app\modules\admin\models;

use Yii;

class ProductCfg extends \app\models\Product
{
	const SCENARIO_IMAGElOADER='image uploade';

	public function scenarios()
	{
		$sc=parent::scenarios();
		$sc[static::SCENARIO_IMAGElOADER]=['imgs'];
		return $sc;
	}

	public function rules()
	{
		return [
			[['name','descr'],'trim'],
			['name','string','max'=>100],
			[['name','price'],'required'],
			['status','boolean'],
			['status','default','value'=>false],
			['descr','string'],
			['price','integer'],
			['cats','in','range'=>array_keys(\app\models\Cat::getAllIds()),'allowArray'=>true,],
			['imgs','image','maxSize'=>1024*1024,'on'=>static::SCENARIO_IMAGElOADER,'maxFiles'=>0],
		];
	}

	public function attributeLabels()
	{
		return [
			'name'=>'Наименование',
			'price'=>'Цена',
			'status'=>'Активный',
			'descr'=>'Описание',
			'cats'=>'Категории',
		];
	}

	public static function getAll()
	{
		return new \yii\data\SqlDataProvider([
			'sql'=>'select * from product',
			'sort'=>[
				'attributes'=>['id','name','price','status'],
				'defaultOrder'=>['name'=>SORT_ASC],
			],
		]);
	}


	public function save($data=[])
	{
		if ($data && !$this->load($data) || !$this->validate())
			return false;
		if ($this->scenario==static::SCENARIO_IMAGElOADER){
			$imgs=[];
			for($i=0;$i<count($this->imgs);$i++){
				$name=md5($i.'-'.date('c').$this->imgs[$i]->name);//.'.'.$this->imgs[$i]->extension;
				$ext=$this->imgs[$i]->extension;
				$img=new \claviska\SimpleImage();
				$img->fromFile($this->imgs[$i]->tempName);
				$img->thumbnail(600,800,'center');
				$img->toFile(Yii::getAlias('@webroot/imgs/'.$name.'.'.$ext));
				$img->thumbnail(200,200,'center');
				$img->toFile(Yii::getAlias('@webroot/imgs/'.$name.'-mini.'.$ext));
				$imgs[]=[
					'pid'=>$this->id,
					'fn'=>$name.'.'.$ext,
				];
				//if ($this->imgs[$i]->saveAs('@webroot/imgs/'.$name))	
			}
			// прилетела картинка ... надо сохранить
			if ($imgs)
				Yii::$app->db->createCommand()->batchInsert('imgs',array_keys($imgs[0]),$imgs)->execute();
			return  true;
		}
		$args=$this->getAttributes($this->activeAttributes(),['cats']);
		if ($this->id){
			Yii::$app->db->createCommand()->update('product',$args,['id'=>$this->id])->execute();
			Yii::$app->db->createCommand()->delete('prodcat',['pid'=>$this->id])->execute();
		}
		else{
			Yii::$app->db->createCommand()->insert('product',$args)->execute();
			$this->id=Yii::$app->db->lastinsertID;
		}
		// осхраняем категории
		if ($this->cats){
			$list=[];
			for($i=0;$i<count($this->cats);$i++)
				$list[]=[
					'pid'=>$this->id,
					'cid'=>$this->cats[$i],
				];
			Yii::$app->db->createCommand()->batchInsert('prodcat',array_keys($list[0]),$list)->execute();
		}
		return true;
	}

	public function kill()
	{
		if (!$this->id)
			return false;
		// зачистка картинок ..
		$imgs=array_keys($this->imgs);
		for($i=0;$i<count($imgs);$i++)
			$this->killImg(substr($imgs[$i], 0,32));
		Yii::$app->db->createCommand()->delete('product',['id'=>$this->id])->execute();
		return true;
	}

		/**
	 * обновить картинкин статус...
	 */
	public function updateImgStatus($hash,$st=false)
	{
		// срос статуса со всех картинок ..
		Yii::$app->db->createCommand()->update('imgs',['active'=>0],['pid'=>$this->id])->execute();
		if ($st)
			Yii::$app->db->createCommand()->update('imgs',['active'=>true],['and',['=','pid',$this->id],['like','fn',$hash.'%',false]])->execute();
		
		return true;
	}

	/**
	 * Убиийство картинки ..
	 */
	public function killImg($hash)
	{
		Yii::$app->db->createCommand()->delete('imgs',['and',['=','pid',$this->id],['like','fn',$hash.'%',false]])->execute();
		$list=\yii\helpers\FileHelper::findFiles(Yii::getAlias('@webroot/imgs'),['only'=>[$hash.'*.*']]);
		for($i=0;$i<count($list);$i++)
			unlink($list[$i]);
	}


}