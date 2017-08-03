<?php
namespace frontend\widgets\post;

/**
 * 文章列表组件
 */
use yii;
use yii\base\Widget;
use yii\helpers\Url;
use yii\data\Pagination;
use common\models\PostModel;
use frontend\models\PostForm;

class PostWidget extends Widget
{
	public $title = '';//文章标题
	public $limit = 5;//显示条数
	public $more = true;//显示更多
	public $page = false;//是否显示分页
	public function run()
	{
		$curPage = Yii::$app->request->get('page',1);//获得当前页,默认为第一页

		 //查询条件
		$cond = ['=','is_valid',PostModel::IS_VALID];
		$model = new PostForm();
		$res = $model->getList($cond,$curPage,$this->limit);
		$result['title'] = $this->title?:"最新文章";
		$result['more'] = Url::to(['post/index']);
		$result['body'] = $res['data']?:[];
		//是否显示分页
		if ($this->page) {
			$pages = new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);

			$result['page'] = $pages;
		}
		return $this->render('index',['data'=>$result]);
	}
}