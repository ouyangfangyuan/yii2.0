<?php
namespace frontend\widgets\banner;

use yii;
use yii\base\Widget;

class BannerWidget extends Widget
{
	public $items = [];

	public function init()
	{	
		if (empty($this->items)) {
			$this->items = [
				[
					'label'=>'demo1',
					'image_url'=>'/statics/images/banner/b_0.png',
					'url'=>['site/index'],
					'html'=>'',
					'active'=>'active',

				],
				[
					'label'=>'demo2',
					'image_url'=>'/statics/images/banner/b_1.png',
					'url'=>['site/index'],
					'html'=>'',
					'active'=>'',
				],
				[
					'label'=>'demo3',
					'image_url'=>'/statics/images/banner/b_2.png',
					'url'=>['site/index'],
					'html'=>'',
					'active'=>'',
				],
			];
		}
		
	}

	public function run()
	{
		$data['items'] = $this->items;
		return $this->render('index',['data'=>$data]);
	}
}