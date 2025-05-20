<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Promocion;

/**
 * PromocionSearch represents the model behind the search form of `app\models\Promocion`.
 */
class PromocionSearch extends Model
{
    public $id_promocion;
    public $nombre;
    public $descripcion;
    public $descuento;
    public $fecha_inicio;
    public $fecha_fin;
    public $id_producto;
    public $nombre_producto; // Para búsqueda por nombre de producto

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_promocion', 'id_producto'], 'integer'],
            [['nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'nombre_producto'], 'safe'],
            [['descuento'], 'number'],
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
        $query = Promocion::find();

        // Agregamos join con la tabla de productos para poder buscar por nombre
        $query->joinWith(['producto']);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Configuramos el sort para poder ordenar por nombre de producto
        $dataProvider->sort->attributes['nombre_producto'] = [
            'asc' => ['productos.nombre' => SORT_ASC],
            'desc' => ['productos.nombre' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_promocion' => $this->id_promocion,
            'descuento' => $this->descuento,
            'id_producto' => $this->id_producto,
        ]);

        if ($this->fecha_inicio) {
            $query->andFilterWhere(['>=', 'fecha_inicio', $this->fecha_inicio . ' 00:00:00']);
        }

        if ($this->fecha_fin) {
            $query->andFilterWhere(['<=', 'fecha_fin', $this->fecha_fin . ' 23:59:59']);
        }

        $query->andFilterWhere(['like', 'promociones.nombre', $this->nombre])
              ->andFilterWhere(['like', 'promociones.descripcion', $this->descripcion])
              ->andFilterWhere(['like', 'productos.nombre', $this->nombre_producto]);

        return $dataProvider;
    }
}