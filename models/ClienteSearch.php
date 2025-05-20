<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cliente;

class ClienteSearch extends Cliente
{
    public function rules()
    {
        return [
            [['id_cliente'], 'integer'],
            [['nombre', 'apellido', 'telefono', 'correo_electronico', 'direccion'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Cliente::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id_cliente' => $this->id_cliente])
              ->andFilterWhere(['like', 'nombre', $this->nombre])
              ->andFilterWhere(['like', 'apellido', $this->apellido])
              ->andFilterWhere(['like', 'telefono', $this->telefono])
              ->andFilterWhere(['like', 'correo_electronico', $this->correo_electronico])
              ->andFilterWhere(['like', 'direccion', $this->direccion]);

        return $dataProvider;
    }
}