<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getActiveUsers();
    public function getInactiveUsers();
    public function search($query);
    public function count();
    public function countActive();
    public function countInactive();
    public function getUsersWithRoles();
    public function findWithRoles($id);
    public function findByUsername($username);
}