<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m200213_063536_add_task_location_two_column
 */
class m200213_063536_add_task_location_two_column extends Migration
{
    public function up()
    {
        $this->addColumn('task', 'latitude', $this->decimal(10, 7));
        $this->addColumn('task', 'longitude', $this->decimal(10, 7));
        $this->db->createCommand()->update('task', [
            'latitude' => new Expression("TRIM(SUBSTR(location, LOCATE(' ', location), LENGTH(location)))"),
            'longitude' => new Expression("TRIM(SUBSTR(location, 1, LOCATE(' ', location)))"),
        ])->execute();
        $this->dropColumn('task', 'location');
    }

    public function down()
    {
        $this->addColumn('task', 'location', $this->char(255));
        $this->db->createCommand()->update('task', [
            'location' => new Expression('concat(`longitude`, " ", `latitude`)'),
        ])->execute();
        $this->dropColumn('task', 'latitude');
        $this->dropColumn('task', 'longitude');
    }
}
