<?php

/**
 *
 * Pour installer le bundle de fixture ET faker
 * composer require orm-fixtures --dev
 * composer require fakerphp/faker
 *
 * Pour exécuter ces fixtures :
 * php bin/console doctrine:fixtures:load
 *
 */

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Cities;
use App\Entity\Event;
use App\Entity\EventStatus;
use App\Entity\Location;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    //----------- PASSWORD / HASHAGE ------------
    //on passe le hashage de mot de passe dans le contructeur
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    //cette méthode est appelée quand on exécute la commande php bin/console d:f:l
    //on reçoit un entitymanager en argument \o/
    public function load(ObjectManager $manager){

        //-------------INSERTION DE DONNEES DANS LES TABLES : ---------------
        //-------------- STATUS, LOCATION, CITIES, CAMPUS -------------------
        //charge un fichier sql et exécute les requêtes qui s'y trouvent
        $sql = file_get_contents(__DIR__ . '/insert.sql');
        $stmt = $manager->getConnection()->prepare($sql);
        $stmt->execute();
        //nécessaire sinon ça pète une erreur
        $stmt->closeCursor();


        //--------------FAKER--------------
        //nous permet de générer des données bidons
        //voir ici pour tout ce qu'on peut générer :
        //https://fakerphp.github.io/formatters/numbers-and-strings/
        $faker = \Faker\Factory::create("fr_FR");


        //-----------TABLE USER------------
        $allCampus = $manager->getRepository(Campus::class)->findAll();

        //on crée un admin
        $user = new User();
        $user->setPseudo($faker->userName);
        $user->setFirstName($faker->firstName());
        $user->setLastName($faker->firstName());
        $user->setPhone('0667676767');
        $user->setEmail('admin@admin.com');
        // encodePassword(1er argument = $user, 2eme argument = le mot de passe)
        $user->setPassword($this->encoder->encodePassword($user, 'admin'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCampus($faker->randomElement($allCampus));
        $user->setPhoto("https://picsum.photos/seed/" . $user->getUsername() . "/400/400");
        //$user->setPhoto($faker->imageUrl(640, 480, 'animals', true));

        $manager->persist($user);

        //on crée un user normal fixe
        for($i = 0; $i < 15; $i++) {
            $user = new User();
            $user->setPseudo($faker->userName.$i);
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->firstName());
            $user->setPhone('0606060606');
            $user->setEmail($faker->email);
            // encodePassword(1er argument = $user, 2eme argument = le mot de passe)
            $user->setPassword($this->encoder->encodePassword($user, 'user'));
            $user->setRoles(['ROLE_USER']);
            $user->setCampus($faker->randomElement($allCampus));

            //($faker->randomElement(['Saint-Herblain','Nantes','Orvault','Rezé','Paris','Lyon','Pau','Montreal','Strasbourg','Londres','Mexico']));


            $user->setPhoto("https://picsum.photos/seed/" . $user->getUsername() . "/400/400");
            //$user->setPhoto($faker->imageUrl(640, 480, 'animals', true));

            $manager->persist($user);
        }


        //on crée des organisateurs
        for($i = 0; $i < 15; $i++) {
            $user = new User();
            $user->setPseudo($faker->userName.$i);
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->firstName());
            $user->setPhone('0606060606');
            $user->setEmail($faker->email);
            // encodePassword(1er argument = $user, 2eme argument = le mot de passe)
            $user->setPassword($this->encoder->encodePassword($user, 'orga'));
            $user->setRoles(['ROLE_ORGANISATEUR']);
            $user->setCampus($faker->randomElement($allCampus));
            $user->setPhoto("https://picsum.photos/seed/" . $user->getUsername() . "/400/400");
            //$user->setPhoto($faker->imageUrl(640, 480, 'animals', true));

            $manager->persist($user);
        }

        //on sauvegarde en bdd tout de suite
        $manager->flush();

        //-----------TABLE EVENT------------
        //on récupère tous les users, les status, les campus pour pouvoir les associer en tant qu'organisateur d'événement ci-dessous
        //$allCampus = $manager->getRepository(Campus::class)->findAll();
        $allCities = $manager->getRepository(Cities::class)->findAll();
        $allStatus = $manager->getRepository(EventStatus::class)->findAll();
        $allUsers = $manager->getRepository(User::class)->findAll();
        $allLocation = $manager->getRepository(Location::class)->findAll();



        //plein de création d'événements
        for($i = 0; $i < 100; $i++) {

            $event = new Event();

            //campus_id => un campus au hasard pour l'utilisateur
            $event->setCampus($faker->randomElement($allCampus));
            //city_ID
            $event->setCity($faker->randomElement($allCities));
            //status_ID
            $event->setStatus($faker->randomElement($allStatus));
            //organizer_ID => un utilisateur au hasard en tant qu'organisateur
            $event->setOrganizer($faker->randomElement($allUsers));
            //location_ID
            $event->setLocation($faker->randomElement($allLocation));

            $event->setEventName($faker->sentence(5));
            $event->setDateAndHour($faker->dateTimeBetween('- 6 months', 'now') );
            $event->setRegistrationLimit($faker->dateTimeBetween('- 6 months', '+ 6 months'));
            $event->setInitialPlaces(NumberOfPlaces);
            $event->setNumberOfPlaces(mt_rand(15, 100));
            $event->setDuration(mt_rand(60, 600));
            $event->setDescription($faker->realText(100));
            //nb_registration
            $event->setNbRegistration(mt_rand(1, 15));

            //on sauvegarde dans la boucle
            $manager->persist($event);
        }

        //et on flush après la boucle (plus rapide)
        $manager->flush();

    }
}

