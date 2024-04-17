<?php
/**
 * Created by PhpStorm.
 * Date: 07.04.2024
 * Time: 11:18
 */

namespace App\View\Request\Admin\UserControl;

use App\View\Request\FormRequestInterface;
use JMS\Serializer\Annotation as JMS;

class ChangeUserRequest implements FormRequestInterface
{
    #[JMS\SerializedName('userId')]
    private null|string $userId = null;

    private string $role;

    private string $email;

    #[JMS\SerializedName('firstName')]
    private string $firstName;

    #[JMS\SerializedName('lastName')]
    private string $lastName;

    #[JMS\Type('array<string>')]
    #[JMS\SerializedName('accessList')]
    private array $accessList;

    #[JMS\Type('array<string>')]
    #[JMS\SerializedName('locationAccessList')]
    private array $locationAccessList;

    private string $password;

    public function getUserId(): null|string
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAccessList(): array
    {
        return $this->accessList;
    }

    public function getLocationAccessList(): array
    {
        return $this->locationAccessList;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
