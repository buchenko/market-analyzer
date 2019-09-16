<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m190915_214059_add_request_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'request', $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'request');
    }
}
