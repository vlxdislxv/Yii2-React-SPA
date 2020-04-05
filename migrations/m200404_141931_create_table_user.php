<?php

use yii\db\Migration;

/**
 * Class m200404_141931_create_table_user
 */
class m200404_141931_create_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'authKey' => $this->string()
        ]);

        $this->createIndex('idx-username', 'user', 'username',TRUE);
        $this->createIndex('idx-email', 'user', 'email',TRUE);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
