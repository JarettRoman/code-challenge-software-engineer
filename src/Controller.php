<?php

namespace SoftwareChallenge\Mid;

use Exception;

class Controller
{
    private Provider $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @throws Exception
     */
    public function getUser(?string $userId): ?User
    {
        if (is_null($userId)) {
            throw new Exception('User ID cannot be null');
        }

        return $this->provider->getUser($userId);
    }

    /**
     * @throws Exception
     */
    public function createUser(string $requestBody): User
    {
        $userData = json_decode($requestBody, associative: true, flags: JSON_THROW_ON_ERROR);

        if (!isset($userData['email'])) {
            throw new Exception('Email is required');
        }

        $users = $this->provider->getUsers();
        $newId = $this->generateNewId($users);

        $user = new User(
            id: $newId,
            email: $userData['email'],
            donationAttempts: []
        );

        $this->provider->saveUser($user);

        return $user;
    }

    private function generateNewId(array $users): string
    {
        if (empty($users)) {
            return '1';
        }
        
        $maxId = max(array_map('intval', array_keys($users)));

        return (string)($maxId + 1);
    }

    /**
     * @throws Exception
     */
    public function recordDonationAttempt(?string $userId): ?User
    {
        if (is_null($userId)) {
            throw new Exception('User ID cannot be null');
        }

        $user = $this->provider->getUser($userId);

        if (!$user) {
            throw new Exception('User not found');
        }

        $user->addDonationAttempt(time() * 1000);

        $this->provider->saveUser($user);

        return $user;
    }
}