<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permission Table Names
    |--------------------------------------------------------------------------
    |
    | When using the Spatie Permission package, you can customize the names of
    | the tables used to store permissions, roles, and related models.
    |
    */
    // added
    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => Spatie\Permission\Models\Role::class,

    ],
    // added

    'table_names' => [
        'permissions' => 'permissions',
        'roles' => 'roles',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Column Names
    |--------------------------------------------------------------------------
    |
    | Here you can specify the names of the columns used to store permissions
    | and roles in your database tables. You can customize these column names
    | according to your database schema and naming conventions.
    |
    */

    'column_names' => [
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    /*
    |--------------------------------------------------------------------------
    | Team Support
    |--------------------------------------------------------------------------
    |
    | If your application supports teams or groups, you can enable team support
    | here. This allows permissions and roles to be assigned to specific teams.
    |
    */

    'teams' => false,

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | You can configure caching settings for the Spatie Permission package here.
    | By default, caching is disabled. You can enable caching and specify the
    | cache store and cache key to use for caching permissions and roles.
    |
    */

    'cache' => [
        'store' => 'default',
        'key' => 'spatie.permission.cache',
    ],

];
