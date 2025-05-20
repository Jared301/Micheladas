<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mesa;

/**
 * MesaSearch represents the model behind the search form of `app\models\Mesa`.
 */
class MesaSearch extends Model
{
    public $id_mesa;
    public $numero_mesa;
    public $capacidad;
    public $estado;
    public $id_empleado;
    public $nombre_empleado; // Para búsqueda por nombre de empleado

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_mesa', 'numero_mesa', 'capacidad', 'id_empleado'], 'integer'],
            [['estado', 'nombre_empleado'], 'safe'],
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
        $query = Mesa::find();

        // Agregamos join con la tabla de empleados para poder buscar por nombre
        $query->joinWith(['empleado']);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Configuramos el sort para poder ordenar por nombre de empleado
        $dataProvider->sort->attributes['nombre_empleado'] = [
            'asc' => ['empleados.nombre' => SORT_ASC],
            'desc' => ['empleados.nombre' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_mesa' => $this->id_mesa,
            'numero_mesa' => $this->numero_mesa,
            'capacidad' => $this->capacidad,
            'id_empleado' => $this->id_empleado,
        ]);

        $query->andFilterWhere(['like', 'estado', $this->estado])
              ->andFilterWhere(['like', 'empleados.nombre', $this->nombre_empleado]);

        return $dataProvider;
    }
}