<?php  
namespace app\models;  

use Yii;  

/**  
* This is the model class for table "producto_ingrediente".  
*  
* @property int $id_producto
* @property int $id_ingrediente
* @property float|null $cantidad
*  
* @property Ingrediente $ingrediente
* @property Producto $producto
*/ 
class ProductoIngrediente extends \yii\db\ActiveRecord {       
    /**      
    * {@inheritdoc}      
    */     
    public static function tableName()     
    {         
        return 'producto_ingrediente';     
    }      
    
    /**      
    * {@inheritdoc}      
    */     
    public function rules()     
    {         
        return [             
            [['id_producto', 'id_ingrediente'], 'required'],
            [['id_producto', 'id_ingrediente'], 'integer'],
            [['cantidad'], 'number'],
            [['id_producto', 'id_ingrediente'], 'unique', 'targetAttribute' => ['id_producto', 'id_ingrediente']],
            [['id_ingrediente'], 'exist', 'skipOnError' => true, 'targetClass' => Ingrediente::class, 'targetAttribute' => ['id_ingrediente' => 'id_ingrediente']],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::class, 'targetAttribute' => ['id_producto' => 'id_producto']],
        ];     
    }      

    /**      
    * {@inheritdoc}      
    */     
    public function attributeLabels()     
    {         
        return [             
            'id_producto' => 'Producto',
            'id_ingrediente' => 'Ingrediente',
            'cantidad' => 'Cantidad',
        ];     
    }  
    
    /**
     * Gets query for [[Ingrediente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngrediente()
    {
        return $this->hasOne(Ingrediente::class, ['id_ingrediente' => 'id_ingrediente']);
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