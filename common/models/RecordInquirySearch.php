<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RecordInquiry;

/**
 * RecordInquirySearch represents the model behind the search form about `common\models\RecordInquiry`.
 */
class RecordInquirySearch extends RecordInquiry
{
    public $start_date;
    public $end_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'new_inquiry_count', 'quotation_count', 'followup_count', 'booking_count', 'cancellation_count', 'date', 'status', 'created_at', 'updated_at'], 'integer'],
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
    public function search($params)
    {
        if(isset($params['RecordInquirySearch']['start_date'])) {
            $start_date = strtotime($params['RecordInquirySearch']['start_date']);
            $params['RecordInquirySearch']['start_date'] = $start_date;
        }
        else {
            $start_date = '';
        }

        if(isset($params['RecordInquirySearch']['end_date'])) {
            $end_date = strtotime($params['RecordInquirySearch']['end_date']);
            $params['RecordInquirySearch']['end_date'] = $end_date;
        }
        else {
            $end_date = '';
        }

        $today =  strtotime('today');
        if(isset($params['sort'])) {
            if(isset($params['RecordInquirySearch']['user_id'])) {
                $query = RecordInquiry::find();
            }
            else  if(isset($params['RecordInquirySearch']['start_date']) || isset($params['RecordInquirySearch']['end_date'])) {
                $query = RecordInquiry::find();
            }
            else{
                $query = RecordInquiry::find()->where(['date' => $today]);
            }
        }
        else{
            if(isset($params['RecordInquirySearch']['user_id'])) {
                $query = RecordInquiry::find()->orderBy(['date' => SORT_DESC]);
            }
            else  if(isset($params['RecordInquirySearch']['start_date']) || isset($params['RecordInquirySearch']['end_date'])) {
                $query = RecordInquiry::find()->orderBy(['date' => SORT_DESC]);
            }
            else{
                $query = RecordInquiry::find()->where(['date' => $today])->orderBy(['date' => SORT_DESC]);
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
            'new_inquiry_count' => $this->new_inquiry_count,
            'quotation_count' => $this->quotation_count,
            'followup_count' => $this->followup_count,
            'booking_count' => $this->booking_count,
            'cancellation_count' => $this->cancellation_count,
           // 'date' => $this->date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if(isset($params['RecordInquirySearch']['start_date']) && isset($params['RecordInquirySearch']['end_date'])) {
            if($start_date!='' && $end_date != '') {
                $query->andFilterWhere(['between', 'date', $start_date, $end_date]);
            }
        }

        return $dataProvider;
    }
}
