<?php  
namespace app\models;  

use Yii;  

/**  
* This is the model class for table "inventario".  
*  
* @property int $id_inventario
* @property int $id_ingrediente
* @property float $cantidad_actual
* @property string|null $fecha_actualizacion
*
* @property Ingrediente $ingrediente
*/ 
class Inventario extends \yii\db\ActiveRecord {       
    /**      
    * {@inheritdoc}      
    */     
    public static function tableName()     
    {         
        return 'inventario';     
    }      
    
    /**
    * Para que el ID sea accesible como 'id' manteniendo compatibilidad
    */
    public function getId()
    {
        return $this->id_inventario;
    }
    
    /**      
    * {@inheritdoc}      
    */     
    public function rules()     
    {         
        return [             
            [['id_ingrediente', 'cantidad_actual'], 'required'],
            [['id_ingrediente'], 'integer'],
            [['cantidad_actual'], 'number'],
            [['fecha_actualizacion'], 'safe'],
            [['id_ingrediente'], 'exist', 'skipOnError' => true, 'targetClass' => Ingrediente::class, 'targetAttribute' => ['id_ingrediente' => 'id_ingrediente']],
        ];     
    }      

    /**      
    * {@inheritdoc}      
    */     
    public function attributeLabels()     
    {         
        return [             
            'id_inventario' => 'ID',             
            'id_ingrediente' => 'Ingrediente',             
            'cantidad_actual' => 'Cantidad Actual',
            'fecha_actualizacion' => 'Fecha de Actualización',
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
     * Before save event handler.
     * 
     * @param bool $insert whether this method is called for inserting or updating
     * @return bool whether the save should continue
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->fecha_actualizacion = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}