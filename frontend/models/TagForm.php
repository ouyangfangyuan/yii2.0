<?php
/**
 * @Author: Administrator
 * @Date:   2017-06-26 11:57:34
 * @Last Modified by:   Administrator
 * @Last Modified time: 2017-06-30 16:01:31
 */
namespace frontend\models;

use yii\base\Model;
use common\models\base\BaseModel;
use common\models\TagModel;
use common\models\RelationPostTagModel;
use common\models\PostModel;
/**
 * 标签的表单模型
 */
class TagForm extends baseModel
{
	public $id;
	public $tags;

	public function rules()
	{
		return [
			['tags','required'],
			['tags','each','rule'=>['string']],
		];
	}

	/**
     * 保存标签
     * @Author
     * @DateTime 2017-06-26T16:41:07+0800
     * @return   [type]                   [description]
     */
    public function saveTags()
    {
        $ids = [];
        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                $ids[] = $this->_saveTag($tag);
            }
        }
        return $ids;
    }

    /**
     * 单个标签的保存
     * @Author
     * @DateTime 2017-06-26T16:41:27+0800
     * @return   [type]                   [description]
     */
    public function _saveTag($tag)
    {
        $model = new TagModel();
        $res = $model->find()->where(['tag_name'=>$tag])->one();
        if (!$res) {
            $model->tag_name = $tag;
            $model->post_num = 1;
            if (!$model->save()) {
                throw new \Exception("保存标签失败!");
            }else{
                $model->updateCounters(['post_num'=>1]);
            }
            return $model->id;
        }
        return $res->id;
    }
}