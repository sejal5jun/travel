<?php

namespace common\models;

use backend\models\enums\InquiryStatusTypes;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Followup;

/**
 * FollowupSearch represents the model behind the search form about `common\models\Followup`.
 */
class FollowupSearch extends Followup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inquiry_package_id', 'date', 'status', 'created_at', 'updated_at', 'inquiry_id'], 'integer'],
            [['note','by'], 'safe'],
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
     * @param array $status
     *
     * @return ActiveDataProvider
     */
    public function search($params,$status)
    {

        if(isset($params['FollowupSearch']['date'])) {

            if($params['FollowupSearch']['date'] != "") {
                $date = strtotime($params['FollowupSearch']['date']);
                $params['FollowupSearch']['date'] = $date;
            }
            else {
                $date =  strtotime(date("M-d-Y"));
            }
        }
        else {
            $date =  strtotime(date("M-d-Y"));
        }



        if($status==Followup::OVERDUE_FOLLOWUPS)

            $query = Followup::find()->joinWith('inquiry')->where(['followup.status' => $status, 'inquiry.status'=>InquiryStatusTypes::QUOTED])->andWhere(['!=', 'is_followup', 1]);
        else
            $query = Followup::find()->joinWith('inquiry')->where(['followup.date'=>$date, 'followup.status' => $status,'inquiry.status'=>InquiryStatusTypes::QUOTED])->andWhere(['!=', 'is_followup', 1]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
      

        $query->andFilterWhere([
            'id' => $this->id,
            'inquiry_id' => $this->inquiry_id,
            'inquiry_package_id' => $this->inquiry_package_id,
            //'date' => $this->date,
           // 'by' => $this->by,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note])
              ->andFilterWhere(['like', 'inquiry.username', $this->by]);

        if(isset($params['FollowupSearch']['date'])) {

            $query->andFilterWhere(['like', 'date', $date]);

        }



        return $dataProvider;
    }
}
