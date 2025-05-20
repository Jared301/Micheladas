<?php
namespace app\models;

use Yii;

class Producto extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'producto';
    }

    public function getId()
    {
        return $this->id_producto;
    }

    public function rules()
    {
        return [
            [['descripcion'],'default','value'=>null],
            [['nombre','precio','stock'],'required'],
            [['descripcion'],'string'],
            [['precio'],'number'],
            [['stock'],'integer'],
            [['created_at'],'safe'],
            [['nombre'],'string','max'=>255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_producto' => 'ID',
            'nombre'      => 'Nombre',
            'descripcion' => 'Descripción',
            'precio'      => 'Precio',
            'stock'       => 'Stock',
            'created_at'  => 'Creado en',
        ];
    }

    public function getPromociones()
    {
        return $this->hasMany(Promocion::class,['id_producto'=>'id_producto']);
    }

    public function getProductoIngredientes()
    {
        return $this->hasMany(ProductoIngrediente::class,['id_producto'=>'id_producto']);
    }

    public function getIngredientes()
    {
        return $this->hasMany(Ingrediente::class,['id_ingrediente'=>'id_ingrediente'])
                    ->viaTable('producto_ingrediente',['id_producto'=>'id_producto']);
    }

    public function getDetalleVentas()
    {
        return $this->hasMany(DetalleVenta::class,['id_producto'=>'id_producto']);
    }
}
