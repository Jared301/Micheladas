<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Metodospago;

/**
 * MetodospagoSearch represents the model behind the search form of `app\models\Metodospago`.
 */
class MetodospagoSearch extends Model
{
    public $id_metodo_pago;
    public $tipo;
    public $detalles;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_metodo_pago'], 'integer'],
            [['tipo', 'detalles'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Metodospago::find();

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
            'id_metodo_pago' => $this->id_metodo_pago,
        ]);

        $query->andFilterWhere(['like', 'tipo', $this->tipo])
              ->andFilterWhere(['like', 'detalles', $this->detalles]);

        return $dataProvider;
    }
}