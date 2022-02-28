<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\AssociationProvider;
use App\DataFixtures\Provider\SpeciesProvider;
use App\Entity\AssoSpecies;
use App\Entity\Species;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory as Faker;
use Faker\Provider\Address;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\MySlugger;

class AppFixtures extends Fixture
{
    private $connexion;
    private $sluggifier;
    private $slugger;

    public function __construct(Connection $connexion, MySlugger $mySlugger)
    {
        $this->connexion = $connexion;
        $this->slugger = $mySlugger;
    }

    private function truncate()
    {
        // On désactive la vérification des FK
        // Sinon les truncate ne fonctionne pas.
        $this->connexion->executeQuery('SET foreign_key_checks = 0');

        // La requete TRUNCATE remet l'auto increment à 1
        $this->connexion->executeQuery('TRUNCATE TABLE species');
        $this->connexion->executeQuery('TRUNCATE TABLE user');

    }
    public function load(ObjectManager $manager): void
    {
        $this->truncate();

        $faker = Faker::create('fr_FR');

        $associationProvider = new AssociationProvider();
        $speciesProvider = new SpeciesProvider();

    /* ============== USER ==============  */

    $allUserEntity = [];
    
    for ($i = 1; $i<= 25; $i++)
    {
        $user = new User;

        $type = rand (1,2) == 1 ? 'Association' : 'Particular';

        $user->setType($type);

        if ( $type === "Association"){
            $user->setName($associationProvider->randAssociation());
            $user->setSiret($faker->siret());
            $user->setAdress($faker->streetAddress());
            $user->setZipcode(Address::postcode());
            $user->setCity($faker->city());
            $user->setRegion($faker->region());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setDescription($faker->text());
            $user->setPicture($faker->imageUrl('cats'));
            $user->setWebsite($faker->url());

            $slug = $this->slugger->slugify($user->getName());
            $user->setSlug($slug);
        }

        if ( $type === "Particular") {
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
        }

        $user->setDepartment($faker->departmentName());
        $user->setMail($faker->safeEmail());
        $user->setPassword('password');

        $status = rand (1,2) == 1 ? 'true' : 'false';

        $user->setStatus($status);
        $user->setRole('ROLE_USER');

        $allUserEntity[] = $user;

        $manager->persist($user);
       
    }

    /* ============== SPECIES ==============  */
    $allSpeciesEntity = [];
    // Tableau des espèces
    $species = [
        'Chat',
        'Chien',
        'Lapin',
        'Cheval',
        'Rongeur',
        'Serpent',
    ];

    foreach($species as $espèce)
    {
        $newSpecies = new Species;
        $newSpecies->setName($espèce);

        $allSpeciesEntity[] = $newSpecies;
        $manager->persist($newSpecies);
    }
    
    /* ============== ASSO_SPECIES ==============  */

    for ($i=0; $i < 50; $i++) {
    
    $assoSpecies = new AssoSpecies;

    $randomUser = $allUserEntity[mt_rand(0, count($allUserEntity) - 1)];
    $assoSpecies->setUser($randomUser);

    $randomSpecies = $allSpeciesEntity[mt_rand(0, count($allSpeciesEntity) - 1)];
    $assoSpecies->setSpecies($randomSpecies);
    
    $manager->persist($assoSpecies);
    }

    $manager->flush();
    }
}