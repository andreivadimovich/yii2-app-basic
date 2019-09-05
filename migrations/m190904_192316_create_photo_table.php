<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%photo}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m190904_192316_create_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%photo}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'body' => $this->text(),
            'user_id' => $this->integer()->notNull(),
            'img_path' => $this->string(),
            'img_hash' => $this->string(32),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-photo-user_id}}',
            '{{%photo}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-photo-user_id}}',
            '{{%photo}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-photo-user_id}}',
            '{{%photo}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-photo-user_id}}',
            '{{%photo}}'
        );

        $this->dropTable('{{%photo}}');
    }
}
