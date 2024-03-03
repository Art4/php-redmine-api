<?php

declare(strict_types=1);

namespace Redmine\Tests\Behat\Bootstrap;

trait RoleContextTrait
{
    /**
     * @Given I have a role with the name :name
     */
    public function iHaveARoleWithTheName($name)
    {
        // support for creating issue status via REST API is missing
        $this->redmine->excecuteDatabaseQuery(
            'INSERT INTO roles(name, position, assignable, builtin, issues_visibility, users_visibility, time_entries_visibility, all_roles_managed, settings) VALUES(:name, :position, :assignable, :builtin, :issues_visibility, :users_visibility, :time_entries_visibility, :all_roles_managed, :settings);',
            [],
            [
                ':name' => $name,
                ':position' => 1,
                ':assignable' => 0,
                ':builtin' => 0,
                ':issues_visibility' => 'default',
                ':users_visibility' => 'all',
                ':time_entries_visibility' => 'all',
                ':all_roles_managed' => 1,
                ':settings' => '',
            ],
        );
    }
}
