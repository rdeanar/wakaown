<?php

use yii\db\Migration;

/**
 * Handles the creation for table `duration`.
 */
class m160804_170847_create_duration_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%duration}}', [
            'entity'       => $this->string()->notNull(),
            'time'         => $this->integer()->notNull(),
            'duration'     => $this->float()->notNull(),
            'user_id'      => $this->integer(),
            'branch'       => $this->string(),
            'dependencies' => $this->string(),
            'is_debugging' => $this->smallInteger()->defaultValue(0),
            'language'     => $this->string(),
            'project'      => $this->string(),
            'type'         => $this->string(),
            'PRIMARY KEY (entity, time)',
        ], $tableOptions);

        $this->createIndex('time_project_branch', '{{%duration}}', ['time', 'project', 'branch']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%duration}}');
    }
}
