<?php

namespace App\Twig;

use App\Repository\UserRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_user_id_by_role', [$this, 'getUserIdByRole']),
        ];
    }

    public function getUserIdByRole(string $role): ?int
    {
        $user = $this->userRepository->findOneByRole($role);
        return $user ? $user->getId() : null;
    }
}
