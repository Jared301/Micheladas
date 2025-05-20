<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductoIngrediente;

/**
 * ProductoIngredienteSearch represents the model behind the search form of `app\models\ProductoIngrediente`.
 */
class ProductoIngredienteSearch extends Model
{
    public $id_producto;
    public $id_ingrediente;
    public $cantidad;
    public $producto_nombre;
    public $ingrediente_nombre;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_producto', 'id_ingrediente'], 'integer'],
            [['producto_nombre', 'ingrediente_nombre'], 'safe'],
            [['cantidad'], 'number'],
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
        $query = ProductoIngrediente::find();

        // add conditions that should always apply here
        $query->joinWith(['producto', 'ingrediente']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['producto_nombre'] = [
            'asc' => ['producto.nombre' => SORT_ASC],
            'desc' => ['producto.nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['ingrediente_nombre'] = [
            'asc' => ['ingrediente.nombre' => SORT_ASC],
            'desc' => ['ingrediente.nombre' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_producto' => $this->id_producto,
            'id_ingrediente' => $this->id_ingrediente,
            'cantidad' => $this->cantidad,
        ]);

        $query->andFilterWhere(['like', 'producto.nombre', $this->producto_nombre])
              ->andFilterWhere(['like', 'ingrediente.nombre', $this->ingrediente_nombre]);

        return $dataProvider;
    }
}