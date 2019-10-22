<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager, 1, 3, 'ROLE_ADMIN');
        $this->loadUsers($manager, 4, 6, 'ROLE_MOD');
        $this->loadUsers($manager, 7, 9, 'ROLE_CUSTODIAN');
    }

    private function loadUsers(ObjectManager $manager, $min, $max, $userRole)
    {
        for ($i = $min; $i <= $max; $i++) {
            $user = new User();
            $user->setEmail("lector$i@pxl.be");
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                "secret"
            ));
            $userRoles = array($userRole);
            $user->setRoles($userRoles) ;
            $manager->persist($user);
        }

        $manager->flush();
    }
}
