<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\AnimalProvider;
use App\DataFixtures\Provider\AssociationProvider;
use App\DataFixtures\Provider\RegionProvider;
use App\DataFixtures\Provider\SpeciesProvider;
use App\Entity\Animal;
use App\Entity\Species;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory as Faker;
use Faker\Provider\Address;
use App\Service\MySlugger;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $connexion;
    private $hasher;
    private $slugger;

    public function __construct(Connection $connexion, UserPasswordHasherInterface $hasher, MySlugger $mySlugger)
    {
        $this->connexion = $connexion;
        $this->hasher = $hasher;
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
        $this->connexion->executeQuery('TRUNCATE TABLE animal');

    }
    public function load(ObjectManager $manager): void
    {
        $this->truncate();

        $faker = Faker::create('fr_FR');

        $associationProvider = new AssociationProvider();
        $speciesProvider = new SpeciesProvider();
        $regionProvider = new RegionProvider();
        $animalProvider = new AnimalProvider();

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

    /* ============== USER ==============  */

    $allUserEntity = [];
    $allAssociationsEntity = [];
    $allParticularEntity = [];
    
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
            $user->setRegion($regionProvider->randRegion());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setDescription($faker->text());
            $user->setPicture(' https://placekitten.com/500/' . mt_rand(500, 600));

            $slug = $this->slugger->slugify($user->getName());
            $user->setSlug($slug);

            $user->setMail($user->getSlug() . '@exemple.com');
            $user->setWebsite('https:://fake-' . $user->getSlug() . '.com');
            for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                $randomSpecies = $allSpeciesEntity[mt_rand(0, count($allSpeciesEntity) - 1)];
                $user->addSpecies($randomSpecies);
            }

            $user->setRoles(['ROLE_ASSO']);
        
            $allAssociationsEntity[] = $user;
            }

        if ( $type === "Particular") {
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setMail($user->getFirstname() . $user->getLastname() . '@exemple.com');
            $allParticularEntity[] = $user;
        }

        $user->setDepartment($faker->departmentName());

        
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);

        $status = rand (1,2) == 1 ? 'true' : 'false';

        $user->setStatus($status);
        $user->setRoles(['ROLE_USER']);

        $allUserEntity[] = $user;

        $manager->persist($user);
    }
    
    
    /* ============== ANIMALS ==============  */
    $allAnimalsEntity = [];
    for($i = 0; $i < 100; $i ++) {
            
    $animal = new Animal;

    $animal->setName($animalProvider->randAnimal());

    $sexe = rand (1,2) == 1 ? 'Female' : 'male';
    $animal->setSexe($sexe);

    $animal->setDescription($faker->text());

    $status = rand (1,3);
    switch ($status) {
        case 1:
            "junior";
            break;
        case 2:
            "adulte";
            break;
        case 3:
            "senior";
            break;
    }
    $animal->setStatus($status);

    $randomUser = $allAssociationsEntity[mt_rand(0, count($allAssociationsEntity) - 1)];
    $animal->setUser($randomUser);

    $randomSpecies = $allSpeciesEntity[mt_rand(0, count($allSpeciesEntity) - 1)];
    $animal->setSpecies($randomSpecies);

    $allAnimalsEntity[] = $animal;
    $manager->persist($animal);
}

$users = [
    [
        'login' => 'admin@admin.com',
        'password' => 'admin',
        'roles' => 'ROLE_ADMIN',
    ]
];

foreach ($users as $currentUser)
{
    $newUser = new User();
    $newUser->setMail($currentUser['login']); 
    $newUser->setRoles([$currentUser['roles']]);

    $hashedPassword = $this->hasher->hashPassword(
        $newUser,
        $currentUser['password']
    );
    $newUser->setPassword($hashedPassword);

    $manager->persist($newUser);
}
    
    /* ============== REVIEWS ==============  */
    /* for($i = 0; $i < 100; $i ++) {
        
    } */

    $manager->flush();
    }
}
