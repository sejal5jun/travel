<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ActivityCount;

/**
 * ActivityCountSearch represents the model behind the search form about `common\models\ActivityCount`.
 */
class ActivityCountSearch extends ActivityCount
{
    public $start_date;
    public $end_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'date', 'quotation_count', 'followup_count', 'status', 'created_at', 'updated_at'], 'integer'],
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
        if(isset($params['ActivityCountSearch']['start_date'])) {
            $start_date = strtotime($params['ActivityCountSearch']['start_date']);
            $params['ActivityCountSearch']['start_date'] = $start_date;
        }
        else {
            $start_date = '';
        }

        if(isset($params['ActivityCountSearch']['end_date'])) {
            $end_date = strtotime($params['ActivityCountSearch']['end_date']);
            $params['ActivityCountSearch']['end_date'] = $end_date;
        }
        else {
            $end_date = '';
        }
        if(isset($params['ActivityCountSearch']['user_id'])) {
            $query = ActivityCount::find();
        }
        else  if(isset($params['ActivityCountSearch']['start_date']) || isset($params['ActivityCountSearch']['end_date'])) {
            $query = ActivityCount::find();
        }
        else{
            $query = ActivityCount::find()->where(['date' => strtotime('today')]);
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
            'user_id' => $this->user_id,
            'date' => $this->date,
            'quotation_count' => $this->quotation_count,
            'followup_count' => $this->followup_count,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if(isset($params['ActivityCountSearch']['start_date']) && isset($params['ActivityCountSearch']['end_date'])) {
            if($start_date!='' && $end_date!='') {
                $query->andFilterWhere(['between', 'date', $start_date, $end_date]);
            }
        }

        return $dataProvider;
    }
}
