<?php
/**
 * @Author: oyfy
 * @Date:   2017-06-19 15:43:57
 * @Last Modified by:   Administrator
 * @Last Modified time: 2017-07-04 09:21:44
 */
namespace common\models\base;
/**
 * 基础模型
 */


use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
	public function getPages($query,$curPage=1,$pageSize=10,$search=null)
	{
		if ($search)$query= $query->andFilterWhere($search);
			$data['count'] = $query->count();
			if (!$data['count']) {
				return ['count'=>0,'curPage'=>$curPage,'pageSize'=>$pageSize,'start'=>0,'end'=>0,'data'=>[]
				];
			}

			$curPage = (ceil($data['count']/$pageSize)<$curPage)?ceil($data['count']/$pageSize):$curPage;

			//当前页
			$data['curPage']=$curPage;
			//每页显示条数
			$data['pageSize'] = $pageSize;

			$data['start'] = ($curPage-1)*$pageSize+1;
			$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
			$data['data'] = $query->offset(($curPage-1)*$pageSize)->limit($pageSize)->asArray()->all();

			return $data;
	}	
}	