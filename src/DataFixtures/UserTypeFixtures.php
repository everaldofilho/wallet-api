<?php

namespace App\DataFixtures;

use App\Entity\UserType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserTypeFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
        $this->create($manager, UserType::TYPE_COMMUN, 'Pessoa Fisica');
        $this->create($manager, UserType::TYPE_COMPANY, 'Pessoa Juridica');
       
    }

    private function create(ObjectManager $manager, $id, $description)
    {
        $userType = (new UserType())
            ->setId($id)
            ->setDescription($description);

        $manager->persist($userType);
        $manager->flush();

        $this->setReference("user_type_$id", $userType);
    }
}
