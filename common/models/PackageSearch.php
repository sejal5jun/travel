<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Package;

/**
 * PackageSearch represents the model behind the search form about `common\models\Package`.
 */
class PackageSearch extends Package
{

    public $pac_country;
    public $pac_city;
    public $pac_pricetype;
    public $pac_currency;
    public $pac_price;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'for_agent','updated_at', 'category', 'type'], 'integer'],
            [['pac_country','validity','pac_city','pac_pricetype','pac_currency','pac_price','name', 'package_include', 'package_exclude', 'terms_and_conditions', 'other_info', 'no_of_days_nights', 'itinerary_name'], 'safe'],
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
     //echo count(array_filter($params['PackageSearch'])); exit;


/*if(!isset($params['PackageSearch']) ){

    $query = Package::find()->where(['package.status' => 10]);
}
if(isset($params['PackageSearch'])  && count(array_filter($params['PackageSearch']))== 0 )
{

    $query = Package::find()->where(['package.status' => 10]);
}
else
 {*/
    $query = Package::find()->where(['package.status' => 10]);

    $query->joinWith(['packagePricings','packageCities','packageCountries'])->groupBy('package.id');
//}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'totalCount' => Package::find()->where(['package.status' => 10])->count(),

            'pagination' =>[
                'pageSize'=>75,

            ],
               ]);

        //print_r($dataProvider->count()); exit;
      //  print_r($dataProvider->getPagination()); exit;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'category' => $this->category,
            'no_of_days_nights' => $this->no_of_days_nights,
            'status' => $this->status,
            'for_agent' => $this->for_agent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'package_pricing.currency_id' => $this->pac_currency,
            'package_pricing.type' => $this->pac_pricetype,
            'package_city.city_id' => $this->pac_city,
            'package_country.country_id' => $this->pac_country,
            'package_pricing.pac_price' => $this->pac_price,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'itinerary_name', $this->itinerary_name])
            ->andFilterWhere(['like', 'package_include', $this->package_include])
            ->andFilterWhere(['like', 'package_exclude', $this->package_exclude])
            ->andFilterWhere(['like', 'validity', $this->validity])
            ->andFilterWhere(['like', 'terms_and_conditions', $this->terms_and_conditions])
            //price and country and city
           // ->andFilterWhere(['like', 'package_pricing.price', $this->pac_price])

            ->andFilterWhere(['like', 'other_info', $this->other_info]);
			
			//echo'<pre>'; print_r($query); exit;

        //echo "<pre>";print_r($query->createCommand()->rawSql);exit;
//echo '<pre>'; print_r($dataProvider); exit;
        return $dataProvider;
    }
}
