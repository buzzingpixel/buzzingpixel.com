<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/** @noinspection AutoloadingIssuesInspection */
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ClassFileName.NoMatch

/**
 * @noinspection PhpIllegalPsrClassPathInspection
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CreateUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('users', [
            'id' => false,
            'primary_key' => ['id'],
        ])
            ->addColumn('id', 'uuid')
            ->addColumn(
                'is_admin',
                'boolean',
                ['default' => 0]
            )
            ->addColumn('email_address', 'string')
            ->addColumn('password_hash', 'string')
            ->addColumn(
                'is_active',
                'boolean',
                ['default' => 0]
            )
            ->addColumn('timezone', 'string', [
                'after' => 'is_active',
                'default' => 'US/Central',
            ])
            ->addColumn(
                'created_at',
                'datetime',
                ['timezone' => true]
            )
            ->create();
    }
}
