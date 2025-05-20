<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Inventario;

/**
 * InventarioSearch represents the model behind the search form of `app\models\Inventario`.
 */
class InventarioSearch extends Model
{
    public $id_inventario;
    public $id_ingrediente;
    public $cantidad_actual;
    public $fecha_actualizacion;
    public $ingrediente_nombre;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'id_ingrediente'], 'integer'],
            [['fecha_actualizacion', 'ingrediente_nombre'], 'safe'],
            [['cantidad_actual'], 'number'],
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
        $query = Inventario::find();

        // add conditions that should always apply here
        $query->joinWith(['ingrediente']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['ingrediente_nombre'] = [
            'asc' => ['ingredientes.nombre' => SORT_ASC],
            'desc' => ['ingredientes.nombre' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_inventario' => $this->id_inventario,
            'id_ingrediente' => $this->id_ingrediente,
            'cantidad_actual' => $this->cantidad_actual,
        ]);

        if ($this->fecha_actualizacion) {
            $query->andFilterWhere(['between', 'fecha_actualizacion', $this->fecha_actualizacion . ' 00:00:00', $this->fecha_actualizacion . ' 23:59:59']);
        }

        $query->andFilterWhere(['like', 'ingredientes.nombre', $this->ingrediente_nombre]);

        return $dataProvider;
    }
}