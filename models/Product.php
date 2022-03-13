<?php 

namespace app\models;

use Yii;

class Product extends \yii\base\Model
{
	public $id,$name,$status,$descr,$price;
	public $cats,$imgs;

	

	public function init()
	{
		parent::init();
		$this->cats=[];
		if ($this->id){
			$this->cats=Yii::$app->db->createCommand('select cid from prodcat where pid=:id',[':id'=>$this->id])->queryColumn();
			$imgs=Yii::$app->db->createCommand('select fn,active from imgs where pid=:id',[':id'=>$this->id])->queryAll();
			$this->imgs=[];
			for($i=0;$i<count($imgs);$i++)
				$this->imgs[$imgs[$i]['fn']]=intval($imgs[$i]['active'])>0;

		}
	}


	public static function getAll($params=[])
	{
		$q=new \yii\db\Query();
		$q->from(['p'=>'product']);
		$q->select(['p.*','i.fn']);
		$q->leftJoin(['i'=>'imgs'],'i.pid=p.id and i.active>0');
		$where=['and',['status'=>1]];
		if (!empty($params['cat'])){
			$q->leftJoin(['pc'=>'prodcat'],'pc.pid=p.id');
			$q->groupBy('p.id');
			$where[]=['=','pc.cid',$params['cat']];
		}
		$q->where($where);

		$cmd=$q->createCommand();
		$dp=new \yii\data\SqlDataProvider([
			'sql'=>$cmd->sql,
			'params'=>$cmd->params,
			'sort'=>[
				'attributes'=>[
					'price'=>['label'=>'по цене'],
					'id'=>['label'=>'по дате'],
				],
			],
		]);
		$models=$dp->models;
		$ids=[];
		for($i=0;$i<count($models);$i++){
			$models[$i]['img']='https://via.placeholder.com/200x200';
			$models[$i]['key']=$models[$i]['id'];

			if (!empty($models[$i]['fn']))
				$models[$i]['img']=Yii::getAlias('@web/imgs/'.str_replace('.', '-mini.', $models[$i]['fn']));

			$ids[]=$models[$i]['id'];
		}
		if ($ids){
			// забрать категории 
			$q=new \yii\db\Query;
			$q->select(['c.*','pc.pid']);
			$q->from(['c'=>'cats']);
			$q->leftjoin(['pc'=>'prodcat'],'pc.cid=c.id');
			$q->where(['pc.pid'=>$ids]);
			$cats=[];
			foreach($q->all() as $el){
				$pid=$el['pid'];
				unset($el['pid']);
				$cats[$pid][]=new \app\models\Cat($el);
			}

			for($s=0;$s<count($models);$s++){
				$models[$s]['cats']=[];
				if(isset($cats[$models[$s]['id']]))
					$models[$s]['cats']=array_merge($models[$s]['cats'],$cats[$models[$s]['id']]);
			}
		}
		$dp->models=$models;
		return $dp;
	}


	public function getById($id)
	{
		$res=Yii::$app->db->createCommand('select * from product where id=:id limit 1',[':id'=>$id])->queryOne();
		if($res)
			return new static($res);
	}

	/**
	 * отдаём набор картинок с пометками ..
	 * @return [type] [description]
	 */
	public function getImages($activeOnly=false,$mini=true)
	{
		$list=[];
		foreach($this->imgs as $fn=>$a){
			if ($mini)
				$fn=str_replace('.', '-mini.', $fn);
			$list[]=[
				'url'=>Yii::getAlias('@web/imgs/'.$fn),
				'active'=>$a,
			];
		}
		return $list;
	}

	public function getImgForGalery()
	{
		Yii::info($this->imgs,'imgs');
		$list=[];
		foreach(array_keys($this->imgs) as $i){
			$t=str_replace( '.', '-mini.',$i);
			$list[]=[
				'title'=>$this->name,
				'image'=>Yii::getAlias('@web/imgs/'.$i),
				'thumb'=>Yii::getAlias('@web/imgs/'.$t),
				'size'=>'600x800',
			];
		}
		return $list;
	}


}