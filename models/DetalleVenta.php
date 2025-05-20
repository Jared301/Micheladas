<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_venta".
 *
 * @property int $id_detalle
 * @property int $id_venta
 * @property int $id_producto
 * @property float $cantidad
 * @property float $precio_unitario
 *
 * @property Producto $producto
 * @property Venta $venta
 */
class DetalleVenta extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_venta', 'id_producto', 'cantidad', 'precio_unitario'], 'required'],
            [['id_venta', 'id_producto'], 'integer'],
            [['cantidad', 'precio_unitario'], 'number'],
            [['id_venta'], 'exist', 'skipOnError' => true, 'targetClass' => Venta::class, 'targetAttribute' => ['id_venta' => 'id_venta']],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::class, 'targetAttribute' => ['id_producto' => 'id_producto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_venta' => 'Id Venta',
            'id_producto' => 'Id Producto',
            'cantidad' => 'Cantidad',
            'precio_unitario' => 'Precio Unitario',
        ];
    }

    /**
     * Gets query for [[Producto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Producto::class, ['id_producto' => 'id_producto']);
    }

    /**
     * Gets query for [[Venta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenta()
    {
        return $this->hasOne(Venta::class, ['id_venta' => 'id_venta']);
    }

}
