<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%business_data}}`.
 */
class m250108_083417_create_business_data_google_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%business_data_google}}', [
            'id' => $this->primaryKey(),
            'position' => $this->integer(),
            'title' => $this->string(),
            'address' => $this->string(),
            'description' => $this->string(),
            'category' => $this->string(),
            'rating' => $this->string(),
            'place_id' => $this->string(),
            'latitude' => $this->float(),
            'longitude' => $this->float(),
            'country_code' => $this->string(),
            'main_image' => $this->string(),
            'check_store_place_id' => $this->string(),
            'time_update' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%business_data_google}}');
    }
}
