<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InquiryPackage;

/**
 * InquiryPackageSearch represents the model behind the search form about `common\models\InquiryPackage`.
 */
class InquiryPackageSearch extends InquiryPackage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inquiry_id', 'package_id','is_itinerary' ,'from_date', 'return_date', 'no_of_nights', 'adult_count', 'children_count', 'room_count', 'category', 'status', 'created_at', 'updated_at', 'is_quotation_sent'], 'integer'],
            [['notes', 'package_include','validity', 'package_exclude', 'package_name', 'destination', 'passenger_name', 'passenger_email', 'passenger_mobile', 'no_of_days_nights', 'terms_and_conditions', 'other_info', 'hotel_details','leaving_from', 'itinerary_name'], 'safe'],
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
        $query = InquiryPackage::find();

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
            'package_id' => $this->package_id,
            'is_itinerary' => $this->is_itinerary,
            'from_date' => $this->from_date,
            'return_date' => $this->return_date,
            'no_of_nights' => $this->no_of_nights,
            'adult_count' => $this->adult_count,
            'children_count' => $this->children_count,
            'room_count' => $this->room_count,
            'category' => $this->category,
            'is_quotation_sent' => $this->is_quotation_sent,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'itinerary', $this->no_of_days_nights])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'destination', $this->destination])
            ->andFilterWhere(['like', 'leaving_from', $this->destination])
            ->andFilterWhere(['like', 'package_include', $this->package_include])
            ->andFilterWhere(['like', 'package_exclude', $this->package_exclude])
            ->andFilterWhere(['like', 'package_name', $this->package_name])
            ->andFilterWhere(['like', 'itinerary_name', $this->itinerary_name])
            ->andFilterWhere(['like', 'passenger_name', $this->passenger_name])
            ->andFilterWhere(['like', 'passenger_email', $this->passenger_email])
            ->andFilterWhere(['like', 'passenger_mobile', $this->passenger_mobile])
            ->andFilterWhere(['like', 'terms_and_conditions', $this->terms_and_conditions])
            ->andFilterWhere(['like', 'inquiry_details', $this->inquiry_details])
            ->andFilterWhere(['like', 'hotel_details', $this->hotel_details])
            ->andFilterWhere(['like', 'quotation_details', $this->quotation_details])
            ->andFilterWhere(['like', 'other_info', $this->other_info]);

        return $dataProvider;
    }
}
