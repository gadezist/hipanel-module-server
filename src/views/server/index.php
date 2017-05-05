<?php

use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;

/** @var OsimageSearch $osimages */
/** @var yii\web\View $this */
$this->title = Yii::t('hipanel:server', 'Servers');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
    <?php $page->beginContent('show-actions') ?>
        <?= $page->renderLayoutSwitcher() ?>
        <?= $page->renderSorter([
            'attributes' => [
                'name', 'id',
                'client', 'tariff', 'ip',
                'state', 'status_time', 'expires',
            ],
        ]) ?>
        <?= $page->renderPerPage() ?>
        <?= $page->renderRepresentations(ServerGridView::class) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if (Yii::$app->user->can('manage')): ?>
            <div class="dropdown" style="display: inline-block">
                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('hipanel', 'Basic actions') ?>
                    <span class="caret"></span>
                </button>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'pull-right'],
                    'items' => [
                        [
                            'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel', 'Enable block'),
                            'url' => '#bulk-server-block-modal',
                            'linkOptions' => ['data-toggle' => 'modal']
                        ],
                        [
                            'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel', 'Disable block'),
                            'url' => '#bulk-server-unblock-modal',
                            'linkOptions' => ['data-toggle' => 'modal']
                        ],
                        [
                            'label' => '<i class="fa fa-trash"></i> ' . Yii::t('hipanel', 'Delete'),
                            'url' => '#bulk-server-delete-modal',
                            'linkOptions' => ['data-toggle' => 'modal']
                        ],
                    ],
                ]); ?>
            </div>
            <?= AjaxModal::widget([
                'id' => 'bulk-server-block-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Block servers'), ['class' => 'modal-title']),
                'scenario' => 'enable-block',
                'actionUrl' => ['bulk-enable-block-modal'],
                'toggleButton' => false,
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-server-unblock-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Unblock servers'), ['class' => 'modal-title']),
                'scenario' => 'disable-block',
                'actionUrl' => ['bulk-disable-block-modal'],
                'toggleButton' => false,
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-server-delete-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel', 'Delete'), ['class' => 'modal-title']),
                'scenario' => 'delete',
                'actionUrl' => ['bulk-delete-modal'],
                'toggleButton' => false,
            ]) ?>
        <?php endif ?>
    <?php $page->endContent('bulk-actions') ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm(); ?>
            <?= ServerGridView::widget([
                'dataProvider' => $dataProvider,
                'boxed' => false,
                'filterModel' => $model,
                'osImages' => $osimages,
                'representation' => $uiModel->representation,
            ]) ?>
        <?php $page->endBulkForm(); ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
<?php Pjax::end() ?>
