<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Producto;

/**
 * ProductoSearch represents the model behind the search form of `app\models\Producto`.
 */
class ProductoSearch extends Model
{
    public $id_producto;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $tamaño;
    public $created_at;
    public $tiene_promociones; // Para buscar productos con/sin promociones

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_producto', 'stock'], 'integer'],
            [['nombre', 'descripcion', 'tamaño', 'created_at', 'tiene_promociones'], 'safe'],
            [['precio'], 'number'],
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
        $query = Producto::find();

        // add conditions that should always apply here
        $query->with('promociones');

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
            'id_producto' => $this->id_producto,
            'precio' => $this->precio,
            'stock' => $this->stock,
        ]);

        if ($this->created_at) {
            $query->andFilterWhere(['between', 'created_at', $this->created_at . ' 00:00:00', $this->created_at . ' 23:59:59']);
        }

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
              ->andFilterWhere(['like', 'descripcion', $this->descripcion])
              ->andFilterWhere(['like', 'tamaño', $this->tamaño]);

        // Filtrar por productos con/sin promociones
        if ($this->tiene_promociones == 'sí') {
            $query->joinWith(['promociones']);
        } elseif ($this->tiene_promociones == 'no') {
            $query->leftJoin('promociones', 'productos.id_producto = promociones.id_producto')
                  ->andWhere(['promociones.id_promocion' => null]);
        }

        return $dataProvider;
    }
}