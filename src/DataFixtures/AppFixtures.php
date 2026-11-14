<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Entity\Commande;
use App\Entity\Employer;
use App\Entity\Menu;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Données de test : la même chose que dans la base mon_projet_vf.
 * Pour recharger (attention : vide d'abord toutes les tables) :
 *   php bin/console doctrine:fixtures:load
 *
 * Tous les comptes (clients, employés, admin) ont le mot de passe : Password123!
 */
class AppFixtures extends Fixture
{
    public const DEV_PASSWORD = 'Password123!';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // ---------------- Admin et employés (table employer) ----------------
        // email, nom, code postal, téléphone, rôles
        $employesData = [
            ['admin@vite-gourmand.test', 'Admin Principal', '33000', 556000001, ['ROLE_ADMIN']],
            ['employe1@vite-gourmand.test', 'Julie Martin', '33000', 556000002, ['ROLE_EMPLOYER']],
        ];
        foreach ($employesData as [$email, $nom, $postale, $num, $roles]) {
            $manager->persist($this->createEmployer($email, $nom, $postale, $num, $roles));
        }

        // ---------------- Clients (table user), déjà vérifiés ----------------
        // email, nom, code postal, téléphone
        $clientsData = [
            ['client1@vite-gourmand.test', 'Sophie Durand', '33000', 612345601],
            ['client2@vite-gourmand.test', 'Lucas Bernard', '33200', 612345602],
            ['client3@vite-gourmand.test', 'Emma Petit', '33400', 612345603],
        ];
        $clients = [];
        foreach ($clientsData as [$email, $nom, $postale, $num]) {
            $client = $this->createUser($email, $nom, $postale, $num);
            $manager->persist($client);
            $clients[] = $client;
        }

        // ---------------- Menus du traiteur ----------------
        // Thèmes : Noël, Pâques, Classique, Événement. Régimes : Classique, Végétarien, Végan.
        // titre, description, prix par personne, nombre minimum de personnes, image principale,
        // thème, régime, galerie d'images (public/images/), plats : [0] entrée, [1] plat, [2] dessert
        $menuData = [
            [
                'Réveillon de Noël',
                'Le repas de fête par excellence : foie gras maison et pain toasté, chapon rôti aux marrons, puis bûche au chocolat. À commander au moins 7 jours avant.',
                48.0, 6, 'event1-i1.jpg', 'Noël', 'Classique',
                ['event1-i1.jpg', 'event1-i2.jpg', 'event1-i3.jpg'],
                [
                    'Foie gras maison, chutney de figues et pain toasté (allergènes : gluten, lait)',
                    'Chapon rôti aux marrons et légumes d\'hiver (allergènes : fruits à coque, céleri)',
                    'Bûche au chocolat et crème légère (allergènes : gluten, oeufs, lait)',
                ],
            ],
            [
                'Dîner de Réception Végétarien',
                'Pour vos réceptions et anniversaires : tartelette aux tomates cerises, risotto aux champignons et légumes rôtis, moelleux au chocolat.',
                39.0, 10, 'event2-i1.jpg', 'Événement', 'Végétarien',
                ['event2-i1.jpg', 'event2-i2.jpg', 'event2-i3.jpg'],
                [
                    'Tartelette aux tomates cerises, fromage frais et basilic (allergènes : gluten, lait, oeufs)',
                    'Risotto aux champignons, carottes et panais rôtis (allergènes : lait)',
                    'Moelleux au chocolat, fruits rouges et glace vanille (allergènes : gluten, oeufs, lait)',
                ],
            ],
            [
                'Menu Bistrot du Terroir',
                'Un repas de famille généreux : pâté en croûte et salade, bœuf bourguignon et purée maison, tarte fine aux pommes.',
                34.0, 6, 'event3-i1.jpg', 'Classique', 'Classique',
                ['event3-i1.jpg'],
                [
                    'Pâté en croûte maison, salade et cornichons (allergènes : gluten, oeufs, moutarde)',
                    'Bœuf bourguignon, purée maison (allergènes : lait, céleri, sulfites)',
                    'Tarte fine aux pommes et glace vanille (allergènes : gluten, lait)',
                ],
            ],
            [
                'Brunch Gourmand de Pâques',
                'Un brunch complet pour le week-end de Pâques : tartines avocat et oeuf poché, saumon fumé, viennoiseries, jus pressé et fruits frais.',
                26.0, 6, 'brunch-gourmand-dimanche.jpg', 'Pâques', 'Classique',
                ['brunch-gourmand-dimanche.jpg', 'brunch-gourmand-dimanche2.jpg', 'brunch-gourmand-dimanche3.jpg', 'brunch-gourmand-dimanche4.jpg'],
                [
                    'Tartine avocat et oeuf poché, tartine au saumon fumé (allergènes : gluten, oeufs, poisson)',
                    'Panier de croissants et pains au chocolat, jus d\'orange pressé (allergènes : gluten, lait, oeufs)',
                    'Verrine yaourt et fruits rouges, salade de fruits frais (allergènes : lait)',
                ],
            ],
            [
                'Menu Mariage Élégance',
                'Le menu pour le plus beau jour : dos de cabillaud, purée de petits pois et légumes glacés, servi à table dans une décoration raffinée.',
                55.0, 50, 'mariage.jpg', 'Événement', 'Classique',
                ['mariage.jpg', 'mariage2.jpg'],
                [
                    'Velouté de petits pois et crème légère (allergènes : lait)',
                    'Dos de cabillaud, purée de petits pois et carottes glacées (allergènes : poisson, lait)',
                    'Pièce montée et coupe de fruits (allergènes : gluten, oeufs, lait)',
                ],
            ],
            [
                'Assiette Signature',
                'Nos bouchées signature pour vos cocktails et réceptions : tartare de Saint-Jacques, toasts figues et noix, gambas et magret laqué.',
                35.0, 10, 'signature.jpg', 'Événement', 'Classique',
                ['signature.jpg', 'signature2.jpg', 'siganture3.jpg', 'signature4.jpg'],
                [
                    'Tartare de Saint-Jacques et légumes en coquille (allergènes : mollusques)',
                    'Toast aux figues et noix, gambas rôtie, magret laqué (allergènes : gluten, fruits à coque, crustacés)',
                    'Assortiment de mignardises (allergènes : gluten, oeufs, lait)',
                ],
            ],
            [
                'Croustillant de Boeuf',
                'Un menu de chef : croustillant de champignons en feuille de brick, filet de boeuf rosé et son jus, légumes de saison.',
                38.0, 6, 'croustiant.jpg', 'Classique', 'Classique',
                ['croustiant.jpg', 'croustiant2.jpg', 'croustiant3.jpg', 'croustiant4.jpg', 'plats.jpg'],
                [
                    'Croustillant de champignons en feuille de brick (allergènes : gluten)',
                    'Filet de boeuf rosé, jus corsé et légumes de saison (allergènes : céleri, sulfites)',
                    'Cerises rôties et crème vanille (allergènes : lait, oeufs)',
                ],
            ],
            [
                'Ratatouille Provençale',
                'La ratatouille traditionnelle de Provence : légumes du soleil mijotés à l\'huile d\'olive, pommes de terre rôties. 100% végétal.',
                18.0, 4, '0f9629b94fc40556.jpg', 'Classique', 'Végan',
                ['0f9629b94fc40556.jpg', 'b4a4d046114a8b68.jpg', 'be0a1e7c9871c57a.jpg', 'becb9d67be6532f7.jpg', 'fa6ad5e42c391558.jpg'],
                [
                    'Tartinade de légumes grillés et pain de campagne (allergènes : gluten)',
                    'Ratatouille provençale et pommes de terre rôties (allergènes : aucun)',
                    'Sorbet aux fruits rouges (allergènes : aucun)',
                ],
            ],
        ];
        $menus = [];
        foreach ($menuData as [$titre, $description, $prix, $nbr, $image, $theme, $regime, $galerie, $plats]) {
            $menu = (new Menu())
                ->setTitre($titre)
                ->setDescription($description)
                ->setPrix($prix)
                ->setNbrPersonnes($nbr)
                ->setImage($image)
                ->setTheme($theme)
                ->setRegime($regime)
                ->setImages($galerie)
                ->setPlats($plats);
            $manager->persist($menu);
            $menus[] = $menu;
        }

        // ---------------- Commandes ----------------
        // client = numéro dans $clients (0, 1, 2), menu = numéro dans $menus (0 = 1er menu)
        // dates des statuts : null si la commande n'est pas encore arrivée à ce statut
        $commandesData = [
            [
                'client' => 0, 'menu' => 0,
                'date_event' => '2026-10-28', 'heure' => '12:00',
                'adresse' => '10 rue Sainte-Catherine', 'lieu' => 'Bordeaux',
                'nbr_personnes' => 6, 'frais' => 5.0, 'remise' => 0.0, 'total' => 293.0,
                'date_creation' => '2026-10-14', 'statut' => 'en attente',
                'date_en_attente' => '2026-10-14 21:34:42',
                'date_acceptee' => null,
                'date_en_preparation' => null,
                'date_en_cours_de_livraison' => null,
                'date_livree' => null,
                'date_terminee' => null,
            ],
            [
                'client' => 1, 'menu' => 1,
                'date_event' => '2026-11-01', 'heure' => '13:00',
                'adresse' => '17 rue Sainte-Catherine', 'lieu' => 'Mérignac',
                'nbr_personnes' => 16, 'frais' => 8.54, 'remise' => 62.4, 'total' => 570.14,
                'date_creation' => '2026-10-18', 'statut' => 'acceptée',
                'date_en_attente' => '2026-10-18 21:34:42',
                'date_acceptee' => '2026-10-19 03:34:42',
                'date_en_preparation' => null,
                'date_en_cours_de_livraison' => null,
                'date_livree' => null,
                'date_terminee' => null,
            ],
            [
                'client' => 2, 'menu' => 2,
                'date_event' => '2026-11-05', 'heure' => '14:00',
                'adresse' => '24 rue Sainte-Catherine', 'lieu' => 'Pessac',
                'nbr_personnes' => 6, 'frais' => 9.13, 'remise' => 0.0, 'total' => 213.13,
                'date_creation' => '2026-10-22', 'statut' => 'en préparation',
                'date_en_attente' => '2026-10-22 21:34:42',
                'date_acceptee' => '2026-10-23 03:34:42',
                'date_en_preparation' => '2026-10-23 09:34:42',
                'date_en_cours_de_livraison' => null,
                'date_livree' => null,
                'date_terminee' => null,
            ],
            [
                'client' => 0, 'menu' => 3,
                'date_event' => '2026-11-09', 'heure' => '15:00',
                'adresse' => '31 rue Sainte-Catherine', 'lieu' => 'Talence',
                'nbr_personnes' => 12, 'frais' => 9.72, 'remise' => 31.2, 'total' => 290.52,
                'date_creation' => '2026-10-26', 'statut' => 'en cours de livraison',
                'date_en_attente' => '2026-10-26 21:34:42',
                'date_acceptee' => '2026-10-27 03:34:42',
                'date_en_preparation' => '2026-10-27 09:34:42',
                'date_en_cours_de_livraison' => '2026-10-27 15:34:42',
                'date_livree' => null,
                'date_terminee' => null,
            ],
            [
                'client' => 1, 'menu' => 4,
                'date_event' => '2026-11-13', 'heure' => '16:00',
                'adresse' => '38 rue Sainte-Catherine', 'lieu' => 'Bègles',
                'nbr_personnes' => 50, 'frais' => 10.31, 'remise' => 0.0, 'total' => 2760.31,
                'date_creation' => '2026-10-30', 'statut' => 'livrée',
                'date_en_attente' => '2026-10-30 21:34:42',
                'date_acceptee' => '2026-10-31 03:34:42',
                'date_en_preparation' => '2026-10-31 09:34:42',
                'date_en_cours_de_livraison' => '2026-10-31 15:34:42',
                'date_livree' => '2026-10-31 21:34:42',
                'date_terminee' => null,
            ],
            [
                'client' => 2, 'menu' => 5,
                'date_event' => '2026-11-17', 'heure' => '17:00',
                'adresse' => '45 rue Sainte-Catherine', 'lieu' => 'Bordeaux',
                'nbr_personnes' => 16, 'frais' => 5.0, 'remise' => 56.0, 'total' => 509.0,
                'date_creation' => '2026-11-03', 'statut' => 'terminée',
                'date_en_attente' => '2026-11-03 21:34:42',
                'date_acceptee' => '2026-11-04 03:34:42',
                'date_en_preparation' => '2026-11-04 09:34:42',
                'date_en_cours_de_livraison' => '2026-11-04 15:34:42',
                'date_livree' => '2026-11-04 21:34:42',
                'date_terminee' => '2026-11-05 03:34:42',
            ],
        ];
        $commandes = [];
        foreach ($commandesData as $c) {
            $client = $clients[$c['client']];
            $menu = $menus[$c['menu']];

            $commande = (new Commande())
                ->setIdUser($client)
                ->setIdMenu($menu)
                ->setUserNom($client->getNom())
                ->setMenuNom($menu->getTitre())
                ->setDateEvent(new \DateTime($c['date_event']))
                ->setHeure(new \DateTime($c['heure']))
                ->setAdresse($c['adresse'])
                ->setLieu($c['lieu'])
                ->setNbrPersonnes($c['nbr_personnes'])
                ->setFrais($c['frais'])
                ->setRemise($c['remise'])
                ->setTotal($c['total'])
                ->setDateCreation(new \DateTime($c['date_creation']))
                ->setStatut($c['statut'])
                ->setDateEnAttente(new \DateTime($c['date_en_attente']));

            // les autres dates seulement si elles existent
            if ($c['date_acceptee'] !== null) {
                $commande->setDateAcceptee(new \DateTime($c['date_acceptee']));
            }
            if ($c['date_en_preparation'] !== null) {
                $commande->setDateEnPreparation(new \DateTime($c['date_en_preparation']));
            }
            if ($c['date_en_cours_de_livraison'] !== null) {
                $commande->setDateEnCoursDeLivraison(new \DateTime($c['date_en_cours_de_livraison']));
            }
            if ($c['date_livree'] !== null) {
                $commande->setDateLivree(new \DateTime($c['date_livree']));
            }
            if ($c['date_terminee'] !== null) {
                $commande->setDateTerminee(new \DateTime($c['date_terminee']));
            }

            $manager->persist($commande);
            $commandes[] = $commande;
        }

        // ---------------- Avis ----------------
        // commande = numéro dans $commandes, note sur 5, statut : envoyé, en attente, publié, annulé
        $avisData = [
            [4, 5, 'publié', 'Excellent service, tous nos invités ont adoré !'],
            [5, 4, 'publié', 'Très bon repas, livraison ponctuelle.'],
            [3, 3, 'envoyé', 'Bon menu mais un peu de retard à la livraison.'],
        ];
        foreach ($avisData as [$index, $note, $statut, $commentaire]) {
            $commande = $commandes[$index];
            $avis = (new Avis())
                ->setCommande($commande)
                ->setUser($commande->getUser())
                ->setUserNom($commande->getUserNom())
                ->setNote($note)
                ->setStatut($statut)
                ->setCommentaire($commentaire);
            $manager->persist($avis);
        }

        $manager->flush();
    }

    /**
     * @param list<string> $roles
     */
    private function createEmployer(string $email, string $nom, string $postale, int $num, array $roles): Employer
    {
        $employer = new Employer();
        $employer->setEmail($email)
            ->setNom($nom)
            ->setPostale($postale)
            ->setNum($num)
            ->setRoles($roles);
        $employer->setPassword($this->passwordHasher->hashPassword($employer, self::DEV_PASSWORD));

        return $employer;
    }

    private function createUser(string $email, string $nom, string $postale, int $num): User
    {
        $user = new User();
        $user->setEmail($email)
            ->setNom($nom)
            ->setPostale($postale)
            ->setNum($num)
            ->setRoles(['ROLE_USER'])
            ->setIsVerified(true);
        $user->setPassword($this->passwordHasher->hashPassword($user, self::DEV_PASSWORD));

        return $user;
    }
}
