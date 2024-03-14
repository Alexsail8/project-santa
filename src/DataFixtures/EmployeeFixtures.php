<?php
namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmployeeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('ru_RU');

        for ($i = 0; $i < 10; $i++) {
            $employee = new Employee();
            $employee->setFirstName($faker->lastName); // фамилия
            $employee->setName($faker->firstName); // имя
            $employee->setLastName($faker->lastName . 'ович'); // отчество
            $employee->setEmail($faker->email);
            $employee->setCreatedAt(new \DateTime());

            $manager->persist($employee);
        }

        $manager->flush();
    }
}

