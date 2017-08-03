<?php
/**
 * @Author: oyfy
 * @Date:   2017-06-16 09:56:32
 * @Last Modified by:   Administrator
 * @Last Modified time: 2017-06-30 15:48:41
 */
namespace frontend\controllers;


use Yii;
use frontend\models\PostForm;
use frontend\controllers\base\BaseController;
use common\models\CatModel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\PostExtendModel;
/**
 * 文章控制器,文章列表
 * 
 */
class PostController extends BaseController
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create','upload','ueditor'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create','upload','ueditor'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                	'*' =>['get','post'],
                ],
            ],
        ];
    }

    /**
     * 文章列表
     * @Author
     * @DateTime 2017-06-30T09:49:15+0800
     * @return   [type]                   [description]
     */
	public function actionIndex()
	{
        
		return $this->render('index');
	}
/**
 * 
 * @Author
 * @DateTime 2017-06-22T10:17:17+0800
 * @return   [type]                   [description]
 */
	 public function actions()
    {
        return [
            'upload'=>[
                'class' => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' => [
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            'ueditor'=>[
	            'class' => 'common\widgets\ueditor\UeditorAction',
	            'config'=>[
	                //上传图片配置
	                'imageUrlPrefix' => "", /* 图片访问路径前缀 */
	                'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
	            ]
	        ]

        ];
    } 
/**
 * 文章添加
 * @Author oyfy
 * @DateTime 2017-06-21T16:38:21+0800
 * @return   [type]                   [description]
 */
	public function actionCreate()
	{
		$model = new PostForm();
		//定义场景
		$model->setScenario(PostForm::SCENARIOS_CREATE);
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if (!$model->create()) {
				Yii::$app->session->setFlash('warning',$model->_lastError);
			}else{
				return $this->redirect(['post/view','id'=>$model->id]);
			}
		}
		$cat = CatModel::getAllCats();
		return $this->render('create',['model'=>$model,'cat'=>$cat]);
	}

    /**
     * 文章详情
     * @Author
     * @DateTime 2017-06-29T09:27:45+0800
     * @return   [type]                   [description]
     */
    public function actionView($id)
    {
        $model = new PostForm();
        $data = $model->getViewById($id);
        //文章浏览统计
        $model = new PostExtendModel();
        $model->upCounter(['post_id'=>$id],'browser',1);
        return $this->render('view',['data'=>$data]);

    }


}