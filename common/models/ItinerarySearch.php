<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Itinerary;

/**
 * ItinerarySearch represents the model behind the search form about `common\models\Itinerary`.
 */
class ItinerarySearch extends Itinerary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'package_id', 'no_of_itineraries', 'media_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'title', 'description'], 'safe'],
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
        $query = Itinerary::find();

        if($package_id!=null){
            $query = Itinerary::find()->where(['package_id' => $package_id]);
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
            'no_of_itineraries' => $this->no_of_itineraries,
            'media_id' => $this->media_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
