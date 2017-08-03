<?php
/**
 * @Author: Administrator
 * @Date:   2017-06-19 16:06:43
 * @Last Modified by:   Administrator
 * @Last Modified time: 2017-07-03 10:07:57
 */
namespace frontend\models;
use yii;
use yii\base\model;
use common\models\PostModel;
use yii\db\Query;
use common\models\RelationPostTagModel;
use yii\web\NotFoundHttpException;

/**
 * 文章表单模型
 * 
 */


class PostForm extends model
{
	public $id;

	public $title;

	public $content;

	public $label_img;

	public $cat_id;

	public $tags;

	public $_lastError = '';
	/**
	 * 定义场景
	 * SCENARIOS_CREATE 创建
	 * SCENARIOS_UPDATE 更新
	 */
	/**
	 * 定义事件
	 */
	const SCENARIOS_CREATE = 'create';
	const SCENARIOS_UPDATE = 'update';
	const IS_VALID = 1;
	const EVENT_AFTER_CREATE = 'eventAfterCreate';//创建之后的事件
	const EVENT_AFTER_UPDATE = 'eventAfterUpdate';//更新之后的事件
	/**
	 * 场景设置
	 * @Author  oyfy
	 * @DateTime 2017-06-22T14:28:33+0800
	 * @return   [type]                   [description]
	 */
	public function scenarios()
	{
		$scenarios=	[self::SCENARIOS_CREATE=>['title','content','label_img','cat_id','tags'],
					 self::SCENARIOS_UPDATE=>['title','content','label_img','cat_id','tags'],
		];

		return array_merge(parent::scenarios(),$scenarios);
	}

	/**
	 * 事务
	 * @Author
	 * @DateTime 2017-06-22T14:38:22+0800
	 * @return   [type]                   [description]
	 */
	public function create()
	{	
		//事务
		$transaction = Yii::$app->db->beginTransaction();
		try{
			$model = new PostModel();
			$model->setAttributes($this->attributes);
			$model->summary = $this->_getSummary();
			$model->user_id =  Yii::$app->user->identity->id;
			$model->user_name = Yii::$app->user->identity->username;
			$model->is_valid = PostModel::IS_VALID;
			$model->created_at = time();
			$model->updated_at = time();
			if (!$model->save())
				throw new \Exception("文章保存失败!");
			$this->id = $model->id;

			//调用事件
			$data = array_merge($this->getAttributes(),$model->getAttributes());
			$this->_eventAfterCreate($data);
			$transaction->commit();
			return true;
		}catch(\Exception $e){
			$transaction->rollBack();
			$this->_lastError = $e->getMessage();
			return false;
		}
	}

	public function getViewById($id)
	{
		$res = PostModel::find()->with('relate.tag','extend')->where(['id'=>$id])->asArray()->one();
		if (!$res) {
			throw  new 	NotFoundHttpException('文章不存在!');
		}

		//处理标签
		$res['tags'] = [];
		if (isset($res['relate']) && !empty($res['relate'])) {
			foreach ($res['relate'] as $k => $v) {
				$res['tags'][]= $v['tag']['tag_name'];
			}
		}
		unset($res['relate']);
		return $res;
	}
	/**
	 * 截取文章摘要
	 * @Author
	 * @DateTime 2017-06-22T16:32:30+0800
	 * @param    integer                  $s    [description]
	 * @param    integer                  $e    [description]
	 * @param    string                   $char [description]
	 * @return   [type]                         [description]
	 */
	private function _getSummary($s=0,$e =90,$char='utf-8')
	{
		if (empty($this->content)) {
			return null;
		}

		return (mb_substr(str_replace("&nbsp;",'',strip_tags($this->content)),$s,$e,$char));
	}


	public function rules()
	{
		return [
			[['id','title','content','cat_id'],'required'],
			[['id','cat_id'],'integer'],
			['title','string','min'=>4,'max'=>50],
			];
	}


	public function attributeLabels()
	{
		return [
			'id'=>'编码',
			'title'=>'标题',
			'content'=>'内容',
			'label_img'=>'标签图',
			'tags'=>'标签',
			'cat_id'=>'分类',
		];
	}

	/**
	 *文章创建之后的事件
	 */
	public function _eventAfterCreate($data)
	{
		//添加事件(添加标签事件) off取消事件，on添加事件
		$this->on(self::EVENT_AFTER_CREATE,[$this,'_eventAddTag'],$data);
		//触发事件
		$this->trigger(self::EVENT_AFTER_CREATE);
	}
	/**
	 * 添加标签事件
	 * @Author
	 * @DateTime 2017-06-26T11:56:36+0800
	 * @return   [type]                   [description]
	 */
	public function _eventAddTag($event)
	{
		//保存标签
		$tag = new TagForm();
		$tag->tags = $event->data['tags'];
		$tagids = $tag->saveTags();
		//删除原先的关联关系
		RelationPostTagModel::deleteAll(['post_id'=>$event->data['id']]);
		//批量保存文章和标签的关联关系
		if (!empty($tagids)) {
				foreach ($tagids as $k => $id) {
					$row[$k]['post_id'] = $this->id;
					$row[$k]['tag_id'] = $id;
				}
				$res = (new Query())->createCommand()
					->batchInsert(RelationPostTagModel::tablename(),['post_id','tag_id'],$row)
					->execute();
				if (!$res) {
					throw new \Exception('关联关系保存失败！');
				}
			}	
	}
	/**
	 * 文章列表页
	 * @Author
	 * @DateTime 2017-06-30T10:35:55+0800
	 * @return   [type]                   [description]
	 */
	public   function getList($cond,$curPage=1,$pageSize=5,	$orderBy=['id'=>SORT_DESC])
	{	
		$model = new PostModel();

		//查询语句
		$select = ['id','title','summary','label_img','cat_id','user_id','user_name','is_valid','created_at','updated_at'];
		$query  = $model->find()
				->select($select)
				->where($cond)
				->with('relate.tag','extend')
				->orderBy($orderBy);
		//获取分页数据
		$res = $model->getPages($query,$curPage,$pageSize);
		// echo "<pre>";
		// print_r($res);	
		// echo "</pre>";exit;
		//数据格式化
		$res['data'] = self::_formatList($res['data']);

		return $res;
	}
	
	
	public static  function _formatList($data)
	{
		foreach ($data as &$list) {
			$list['tags'] = [];
			if (isset($list['relate']) && !empty($list['relate'])) {
				foreach ($list['relate'] as $lt) {
					$list['tags'][] = $lt['tag']['tag_name'];				
				}
			}
			unset($list['relate']);
		}


		return $data;
	}

}