<?php
use yii\helpers\Url;

?>
<!-- 只言片语 -->
<div class="panel-title box-title">
	<span><strong>只言片语</strong></span>
	<span class="pull-right"><a href="#" class="font-12">更多>></a></span>
</div>
<div class="panel-body">
	<form action="#" id="w0" method="post">
		<div class="form-group input-group field-feed-content required">
			<textarea name="content" id="feed-content" cols="30" rows="10" class="form-control" placeholder="期待您的伟大言论......"></textarea>
			<span class="input-group-btn">
					<button type="button" data-url="<?=Url::to(['site/feed'])?>" class="btn btn-success" id="fb">发布</button>
			</span>
		</div>
	</form>
	<?php if(!empty($data['feed'])):?>
		<ul class="media-list media-feed feed-index ps-container ps-active-y">
		<?php foreach($data['feed'] as $list):?>
			<li class="media">
			<div class="media-left"><a href="#" rel="author" data-original-title="" title="">
			<img width="30px" src="<?=Yii::$app->params['avatar']['small']?>"/>
			</a></div>
			<div class="media-body" style="font-size: 12px;">
			<div class="media-content">
			<?=$list['user']['username']?>说:<?=$list['content']?>
			</div>
			<div class="media-action">
			<?=date('Y-m-d h:i:s',$list['created_at'])?>
			</div>
			</div>
			</li>
		<?php endforeach;?>
		</ul>
	<?php endif;?>
</div>

