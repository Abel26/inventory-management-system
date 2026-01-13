<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    /**
     * Get all roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find role by ID.
     *
     * @param int $id
     * @return \Spatie\Permission\Models\Role|null
     */
    public function find(int $id);

    /**
     * Create new role.
     *
     * @param array $data
     * @return \Spatie\Permission\Models\Role
     */
    public function create(array $data);

    /**
     * Update existing role.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);

    /**
     * Delete role.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Find role by name.
     *
     * @param string $name
     * @return \Spatie\Permission\Models\Role|null
     */
    public function findByName(string $name);

    /**
     * Get role with permissions.
     *
     * @param int $id
     * @return \Spatie\Permission\Models\Role|null
     */
    public function findWithPermissions(int $id);
    
    /**
     * Get permissions grouped by module.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsByModule(): Collection;
}
