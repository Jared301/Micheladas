<?php  
namespace app\models;  

use Yii;  

/**  
* This is the model class for table "proveedores".  
*  
* @property int $id_proveedor  
* @property string $nombre_empresa  
* @property string|null $contacto  
* @property string|null $telefono  
* @property string|null $correo_electronico  
* @property string|null $direccion  
*/ 
class Proveedores extends \yii\db\ActiveRecord {       
    /**      
    * {@inheritdoc}      
    */     
    public static function tableName()     
    {         
        return 'proveedores';     
    }      
    
    /**
    * Para que el ID sea accesible como 'id' manteniendo compatibilidad
    */
    public function getId()
    {
        return $this->id_proveedor;
    }
    
    /**      
    * {@inheritdoc}      
    */     
    public function rules()     
    {         
        return [             
            [['nombre_empresa'], 'required'],             
            [['direccion'], 'string'],             
            [['nombre_empresa'], 'string', 'max' => 150],             
            [['contacto'], 'string', 'max' => 100],             
            [['telefono'], 'string', 'max' => 20],             
            [['correo_electronico'], 'string', 'max' => 100],
            [['correo_electronico'], 'email'],
            [['correo_electronico'], 'unique'],         
        ];     
    }      

    /**      
    * {@inheritdoc}      
    */     
    public function attributeLabels()     
    {         
        return [             
            'id_proveedor' => 'ID',             
            'nombre_empresa' => 'Nombre de Empresa',             
            'contacto' => 'Contacto',             
            'telefono' => 'Teléfono',             
            'correo_electronico' => 'Correo Electrónico',             
            'direccion' => 'Dirección',         
        ];     
    }  
}