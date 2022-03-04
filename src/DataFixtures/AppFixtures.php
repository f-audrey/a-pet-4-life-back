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
    /* ============== GEOLOCATION ==============  */
    $dataGeolocation = [
        [69001, 'Lyon', 'Rhône','Auvergne-Rhône-Alpes'],
        [39100, 'Dole', 'Jura','Bourgogne-Franche-Comté'],
        [29200, 'Brest', 'Finistère','Bretagne'],
        [45250, 'Briare', 'Loiret','Centre-Val de Loire'],
        [20000, 'Ajaccio', 'Corse-du-Sud','Corse'],
        [20600, 'Bastia', 'Haute-Corse','Corse'],
        [57000, 'Metz', 'Moselle','Grand Est'],
        [62000, 'Arras', 'Pas-de-Calais','Hauts-de-France'],
        [93370, 'Montfermeil', 'Seine-Saint-Denis','Ile-de-France'],
        [14118, 'Caen', 'Calvados','Normandie'],
        [86000, 'Poitiers', 'Vienne','Nouvelle-Aquitaine'],
        [46500, 'Rocamadour', 'Lot','Occitanie'],
        [44190, 'Clisson', 'Loire-Atlantique','Pays de la Loire'],
        [85600, 'Montaigu', 'Vendée','Pays de la Loire'],
        [06000, 'Nice', 'Alpes-Maritimes','Provence-Alpes-Côte d’Azur'],
        [97100, 'Basse-Terre', 'Guadeloupe','Guadeloupe'],
        [97200, 'Fort-de-France', 'Martinique','Martinique'],
        [97300, 'Cayenne', 'Guyane','Guyane'],
        [97400, 'Saint-Denis', 'La Réunion','La Réunion'],
        [97611, 'Mamoudzou', 'Mayotte','Mayotte'],
    ];

    /* ============== USER ==============  */

    $allUserEntity = [];
    $allAssociationsEntity = [];
    $allParticularEntity = [];
    
    for ($i = 1; $i<= 50; $i++)
    {
        $user = new User;

        $type = rand (1,2) == 1 ? 'Association' : 'Particular';

        $user->setType($type);

        $randDataGeolocation = mt_rand(0, count($dataGeolocation) - 1);

        if ( $type === "Association"){
            $user->setName($associationProvider->randAssociation());
            $user->setSiret($faker->siret());
            $user->setAdress($faker->streetAddress());
            $user->setZipcode($dataGeolocation[$randDataGeolocation]['0']);
            $user->setCity($dataGeolocation[$randDataGeolocation]['1']);
            $user->setRegion($dataGeolocation[$randDataGeolocation]['3']);
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setDescription($faker->text());
            $user->setPicture(' https://placekitten.com/500/' . mt_rand(500, 600));

            $slug = $this->slugger->slugify($user->getName());
            $user->setSlug($slug);

            $user->setMail($user->getSlug() . '@exemple.com');
            $user->setWebsite('https:://fake-' . $user->getSlug() . '.com');

            $allSpeciesAssocEntity = [];
            for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                $randomSpecies = $allSpeciesEntity[mt_rand(0, count($allSpeciesEntity) - 1)];
                $user->addSpecies($randomSpecies);
                $allSpeciesAssocEntity[] = $randomSpecies;
            }

                /* ============== ANIMALS ==============  */
                $allAnimalsEntity = [];
                for($a = 1; $a <= mt_rand(1, 10); $a++) {
                        
                $animal = new Animal;

                $animal->setName($animalProvider->randAnimal());

                $sexe = rand (1,2) == 1 ? 'Female' : 'Male';
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
                
                $randomSpecies = $allSpeciesAssocEntity[mt_rand(0, count($allSpeciesAssocEntity) - 1)];
                $animal->setSpecies($randomSpecies);

                $allAnimalsEntity[] = $animal;
                $user->addAnimal($animal);

                $manager->persist($animal);
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

        $user->setDepartment($dataGeolocation[$randDataGeolocation]['2']);

        
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);

        $status = rand (1,2) == 1 ? 'true' : 'false';

        $user->setStatus($status);
        $user->setRoles(['ROLE_USER']);

        $allUserEntity[] = $user;

        $manager->persist($user);
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
    $type = 'Administrateur';
    $newUser->setType($type);
    $newUser->setMail($currentUser['login']); 
    $newUser->setRoles([$currentUser['roles']]);
    $newUser->setDepartment('null');

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
