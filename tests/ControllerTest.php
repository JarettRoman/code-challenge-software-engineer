<?php

namespace SoftwareChallenge\Mid\Tests;

use JsonException;
use PHPUnit\Framework\TestCase;
use SoftwareChallenge\Mid\Controller;
use SoftwareChallenge\Mid\Provider;
use SoftwareChallenge\Mid\User;
use Exception;

class ControllerTest extends TestCase
{
    private function getMockProvider(): Provider
    {
        return $this->createMock(Provider::class);
    }

    public function testNewUserCreationForValidRequest(): void
    {
        $providerMock = $this->getMockProvider();
        $providerMock->method('saveUser')->willReturn(true);

        $user = (new Controller($providerMock))->createUser('{"id":1,"email":"a"}');

        $this->assertInstanceOf(User::class, $user);
    }

    public function testNewUserCreationForInValidRequestBody(): void
    {
        $this->expectException(JsonException::class);

        $mockProvider = $this->getMockProvider();

        (new Controller($mockProvider))->createUser('invalid-json');
    }

    public function testRecordDonationAttempt(): void
    {
        $mockProvider = $this->getMockProvider();

        $testUser = new User('test123', 'test@example.com');

        $mockProvider->expects($this->once())
            ->method('getUser')
            ->with('test123')
            ->willReturn($testUser);

        $mockProvider->expects($this->once())
            ->method('saveUser')
            ->with($this->callback(function ($user) {
                return $user instanceof User &&
                    $user->getId() === 'test123' &&
                    count($user->getDonationAttempts()) === 1;
            }));

        $controller = new Controller($mockProvider);

        $result = $controller->recordDonationAttempt('test123');

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('test123', $result->getId());
        $this->assertCount(1, $result->getDonationAttempts());

        $donationAttempts = $result->getDonationAttempts();
        $this->assertEqualsWithDelta(time() * 1000, $donationAttempts[0], 1000);
    }

    public function testRecordDonationAttemptWithNullUserId(): void
    {
        $mockProvider = $this->getMockProvider();
        $controller = new Controller($mockProvider);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User ID cannot be null');

        $controller->recordDonationAttempt(null);
    }

    public function testRecordDonationAttemptWithNonExistentUser(): void
    {
        $mockProvider = $this->getMockProvider();
        $mockProvider->method('getUser')->willReturn(null);

        $controller = new Controller($mockProvider);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User not found');

        $controller->recordDonationAttempt('nonexistent');
    }
}
