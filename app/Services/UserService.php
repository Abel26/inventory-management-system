<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * User Repository instance.
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users.
     */
    public function getAll()
    {
        return $this->userRepository->all();
    }

    /**
     * Find user by ID.
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Create new user.
     */
    public function create(array $data)
    {
        // Generate dummy email if not provided (for database constraints)
        if (!isset($data['email']) || empty($data['email'])) {
            $data['email'] = $data['username'] . '@local';
        }
        
        // Hash password
        $data['password'] = Hash::make($data['password']);
        
        // Handle roles - convert single role to array if needed
        if (isset($data['role']) && !isset($data['roles'])) {
            $roleName = $data['role'];
            $role = \Spatie\Permission\Models\Role::where('name', $roleName)->first();
            if ($role) {
                $data['roles'] = [$role->id];
            }
            unset($data['role']);
        }
        
        return $this->userRepository->create($data);
    }

    /**
     * Update existing user.
     */
    public function update($id, array $data)
    {
        // Generate dummy email if username changes
        if (isset($data['username']) && !isset($data['email'])) {
            $data['email'] = $data['username'] . '@local';
        }
        
        // Hash password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        // Handle roles - convert single role to array if needed
        if (isset($data['role']) && !isset($data['roles'])) {
            $roleName = $data['role'];
            $role = \Spatie\Permission\Models\Role::where('name', $roleName)->first();
            if ($role) {
                $data['roles'] = [$role->id];
            }
            unset($data['role']);
        }
        
        return $this->userRepository->update($id, $data);
    }

    /**
     * Delete user.
     */
    public function delete($id)
    {
        // Prevent self-deletion
        if ($id == Auth::id()) {
            throw new \Exception('Anda tidak dapat menghapus akun sendiri');
        }
        
        return $this->userRepository->delete($id);
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus($id)
    {
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }
        
        // Prevent self-deactivation
        if ($id == Auth::id()) {
            throw new \Exception('Anda tidak dapat menonaktifkan akun sendiri');
        }
        
        $newStatus = !$user->is_active;
        
        return $this->userRepository->update($id, ['is_active' => $newStatus]);
    }

    /**
     * Assign roles to user.
     */
    public function assignRoles($id, array $roles)
    {
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }
        
        return $this->userRepository->update($id, ['roles' => $roles]);
    }

    /**
     * Search users.
     */
    public function search($query)
    {
        return $this->userRepository->search($query);
    }

    /**
     * Get user statistics.
     */
    public function getStatistics()
    {
        return [
            'total' => $this->userRepository->count(),
            'active' => $this->userRepository->countActive(),
            'inactive' => $this->userRepository->countInactive(),
        ];
    }

    /**
     * Get active users.
     */
    public function getActiveUsers()
    {
        return $this->userRepository->getActiveUsers();
    }

    /**
     * Get inactive users.
     */
    public function getInactiveUsers()
    {
        return $this->userRepository->getInactiveUsers();
    }

    /**
     * Find user by username for authentication.
     */
    public function findByUsername($username)
    {
        return $this->userRepository->findByUsername($username);
    }
}