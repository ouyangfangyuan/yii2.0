<?php

use frontend\widgets\banner\BannerWidget;
use frontend\widgets\post\PostWidget;
use yii\base\Widget;
use frontend\widgets\chat\ChatWidget;
use frontend\widgets\hot\HotWidget;
use frontend\widgets\tag\TagWidget;
$this->title = '博客-首页';
?>
<div class="row">
    <div class="col-lg-9">
        <!-- 图片轮播 -->
       <?=BannerWidget::widget()?>
       <?=PostWidget::widget()?>
    </div>
    <div class="col-lg-3">
        <?=ChatWidget::widget()?>
        <?=HotWidget::widget()?>
        <?=TagWidget::widget()?>
    </div>
</div>

