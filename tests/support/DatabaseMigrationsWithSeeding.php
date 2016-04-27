<?php

/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 27/04/16
 * Time: 10:17
 */

trait DatabaseMigrationsWithSeeding
{
    /**
     * @before
     */
    public function runDatabaseMigrations()
    {
        $this->artisan('migrate');
        $this->artisan('db:seed');

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }
}