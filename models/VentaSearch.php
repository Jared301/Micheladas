<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Venta;

/**
 * VentaSearch represents the model behind the search form of `app\models\Venta`.
 */
class VentaSearch extends Model
{
    public $id_venta;
    public $fecha;
    public $total;
    public $id_cliente;
    public $id_empleado;
    public $id_metodo_pago;
    public $cliente_nombre;
    public $empleado_nombre;
    public $metodo_pago_tipo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_venta', 'id_cliente', 'id_empleado', 'id_metodo_pago'], 'integer'],
            [['fecha', 'cliente_nombre', 'empleado_nombre', 'metodo_pago_tipo'], 'safe'],
            [['total'], 'number'],
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
        $query = Venta::find();

        // add conditions that should always apply here
        $query->joinWith(['cliente', 'empleado', 'metodoPago']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'fecha' => SORT_DESC,
                ]
            ]
        ]);

        $dataProvider->sort->attributes['cliente_nombre'] = [
            'asc' => ['clientes.nombre' => SORT_ASC],
            'desc' => ['clientes.nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['empleado_nombre'] = [
            'asc' => ['empleados.nombre' => SORT_ASC],
            'desc' => ['empleados.nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['metodo_pago_tipo'] = [
            'asc' => ['metodos_de_pago.tipo' => SORT_ASC],
            'desc' => ['metodos_de_pago.tipo' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_venta' => $this->id_venta,
            'total' => $this->total,
            'id_cliente' => $this->id_cliente,
            'id_empleado' => $this->id_empleado,
            'id_metodo_pago' => $this->id_metodo_pago,
        ]);

        if ($this->fecha) {
            $query->andFilterWhere(['between', 'fecha', $this->fecha . ' 00:00:00', $this->fecha . ' 23:59:59']);
        }

        $query->andFilterWhere(['like', 'clientes.nombre', $this->cliente_nombre])
              ->andFilterWhere(['like', 'empleados.nombre', $this->empleado_nombre])
              ->andFilterWhere(['like', 'metodos_de_pago.tipo', $this->metodo_pago_tipo]);

        return $dataProvider;
    }
}