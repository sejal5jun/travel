<?php

use backend\models\enums\CategoryTypes;
use backend\models\enums\PackageTypes;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PackageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $type */

$this->title = 'Packages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Packages</div>
</div>
<ol class="breadcrumb">
    <li>
        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("site/index"); ?>">Dashboard</a>
    </li>
    <?php foreach ($this->params['breadcrumbs'] as $k => $v) {
        if (isset($v['label'])) {
            echo "<li><a href=" . $v['url'][0] . ">" . $v['label'] . "</a></li>";
        } else {
            echo "<li class='active ng-binding'>$v</li>";
        }
    }?>
</ol>


<div class="card bg-white">
    <div class="card-header">
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-primary btn-round']) ?>
    </div>
</div>
    <label class="control-label"><span class="inline bullet-label bg-info"></span>&nbsp;&nbsp;<span style="font-size: small">Domestic Holidays</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <label class="control-label"><span class="inline bullet-label bg-success"></span>&nbsp;&nbsp;<span style="font-size: small">International Holidays</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <label class="control-label"><span class="inline bullet-label bg-warning"></span>&nbsp;&nbsp;<span style="font-size: small">Luxury Holidays</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <label class="control-label"><span class="inline bullet-label bg-danger"></span>&nbsp;&nbsp;<span style="font-size: small">Honeymoon's Corner</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <label class="control-label"><span class="inline bullet-label bg-primary"></span>&nbsp;&nbsp;<span style="font-size: small">Weekend Getaways</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
<?php if(count($model)>0) {echo $this->render('_search', ['model' => $searchModel]); }?>


<div class="row">
    <div class="col-md-12 col-xs-12">
    <div class="card bg-white">
        <div class="card-block ">
            <div class="row">
            <div class="col-md-12 pull-right">
            <?= Html::button('Listview',['class'=>'btn btn-success','id'=>'change-view']) ?>
            </div>
            </div><br/>
            <div class="list-view">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
             'itemView' => function ($model) {

                return $this->render('_index_package', ['model' => $model]);
            },
            'layout' => '{summary}{pager}
                        <div class="card bg-white">
                            <div class="card-block">
                                <div class="row mb25">{items}</div>
                            </div>
                        </div>'
            ]);
        ?>
    </div>
            <div class="grid-view">

                <div class="card bg-white">
                    <div class="card-header bg-danger-dark">
                        <Strong class="text-white">Packages</Strong>
                        </div>
                    <div class="card-block">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel_quoted,
                    'tableOptions' => [
                        'class' => 'table table-hover table-condensed responsive m-b-0',
                    ],
                    'rowOptions'   => function ($model, $key, $index, $grid) {
                        return [
                            'data-id' => $model->id,
                            'class' => 'card-custom-height pending_index'
                        ];
                    },

                    'columns' => [

                        [
                            'attribute'=>'name',
                            'contentOptions' => ['class' => 'package-index'],

                        ],
                        [
                            'attribute'=>'validity',
                            'contentOptions' => ['class' => 'package-index'],
                            'value'=>function($model)
                            {
                                return ($model->validity!= '')?($model->validity.' to '.$model->till_validity):'';
                            }

                        ],

                        [
                            'attribute'=>'no_of_days_nights',
                            'contentOptions' => ['class' => 'package-index'],

                        ],
                        [
                            'attribute'=>'itinerary_name',
                            'contentOptions' => ['class' => 'package-index'],

                        ],
                        [
                            'attribute'=>'for_agent',
                            'format' =>'raw',
                            'label'=>'Package For',
                            'value'=>function($model)
                            {
                                return ($model->for_agent == 1)?'<span class="label label-danger">Agent</span>':'<span class="label label-success">Customer</span>';
                            },
                            'contentOptions' => ['class' => 'package-index'],

                        ],
                        'created_at:datetime'

                    ],
                ]); ?>

                </div>
                </div>
                </div>


    </div>
    </div>
    </div>
</div>

<?php

$this->registerJs('
     $("document").ready(function(){
     $(".list-view").hide();
        $(".package_type").on("change", function(){
            window.location = "' . Yii::$app->urlManager->createAbsoluteUrl("package/index"). '&type=" + $(this).val();
        });

        $(".card-header").click(function(){
          var model_id= $(this).attr("id");
        var url="'. Yii::$app->getUrlManager()->createUrl(['package/view']).'"
          window.location.href= url+"&id="+model_id ;


        })

      $("#change-view").click(function(){
     if( $(this).text() == "Listview")
      {
      $(".grid-view").hide();
      $(".list-view").show();
      $(this).text("Gridview");
      }else
      {
      $(".grid-view").show();
      $(".list-view").hide();
      $(this).text("Listview");
      }

      });

      $(".package-index").click(function (e) {
        var id = $(this).closest("tr").data("id");
        if(e.target == this)
            location.href = "' . Url::to(["package/view"]) . '&id=" + id;
    });

     });
');