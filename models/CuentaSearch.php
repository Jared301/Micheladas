<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cuenta;

/**
 * CuentaSearch represents the model behind the search form of `app\models\Cuenta`.
 */
class CuentaSearch extends Model
{
    public $id_cuenta;
    public $id_venta;
    public $id_producto;
    public $venta_fecha;
    public $producto_nombre;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cuenta', 'id_venta', 'id_producto'], 'integer'],
            [['venta_fecha', 'producto_nombre'], 'safe'],
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
        $query = Cuenta::find();

        // add conditions that should always apply here
        $query->joinWith(['venta', 'producto']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id_cuenta' => SORT_DESC,
                ]
            ]
        ]);

        $dataProvider->sort->attributes['venta_fecha'] = [
            'asc' => ['ventas.fecha' => SORT_ASC],
            'desc' => ['ventas.fecha' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['producto_nombre'] = [
            'asc' => ['producto.nombre' => SORT_ASC],
            'desc' => ['producto.nombre' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_cuenta' => $this->id_cuenta,
            'id_venta' => $this->id_venta,
            'id_producto' => $this->id_producto,
        ]);

        if ($this->venta_fecha) {
            $query->andFilterWhere(['between', 'ventas.fecha', $this->venta_fecha . ' 00:00:00', $this->venta_fecha . ' 23:59:59']);
        }

        $query->andFilterWhere(['like', 'producto.nombre', $this->producto_nombre]);

        return $dataProvider;
    }
}