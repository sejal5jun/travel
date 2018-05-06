<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PackagePricing;

/**
 * PackagePricingSearch represents the model behind the search form about `common\models\PackagePricing`.
 */
class PackagePricingSearch extends PackagePricing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'package_id', 'currency_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'safe'],
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
    public function search($params,$package_id=null)
    {
        $query = PackagePricing::find();

        if($package_id!=null){
            $query = PackagePricing::find()->where(['package_id' => $package_id]);
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
            'package_id' => $this->package_id,
            'currency_id' => $this->currency_id,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'price', $this->price]);

        return $dataProvider;
    }
}
