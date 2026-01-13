<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Support\Facades\Log;

class RoleService
{
    /**
     * Role Repository instance.
     */
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get all roles.
     */
    public function getAll()
    {
        return $this->roleRepository->all();
    }

    /**
     * Find role by ID.
     */
    public function find(int $id)
    {
        return $this->roleRepository->find($id);
    }

    /**
     * Create new role.
     */
    public function create(array $data)
    {
        return $this->roleRepository->create($data);
    }

    /**
     * Update existing role.
     */
    public function update(int $id, array $data)
    {
        return $this->roleRepository->update($id, $data);
    }

    /**
     * Delete role.
     */
    public function delete(int $id)
    {
        return $this->roleRepository->delete($id);
    }

    /**
     * Find role by name.
     */
    public function findByName(string $name)
    {
        return $this->roleRepository->findByName($name);
    }

    /**
     * Get role with permissions.
     */
    public function findWithPermissions(int $id)
    {
        return $this->roleRepository->findWithPermissions($id);
    }
    
    /**
     * Get permissions grouped by module.
     */
    public function getPermissionsByModule()
    {
        return $this->roleRepository->getPermissionsByModule();
    }
}
