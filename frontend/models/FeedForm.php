<?php

namespace frontend\models;
use yii;
use yii\base\Model;
use common\models\FeedModel;

class FeedForm extends Model
{
	public $content;

	public $_lastError;

	public function rules()
	{
		return [
			['content','required'],
			['content','string','max'=>'255'],
		];
	}

	public function attributeLabels()
	{
		return [
			'id'=>'ID',
			'content'=>'内容',
		];
	}
	/**
	 * 获得留言板内容
	 * @Author
	 * @DateTime 2017-07-03T15:10:32+0800
	 * @return   [type]                   [description]
	 */
	public function getList()
	{
		$model = new FeedModel;
		$res   = $model->find()
				  ->limit(10)
				  ->with('user')
				  ->orderBy(['id'=>SORT_DESC])
				  ->asArray()
				  ->all();

		return $res?$res:[];
	}
	/**
	 * 言论添加
	 * @Author
	 * @DateTime 2017-07-03T17:21:42+0800
	 * @return   [type]                   [description]
	 */
	public function create($data)
	{	
        try{
            $model = new FeedModel();
         	$model->user_id = Yii::$app->user->identity->id;
            $model->content = $data;
            $model->created_at = time();          
            if(!$model->save()){
                throw new \Exception('保存失败！');
            }else{
            	return true;
            }
        }catch (\Exception $e){
            $this->_lastError = $e->getMessage();
            return false;
        }
	}
}
