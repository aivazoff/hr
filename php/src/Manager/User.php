<?php

namespace App\Manager;

use App\Exception\EntityManagerException;
use App\ORM\EntityManager;
use \App\Repository\UserRepository;
use \App\Entity;
use Exception;


class User
{
    private readonly EntityManager $em;
    private readonly UserRepository $repo;

    public function __construct()
    {
        $this->em = new EntityManager();
        $this->repo = new UserRepository($this->em);
    }

    public function getUser(int $id): Entity\User {
        return $this->repo->find($id);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->em;
    }

    /**
     * @return UserRepository
     */
    public function getRepository(): UserRepository
    {
        return $this->repo;
    }

    /**
     * Возвращает пользователей старше заданного возраста.
     *
     * @return Entity\User[]
     */
    function getUsers(int $ageFrom, int $limit = null): array {
        return $this->repo->getUsers($ageFrom, $limit);
    }

    /**
     * Возвращает пользователей по списку имен.
     * @return Entity\User[]
     */
    public function getByNames($names): array
    {
        return $this->repo->getUsersByNames($names);
    }

    /**
     * Добавляет пользователей в базу данных.
     * @return int[]
     * @throws EntityManagerException
     * @throws Exception
     */
    public function addUsers(Entity\User ...$users): array
    {
        $em = $this->repo->getEntityManager();
        array_walk($users, fn($user) => $em->persist($user));
        $em->flush();
        return array_map(fn($user) => $user->getId(), $users);
    }
}