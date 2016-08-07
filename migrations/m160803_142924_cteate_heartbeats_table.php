<?php

use yii\db\Migration;

class m160803_142924_cteate_heartbeats_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%heartbeat}}', [
            'id'           => $this->string()->notNull(),
            'user_id'      => $this->integer(),
            'branch'       => $this->string(),
            'entity'       => $this->string()->notNull(),
            'is_debugging' => $this->smallInteger()->defaultValue(0),
            'is_write'     => $this->smallInteger()->notNull(),
            'language'     => $this->string(),
            'project'      => $this->string(),
            'time'         => $this->integer()->notNull(),
            'time_micro'   => $this->integer()->notNull(),
            'type'         => $this->string(),
            'PRIMARY KEY (id)',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%heartbeat}}');
    }
}
