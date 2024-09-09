<?php

namespace SoftwareChallenge\Mid;

use JsonSerializable;

class User implements JsonSerializable
{
    public function __construct(
        private readonly string $id,
        private readonly string $email,
        private array $donationAttempts = []
    ) {
        $this->donationAttempts = $donationAttempts;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDonationAttempts(): array
    {
        return $this->donationAttempts;
    }

    public function addDonationAttempt(int $timestamp): void
    {
        $this->donationAttempts[] = $timestamp;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'donationAttempts' => $this->getDonationAttempts()
        ];
    }
}