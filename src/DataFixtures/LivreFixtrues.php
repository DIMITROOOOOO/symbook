<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LivreFixtrues extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create('fr_FR');
        for ($i=1; $i<100 ; $i++) { 
            $Livre = new Livre();
            $Livre->setTitre($faker->name());
            $Livre->setIsbn($faker->isbn13());
            $Livre->setSlug(strtolower(str_replace(' ', '-', $Livre->getTitre())));
            $Livre->setResume($faker->text);
            $Livre->setEditeur($faker->company());
            $Livre->setPrix($faker->randomFloat(2, 10, 700));
            $Livre->setDateEdition($faker->dateTimeBetween('-5 years', 'now'));
            $Livre->setImage('https://picsum.photos/images/300/?id='.$i);
            
            $manager->persist($Livre);
        }
                 $manager->flush();
   
    }
}
