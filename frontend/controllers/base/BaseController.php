<?php
/**
 * @Author: oyfy
 * @Date:   2017-06-16 09:57:50
 * @Last Modified by:   Administrator
 * @Last Modified time: 2017-06-19 11:32:47
 */
namespace frontend\controllers\base;
/**
 * 基础控制器
 */

use yii\web\Controller;


class BaseController extends Controller
{
	public function beforeAction($action)
	{
		if (!parent::beforeAction($action)) {
			return false;
		}

		return true;
	}
}
