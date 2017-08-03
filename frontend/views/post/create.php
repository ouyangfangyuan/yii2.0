<?php
/**
 * @Author: Administrator
 * @Date:   2017-06-21 16:41:30
 * @Last Modified by:   Administrator
 * @Last Modified time: 2017-06-29 14:31:57
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "创建";//标题

$this->params['breadcrumbs'][] = ['label'=>'文章','url'=>['post/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-lg-9">
		<div class="panel-title box-title">
			<span>创建文章</span>
		</div>
		<div class="panel-body">
			<?php $form = ActiveForm::begin() ?>
			<?=$form->field($model,'title')->textinput(['maxlength'=>true]) ?>
			<?=$form->field($model,'cat_id')->dropDownList($cat) ?>
			<?=$form->field($model,'label_img')->widget('common\widgets\file_upload\FileUpload',[
        	'config'=>[
            //图片上传的一些配置，不写调用默认配置
            // 'domain_url' => 'http://www.yii-china.com',
        	]
    		]) ?>
			<?=$form->field($model,'content')->widget('common\widgets\ueditor\Ueditor',[
				'options'=>[
					//具体参数参考ueditor官网
        				'initialFrameWidth' => 850,
        				'initialFrameHeight' => 400,
    			]
			]) ?>
			<?=$form->field($model,'tags')->widget('common\widgets\tags\TagWidget') ?>
			<div class="form-group">
				<?=Html::submitButton('发布',['class'=>'btn  btn-success'])?>
			</div>	
			<?php ActiveForm::end() ?>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="panel-title box-title">
			<span>注意事项	</span>
		</div>
		<div  class="panel-body">
			<p>1.信息必须真实</p>
			<p>2.已于公安部联网</p>
		</div>
	</div>
</div>