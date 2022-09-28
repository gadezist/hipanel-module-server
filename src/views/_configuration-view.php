<?php

use hipanel\helpers\Url;
use hipanel\modules\server\models\Server;
use hipanel\widgets\Box;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var Server $model
 * @var string[] $configAttrs
 */

?>

<?php if (Yii::getAlias('@part', false) && Yii::$app->user->can('part.read')) : ?>
    <div class="row">
        <div class="col-md-12">
            <?php $box = Box::begin(['renderBody' => false]) ?>
                <?php $box->beginHeader() ?>
                    <?= $box->renderTitle(Yii::t('hipanel:server', 'Configuration')) ?>
                    <?php $box->beginTools(['class' => 'box-tools pull-right']) ?>
                        <?= Html::a(
                            Yii::t('hipanel', 'Details'),
                            Url::toSearch('part', ['dst_name_like' => $model->name]),
                            ['class' => 'btn btn-box-tool']
                        ) ?>
                    <?php $box->endTools() ?>
                <?php $box->endHeader() ?>
                <?php $box->beginBody() ?>
                    <?= DetailView::widget([
                        'model' => $model->hardwareSettings,
                        'attributes' => $configAttrs,
                    ]) ?>
                    <hr>
                    <?php $url = Url::to(['@part/render-object-parts', 'id' => $model->id]) ?>
                        <?= Html::tag('div', '', ['class'  => 'server-parts']) ?>
                    <?php $this->registerJs("$('.server-parts').load('$url', function () {
                            $(this).closest('.box').find('.overlay').remove();
                        });") ?>
                <?php $box->endBody() ?>
                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
            <?php $box->end() ?>
        </div>
    </div>
<?php endif ?>
