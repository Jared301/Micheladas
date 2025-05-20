<?php  
namespace app\models;  

use Yii;  

/**  
* This is the model class for table "Metodos_de_Pago".  
*  
* @property int $id_metodo_pago  
* @property string $tipo  
* @property string|null $detalles  
*/ 
class Metodospago extends \yii\db\ActiveRecord {       
    /**      
    * {@inheritdoc}      
    */     
    public static function tableName()     
    {         
        return 'Metodos_de_Pago';     
    }      
    
    /**
    * Para que el ID sea accesible como 'id' manteniendo compatibilidad
    */
    public function getId()
    {
        return $this->id_metodo_pago;
    }
    
    /**      
    * {@inheritdoc}      
    */     
    public function rules()     
    {         
        return [             
            [['tipo'], 'required'],
            [['tipo'], 'string', 'max' => 50],             
            [['detalles'], 'default', 'value' => null],             
            [['detalles'], 'string', 'max' => 255],         
        ];     
    }      

    /**      
    * {@inheritdoc}      
    */     
    public function attributeLabels()     
    {         
        return [             
            'id_metodo_pago' => 'ID',             
            'tipo' => 'Tipo',             
            'detalles' => 'Detalles',         
        ];     
    }  
}