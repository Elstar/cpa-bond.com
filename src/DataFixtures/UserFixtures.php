<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 1, function (User $user) {
            $user
                ->setFirstName('Sergiy')
                ->setBalance(0)
                ->setTelegram('@SergiyDmb')
                ->setActivate(1)
                ->setEmail('sergiy.dmb@gmail.com')
                ->setPassword($this->passwordHasher->hashPassword($user,'111111'))
                ->setPayOutAccess(0)
                ->setApiToken(md5(uniqid('token_sergiy.dmb@gmail.com' . mt_rand(), true)))
            ;
        });

        $manager->flush();
    }
}
