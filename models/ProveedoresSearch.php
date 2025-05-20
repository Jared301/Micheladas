<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Proveedores;

class ProveedoresSearch extends Proveedores
{
    public function rules()
    {
        return [
            [['id_proveedor'], 'integer'],
            [['nombre_empresa', 'contacto', 'telefono', 'correo_electronico', 'direccion'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Proveedores::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id_proveedor' => $this->id_proveedor])
              ->andFilterWhere(['like', 'nombre_empresa', $this->nombre_empresa])
              ->andFilterWhere(['like', 'contacto', $this->contacto])
              ->andFilterWhere(['like', 'telefono', $this->telefono])
              ->andFilterWhere(['like', 'correo_electronico', $this->correo_electronico])
              ->andFilterWhere(['like', 'direccion', $this->direccion]);

        return $dataProvider;
    }
}