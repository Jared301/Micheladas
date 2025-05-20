<?php  
namespace app\models;  

use Yii;  

/**  
* This is the model class for table "ingredientes".  
*  
* @property int $id_ingrediente
* @property string $nombre
* @property string|null $tipo
* @property string|null $unidad_medida
* @property int|null $id_proveedor
*
* @property Inventario[] $inventarios
* @property Proveedores $proveedor
*/ 
class Ingrediente extends \yii\db\ActiveRecord {       
    /**      
    * {@inheritdoc}      
    */     
    public static function tableName()     
    {         
        return 'ingredientes';     
    }      
    
    /**
    * Para que el ID sea accesible como 'id' manteniendo compatibilidad
    */
    public function getId()
    {
        return $this->id_ingrediente;
    }
    
    /**      
    * {@inheritdoc}      
    */     
    public function rules()     
    {         
        return [             
            [['nombre'], 'required'],
            [['id_proveedor'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['tipo', 'unidad_medida'], 'string', 'max' => 50],
            [['id_proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedores::class, 'targetAttribute' => ['id_proveedor' => 'id_proveedor']],
        ];     
    }      

    /**      
    * {@inheritdoc}      
    */     
    public function attributeLabels()     
    {         
        return [             
            'id_ingrediente' => 'ID',             
            'nombre' => 'Nombre',             
            'tipo' => 'Tipo',
            'unidad_medida' => 'Unidad de Medida',
            'id_proveedor' => 'Proveedor',
        ];     
    }  
    
    /**
     * Gets query for [[Inventarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInventarios()
    {
        return $this->hasMany(Inventario::class, ['id_ingrediente' => 'id_ingrediente']);
    }
    
    /**
     * Gets query for [[Proveedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasOne(Proveedores::class, ['id_proveedor' => 'id_proveedor']);
    }
    

    /**
     * Gets query for [[ProductoIngredientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductoIngredientes()
    {
        return $this->hasMany(ProductoIngrediente::class, ['id_ingrediente' => 'id_ingrediente']);
    }

    /**
     * Gets query for [[Productos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductos()
    {
        return $this->hasMany(Producto::class, ['id_producto' => 'id_producto'])
            ->viaTable('producto_ingrediente', ['id_ingrediente' => 'id_ingrediente']);
    }
}