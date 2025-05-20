<?php  
namespace app\models;  

use Yii;  

/**  
* This is the model class for table "cuenta".  
*  
* @property int $id_cuenta
* @property int $id_venta
* @property int $id_producto
*
* @property Venta $venta
* @property Producto $producto
*/ 
class Cuenta extends \yii\db\ActiveRecord {       
    /**      
    * {@inheritdoc}      
    */     
    public static function tableName()     
    {         
        return 'cuenta';     
    }      
    
    /**
    * Para que el ID sea accesible como 'id' manteniendo compatibilidad
    */
    public function getId()
    {
        return $this->id_cuenta;
    }
    
    /**      
    * {@inheritdoc}      
    */     
    public function rules()     
    {         
        return [             
            [['id_venta', 'id_producto'], 'required'],
            [['id_venta', 'id_producto'], 'integer'],
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
            'id_cuenta' => 'ID',             
            'id_venta' => 'Venta',             
            'id_producto' => 'Producto',
        ];     
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
    
    /**
     * Gets query for [[Producto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Producto::class, ['id_producto' => 'id_producto']);
    }
}