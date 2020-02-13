<?php

use yii\db\Migration;

/**
 * Class m200213_063536_add_task_location_two_column
 */
class m200213_063536_add_task_location_two_column extends Migration
{
    public function up()
    {
        $this->addColumn('task', 'latitude', $this->char(255)->defaultValue(55.009316));
        $this->addColumn('task', 'longitude', $this->char(255)->defaultValue(82.670662));
        $this->dropColumn('task', 'location');
    }

    public function down()
    {
        $this->dropColumn('task', 'latitude');
        $this->dropColumn('task', 'longitude');
        $this->addColumn('task', 'location', $this->char(255));
    }
}
