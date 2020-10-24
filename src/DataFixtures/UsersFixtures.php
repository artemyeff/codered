<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    private UserRepository $repository;

    private const PASSWORD = 'admin';

    /**
     * UsersFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepository $repository
     */
    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $repository)
    {
        $this->encoder = $encoder;
        $this->repository = $repository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->repository->findBy(['login' => 'admin'])) {
            $admin = new User();
            $admin->setUsername('admin');
            $admin->setFirstName('Админ');
            $admin->setLastName('Тестовый');
            $admin->setPassword(
                $this->encoder->encodePassword($admin, self::PASSWORD)
            );
            $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

            $manager->persist($admin);
            $manager->flush();
        }
    }
}
