<?php  
namespace app\models;  

use Yii;  

/**  
* This is the model class for table "clientes".  
*  
* @property int $id_cliente  
* @property string $nombre  
* @property string $apellido  
* @property string|null $telefono  
* @property string|null $correo_electronico  
* @property string|null $direccion  
*/ 
class Cliente extends \yii\db\ActiveRecord {       
    /**      
    * {@inheritdoc}      
    */     
    public static function tableName()     
    {         
        return 'clientes';     
    }      
    
    /**
    * Para que el ID sea accesible como 'id' manteniendo compatibilidad
    */
    public function getId()
    {
        return $this->id_cliente;
    }
    
    /**      
    * {@inheritdoc}      
    */     
    public function rules()     
    {         
        return [             
            [['nombre', 'apellido'], 'required'],
            [['nombre', 'apellido'], 'string', 'max' => 100],
            [['telefono'], 'string', 'max' => 20],
            [['correo_electronico'], 'string', 'max' => 100],
            [['correo_electronico'], 'email'],
            [['correo_electronico'], 'unique'],
            [['direccion'], 'string', 'max' => 255],
            [['telefono', 'correo_electronico', 'direccion'], 'default', 'value' => null],
        ];     
    }      

    /**      
    * {@inheritdoc}      
    */     
    public function attributeLabels()     
    {         
        return [             
            'id_cliente' => 'ID',             
            'nombre' => 'Nombre',             
            'apellido' => 'Apellido',             
            'telefono' => 'Teléfono',             
            'correo_electronico' => 'Correo Electrónico',             
            'direccion' => 'Dirección',         
        ];     
    }  
}