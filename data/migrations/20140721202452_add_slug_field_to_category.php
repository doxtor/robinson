<?php

use Phinx\Migration\AbstractMigration;

class AddSlugFieldToCategory extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $sql = <<<HEREDOC
ALTER TABLE `categories`
  ADD COLUMN `slug` VARCHAR(32) NOT NULL AFTER `status`;
HEREDOC;
        $this->query($sql);

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->query('ALTER TABLE `categories` DROP COLUMN `slug`');
    }
}