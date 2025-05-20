<?php
namespace app\models;

use Yii;

class Venta extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'ventas';
    }

    public function getId()
    {
        return $this->id_venta;
    }

    public function rules()
    {
        return [
            [['fecha','total'],'required'],
            [['fecha'],'safe'],
            [['total'],'number'],
            [['id_cliente','id_empleado','id_metodo_pago'],'integer'],
            [['id_cliente'],'exist','skipOnError'=>true,'targetClass'=>Cliente::class,'targetAttribute'=>['id_cliente'=>'id_cliente']],
            [['id_empleado'],'exist','skipOnError'=>true,'targetClass'=>Empleado::class,'targetAttribute'=>['id_empleado'=>'id_empleado']],
            [['id_metodo_pago'],'exist','skipOnError'=>true,'targetClass'=>Metodospago::class,'targetAttribute'=>['id_metodo_pago'=>'id_metodo_pago']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_venta'      => 'ID',
            'fecha'         => 'Fecha',
            'total'         => 'Total',
            'id_cliente'    => 'Cliente',
            'id_empleado'   => 'Empleado',
            'id_metodo_pago'=> 'Método de Pago',
        ];
    }

    public function getCliente()
    {
        return $this->hasOne(Cliente::class,['id_cliente'=>'id_cliente']);
    }

    public function getEmpleado()
    {
        return $this->hasOne(Empleado::class,['id_empleado'=>'id_empleado']);
    }

    public function getMetodoPago()
    {
        return $this->hasOne(Metodospago::class,['id_metodo_pago'=>'id_metodo_pago']);
    }

    public function getDetalles()
    {
        return $this->hasMany(DetalleVenta::class,['id_venta'=>'id_venta']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert && empty($this->fecha)) {
                $this->fecha = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }
}
