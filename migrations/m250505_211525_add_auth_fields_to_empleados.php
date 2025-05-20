<?php

use yii\db\Migration;

class m250505_211525_add_auth_fields_to_empleados extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('Empleados', 'username',      $this->string(50)->Null()->unique());
        $this->addColumn('Empleados', 'password_hash', $this->string(255)->notNull());
        $this->addColumn('Empleados', 'auth_key',      $this->string(32)->notNull());
        $this->addColumn('Empleados', 'role',          $this->string(20)->notNull()->defaultValue('usuario'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('Empleados', 'username');
        $this->dropColumn('Empleados', 'password_hash');
        $this->dropColumn('Empleados', 'auth_key');
        $this->dropColumn('Empleados', 'role');
    }
    

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250505_211525_add_auth_fields_to_empleados cannot be reverted.\n";

        return false;
    }
    */
}
