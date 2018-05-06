<?php

namespace common\models;

use backend\models\enums\UserTypes;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ScheduleFollowup;
use common\models\Inquiry;

/**
 * ScheduleFollowupSearch represents the model behind the search form about `common\models\ScheduleFollowup`.
 */
class ScheduleFollowupSearch extends ScheduleFollowup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inquiry_id', 'scheduled_at', 'scheduled_by', 'is_sent', 'status', 'created_at', 'updated_at'], 'integer'],
            [['passenger_email', 'text_body'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$inquiry_id='',$search='')
    {
        if($search == ""){
            $in_ids=[];
            if($search == '') {
                $inq_model = Inquiry::find()->where(['or',['inquiry_head'=>Yii::$app->user->identity->id],['follow_up_head'=>Yii::$app->user->identity->id]
                ,['quotation_manager'=>Yii::$app->user->identity->id],['quotation_staff'=>Yii::$app->user->identity->id],['follow_up_staff'=>Yii::$app->user->identity->id]])->all();
            }

           foreach($inq_model as $val)
            {
                $in_ids[]= $val->id;
            }

        }
        if(isset($params['sort'])) {
            if($search != ""){

                $query = ScheduleFollowup::find();
                if ($inquiry_id != '') {
                    $query = ScheduleFollowup::find()->where(['inquiry_id' => $inquiry_id]);
                }
               /* if (Yii::$app->user->identity->role != UserTypes::ADMIN) {
                    $query = ScheduleFollowup::find();
                    if ($inquiry_id != '') {
                        $query = ScheduleFollowup::find()->where(['inquiry_id' => $inquiry_id, 'scheduled_by' => Yii::$app->user->identity->id]);
                    }
                }*/
            }else {
                if ($inquiry_id != '') {
                    $query = ScheduleFollowup::find()->where(['inquiry_id' => $inquiry_id]);
                }
                else {
                    $query = ScheduleFollowup::find()->where(['in', 'inquiry_id', $in_ids]);
                }
            }
        }
        else{
            if($search != ""){

                $query = ScheduleFollowup::find()->orderBy(['scheduled_at' => SORT_DESC]);
                if ($inquiry_id != '') {
                    $query = ScheduleFollowup::find()->where(['inquiry_id' => $inquiry_id])->orderBy(['scheduled_at' => SORT_DESC]);
                }
                /*if (Yii::$app->user->identity->role != UserTypes::ADMIN) {
                    $query = ScheduleFollowup::find()->orderBy(['scheduled_at' => SORT_DESC]);
                    if ($inquiry_id != '') {
                        $query = ScheduleFollowup::find()->where(['inquiry_id' => $inquiry_id, 'scheduled_by' => Yii::$app->user->identity->id])->orderBy(['scheduled_at' => SORT_DESC]);
                    }
                }*/
            }
            else {
                if ($inquiry_id != '') {
                    $query = ScheduleFollowup::find()->where(['inquiry_id' => $inquiry_id])->orderBy(['scheduled_at' => SORT_DESC]);
                }
                else {
                    $query = ScheduleFollowup::find()->where(['in', 'inquiry_id', $in_ids])->orderBy(['scheduled_at' => SORT_DESC]);
                }
            }
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'inquiry_id' => $this->inquiry_id,
            'scheduled_at' => $this->scheduled_at,
            'scheduled_by' => $this->scheduled_by,
            'is_sent' => $this->is_sent,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'passenger_email', $this->passenger_email])
            ->andFilterWhere(['like', 'text_body', $this->text_body]);

        return $dataProvider;
    }
}
