<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ingrediente;

/**
 * IngredienteSearch represents the model behind the search form of `app\models\Ingrediente`.
 */
class IngredienteSearch extends Model
{
    public $id_ingrediente;
    public $nombre;
    public $tipo;
    public $unidad_medida;
    public $id_proveedor;
    public $proveedor_nombre;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ingrediente', 'id_proveedor'], 'integer'],
            [['nombre', 'tipo', 'unidad_medida', 'proveedor_nombre'], 'safe'],
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
        $query = Ingrediente::find();

        // add conditions that should always apply here
        $query->joinWith(['proveedor']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['proveedor_nombre'] = [
            'asc' => ['proveedores.nombre_empresa' => SORT_ASC],
            'desc' => ['proveedores.nombre_empresa' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_ingrediente' => $this->id_ingrediente,
            'id_proveedor' => $this->id_proveedor,
        ]);

        $query->andFilterWhere(['like', 'ingredientes.nombre', $this->nombre])
              ->andFilterWhere(['like', 'tipo', $this->tipo])
              ->andFilterWhere(['like', 'unidad_medida', $this->unidad_medida])
              ->andFilterWhere(['like', 'proveedores.nombre_empresa', $this->proveedor_nombre]);

        return $dataProvider;
    }
}