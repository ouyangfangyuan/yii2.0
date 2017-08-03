<?php
use yii\helpers\Url;
?>
<div class="panel">
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

	<!-- 轮播（Carousel）指标 -->

		<ol class="carousel-indicators">

			<?php foreach ($data['items'] as $k=>$list):?>

				<li data-target="#carousel-example-generic" data-slide-to=<?=$k ?> class="<?=$list['active']?>"></li>

				<?php endforeach;?>

		</ol>
		<div class="carousel-inner home-banner" role='listbox'>

			<?php foreach ($data['items'] as $k=>$list):?>
			<div class="item <?=$list['active']?>"><a href="<?=Url::to($list['url']) ?>"><img style="width:848px;height:300px" src="<?=$list['image_url']?>" alt="<?=$list['label']?>">
				<div class="carousel-caption">
					<?=$list['html']?>
				</div>
				</a>
			</div>
			<?php endforeach;?>
		</div>

	<!-- 轮播（Carousel）导航 -->

	<a class="carousel-control left" style="margin-top:60px;font-size:100px;" href="#carousel-example-generic" 

	   data-slide="prev">&lsaquo;</a>

	<a class="carousel-control right" style="margin-top:60px;font-size:100px;" href="#carousel-example-generic" 

	   data-slide="next">&rsaquo;</a>

	</div>

</div>