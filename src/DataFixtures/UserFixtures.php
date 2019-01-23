<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Instancy Admin
        $user = new User();
        $user->setEmail('admin@admin.fr');
        $user->setFirstName('Pierre');
        $user->setLastName('Grimaud');
        $password = $this->encoder->encodePassword($user, 'admin');
        $user->setPassword($password);
        $user->setRoles([
            'ROLE_ADMIN'
        ]);

        $manager->persist($user);

        // Instancy User
        $user = new User();
        $user->setEmail('user@user.fr');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $password = $this->encoder->encodePassword($user, 'user');
        $user->setPassword($password);
        $user->setRoles([
            'ROLE_USER'
        ]);

        $manager->persist($user);

        $manager->flush();
    }
}
