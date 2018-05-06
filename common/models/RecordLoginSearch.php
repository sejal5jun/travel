<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RecordLogin;

/**
 * RecordLoginSearch represents the model behind the search form about `common\models\RecordLogin`.
 */
class RecordLoginSearch extends RecordLogin
{
    public $start_date;
    public $end_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'login_time', 'logout_time', 'status', 'created_at', 'updated_at'], 'integer'],
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
        if(isset($params['RecordLoginSearch']['start_date'])) {
            $start_date = strtotime($params['RecordLoginSearch']['start_date']);
            $params['RecordLoginSearch']['start_date'] = $start_date;
        }
        else {
            $start_date = '';
        }

        if(isset($params['RecordLoginSearch']['end_date'])) {
            $end_date = strtotime($params['RecordLoginSearch']['end_date']);
            $params['RecordLoginSearch']['end_date'] = $end_date;
        }
        else {
            $end_date = '';
        }
      //  echo $start_date . '    ' . $end_date;exit;
       // $tim = date('h:i:s a',$model->login_time);
        $today_start =  strtotime('today');
        $today_end =  strtotime('today')+86400;
      //  echo $today;exit;
        if(isset($params['sort'])) {
            if(isset($params['RecordLoginSearch']['user_id'])) {
                $query = RecordLogin::find()->where(['!=', 'login_time', '']);
            }
            else  if(isset($params['RecordLoginSearch']['start_date']) || isset($params['RecordLoginSearch']['end_date'])) {
                $query = RecordLogin::find()->where(['!=', 'login_time', '']);
            }
            else{
                $query = RecordLogin::find()->where(['!=', 'login_time', ''])->andWhere(['between', 'login_time', $today_start, $today_end]);
            }
        }
        else{
            if(isset($params['RecordLoginSearch']['user_id'])) {
                $query = RecordLogin::find()->where(['!=', 'login_time', ''])->orderBy(['login_time' => SORT_DESC]);
            }
            else  if(isset($params['RecordLoginSearch']['start_date']) || isset($params['RecordLoginSearch']['end_date'])) {
                $query = RecordLogin::find()->where(['!=', 'login_time', ''])->orderBy(['login_time' => SORT_DESC]);
            }
            else{
                $query = RecordLogin::find()->where(['!=', 'login_time', ''])->andWhere(['between', 'login_time', $today_start, $today_end])->orderBy(['login_time' => SORT_DESC]);
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
            'user_id' => $this->user_id,
            'login_time' => $this->login_time,
            'logout_time' => $this->logout_time,
            'status' => $this->status,
            //'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if(isset($params['RecordLoginSearch']['start_date']) && isset($params['RecordLoginSearch']['end_date'])) {
            if($start_date!='' && $end_date != '') {
                $query->andFilterWhere(['between', 'login_time', $start_date, $end_date+86400]);
            }
        }

        return $dataProvider;
    }
}
