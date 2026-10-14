<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    public function partenaires(Request $request)
    {
        return view('partenaires', [
            'title' => 'Nos partenariats',
            'metaDescription' => 'Nos formes de partenariat avec MARINA : entreprise, technique, financier et média.',
            'bodyClass' => 'page-partenaires',
            'type' => $request->query('type'),
        ]);
    }

    public function nosPartenariats(Request $request)
    {
        return $this->partenaires($request);
    }

    public function apropos(Request $request)
    {
        return view('apropos', [
            'title' => 'À propos',
            'metaDescription' => 'À propos de MARINA : entreprise spécialisée en développement IT, communication digitale, immobilier & BTP et sous-traitance internationale.',
            'bodyClass' => 'page-apropos',
        ]);
    }

    public function contact(Request $request)
    {
        return view('contact', [
            'title' => 'Contact',
            'metaDescription' => 'Contactez MARINA : téléphone, email, adresse à Lomé et formulaire. Parlons de votre projet.',
            'bodyClass' => 'page-contact',
        ]);
    }

    public function formations(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $all = collect($this->getFormations())->map(function ($f) {
            $f['prixEnLigne'] = $f['prixEnLigne'] ?? 20000;
            $f['prixPresentiel'] = $f['prixPresentiel'] ?? 25000;
            return $f;
        });

        if ($q !== '') {
            $needle = mb_strtolower($q);
            $all = $all->filter(function ($f) use ($needle) {
                return str_contains(mb_strtolower($f['title']), $needle)
                    || str_contains(mb_strtolower($f['description']), $needle)
                    || str_contains(mb_strtolower($f['category']), $needle);
            })->values();
        }

        $perPage = 8;
        $page = max(1, (int) $request->query('page', 1));
        $total = $all->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = min($page, $lastPage);
        $items = $all->slice(($page - 1) * $perPage, $perPage)->values();

        return view('formations', [
            'formations' => $items,
            'q' => $q,
            'page' => $page,
            'lastPage' => $lastPage,
            'total' => $total,
            'title' => 'Nos formations',
            'metaDescription' => 'Catalogue de formations pratiques et certifiantes : bureautique, développement web, design graphique et outils numériques.',
            'bodyClass' => 'page-formations',
        ]);
    }

    public function offreEmplois(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $categorie = trim((string) $request->query('categorie', ''));
        $lieu = trim((string) $request->query('lieu', ''));
        $contrat = trim((string) $request->query('contrat', ''));

        $all = collect($this->getOffresEmploi())->map(fn ($o) => $this->enrichOffreEmploi($o));

        if ($q !== '') {
            $needle = mb_strtolower($q);
            $all = $all->filter(function ($o) use ($needle) {
                $skills = implode(' ', $o['skills'] ?? []);
                return str_contains(mb_strtolower($o['title']), $needle)
                    || str_contains(mb_strtolower($o['description']), $needle)
                    || str_contains(mb_strtolower($o['categorie']), $needle)
                    || str_contains(mb_strtolower($skills), $needle);
            })->values();
        }

        if ($categorie !== '') {
            $all = $all->where('categorie', $categorie)->values();
        }
        if ($lieu !== '') {
            $all = $all->where('lieu', $lieu)->values();
        }
        if ($contrat !== '') {
            $all = $all->where('contrat', $contrat)->values();
        }

        $perPage = 8;
        $page = max(1, (int) $request->query('page', 1));
        $total = $all->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = min($page, $lastPage);
        $items = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $categories = collect($this->getOffresEmploi())->pluck('categorie')->unique()->sort()->values();
        $lieux = collect($this->getOffresEmploi())->pluck('lieu')->unique()->sort()->values();
        $contrats = collect($this->getOffresEmploi())->pluck('contrat')->unique()->sort()->values();

        return view('offreemplois', [
            'offres' => $items,
            'q' => $q,
            'categorie' => $categorie,
            'lieu' => $lieu,
            'contrat' => $contrat,
            'categories' => $categories,
            'lieux' => $lieux,
            'contrats' => $contrats,
            'page' => $page,
            'lastPage' => $lastPage,
            'total' => $total,
            'title' => "Offres d'emploi",
            'metaDescription' => "Découvrez les offres d'emploi MARINA GRADE PSF : CDI, CDD et opportunités à Lomé et ailleurs.",
            'bodyClass' => 'page-offres',
        ]);
    }

    public function digitalService(Request $request)
    {
        return view('digitalservice', [
            'services' => $this->getDigitalServices(),
            'packs' => $this->getDigitalPacks(),
            'title' => 'Communication Digitale',
            'metaDescription' => 'Solutions digitales MARINA GRADE PSF : stratégie, réseaux sociaux, contenu, publicité, SEO et packs sur mesure.',
            'bodyClass' => 'page-digital',
        ]);
    }

    public function digitalServiceShow(string $slug)
    {
        $services = collect($this->getDigitalServices())->keyBy('slug');
        if (!$services->has($slug)) {
            abort(404);
        }

        $service = $services->get($slug);

        return view('digitalservice-show', [
            'service' => $service,
            'title' => $service['title'],
            'metaDescription' => $service['lead'] ?? $service['description'],
            'bodyClass' => 'page-digital page-digital-detail',
        ]);
    }

    public function conceptionApplication()
    {
        return view('conception-application', [
            'services' => $this->getConceptionServices(),
            'packs' => $this->getConceptionPacks(),
            'title' => "Conception d'application web, mobile et desktop",
            'metaDescription' => 'Conception et développement d\'applications sur mesure : web, mobile, desktop, UI/UX et maintenance.',
            'bodyClass' => 'page-digital page-conception',
        ]);
    }

    public function conceptionApplicationShow(string $slug)
    {
        $services = collect($this->getConceptionServices())->keyBy('slug');
        if (!$services->has($slug)) {
            abort(404);
        }

        $service = $services->get($slug);

        return view('conception-application-show', [
            'service' => $service,
            'title' => $service['title'],
            'metaDescription' => $service['lead'] ?? $service['description'],
            'bodyClass' => 'page-digital page-digital-detail page-conception',
        ]);
    }

    public function projetsEnvironnement(Request $request)
    {
        return view('projetsenvironnement', [
            'aRealiser' => array_slice($this->getProjetsARealiser(), 0, 3),
            'realises' => array_slice($this->getProjetsRealises(), 0, 3),
            'title' => 'Nos projets Environnement',
            'metaDescription' => 'Projets environnementaux à réaliser et réalisés par MARINA GRADE PSF.',
            'bodyClass' => 'page-projets-env',
        ]);
    }

    public function projetsARealiser(Request $request)
    {
        return view('projetsarealiser', [
            'projets' => $this->getProjetsARealiser(),
            'title' => 'Projets à réaliser',
            'metaDescription' => 'Tous les projets environnementaux à réaliser — MARINA GRADE PSF.',
            'bodyClass' => 'page-projets-env',
        ]);
    }

    public function soutenirProjet(string $slug)
    {
        $aRealiser = collect($this->getProjetsARealiser())->keyBy('slug');
        $realises = collect($this->getProjetsRealises())->keyBy('slug');

        if ($aRealiser->has($slug)) {
            $projet = $aRealiser->get($slug);
            $statut = 'a-realiser';
        } elseif ($realises->has($slug)) {
            $projet = $realises->get($slug);
            $statut = 'realise';
        } else {
            abort(404);
        }

        $collecte = (int) round($projet['objectif'] * $projet['progress'] / 100);
        $reste = $projet['objectif'] - $collecte;

        return view('soutenir', [
            'projet' => $projet,
            'collecte' => $collecte,
            'reste' => $reste,
            'statut' => $statut,
            'title' => 'Soutenir — ' . $projet['title'],
            'metaDescription' => $projet['description'],
            'bodyClass' => 'page-soutenir',
        ]);
    }

    public function projetRealiser(Request $request)
    {
        return view('projetrealiser', [
            'projets' => $this->getProjetsRealises(),
            'title' => 'Projets réalisés',
            'metaDescription' => 'Tous les projets environnementaux réalisés — MARINA GRADE PSF.',
            'bodyClass' => 'page-projets-env',
        ]);
    }

    private function getProjetsARealiser(): array
    {
        return [
            [
                'slug' => 'reboisement-communautaire',
                'title' => 'Reboisement de 10 000 arbres',
                'badge' => 'Reboisement',
                'description' => "Plantation d'arbres avec les communautés pour restaurer les écosystèmes locaux.",
                'about' => "Ce projet vise à planter 10 000 arbres dans la région des Plateaux afin de restaurer les sols, lutter contre l'érosion et sensibiliser les communautés à la protection de l'environnement. Les pépinières locales et les écoles partenaires sont au cœur du dispositif.",
                'lieu' => 'Région des Plateaux, Togo',
                'metric' => '10 000 arbres',
                'metricIcon' => 'seedling',
                'duree' => '12 mois',
                'progress' => 60,
                'objectif' => 10000000,
                'image' => 'projets/reboisement.jpg',
                'video' => 'reboisement-kpalime.mp4',
                'stats' => [
                    ['value' => '10 000', 'label' => 'arbres à planter'],
                    ['value' => '50', 'label' => 'hectares à restaurer'],
                    ['value' => '1 500', 'label' => 'bénéficiaires directs'],
                    ['value' => '12', 'label' => 'mois de mise en œuvre'],
                ],
                'impacts' => [
                    'Restauration des sols et biodiversité locale',
                    'Création d\'emplois verts pour les jeunes',
                    'Sensibilisation environnementale dans les écoles',
                    'Réduction de l\'érosion et des risques climatiques',
                ],
                'dons' => [10000, 25000, 50000, 100000, 250000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => '10 arbres'],
                    ['montant' => 25000, 'label' => '25 arbres'],
                    ['montant' => 50000, 'label' => '50 arbres'],
                    ['montant' => 100000, 'label' => '100 arbres'],
                ],
            ],
            [
                'slug' => 'programme-ecole-verte',
                'title' => 'Programme École Verte',
                'badge' => 'Éducation verte',
                'description' => "Sensibilisation environnementale et équipements pédagogiques dans les écoles.",
                'about' => "Le Programme École Verte équipe les écoles de kits pédagogiques, crée des clubs verts et forme les enseignants pour ancrer l'écologie dans le quotidien des élèves.",
                'lieu' => 'Kara, Togo',
                'metric' => '500 élèves',
                'metricIcon' => 'user-graduate',
                'duree' => '10 mois',
                'progress' => 45,
                'objectif' => 5000000,
                'image' => 'projets/ecole-verte.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '500', 'label' => 'élèves accompagnés'],
                    ['value' => '15', 'label' => 'écoles partenaires'],
                    ['value' => '40', 'label' => 'enseignants formés'],
                    ['value' => '10', 'label' => 'mois de programme'],
                ],
                'impacts' => [
                    'Clubs verts actifs dans chaque école',
                    'Matériel pédagogique écologique distribué',
                    'Ateliers pratiques de jardinage scolaire',
                    'Réduction des déchets dans les établissements',
                ],
                'dons' => [5000, 10000, 25000, 50000, 100000],
                'impactDons' => [
                    ['montant' => 5000, 'label' => '1 kit élève'],
                    ['montant' => 10000, 'label' => '2 kits élèves'],
                    ['montant' => 25000, 'label' => '1 atelier classe'],
                    ['montant' => 50000, 'label' => '1 club vert'],
                ],
            ],
            [
                'slug' => 'collecte-tri-dechets',
                'title' => 'Collecte et tri des déchets',
                'badge' => 'Déchets',
                'description' => "Mise en place de points de collecte et sensibilisation au tri sélectif.",
                'about' => "Ce projet installe des points de collecte, forme des agents de tri et sensibilise les ménages pour réduire la pollution plastique dans la commune d'Aného.",
                'lieu' => 'Aného, Togo',
                'metric' => '10 points de collecte',
                'metricIcon' => 'recycle',
                'duree' => '8 mois',
                'progress' => 30,
                'objectif' => 4000000,
                'image' => 'projets/dechets.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '10', 'label' => 'points de collecte'],
                    ['value' => '5', 'label' => 'quartiers couverts'],
                    ['value' => '800', 'label' => 'ménages sensibilisés'],
                    ['value' => '8', 'label' => 'mois de déploiement'],
                ],
                'impacts' => [
                    'Réduction des déchets dans l\'espace public',
                    'Emplois locaux pour agents de collecte',
                    'Sensibilisation au tri sélectif',
                    'Valorisation des recyclables',
                ],
                'dons' => [5000, 15000, 30000, 75000, 150000],
                'impactDons' => [
                    ['montant' => 5000, 'label' => '20 sacs de tri'],
                    ['montant' => 15000, 'label' => '1 bac de collecte'],
                    ['montant' => 30000, 'label' => '1 campagne quartier'],
                    ['montant' => 75000, 'label' => '1 point de collecte'],
                ],
            ],
            [
                'slug' => 'jardins-agroecologiques',
                'title' => 'Jardins agroécologiques',
                'badge' => 'Agriculture',
                'description' => "Création de jardins potagers collectifs pour renforcer la sécurité alimentaire.",
                'about' => "Installation de jardins agroécologiques communautaires à Sokodé pour produire localement, former les familles et promouvoir des pratiques agricoles durables.",
                'lieu' => 'Sokodé, Togo',
                'metric' => '12 jardins',
                'metricIcon' => 'leaf',
                'duree' => '9 mois',
                'progress' => 25,
                'objectif' => 3500000,
                'image' => 'projets/hero-seedling.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '12', 'label' => 'jardins à créer'],
                    ['value' => '200', 'label' => 'familles bénéficiaires'],
                    ['value' => '6', 'label' => 'espèces cultivées'],
                    ['value' => '9', 'label' => 'mois de suivi'],
                ],
                'impacts' => [
                    'Sécurité alimentaire renforcée',
                    'Formation aux techniques agroécologiques',
                    'Revenus complémentaires pour les familles',
                    'Préservation des sols',
                ],
                'dons' => [10000, 20000, 40000, 80000, 150000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => 'semences pour 1 famille'],
                    ['montant' => 20000, 'label' => 'outils de jardin'],
                    ['montant' => 40000, 'label' => '1 parcelle équipée'],
                    ['montant' => 80000, 'label' => '1 jardin collectif'],
                ],
            ],
            [
                'slug' => 'energie-solaire-villageoise',
                'title' => 'Énergie solaire villageoise',
                'badge' => 'Énergie',
                'description' => "Installation de kits solaires pour éclairer des foyers et écoles rurales.",
                'about' => "Déploiement de kits solaires domestiques et scolaires à Dapaong pour améliorer l'accès à l'énergie propre, l'étude des enfants et les activités productives.",
                'lieu' => 'Dapaong, Togo',
                'metric' => '80 foyers',
                'metricIcon' => 'sun',
                'duree' => '6 mois',
                'progress' => 15,
                'objectif' => 8000000,
                'image' => 'projets/eau.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '80', 'label' => 'foyers à équiper'],
                    ['value' => '5', 'label' => 'écoles ciblées'],
                    ['value' => '100%', 'label' => 'énergie propre'],
                    ['value' => '6', 'label' => 'mois d\'installation'],
                ],
                'impacts' => [
                    'Éclairage propre et sûr',
                    'Meilleures conditions d\'étude',
                    'Réduction des dépenses en pétrole',
                    'Formation à la maintenance solaire',
                ],
                'dons' => [15000, 30000, 60000, 120000, 250000],
                'impactDons' => [
                    ['montant' => 15000, 'label' => '1 lampe solaire'],
                    ['montant' => 30000, 'label' => 'kit foyer basique'],
                    ['montant' => 60000, 'label' => 'kit foyer complet'],
                    ['montant' => 120000, 'label' => 'équipement école'],
                ],
            ],
            [
                'slug' => 'protection-des-rives',
                'title' => 'Protection des rives',
                'badge' => 'Biodiversité',
                'description' => "Stabilisation des berges et plantation d'espèces locales le long des cours d'eau.",
                'about' => "Stabilisation de 3 km de berges à Kpalimé par plantation d'espèces locales, engagé avec les riverains pour protéger l'eau et la biodiversité.",
                'lieu' => 'Kpalimé, Togo',
                'metric' => '3 km de rives',
                'metricIcon' => 'tint',
                'duree' => '11 mois',
                'progress' => 40,
                'objectif' => 6000000,
                'image' => 'projets/mangroves.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '3 km', 'label' => 'de rives à protéger'],
                    ['value' => '4 000', 'label' => 'plants riverains'],
                    ['value' => '20', 'label' => 'familles engagées'],
                    ['value' => '11', 'label' => 'mois de travaux'],
                ],
                'impacts' => [
                    'Réduction de l\'érosion des berges',
                    'Amélioration de la qualité de l\'eau',
                    'Habitats restaurés pour la faune',
                    'Implication des communautés riveraines',
                ],
                'dons' => [10000, 25000, 50000, 100000, 200000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => '20 plants riverains'],
                    ['montant' => 25000, 'label' => '50 plants'],
                    ['montant' => 50000, 'label' => '100 m de berge'],
                    ['montant' => 100000, 'label' => '250 m de berge'],
                ],
            ],
        ];
    }

    private function getProjetsRealises(): array
    {
        return [
            [
                'slug' => 'reboisement-kpalime',
                'title' => 'Reboisement à Kpalimé',
                'badge' => 'Réalisé',
                'description' => "Plus de 5 000 arbres plantés avec les communautés de Kpalimé.",
                'about' => "Ce projet a permis de planter plus de 5 000 arbres à Kpalimé avec les communautés locales, pour restaurer les sols et renforcer la biodiversité.",
                'lieu' => 'Kpalimé, Togo',
                'annee' => '2023',
                'metric' => '5 000 arbres',
                'metricIcon' => 'seedling',
                'duree' => 'Réalisé',
                'progress' => 100,
                'objectif' => 5000000,
                'image' => 'projets/reboisement.jpg',
                'video' => 'reboisement-kpalime.mp4',
                'stats' => [
                    ['value' => '5 000', 'label' => 'arbres plantés'],
                    ['value' => '20', 'label' => 'hectares restaurés'],
                    ['value' => '800', 'label' => 'bénéficiaires'],
                    ['value' => '2023', 'label' => 'année de réalisation'],
                ],
                'impacts' => [
                    'Restauration des sols et de la biodiversité',
                    'Mobilisation des communautés locales',
                    'Sensibilisation environnementale',
                    'Création d\'emplois verts temporaires',
                ],
                'dons' => [10000, 25000, 50000, 100000, 250000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => '10 arbres'],
                    ['montant' => 25000, 'label' => '25 arbres'],
                    ['montant' => 50000, 'label' => '50 arbres'],
                    ['montant' => 100000, 'label' => '100 arbres'],
                ],
            ],
            [
                'slug' => 'acces-eau-potable',
                'title' => "Accès à l'eau potable",
                'badge' => 'Réalisé',
                'description' => "Forage et points d'eau pour améliorer l'accès à l'eau potable.",
                'about' => "Forage et installation de points d'eau à Sokodé pour améliorer durablement l'accès à l'eau potable des communautés.",
                'lieu' => 'Sokodé, Togo',
                'annee' => '2022',
                'metric' => '3 points d\'eau',
                'metricIcon' => 'tint',
                'duree' => 'Réalisé',
                'progress' => 100,
                'objectif' => 7500000,
                'image' => 'projets/eau.jpg',
                'video' => 'acces-eau-potable.mp4',
                'stats' => [
                    ['value' => '3', 'label' => 'points d\'eau'],
                    ['value' => '1 200', 'label' => 'bénéficiaires'],
                    ['value' => '2', 'label' => 'villages desservis'],
                    ['value' => '2022', 'label' => 'année de réalisation'],
                ],
                'impacts' => [
                    'Accès amélioré à l\'eau potable',
                    'Réduction des maladies hydriques',
                    'Gain de temps pour les familles',
                    'Renforcement de la santé communautaire',
                ],
                'dons' => [10000, 25000, 50000, 100000, 250000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => 'Entretien point d\'eau'],
                    ['montant' => 25000, 'label' => 'Kit hygiène'],
                    ['montant' => 50000, 'label' => 'Réparation pompe'],
                    ['montant' => 100000, 'label' => 'Extension réseau'],
                ],
            ],
            [
                'slug' => 'mangroves-protection',
                'title' => 'Mangroves en protection',
                'badge' => 'Réalisé',
                'description' => "Restauration des mangroves côtières — présentation en image.",
                'about' => "Projet de restauration des mangroves côtières à Aného pour protéger le littoral et la biodiversité marine.",
                'lieu' => 'Aného, Togo',
                'annee' => '2023',
                'metric' => '15 ha restaurés',
                'metricIcon' => 'water',
                'duree' => 'Réalisé',
                'progress' => 100,
                'objectif' => 4000000,
                'image' => 'projets/mangroves.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '15', 'label' => 'hectares restaurés'],
                    ['value' => '3 000', 'label' => 'plants de mangrove'],
                    ['value' => '5', 'label' => 'communautés engagées'],
                    ['value' => '2023', 'label' => 'année de réalisation'],
                ],
                'impacts' => [
                    'Protection du littoral',
                    'Préservation de la biodiversité',
                    'Sensibilisation des pêcheurs',
                    'Résilience face à l\'érosion côtière',
                ],
                'dons' => [10000, 25000, 50000, 100000, 250000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => '50 plants'],
                    ['montant' => 25000, 'label' => '125 plants'],
                    ['montant' => 50000, 'label' => '250 plants'],
                    ['montant' => 100000, 'label' => '500 plants'],
                ],
            ],
            [
                'slug' => 'ecole-verte-phase-1',
                'title' => 'École Verte — Phase 1',
                'badge' => 'Réalisé',
                'description' => "Équipement de 8 écoles et ateliers de sensibilisation pour 1 200 élèves.",
                'about' => "Première phase du programme École Verte : équipements pédagogiques et sensibilisation de 1 200 élèves dans 8 écoles de Lomé.",
                'lieu' => 'Lomé, Togo',
                'annee' => '2021',
                'metric' => '1 200 élèves',
                'metricIcon' => 'user-graduate',
                'duree' => 'Réalisé',
                'progress' => 100,
                'objectif' => 3500000,
                'image' => 'projets/ecole-verte.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '8', 'label' => 'écoles équipées'],
                    ['value' => '1 200', 'label' => 'élèves sensibilisés'],
                    ['value' => '40', 'label' => 'enseignants formés'],
                    ['value' => '2021', 'label' => 'année de réalisation'],
                ],
                'impacts' => [
                    'Éducation environnementale renforcée',
                    'Clubs verts créés dans les écoles',
                    'Kits pédagogiques distribués',
                    'Engagement des jeunes pour la planète',
                ],
                'dons' => [10000, 25000, 50000, 100000, 250000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => 'Kit scolaire vert'],
                    ['montant' => 25000, 'label' => 'Atelier classe'],
                    ['montant' => 50000, 'label' => 'Club vert'],
                    ['montant' => 100000, 'label' => 'Équipement école'],
                ],
            ],
            [
                'slug' => 'campagne-zero-plastique',
                'title' => 'Campagne zéro plastique',
                'badge' => 'Réalisé',
                'description' => "Collecte de déchets plastiques et sensibilisation dans 15 quartiers.",
                'about' => "Campagne de collecte et de sensibilisation contre le plastique dans 15 quartiers de Lomé, avec implication des jeunes et des associations locales.",
                'lieu' => 'Lomé, Togo',
                'annee' => '2022',
                'metric' => '15 quartiers',
                'metricIcon' => 'recycle',
                'duree' => 'Réalisé',
                'progress' => 100,
                'objectif' => 2800000,
                'image' => 'projets/dechets.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '15', 'label' => 'quartiers mobilisés'],
                    ['value' => '40 t', 'label' => 'déchets collectés'],
                    ['value' => '2 000', 'label' => 'personnes sensibilisées'],
                    ['value' => '2022', 'label' => 'année de réalisation'],
                ],
                'impacts' => [
                    'Réduction des déchets plastiques',
                    'Sensibilisation citoyenne',
                    'Emplois verts créés',
                    'Quartiers plus propres',
                ],
                'dons' => [10000, 25000, 50000, 100000, 250000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => '50 sacs collectés'],
                    ['montant' => 25000, 'label' => 'Campagne quartier'],
                    ['montant' => 50000, 'label' => 'Atelier tri'],
                    ['montant' => 100000, 'label' => 'Équipe de collecte'],
                ],
            ],
            [
                'slug' => 'puits-communautaire',
                'title' => 'Puits communautaire',
                'badge' => 'Réalisé',
                'description' => "Construction d'un puits et d'un réseau de distribution pour 3 villages.",
                'about' => "Construction d'un puits communautaire et d'un réseau de distribution desservant 3 villages dans la région de Kara.",
                'lieu' => 'Kara, Togo',
                'annee' => '2020',
                'metric' => '3 villages',
                'metricIcon' => 'tint',
                'duree' => 'Réalisé',
                'progress' => 100,
                'objectif' => 6000000,
                'image' => 'projets/eau.jpg',
                'video' => '',
                'stats' => [
                    ['value' => '1', 'label' => 'puits construit'],
                    ['value' => '3', 'label' => 'villages desservis'],
                    ['value' => '900', 'label' => 'bénéficiaires'],
                    ['value' => '2020', 'label' => 'année de réalisation'],
                ],
                'impacts' => [
                    'Accès pérenne à l\'eau',
                    'Amélioration de la santé',
                    'Autonomie des communautés',
                    'Réduction des distances de corvée d\'eau',
                ],
                'dons' => [10000, 25000, 50000, 100000, 250000],
                'impactDons' => [
                    ['montant' => 10000, 'label' => 'Entretien pompe'],
                    ['montant' => 25000, 'label' => 'Kit distribution'],
                    ['montant' => 50000, 'label' => 'Réparation réseau'],
                    ['montant' => 100000, 'label' => 'Extension village'],
                ],
            ],
        ];
    }

    private function getDigitalServices(): array
    {
        $base = [
            [
                'slug' => 'strategie-digitale',
                'title' => 'Stratégie Digitale',
                'icon' => 'fas fa-lightbulb',
                'description' => 'Définissez une feuille de route claire pour atteindre vos objectifs digitaux.',
                'lead' => 'Nous définissons une feuille de route claire et personnalisée pour atteindre vos objectifs digitaux.',
                'about' => "Notre service de stratégie digitale vous aide à structurer votre présence en ligne : diagnostic, positionnement, canaux prioritaires et plan d'actions mesurable pour croître durablement.",
                'inclus' => [
                    'Analyse de votre présence digitale actuelle',
                    'Étude de la concurrence',
                    'Définition des objectifs SMART',
                    'Choix des canaux prioritaires',
                    'Plan d\'action détaillé',
                    'Calendrier de mise en œuvre',
                    'Indicateurs de performance (KPI)',
                    'Recommandations budgétaires',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-chart-pie', 'title' => "Rapport d'analyse complet"],
                    ['icon' => 'fas fa-file-alt', 'title' => "Stratégie & plan d'action"],
                    ['icon' => 'fas fa-comments', 'title' => 'Recommandations personnalisées'],
                    ['icon' => 'fas fa-chart-line', 'title' => 'Tableau de suivi des KPI'],
                ],
                'prix' => 150000,
                'delai' => '3 à 5 jours',
            ],
            [
                'slug' => 'reseaux-sociaux',
                'title' => 'Réseaux Sociaux',
                'icon' => 'fas fa-share-alt',
                'description' => 'Animez vos comptes, engagez votre communauté et développez votre audience.',
                'lead' => 'Animez vos réseaux sociaux avec une ligne éditoriale claire et un engagement régulier.',
                'about' => 'Nous gérons vos comptes sociaux : calendrier éditorial, publications, interactions et reporting pour faire grandir votre communauté.',
                'inclus' => [
                    'Audit des comptes existants',
                    'Ligne éditoriale & ton de marque',
                    'Calendrier de publications',
                    'Création et programmation de contenus',
                    'Community management',
                    'Veille et tendances',
                    'Rapport d\'engagement mensuel',
                    'Optimisation continue',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-calendar-alt', 'title' => 'Calendrier éditorial'],
                    ['icon' => 'fas fa-images', 'title' => 'Visuels & publications'],
                    ['icon' => 'fas fa-users', 'title' => 'Rapport communauté'],
                    ['icon' => 'fas fa-chart-bar', 'title' => 'KPIs réseaux sociaux'],
                ],
                'prix' => 150000,
                'delai' => 'Mise en place sous 7 jours',
            ],
            [
                'slug' => 'creation-contenu',
                'title' => 'Création de Contenu',
                'icon' => 'fas fa-pen-fancy',
                'description' => 'Produisez des textes, visuels et vidéos adaptés à votre marque.',
                'lead' => 'Des contenus engageants pour raconter votre marque et convertir votre audience.',
                'about' => 'Rédaction, design et formats multimédias pour vos réseaux, site web et campagnes marketing.',
                'inclus' => [
                    'Brief créatif',
                    'Rédaction de posts & articles',
                    'Création de visuels',
                    'Scripts vidéo courts',
                    'Adaptation multi-canaux',
                    'Relecture et validation',
                    'Bibliothèque de contenus',
                    'Conseils d\'optimisation',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-pen', 'title' => 'Textes & captions'],
                    ['icon' => 'fas fa-palette', 'title' => 'Visuels de marque'],
                    ['icon' => 'fas fa-video', 'title' => 'Contenus vidéo'],
                    ['icon' => 'fas fa-folder-open', 'title' => 'Banque de contenus'],
                ],
                'prix' => 120000,
                'delai' => '5 à 10 jours',
            ],
            [
                'slug' => 'publicite-en-ligne',
                'title' => 'Publicité en Ligne',
                'icon' => 'fas fa-bullhorn',
                'description' => 'Lancez des campagnes Meta, Google et TikTok performantes et mesurables.',
                'lead' => 'Des campagnes publicitaires ciblées pour générer leads et ventes.',
                'about' => 'Conception, diffusion et optimisation de campagnes publicitaires sur les principales plateformes digitales.',
                'inclus' => [
                    'Cadrage des objectifs pubs',
                    'Ciblage d\'audience',
                    'Création des annonces',
                    'Paramétrage des campagnes',
                    'Suivi quotidien',
                    'A/B testing',
                    'Optimisation du budget',
                    'Reporting de performance',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-ad', 'title' => 'Campagnes actives'],
                    ['icon' => 'fas fa-bullseye', 'title' => 'Audiences ciblées'],
                    ['icon' => 'fas fa-flask', 'title' => 'Tests A/B'],
                    ['icon' => 'fas fa-file-invoice', 'title' => 'Rapport ROI'],
                ],
                'prix' => 200000,
                'delai' => 'Lancement sous 3 à 7 jours',
            ],
            [
                'slug' => 'email-marketing',
                'title' => 'Email Marketing',
                'icon' => 'fas fa-envelope-open-text',
                'description' => 'Fidélisez vos clients avec des newsletters et automatisations efficaces.',
                'lead' => 'Convertissez et fidélisez grâce à l\'email marketing structuré.',
                'about' => 'Stratégie email, templates, scénarios d\'automatisation et analyses d\'ouverture/clics.',
                'inclus' => [
                    'Audit de la base email',
                    'Stratégie de newsletters',
                    'Design de templates',
                    'Scénarios d\'automatisation',
                    'Segmentation',
                    'Tests d\'envoi',
                    'Suivi des performances',
                    'Recommandations d\'amélioration',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-envelope', 'title' => 'Templates email'],
                    ['icon' => 'fas fa-sitemap', 'title' => 'Automations'],
                    ['icon' => 'fas fa-user-friends', 'title' => 'Segments audience'],
                    ['icon' => 'fas fa-chart-area', 'title' => 'Rapport campagnes'],
                ],
                'prix' => 100000,
                'delai' => '5 à 8 jours',
            ],
            [
                'slug' => 'seo-referencement',
                'title' => 'SEO / Référencement',
                'icon' => 'fas fa-search',
                'description' => 'Améliorez votre visibilité organique et attirez un trafic qualifié.',
                'lead' => 'Gagnez en visibilité Google grâce à un SEO durable et mesurable.',
                'about' => 'Audit SEO, optimisation on-page, contenus stratégiques et suivi de positions.',
                'inclus' => [
                    'Audit SEO technique',
                    'Recherche de mots-clés',
                    'Optimisation on-page',
                    'Plan de contenus SEO',
                    'Maillage interne',
                    'Suivi des positions',
                    'Analyse concurrentielle',
                    'Reporting mensuel',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-search-plus', 'title' => 'Audit SEO'],
                    ['icon' => 'fas fa-key', 'title' => 'Liste de mots-clés'],
                    ['icon' => 'fas fa-wrench', 'title' => 'Optimisations'],
                    ['icon' => 'fas fa-trophy', 'title' => 'Suivi de ranking'],
                ],
                'prix' => 180000,
                'delai' => '7 à 14 jours',
            ],
            [
                'slug' => 'site-web-landing',
                'title' => 'Site Web & Landing Page',
                'icon' => 'fas fa-laptop-code',
                'description' => 'Créez des pages modernes, rapides et optimisées pour la conversion.',
                'lead' => 'Des pages web performantes, belles et orientées conversion.',
                'about' => 'Conception et développement de sites et landing pages adaptés à vos objectifs business.',
                'inclus' => [
                    'Brief & wireframes',
                    'Design UI moderne',
                    'Développement responsive',
                    'Optimisation vitesse',
                    'Formulaires de conversion',
                    'Intégration analytics',
                    'Mise en ligne',
                    'Formation d\'utilisation',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-desktop', 'title' => 'Pages responsive'],
                    ['icon' => 'fas fa-paint-brush', 'title' => 'Design UI'],
                    ['icon' => 'fas fa-rocket', 'title' => 'Mise en production'],
                    ['icon' => 'fas fa-graduation-cap', 'title' => 'Guide d\'utilisation'],
                ],
                'prix' => 350000,
                'delai' => '2 à 4 semaines',
            ],
            [
                'slug' => 'branding-design',
                'title' => 'Branding & Design',
                'icon' => 'fas fa-palette',
                'description' => 'Construisez une identité visuelle forte et cohérente sur tous vos canaux.',
                'lead' => 'Une identité visuelle forte pour être reconnu et mémorable.',
                'about' => 'Logo, charte graphique et déclinaisons pour une image de marque cohérente.',
                'inclus' => [
                    'Brief identité',
                    'Moodboard',
                    'Création / refonte de logo',
                    'Charte graphique',
                    'Palette & typographies',
                    'Templates réseaux sociaux',
                    'Supports print de base',
                    'Fichiers sources',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-copyright', 'title' => 'Logo & variantes'],
                    ['icon' => 'fas fa-book', 'title' => 'Charte graphique'],
                    ['icon' => 'fas fa-th', 'title' => 'Templates digitaux'],
                    ['icon' => 'fas fa-download', 'title' => 'Fichiers sources'],
                ],
                'prix' => 250000,
                'delai' => '7 à 15 jours',
            ],
        ];

        return array_map(function ($s) {
            $s['points'] = $s['points'] ?? [
                'Accompagnement personnalisé',
                'Résultats mesurables',
                'Stratégies sur-mesure',
            ];
            $s['pourquoi'] = $s['pourquoi'] ?? [
                ['icon' => 'fas fa-award', 'title' => 'Expertise', 'text' => 'Une équipe spécialisée en marketing digital.'],
                ['icon' => 'fas fa-sliders-h', 'title' => 'Sur-mesure', 'text' => 'Des stratégies adaptées à vos besoins et à votre budget.'],
                ['icon' => 'fas fa-chart-line', 'title' => 'Résultats', 'text' => 'Des actions orientées performance et ROI.'],
                ['icon' => 'fas fa-hands-helping', 'title' => 'Accompagnement', 'text' => 'Un suivi régulier et des conseils continus.'],
            ];
            $s['support'] = $s['support'] ?? 'Support dédié';
            $s['livrablesLabel'] = $s['livrablesLabel'] ?? 'Livrables inclus';
            return $s;
        }, $base);
    }

    private function getDigitalPacks(): array
    {
        return [
            [
                'slug' => 'starter',
                'name' => 'Pack Starter',
                'price' => 150000,
                'popular' => false,
                'features' => [
                    '2 réseaux sociaux',
                    '8 publications / mois',
                    'Rapport mensuel',
                ],
            ],
            [
                'slug' => 'business',
                'name' => 'Pack Business',
                'price' => 300000,
                'popular' => true,
                'features' => [
                    '3 réseaux sociaux',
                    'Création de contenu',
                    'Campagnes pubs (50 000 FCFA inclus)',
                    'Rapport & analyse',
                ],
            ],
            [
                'slug' => 'premium',
                'name' => 'Pack Premium',
                'price' => 600000,
                'popular' => false,
                'features' => [
                    '4+ réseaux sociaux',
                    'Contenu illimité',
                    'Campagnes pubs (100 000 FCFA inclus)',
                    'SEO / Site web',
                    'Stratégie avancée',
                ],
            ],
        ];
    }

    private function getConceptionServices(): array
    {
        $base = [
            [
                'slug' => 'application-web',
                'title' => 'Application Web',
                'icon' => 'fas fa-globe',
                'image' => 'web.jpg',
                'description' => 'Sites et plateformes web modernes, sécurisés et orientés performance.',
                'lead' => 'Développez une application web sur mesure, rapide, responsive et évolutive.',
                'about' => "De l'analyse du besoin au déploiement, nous concevons des applications web robustes (vitrine, e-commerce, portails métier) avec une expérience utilisateur soignée.",
                'inclus' => [
                    'Cadrage fonctionnel & technique',
                    'Wireframes et maquettes UI',
                    'Développement front & back',
                    'Responsive (mobile / tablette / desktop)',
                    'Sécurité et bonnes pratiques',
                    'Tests et mise en production',
                    'Formation à l\'administration',
                    'Documentation technique',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-desktop', 'title' => 'Application en ligne'],
                    ['icon' => 'fas fa-paint-brush', 'title' => 'Maquettes UI'],
                    ['icon' => 'fas fa-database', 'title' => 'Base de données'],
                    ['icon' => 'fas fa-book', 'title' => 'Documentation'],
                ],
                'prix' => 850000,
                'delai' => '4 à 8 semaines',
            ],
            [
                'slug' => 'application-mobile',
                'title' => 'Application Mobile',
                'icon' => 'fas fa-mobile-alt',
                'image' => 'mobile.jpg',
                'description' => 'Applications iOS et Android natives ou multiplateformes.',
                'lead' => 'Créez une app mobile fluide, intuitive et prête pour vos utilisateurs.',
                'about' => 'Conception et développement d\'applications mobiles (Android / iOS) avec publication store, notifications et intégrations API.',
                'inclus' => [
                    'Étude UX mobile',
                    'Design d\'interfaces',
                    'Développement multiplateforme ou natif',
                    'Intégration API / backend',
                    'Notifications push',
                    'Tests sur appareils réels',
                    'Publication Play Store / App Store',
                    'Support post-lancement',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-mobile', 'title' => 'App iOS / Android'],
                    ['icon' => 'fas fa-pencil-ruler', 'title' => 'Design mobile'],
                    ['icon' => 'fas fa-cloud', 'title' => 'API connectée'],
                    ['icon' => 'fas fa-store', 'title' => 'Publication stores'],
                ],
                'prix' => 1200000,
                'delai' => '6 à 12 semaines',
            ],
            [
                'slug' => 'application-desktop',
                'title' => 'Application Desktop',
                'icon' => 'fas fa-desktop',
                'image' => 'desktop.jpg',
                'description' => 'Logiciels métier pour Windows, macOS et Linux.',
                'lead' => 'Développez un logiciel desktop adapté à vos process et à votre métier.',
                'about' => 'Applications de bureau performantes pour la gestion interne, la productivité ou les outils spécialisés, avec installation et maintenance.',
                'inclus' => [
                    'Analyse des besoins métier',
                    'Architecture logicielle',
                    'Développement desktop',
                    'Interface ergonomique',
                    'Gestion des droits utilisateurs',
                    'Installateurs multi-OS',
                    'Tests de stabilité',
                    'Formation utilisateurs',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-laptop', 'title' => 'Logiciel installable'],
                    ['icon' => 'fas fa-cogs', 'title' => 'Modules métier'],
                    ['icon' => 'fas fa-user-shield', 'title' => 'Gestion des accès'],
                    ['icon' => 'fas fa-file-alt', 'title' => 'Manuel utilisateur'],
                ],
                'prix' => 1500000,
                'delai' => '8 à 14 semaines',
            ],
            [
                'slug' => 'ui-ux-design',
                'title' => 'UI / UX Design',
                'icon' => 'fas fa-pencil-ruler',
                'image' => 'uiux.jpg',
                'description' => 'Interfaces claires, élégantes et centrées utilisateur.',
                'lead' => 'Concevez une expérience utilisateur fluide avant même de coder.',
                'about' => 'Recherche utilisateur, wireframes, prototypes et design system pour des interfaces web et mobile cohérentes.',
                'inclus' => [
                    'Interviews / personas',
                    'Parcours utilisateurs',
                    'Wireframes basse fidélité',
                    'Maquettes haute fidélité',
                    'Prototype interactif',
                    'Design system / composants',
                    'Guidelines UI',
                    'Itérations avec validation',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-sitemap', 'title' => 'Wireframes'],
                    ['icon' => 'fas fa-palette', 'title' => 'Maquettes UI'],
                    ['icon' => 'fas fa-project-diagram', 'title' => 'Prototype'],
                    ['icon' => 'fas fa-swatchbook', 'title' => 'Design system'],
                ],
                'prix' => 450000,
                'delai' => '2 à 4 semaines',
            ],
            [
                'slug' => 'api-backend',
                'title' => 'API & Backend',
                'icon' => 'fas fa-server',
                'image' => 'api.jpg',
                'description' => 'APIs sécurisées et backends scalables pour vos applications.',
                'lead' => 'Construisez le cœur technique de vos apps web et mobile.',
                'about' => 'Conception d\'APIs REST, bases de données, authentification et services cloud pour alimenter vos frontends.',
                'inclus' => [
                    'Modélisation des données',
                    'API REST / JSON',
                    'Authentification sécurisée',
                    'Documentation API',
                    'Hébergement & déploiement',
                    'Monitoring de base',
                    'Tests d\'intégration',
                    'Support technique',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-code', 'title' => 'API documentée'],
                    ['icon' => 'fas fa-database', 'title' => 'Base de données'],
                    ['icon' => 'fas fa-lock', 'title' => 'Auth sécurisée'],
                    ['icon' => 'fas fa-cloud-upload-alt', 'title' => 'Déploiement cloud'],
                ],
                'prix' => 700000,
                'delai' => '3 à 6 semaines',
            ],
            [
                'slug' => 'maintenance-evolution',
                'title' => 'Maintenance & Évolution',
                'icon' => 'fas fa-tools',
                'image' => 'maintenance.jpg',
                'description' => 'Suivi, correctifs et nouvelles fonctionnalités pour vos applications.',
                'lead' => 'Gardez vos applications stables, sécurisées et à jour.',
                'about' => 'Contrats de maintenance corrective et évolutive : monitoring, correctifs, mises à jour et nouvelles features.',
                'inclus' => [
                    'Monitoring & alertes',
                    'Correctifs de bugs',
                    'Mises à jour de sécurité',
                    'Petites évolutions',
                    'Sauvegardes',
                    'Rapport mensuel',
                    'Hotline technique',
                    'Priorisation des tickets',
                ],
                'livrables' => [
                    ['icon' => 'fas fa-bug', 'title' => 'Correctifs'],
                    ['icon' => 'fas fa-shield-alt', 'title' => 'Sécurité'],
                    ['icon' => 'fas fa-sync', 'title' => 'Mises à jour'],
                    ['icon' => 'fas fa-chart-line', 'title' => 'Rapport mensuel'],
                ],
                'prix' => 150000,
                'delai' => 'Contrat mensuel',
            ],
        ];

        return array_map(function ($s) {
            $s['points'] = $s['points'] ?? [
                'Développement sur mesure',
                'Qualité & performance',
                'Accompagnement de A à Z',
            ];
            $s['pourquoi'] = $s['pourquoi'] ?? [
                ['icon' => 'fas fa-code', 'title' => 'Expertise tech', 'text' => 'Une équipe spécialisée en développement logiciel.'],
                ['icon' => 'fas fa-sliders-h', 'title' => 'Sur-mesure', 'text' => 'Des solutions adaptées à votre métier et budget.'],
                ['icon' => 'fas fa-rocket', 'title' => 'Livraison', 'text' => 'Méthode agile, jalons clairs et livrables concrets.'],
                ['icon' => 'fas fa-hands-helping', 'title' => 'Accompagnement', 'text' => 'Formation, support et évolution continue.'],
            ];
            $s['support'] = $s['support'] ?? 'Support dédié';
            $s['livrablesLabel'] = $s['livrablesLabel'] ?? 'Livrables inclus';
            return $s;
        }, $base);
    }

    private function getConceptionPacks(): array
    {
        return [
            [
                'slug' => 'starter-app',
                'name' => 'Pack Starter App',
                'price' => 850000,
                'popular' => false,
                'features' => [
                    'Landing / site vitrine',
                    'Design responsive',
                    'Mise en ligne',
                    '1 mois de support',
                ],
            ],
            [
                'slug' => 'business-app',
                'name' => 'Pack Business App',
                'price' => 1800000,
                'popular' => true,
                'features' => [
                    'Application web métier',
                    'UI / UX inclus',
                    'API & authentification',
                    'Formation + 2 mois support',
                ],
            ],
            [
                'slug' => 'premium-app',
                'name' => 'Pack Premium Multiplateforme',
                'price' => 3500000,
                'popular' => false,
                'features' => [
                    'Web + Mobile',
                    'Design system complet',
                    'Backend & API',
                    'Publication stores',
                    '3 mois de maintenance',
                ],
            ],
        ];
    }

    private function enrichOffreEmploi(array $o): array
    {
        $details = [
            'developpeur-web' => [
                'contrat' => 'CDD',
                'about' => 'Nous recherchons un développeur web passionné pour concevoir, développer et maintenir des applications web performantes et sécurisées.',
                'responsabilites' => [
                    'Développer des sites et applications web responsives.',
                    'Assurer la maintenance et l\'évolution des applications existantes.',
                    'Collaborer avec les designers et chefs de projet.',
                    'Optimiser les performances et la sécurité des applications.',
                    'Respecter les normes de qualité et les bonnes pratiques.',
                ],
                'profil' => [
                    'Bac+2/3 en informatique ou domaine similaire.',
                    'Expérience de 2 ans minimum à un poste similaire.',
                    'Rigueur, autonomie et sens du travail en équipe.',
                    'Bonne capacité d\'analyse et de résolution de problèmes.',
                ],
                'skills' => ['HTML', 'CSS', 'JavaScript', 'PHP', 'MySQL', 'Laravel', 'Bootstrap', 'Git', 'API REST', 'Responsive Design'],
                'duree' => '12 mois renouvelable',
                'disponibilite' => 'Immédiate',
            ],
            'responsable-marketing' => [
                'about' => 'Nous recherchons un responsable marketing pour piloter la stratégie de marque et les campagnes digitales.',
                'responsabilites' => [
                    'Définir et déployer la stratégie marketing.',
                    'Piloter les campagnes digitales et le contenu.',
                    'Analyser les performances et optimiser le ROI.',
                    'Coordonner les prestataires et l\'équipe communication.',
                ],
                'profil' => [
                    'Bac+3/5 en marketing ou communication.',
                    'Expérience de 3 ans minimum.',
                    'Maîtrise des outils digitaux et analytics.',
                    'Sens du leadership et créativité.',
                ],
                'duree' => 'CDI — durée indéterminée',
            ],
        ];

        $extra = $details[$o['slug']] ?? [];

        $defaults = [
            'about' => $o['description'],
            'responsabilites' => [
                'Assurer les missions principales du poste.',
                'Collaborer avec les équipes internes et partenaires.',
                'Respecter les délais et la qualité attendue.',
                'Participer à l\'amélioration continue des process.',
            ],
            'profil' => [
                'Formation adaptée au poste.',
                'Expérience pertinente souhaitée.',
                'Rigueur, autonomie et esprit d\'équipe.',
                'Bonne communication orale et écrite.',
            ],
            'duree' => ($o['contrat'] ?? '') === 'CDI'
                ? 'CDI — durée indéterminée'
                : (($o['contrat'] ?? '') === 'Freelance' ? 'Mission selon besoin' : '12 mois renouvelable'),
            'disponibilite' => 'Immédiate',
            'email' => 'recrutement@marinagradepsf.org',
        ];

        return array_merge($o, $defaults, $extra);
    }

    private function getOffresEmploi(): array
    {
        return [
            [
                'slug' => 'developpeur-web',
                'title' => 'Développeur Web',
                'categorie' => 'Informatique',
                'contrat' => 'CDI',
                'lieu' => 'Lomé, Togo',
                'description' => 'Concevez et développez des sites et applications web modernes pour nos clients et partenaires.',
                'skills' => ['HTML', 'CSS', 'JavaScript', 'PHP'],
                'icon' => 'fas fa-code',
                'nouveau' => true,
            ],
            [
                'slug' => 'responsable-marketing',
                'title' => 'Responsable Marketing',
                'categorie' => 'Marketing',
                'contrat' => 'CDI',
                'lieu' => 'Lomé, Togo',
                'description' => 'Pilotez la stratégie marketing, les campagnes digitales et la communication de marque.',
                'skills' => ['Marketing', 'SEO', 'Social Media', 'Analytics'],
                'icon' => 'fas fa-bullhorn',
                'nouveau' => true,
            ],
            [
                'slug' => 'charge-relation-client',
                'title' => 'Chargé(e) Relation Client',
                'categorie' => 'Commerce',
                'contrat' => 'CDD',
                'lieu' => 'Lomé, Togo',
                'description' => 'Accueillez, accompagnez et fidélisez les clients avec un service de qualité.',
                'skills' => ['Accueil', 'CRM', 'Communication', 'Négociation'],
                'icon' => 'fas fa-headset',
                'nouveau' => true,
            ],
            [
                'slug' => 'ingenieur-btp',
                'title' => 'Ingénieur BTP',
                'categorie' => 'BTP',
                'contrat' => 'CDI',
                'lieu' => 'Kara, Togo',
                'description' => 'Suivez les chantiers, gérez les équipes techniques et assurez la qualité des ouvrages.',
                'skills' => ['AutoCAD', 'Gestion chantier', 'Sécurité', 'Planning'],
                'icon' => 'fas fa-hard-hat',
                'nouveau' => false,
            ],
            [
                'slug' => 'monteur-video',
                'title' => 'Monteur Vidéo',
                'categorie' => 'Audiovisuel',
                'contrat' => 'CDD',
                'lieu' => 'Lomé, Togo',
                'description' => 'Montez des contenus vidéo pour le web, les réseaux sociaux et les campagnes.',
                'skills' => ['Premiere Pro', 'After Effects', 'Storytelling', 'Motion'],
                'icon' => 'fas fa-film',
                'nouveau' => true,
            ],
            [
                'slug' => 'secretaire-administrative',
                'title' => 'Secrétaire Administrative',
                'categorie' => 'Administration',
                'contrat' => 'CDI',
                'lieu' => 'Lomé, Togo',
                'description' => 'Assurez la gestion administrative, l\'accueil et le suivi des dossiers au quotidien.',
                'skills' => ['Word', 'Excel', 'Organisation', 'Accueil'],
                'icon' => 'fas fa-file-alt',
                'nouveau' => false,
            ],
            [
                'slug' => 'livreur',
                'title' => 'Livreur',
                'categorie' => 'Logistique',
                'contrat' => 'CDD',
                'lieu' => 'Lomé, Togo',
                'description' => 'Assurez la livraison rapide et soignée des colis sur Lomé et environs.',
                'skills' => ['Permis', 'Ponctualité', 'Orientation', 'Service client'],
                'icon' => 'fas fa-motorcycle',
                'nouveau' => true,
            ],
            [
                'slug' => 'graphiste-designer',
                'title' => 'Graphiste Designer',
                'categorie' => 'Design',
                'contrat' => 'CDD',
                'lieu' => 'Lomé, Togo',
                'description' => 'Créez des identités visuelles, supports print et contenus graphiques impactants.',
                'skills' => ['Photoshop', 'Illustrator', 'Canva', 'Branding'],
                'icon' => 'fas fa-paint-brush',
                'nouveau' => true,
            ],
            [
                'slug' => 'comptable',
                'title' => 'Comptable',
                'categorie' => 'Administration',
                'contrat' => 'CDI',
                'lieu' => 'Lomé, Togo',
                'description' => 'Tenez la comptabilité, préparez les états financiers et suivez la trésorerie.',
                'skills' => ['Comptabilité', 'Excel', 'Fiscalité', 'Sage'],
                'icon' => 'fas fa-calculator',
                'nouveau' => false,
            ],
            [
                'slug' => 'community-manager',
                'title' => 'Community Manager',
                'categorie' => 'Marketing',
                'contrat' => 'CDD',
                'lieu' => 'Lomé, Togo',
                'description' => 'Animez les réseaux sociaux, créez du contenu et engagez la communauté.',
                'skills' => ['Instagram', 'Facebook', 'Rédaction', 'Canva'],
                'icon' => 'fas fa-share-alt',
                'nouveau' => true,
            ],
            [
                'slug' => 'technicien-informatique',
                'title' => 'Technicien Informatique',
                'categorie' => 'Informatique',
                'contrat' => 'CDI',
                'lieu' => 'Sokodé, Togo',
                'description' => 'Installez, maintenez et dépannnez le matériel informatique et les réseaux.',
                'skills' => ['Hardware', 'Réseaux', 'Windows', 'Support'],
                'icon' => 'fas fa-laptop',
                'nouveau' => false,
            ],
            [
                'slug' => 'assistant-rh',
                'title' => 'Assistant(e) RH',
                'categorie' => 'Administration',
                'contrat' => 'CDD',
                'lieu' => 'Lomé, Togo',
                'description' => 'Participez au recrutement, à la gestion des dossiers et au suivi du personnel.',
                'skills' => ['Recrutement', 'Paie', 'Droit social', 'Organisation'],
                'icon' => 'fas fa-users',
                'nouveau' => true,
            ],
            [
                'slug' => 'commercial-terrain',
                'title' => 'Commercial Terrain',
                'categorie' => 'Commerce',
                'contrat' => 'CDI',
                'lieu' => 'Kpalimé, Togo',
                'description' => 'Développez le portefeuille clients et présentez nos offres sur le terrain.',
                'skills' => ['Vente', 'Prospection', 'Négociation', 'Reporting'],
                'icon' => 'fas fa-handshake',
                'nouveau' => false,
            ],
            [
                'slug' => 'chef-chantier',
                'title' => 'Chef de chantier',
                'categorie' => 'BTP',
                'contrat' => 'CDI',
                'lieu' => 'Lomé, Togo',
                'description' => 'Coordonnez les équipes sur site et veillez au respect des délais et normes.',
                'skills' => ['Management', 'Sécurité', 'Planning', 'BTP'],
                'icon' => 'fas fa-building',
                'nouveau' => true,
            ],
            [
                'slug' => 'redacteur-web',
                'title' => 'Rédacteur Web',
                'categorie' => 'Marketing',
                'contrat' => 'Freelance',
                'lieu' => 'Télétravail',
                'description' => 'Rédigez des articles, fiches et contenus SEO pour le web et les newsletters.',
                'skills' => ['Rédaction', 'SEO', 'WordPress', 'Français'],
                'icon' => 'fas fa-pen',
                'nouveau' => false,
            ],
            [
                'slug' => 'agent-logistique',
                'title' => 'Agent Logistique',
                'categorie' => 'Logistique',
                'contrat' => 'CDD',
                'lieu' => 'Lomé, Togo',
                'description' => 'Gérez les stocks, les préparations de commandes et les expéditions.',
                'skills' => ['Stock', 'Inventaire', 'Organisation', 'Manutention'],
                'icon' => 'fas fa-boxes',
                'nouveau' => true,
            ],
        ];
    }

    private function getFormations(): array
    {
        return [
            [
                'slug' => 'microsoft-word',
                'title' => 'Microsoft Word',
                'category' => 'Bureautique',
                'categoryClass' => 'bureautique',
                'icon' => 'fab fa-microsoft',
                'description' => 'Maîtrisez Word : mise en page, styles, tableaux et documents professionnels.',
                'duree' => '10 heures',
                'niveau' => 'Débutant',
            ],
            [
                'slug' => 'microsoft-excel',
                'title' => 'Microsoft Excel',
                'category' => 'Bureautique',
                'categoryClass' => 'bureautique',
                'icon' => 'fas fa-file-excel',
                'description' => 'Formules, tableaux croisés, graphiques et analyse de données avec Excel.',
                'duree' => '12 heures',
                'niveau' => 'Débutant',
                'prixEnLigne' => 20000,
                'prixPresentiel' => 25000,
            ],
            [
                'slug' => 'microsoft-powerpoint',
                'title' => 'Microsoft PowerPoint',
                'category' => 'Bureautique',
                'categoryClass' => 'bureautique',
                'icon' => 'fas fa-file-powerpoint',
                'description' => 'Créez des présentations claires, impactantes et professionnelles.',
                'duree' => '8 heures',
                'niveau' => 'Débutant',
            ],
            [
                'slug' => 'html-css-javascript',
                'title' => 'HTML, CSS, JavaScript',
                'category' => 'Développement Web',
                'categoryClass' => 'devweb',
                'icon' => 'fas fa-code',
                'description' => 'Les bases du web moderne pour créer vos premières pages interactives.',
                'duree' => '20 heures',
                'niveau' => 'Débutant',
            ],
            [
                'slug' => 'php-mysql',
                'title' => 'PHP & MySQL',
                'category' => 'Développement Web',
                'categoryClass' => 'devweb',
                'icon' => 'fab fa-php',
                'description' => 'Développez des sites dynamiques et gérez vos bases de données.',
                'duree' => '24 heures',
                'niveau' => 'Intermédiaire',
            ],
            [
                'slug' => 'adobe-photoshop',
                'title' => 'Adobe Photoshop',
                'category' => 'Design Graphique',
                'categoryClass' => 'design',
                'icon' => 'fas fa-paint-brush',
                'description' => 'Retouche photo, montage et création visuelle pour vos projets.',
                'duree' => '16 heures',
                'niveau' => 'Débutant',
            ],
            [
                'slug' => 'adobe-illustrator',
                'title' => 'Adobe Illustrator',
                'category' => 'Design Graphique',
                'categoryClass' => 'design',
                'icon' => 'fas fa-pen-nib',
                'description' => 'Logo, illustration vectorielle et identité visuelle professionnelle.',
                'duree' => '16 heures',
                'niveau' => 'Intermédiaire',
            ],
            [
                'slug' => 'windows-outils',
                'title' => 'Windows & Outils essentiels',
                'category' => 'Outils & Logiciels',
                'categoryClass' => 'outils',
                'icon' => 'fab fa-windows',
                'description' => 'Prenez en main Windows, fichiers, sécurité et outils du quotidien.',
                'duree' => '6 heures',
                'niveau' => 'Débutant',
            ],
            [
                'slug' => 'canva-pro',
                'title' => 'Canva Pro',
                'category' => 'Design Graphique',
                'categoryClass' => 'design',
                'icon' => 'fas fa-palette',
                'description' => 'Créez rapidement des visuels réseaux sociaux, flyers et présentations.',
                'duree' => '8 heures',
                'niveau' => 'Débutant',
            ],
            [
                'slug' => 'wordpress',
                'title' => 'WordPress',
                'category' => 'Développement Web',
                'categoryClass' => 'devweb',
                'icon' => 'fab fa-wordpress',
                'description' => 'Créez et gérez un site WordPress sans coder : thèmes, pages et plugins.',
                'duree' => '14 heures',
                'niveau' => 'Débutant',
            ],
            [
                'slug' => 'google-workspace',
                'title' => 'Google Workspace',
                'category' => 'Bureautique',
                'categoryClass' => 'bureautique',
                'icon' => 'fab fa-google',
                'description' => 'Docs, Sheets, Drive et collaboration en ligne en équipe.',
                'duree' => '10 heures',
                'niveau' => 'Débutant',
            ],
            [
                'slug' => 'reseaux-sociaux',
                'title' => 'Réseaux sociaux & contenu',
                'category' => 'Outils & Logiciels',
                'categoryClass' => 'outils',
                'icon' => 'fas fa-share-alt',
                'description' => 'Animez vos comptes, créez du contenu et mesurez vos résultats.',
                'duree' => '12 heures',
                'niveau' => 'Intermédiaire',
            ],
            [
                'slug' => 'excel-avance',
                'title' => 'Excel Avancé',
                'category' => 'Bureautique',
                'categoryClass' => 'bureautique',
                'icon' => 'fas fa-chart-line',
                'description' => 'Tableaux croisés dynamiques, Power Query et automatisations.',
                'duree' => '15 heures',
                'niveau' => 'Intermédiaire',
            ],
            [
                'slug' => 'figma-ui',
                'title' => 'Figma & UI Design',
                'category' => 'Design Graphique',
                'categoryClass' => 'design',
                'icon' => 'fab fa-figma',
                'description' => 'Concevez des interfaces modernes et des prototypes interactifs.',
                'duree' => '18 heures',
                'niveau' => 'Intermédiaire',
            ],
            [
                'slug' => 'laravel-bases',
                'title' => 'Laravel — Les bases',
                'category' => 'Développement Web',
                'categoryClass' => 'devweb',
                'icon' => 'fab fa-laravel',
                'description' => 'MVC, routes, Blade et bases de données avec le framework PHP.',
                'duree' => '22 heures',
                'niveau' => 'Intermédiaire',
            ],
            [
                'slug' => 'cybersecurite-intro',
                'title' => 'Introduction à la cybersécurité',
                'category' => 'Outils & Logiciels',
                'categoryClass' => 'outils',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Bonnes pratiques, mots de passe et protection des données personnelles.',
                'duree' => '8 heures',
                'niveau' => 'Débutant',
            ],
        ];
    }

    public function service(string $slug)
    {
        $services = $this->services();

        if (!isset($services[$slug])) {
            abort(404);
        }

        $service = $services[$slug];

        if ($slug === 'ong-environnement') {
            return view('services.ong-environnement', [
                'title' => $service['title'],
                'metaDescription' => $service['intro'],
                'bodyClass' => 'page-service page-ong-env',
            ]);
        }

        if ($slug === 'business-center') {
            return view('services.business-center', [
                'pays' => $this->getBusinessPays(),
                'title' => "Produits disponibles à l'international",
                'metaDescription' => 'Commandez des produits de qualité depuis plusieurs pays et faites-vous livrer au Togo.',
                'bodyClass' => 'page-service page-business-intl',
            ]);
        }

        if ($slug === 'immobilier-btp') {
            return view('services.immobilier-btp', [
                'solutions' => $this->getImmobilierSolutions(),
                'title' => 'Immobilier & BTP — Investir au Togo & en Côte d\'Ivoire',
                'metaDescription' => 'Maisons, terrains, construction et investissement au Togo et en Côte d\'Ivoire. Accompagnement local et suivi à distance.',
                'bodyClass' => 'page-service page-immobilier-btp',
            ]);
        }

        if ($slug === 'sous-traitance') {
            return view('services.sous-traitance', [
                'domaines' => $this->getSousTraitanceDomaines(),
                'etapes' => $this->getSousTraitanceEtapes(),
                'title' => 'Sous-traitance internationale',
                'metaDescription' => 'Connectons les entreprises en France aux talents togolais pour des missions à distance.',
                'bodyClass' => 'page-service page-sous-traitance',
            ]);
        }

        return view('services.show', [
            'service' => $service,
            'title' => $service['title'],
            'metaDescription' => $service['intro'],
            'bodyClass' => 'page-service',
        ]);
    }

    public function immobilierBiens(string $type)
    {
        $catalogues = $this->getImmobilierCatalogues();
        if (!isset($catalogues[$type])) {
            abort(404);
        }

        $meta = $catalogues[$type];
        $biens = array_values(array_filter(
            $this->getImmobilierBiens(),
            fn ($b) => ($b['type'] ?? '') === $type
        ));

        return view('services.immobilier-biens', [
            'type' => $type,
            'meta' => $meta,
            'biens' => $biens,
            'title' => $meta['title'],
            'metaDescription' => $meta['intro'],
            'bodyClass' => 'page-service page-immobilier-biens',
        ]);
    }

    public function immobilierBienShow(string $type, string $slug)
    {
        $catalogues = $this->getImmobilierCatalogues();
        if (!isset($catalogues[$type])) {
            abort(404);
        }

        $biens = array_values(array_filter(
            $this->getImmobilierBiens(),
            fn ($b) => ($b['type'] ?? '') === $type
        ));

        $bien = collect($biens)->firstWhere('slug', $slug);
        if (!$bien) {
            abort(404);
        }

        $bien = $this->enrichImmobilierBien($bien);
        $similaires = array_values(array_filter(
            $biens,
            fn ($b) => ($b['slug'] ?? '') !== $slug
        ));
        $similaires = array_slice($similaires, 0, 4);

        return view('services.immobilier-bien-show', [
            'type' => $type,
            'meta' => $catalogues[$type],
            'bien' => $bien,
            'similaires' => $similaires,
            'title' => $bien['title'],
            'metaDescription' => $bien['resume'] ?? $bien['title'],
            'bodyClass' => 'page-service page-immobilier-bien-show',
        ]);
    }

    private function enrichImmobilierBien(array $b): array
    {
        $type = $b['type'] ?? 'vente';
        $gallery = array_values(array_unique(array_filter([
            $b['image'] ?? null,
            'interieur-1.jpg',
            'interieur-2.jpg',
            'interieur-3.jpg',
            'interieur-4.jpg',
            'hero-villa.jpg',
            'maison-vente.jpg',
        ])));

        $statut = match ($type) {
            'location' => 'À louer',
            'terrains' => 'Terrain à vendre',
            'investir' => 'Opportunité d\'investissement',
            default => 'À vendre',
        };

        $typeBien = match ($type) {
            'terrains' => 'Terrain',
            'investir' => 'Investissement',
            default => (($b['chambres'] ?? 0) <= 1 && ($b['surface'] ?? 0) < 60) ? 'Studio' : 'Maison',
        };

        $prixLabel = match ($type) {
            'location' => number_format($b['prix'], 0, ',', ' ') . ' FCFA / mois',
            default => number_format($b['prix'], 0, ',', ' ') . ' FCFA',
        };

        $b['gallery'] = $gallery;
        $b['visite3d'] = in_array($type, ['vente', 'location'], true);
        $b['tour3d'] = [
            [
                'id' => 'salon',
                'title' => 'Salon',
                'image' => 'interieur-1.jpg',
                'desc' => 'La caméra entre dans le salon et se promène doucement dans l’espace de vie.',
            ],
            [
                'id' => 'cuisine',
                'title' => 'Cuisine',
                'image' => 'interieur-2.jpg',
                'desc' => 'Passage vers la cuisine équipée, avec un plan de caméra fluide.',
            ],
            [
                'id' => 'chambre',
                'title' => 'Chambre',
                'image' => 'interieur-3.jpg',
                'desc' => 'Découverte de la chambre : la visite avance comme un travelling vidéo.',
            ],
            [
                'id' => 'exterieur',
                'title' => 'Extérieur',
                'image' => 'interieur-4.jpg',
                'desc' => 'Sortie vers l’extérieur : terrasse et abords de la propriété.',
            ],
            [
                'id' => 'facade',
                'title' => 'Façade',
                'image' => $b['image'] ?? 'hero-villa.jpg',
                'desc' => 'Plan final sur la façade — fin de la balade virtuelle.',
            ],
        ];
        $b['statut'] = $statut;
        $b['typeBien'] = $typeBien;
        $b['prixLabel'] = $prixLabel;
        $b['surfaceTerrain'] = $b['surfaceTerrain'] ?? (int) round(($b['surface'] ?? 100) * ($type === 'terrains' ? 1 : 1.5));
        $b['salons'] = $b['salons'] ?? max(1, (int) floor(($b['chambres'] ?? 1) / 2));
        $b['cuisine'] = $b['cuisine'] ?? ($type === 'terrains' ? 0 : 1);
        $b['garage'] = $b['garage'] ?? ($type === 'terrains' ? '—' : '1 voiture');
        $b['toilettes'] = $b['toilettes'] ?? ($type === 'terrains' ? 0 : 1);
        $b['disponibilite'] = $b['disponibilite'] ?? 'Immédiate';
        $b['dureeMin'] = $b['dureeMin'] ?? ($type === 'location' ? '12 mois' : '—');
        $b['ref'] = $b['ref'] ?? 'MI-' . strtoupper(substr(md5($b['slug']), 0, 6));
        $b['vues'] = $b['vues'] ?? random_int(80, 420);
        $b['resume'] = $b['resume'] ?? sprintf(
            '%s située à %s, %s (%s). Bien spacieux, moderne et idéal pour un usage familial ou professionnel.',
            $b['title'],
            $b['quartier'],
            $b['ville'],
            $b['pays']
        );
        $b['description'] = $b['description'] ?? $this->getImmobilierBienDescription($b);
        $b['equipements'] = $b['equipements'] ?? $this->getImmobilierEquipements($type);
        $b['localisationPoints'] = $b['localisationPoints'] ?? [
            'Proche de la voie principale',
            'Écoles et commerces à proximité',
            'Quartier calme et sécurisé',
            'Accès facile en transport',
        ];

        return $b;
    }

    private function getImmobilierBienDescription(array $b): string
    {
        $type = $b['type'] ?? 'vente';
        if ($type === 'terrains') {
            return sprintf(
                "Terrain de %s m² situé à %s, %s (%s). Idéal pour un projet de construction ou d'investissement. Titre et démarches accompagnés par BUSINESS CENTER Immobilier & BTP.",
                number_format($b['surface'] ?? 0, 0, ',', ' '),
                $b['quartier'],
                $b['ville'],
                $b['pays']
            );
        }
        if ($type === 'investir') {
            return sprintf(
                "%s — opportunité d'investissement à %s, %s. Rendement estimé : %s. Accompagnement diaspora et suivi à distance inclus.",
                $b['title'],
                $b['quartier'],
                $b['ville'],
                $b['rendement'] ?? 'à définir'
            );
        }
        if ($type === 'location') {
            return sprintf(
                "Belle %s à louer à %s (%s). %s chambre(s), %s salle(s) de bain, surface %s m². Engagement minimum 12 mois. Caution : 2 mois. Avance : 1 mois. Frais d'agence : 1 mois.",
                strtolower($b['typeBien'] ?? 'maison'),
                $b['quartier'],
                $b['ville'],
                $b['chambres'] ?? 0,
                $b['sdb'] ?? 0,
                $b['surface'] ?? 0
            );
        }

        return sprintf(
            "%s à vendre à %s, %s. %s chambre(s), %s salle(s) de bain pour %s m². Terrasse, jardin et équipements modernes. Transactions sécurisées avec accompagnement BUSINESS CENTER Immobilier & BTP.",
            $b['title'],
            $b['quartier'],
            $b['ville'],
            $b['chambres'] ?? 0,
            $b['sdb'] ?? 0,
            $b['surface'] ?? 0
        );
    }

    private function getImmobilierEquipements(string $type): array
    {
        if ($type === 'terrains') {
            return ['Viabilisation possible', 'Accès route', 'Titre sécurisé', 'Bornage', 'Quartier en développement'];
        }

        return [
            'Climatisation',
            'Placards',
            'Forage',
            'Chauffe-eau',
            'Sécurité 24h/24',
            'Terrasse',
            'Jardin',
            'Parking',
            'Coin repas',
            'Buanderie',
        ];
    }

    public function sousTraitanceInscription()
    {
        return view('services.sous-traitance-inscription', [
            'title' => "Je m'inscris — Sous-traitance internationale",
            'metaDescription' => 'Inscrivez-vous comme prestataire pour des missions internationales à distance.',
            'bodyClass' => 'page-service page-sous-traitance-insc',
        ]);
    }

    public function sousTraitanceProjet()
    {
        return view('services.sous-traitance-projet', [
            'title' => 'Déposer un projet — Sous-traitance internationale',
            'metaDescription' => 'Déposez votre projet et connectez-vous à des talents togolais qualifiés.',
            'bodyClass' => 'page-service page-sous-traitance-insc',
        ]);
    }

    private function getSousTraitanceDomaines(): array
    {
        return [
            ['title' => 'Développement Web', 'text' => 'Sites, applications et maintenance technique.', 'icon' => 'fas fa-code', 'color' => '#1565C0'],
            ['title' => 'Design & Graphisme', 'text' => 'Identité visuelle, UI/UX et supports print.', 'icon' => 'fas fa-paint-brush', 'color' => '#C62828'],
            ['title' => 'Marketing Digital', 'text' => 'Réseaux sociaux, campagnes et contenu.', 'icon' => 'fas fa-bullhorn', 'color' => '#EF6C00'],
            ['title' => 'Rédaction & Traduction', 'text' => 'Contenus FR/EN et localisation.', 'icon' => 'fas fa-pen-fancy', 'color' => '#6A1B9A'],
            ['title' => 'Support & Assistance', 'text' => 'SAV, back-office et relation client.', 'icon' => 'fas fa-headset', 'color' => '#00838F'],
            ['title' => 'Gestion de projets', 'text' => 'Coordination, délais et reporting.', 'icon' => 'fas fa-tasks', 'color' => '#2E7D32'],
        ];
    }

    private function getSousTraitanceEtapes(): array
    {
        return [
            ['title' => 'Déposez votre projet', 'text' => 'Décrivez votre besoin en quelques minutes.'],
            ['title' => 'Recevez des propositions', 'text' => 'Des profils adaptés vous sont présentés.'],
            ['title' => 'Validez le prestataire', 'text' => 'Choisissez le talent qui vous convient.'],
            ['title' => 'Suivi de mission', 'text' => 'Accompagnement et contrôle qualité.'],
            ['title' => 'Livraison & paiement', 'text' => 'Réception sécurisée et règlement.'],
        ];
    }

    public function businessCommande(string $slug)
    {
        $produits = $this->getBusinessCatalogueProduits();
        $produit = collect($produits)->firstWhere('slug', $slug);
        if (!$produit) {
            abort(404);
        }

        if (empty($produit['specs'])) {
            $produit['specs'] = $this->getBusinessProduitSpecs($produit);
        }

        return view('services.business-commande', [
            'produit' => $produit,
            'paysList' => $this->getBusinessPays(),
            'title' => 'Commande — ' . $produit['title'],
            'metaDescription' => 'Détails de commande Business Center — ' . $produit['title'],
            'bodyClass' => 'page-service page-business-commande',
        ]);
    }

    private function getBusinessProduitSpecs(array $produit): array
    {
        $defaults = [
            'Informatique' => ['Processeur performant', 'Stockage SSD', 'Garantie constructeur', 'Livraison suivie'],
            'Téléphones & Tablettes' => ['Écran haute définition', 'Batterie longue durée', 'Double SIM possible', 'Accessoires inclus selon stock'],
            'Électronique' => ['Compatibilité universelle', 'Qualité audio/vidéo', 'Garantie standard', 'Notice en français'],
            'Électroménager' => ['Faible consommation', 'Installation simple', 'Pièces détachées disponibles', 'Garantie 12 mois'],
            'Énergie' => ['Rendement optimisé', 'Adapté au climat tropical', 'Sécurité renforcée', 'Support technique'],
            'Mobilier' => ['Matériaux résistants', 'Montage facilité', 'Design professionnel', 'Livraison soignée'],
            'Mode & Accessoires' => ['Finitions soignées', 'Taille / coloris au choix', 'Qualité premium', 'Emballage cadeau possible'],
            'Maison & Cuisine' => ['Usage quotidien', 'Qualité contrôlée', 'Origine vérifiée', 'Conservation optimale'],
            'Automobile' => ['Compatibilité standard', 'Pièces neuves', 'Contrôle qualité', 'Conseils d\'installation'],
            'Beauté' => ['Formule douce', 'Ingrédients naturels', 'Testé et contrôlé', 'Emballage sécurisé'],
        ];

        $cat = $produit['categorie'] ?? '';

        return $defaults[$cat] ?? [
            'Produit sélectionné avec soin',
            'Qualité contrôlée',
            'Livraison au Togo',
            'Support Business Center',
        ];
    }

    public function businessCatalogue(string $pays = 'togo')
    {
        $pays = strtolower($pays);
        $allowed = ['togo', 'chine', 'turquie', 'nigeria', 'tous'];
        if (!in_array($pays, $allowed, true)) {
            abort(404);
        }

        $produits = $this->getBusinessCatalogueProduits();
        $paysMeta = collect($this->getBusinessPays())->keyBy('slug');

        $paysLabel = $pays === 'tous'
            ? 'Tous les pays'
            : ($paysMeta[$pays]['pays'] ?? ucfirst($pays));

        $produitsFiltres = $pays === 'tous'
            ? $produits
            : array_values(array_filter($produits, fn ($p) => ($p['paysSlug'] ?? '') === $pays));

        $allCategories = array_keys($this->getBusinessCatalogueCategories($produits));
        $countsCatsFiltres = $this->getBusinessCatalogueCategories($produitsFiltres);
        $categories = [];
        foreach ($allCategories as $cat) {
            $categories[$cat] = $countsCatsFiltres[$cat] ?? 0;
        }

        return view('services.business-catalogue', [
            'paysActif' => $pays,
            'paysLabel' => $paysLabel,
            'paysList' => $this->getBusinessPays(),
            'produits' => $produits,
            'categories' => $categories,
            'countAllFiltered' => count($produitsFiltres),
            'countsPays' => $this->countProduitsByPays($produits),
            'title' => 'Catalogue — ' . $paysLabel,
            'metaDescription' => 'Catalogue de produits Business Center — origine ' . $paysLabel,
            'bodyClass' => 'page-service page-business-catalogue',
        ]);
    }

    private function getBusinessCatalogueCategories(array $produits): array
    {
        $cats = [];
        foreach ($produits as $p) {
            $c = $p['categorie'];
            $cats[$c] = ($cats[$c] ?? 0) + 1;
        }
        arsort($cats);

        return $cats;
    }

    private function countProduitsByPays(array $produits): array
    {
        $counts = ['tous' => count($produits)];
        foreach ($produits as $p) {
            $slug = $p['paysSlug'];
            $counts[$slug] = ($counts[$slug] ?? 0) + 1;
        }

        return $counts;
    }

    private function getBusinessCatalogueProduits(): array
    {
        return [
            ['slug' => 'pagne-wax', 'title' => 'Pagne Wax premium', 'categorie' => 'Mode & Accessoires', 'pays' => 'Togo', 'paysSlug' => 'togo', 'flagCode' => 'tg', 'prix' => 15000, 'icon' => 'fas fa-tshirt', 'popularite' => 80],
            ['slug' => 'huile-palme', 'title' => 'Huile de palme artisanale 5L', 'categorie' => 'Maison & Cuisine', 'pays' => 'Togo', 'paysSlug' => 'togo', 'flagCode' => 'tg', 'prix' => 8500, 'icon' => 'fas fa-wine-bottle', 'popularite' => 70],
            ['slug' => 'sac-osier', 'title' => 'Sac en osier artisanal', 'categorie' => 'Mode & Accessoires', 'pays' => 'Togo', 'paysSlug' => 'togo', 'flagCode' => 'tg', 'prix' => 12000, 'icon' => 'fas fa-shopping-bag', 'popularite' => 65],
            ['slug' => 'savon-karite', 'title' => 'Savon au karité bio', 'categorie' => 'Beauté', 'pays' => 'Togo', 'paysSlug' => 'togo', 'flagCode' => 'tg', 'prix' => 3500, 'icon' => 'fas fa-soap', 'popularite' => 75],
            ['slug' => 'table-basse-teck', 'title' => 'Table basse en teck', 'categorie' => 'Mobilier', 'pays' => 'Togo', 'paysSlug' => 'togo', 'flagCode' => 'tg', 'prix' => 95000, 'icon' => 'fas fa-table', 'popularite' => 60],
            ['slug' => 'ventilateur-local', 'title' => 'Ventilateur stand 16"', 'categorie' => 'Électroménager', 'pays' => 'Togo', 'paysSlug' => 'togo', 'flagCode' => 'tg', 'prix' => 45000, 'icon' => 'fas fa-fan', 'popularite' => 85],
            ['slug' => 'riz-local', 'title' => 'Riz local premium 25kg', 'categorie' => 'Maison & Cuisine', 'pays' => 'Togo', 'paysSlug' => 'togo', 'flagCode' => 'tg', 'prix' => 22000, 'icon' => 'fas fa-seedling', 'popularite' => 90],
            ['slug' => 'chaise-plastique', 'title' => 'Chaise plastique renforcée', 'categorie' => 'Mobilier', 'pays' => 'Togo', 'paysSlug' => 'togo', 'flagCode' => 'tg', 'prix' => 7500, 'icon' => 'fas fa-chair', 'popularite' => 72],
            ['slug' => 'laptop-hp', 'title' => 'Laptop HP 250 G9', 'categorie' => 'Informatique', 'pays' => 'Chine', 'paysSlug' => 'chine', 'flagCode' => 'cn', 'prix' => 350000, 'icon' => 'fas fa-laptop', 'popularite' => 95],
            ['slug' => 'iphone-14', 'title' => 'iPhone 14 Pro Max', 'categorie' => 'Téléphones & Tablettes', 'pays' => 'Chine', 'paysSlug' => 'chine', 'flagCode' => 'cn', 'prix' => 650000, 'icon' => 'fas fa-mobile-alt', 'popularite' => 98],
            ['slug' => 'casque-jbl', 'title' => 'Casque Bluetooth JBL', 'categorie' => 'Électronique', 'pays' => 'Chine', 'paysSlug' => 'chine', 'flagCode' => 'cn', 'prix' => 45000, 'icon' => 'fas fa-headphones', 'popularite' => 88],
            ['slug' => 'imprimante-epson', 'title' => 'Imprimante Epson EcoTank', 'categorie' => 'Informatique', 'pays' => 'Chine', 'paysSlug' => 'chine', 'flagCode' => 'cn', 'prix' => 185000, 'icon' => 'fas fa-print', 'popularite' => 82],
            ['slug' => 'panneau-solaire', 'title' => 'Panneau solaire 300W', 'categorie' => 'Énergie', 'pays' => 'Chine', 'paysSlug' => 'chine', 'flagCode' => 'cn', 'prix' => 120000, 'icon' => 'fas fa-solar-panel', 'popularite' => 86],
            ['slug' => 'tablette-lenovo', 'title' => 'Tablette Lenovo Tab M10', 'categorie' => 'Téléphones & Tablettes', 'pays' => 'Chine', 'paysSlug' => 'chine', 'flagCode' => 'cn', 'prix' => 145000, 'icon' => 'fas fa-tablet-alt', 'popularite' => 78],
            ['slug' => 'souris-logitech', 'title' => 'Souris Logitech MX Master', 'categorie' => 'Informatique', 'pays' => 'Chine', 'paysSlug' => 'chine', 'flagCode' => 'cn', 'prix' => 55000, 'icon' => 'fas fa-mouse', 'popularite' => 74],
            ['slug' => 'chargeur-rapide', 'title' => 'Chargeur rapide 65W GaN', 'categorie' => 'Électronique', 'pays' => 'Chine', 'paysSlug' => 'chine', 'flagCode' => 'cn', 'prix' => 18000, 'icon' => 'fas fa-plug', 'popularite' => 81],
            ['slug' => 'chaise-ergonomique', 'title' => 'Chaise de bureau ergonomique', 'categorie' => 'Mobilier', 'pays' => 'Turquie', 'paysSlug' => 'turquie', 'flagCode' => 'tr', 'prix' => 95000, 'icon' => 'fas fa-chair', 'popularite' => 84],
            ['slug' => 'climatiseur', 'title' => 'Climatiseur 1.5 CV', 'categorie' => 'Électroménager', 'pays' => 'Turquie', 'paysSlug' => 'turquie', 'flagCode' => 'tr', 'prix' => 280000, 'icon' => 'fas fa-snowflake', 'popularite' => 91],
            ['slug' => 'armoire-bureau', 'title' => 'Armoire de bureau métallique', 'categorie' => 'Mobilier', 'pays' => 'Turquie', 'paysSlug' => 'turquie', 'flagCode' => 'tr', 'prix' => 165000, 'icon' => 'fas fa-archive', 'popularite' => 70],
            ['slug' => 'sac-main-cuir', 'title' => 'Sac à main en cuir', 'categorie' => 'Mode & Accessoires', 'pays' => 'Turquie', 'paysSlug' => 'turquie', 'flagCode' => 'tr', 'prix' => 75000, 'icon' => 'fas fa-briefcase', 'popularite' => 77],
            ['slug' => 'machine-cafe', 'title' => 'Machine à café expresso', 'categorie' => 'Électroménager', 'pays' => 'Turquie', 'paysSlug' => 'turquie', 'flagCode' => 'tr', 'prix' => 110000, 'icon' => 'fas fa-mug-hot', 'popularite' => 79],
            ['slug' => 'tapis-salon', 'title' => 'Tapis salon design 2x3m', 'categorie' => 'Maison & Cuisine', 'pays' => 'Turquie', 'paysSlug' => 'turquie', 'flagCode' => 'tr', 'prix' => 89000, 'icon' => 'fas fa-border-all', 'popularite' => 68],
            ['slug' => 'groupe-electro', 'title' => 'Groupe électrogène 2.5KVA', 'categorie' => 'Énergie', 'pays' => 'Nigeria', 'paysSlug' => 'nigeria', 'flagCode' => 'ng', 'prix' => 320000, 'icon' => 'fas fa-bolt', 'popularite' => 93],
            ['slug' => 'onduleur-2kva', 'title' => 'Onduleur solaire 2KVA', 'categorie' => 'Énergie', 'pays' => 'Nigeria', 'paysSlug' => 'nigeria', 'flagCode' => 'ng', 'prix' => 275000, 'icon' => 'fas fa-car-battery', 'popularite' => 87],
            ['slug' => 'tv-smart-43', 'title' => 'TV Smart 43" LED', 'categorie' => 'Électronique', 'pays' => 'Nigeria', 'paysSlug' => 'nigeria', 'flagCode' => 'ng', 'prix' => 195000, 'icon' => 'fas fa-tv', 'popularite' => 89],
            ['slug' => 'refrigerateur', 'title' => 'Réfrigérateur 200L', 'categorie' => 'Électroménager', 'pays' => 'Nigeria', 'paysSlug' => 'nigeria', 'flagCode' => 'ng', 'prix' => 240000, 'icon' => 'fas fa-temperature-low', 'popularite' => 83],
            ['slug' => 'moto-piece', 'title' => 'Kit pièces moto standard', 'categorie' => 'Automobile', 'pays' => 'Nigeria', 'paysSlug' => 'nigeria', 'flagCode' => 'ng', 'prix' => 65000, 'icon' => 'fas fa-motorcycle', 'popularite' => 71],
            ['slug' => 'batterie-voiture', 'title' => 'Batterie voiture 75Ah', 'categorie' => 'Automobile', 'pays' => 'Nigeria', 'paysSlug' => 'nigeria', 'flagCode' => 'ng', 'prix' => 85000, 'icon' => 'fas fa-car-battery', 'popularite' => 76],
        ];
    }

    private function getBusinessEspaces(): array
    {
        return [
            [
                'slug' => 'salle-reunion',
                'title' => 'Salle de réunion',
                'capacite' => "Jusqu'à 10 personnes",
                'prix' => 15000,
                'prixLabel' => 'À partir de 15 000 FCFA / heure',
                'icon' => 'fas fa-users',
            ],
            [
                'slug' => 'bureau-prive',
                'title' => 'Bureau privé',
                'capacite' => '1 à 4 personnes',
                'prix' => 25000,
                'prixLabel' => 'À partir de 25 000 FCFA / heure',
                'icon' => 'fas fa-door-closed',
            ],
            [
                'slug' => 'coworking',
                'title' => 'Coworking',
                'capacite' => 'Accès flexible',
                'prix' => 5000,
                'prixLabel' => 'À partir de 5 000 FCFA / jour',
                'icon' => 'fas fa-laptop',
            ],
            [
                'slug' => 'salle-formation',
                'title' => 'Salle de formation',
                'capacite' => "Jusqu'à 20 personnes",
                'prix' => 20000,
                'prixLabel' => 'À partir de 20 000 FCFA / heure',
                'icon' => 'fas fa-chalkboard-teacher',
            ],
        ];
    }

    private function getBusinessPays(): array
    {
        return [
            [
                'slug' => 'togo',
                'pays' => 'Togo',
                'title' => 'Produits disponibles au Togo',
                'text' => 'Large choix de produits locaux et importés.',
                'image' => 'togo.jpg',
                'flag' => '🇹🇬',
                'flagCode' => 'tg',
                'tags' => [
                    ['icon' => 'fas fa-box', 'label' => 'Produits variés'],
                    ['icon' => 'fas fa-shipping-fast', 'label' => 'Livraison rapide'],
                ],
            ],
            [
                'slug' => 'nigeria',
                'pays' => 'Nigeria',
                'title' => 'Produits disponibles au Nigeria',
                'text' => 'Produits de qualité à des prix compétitifs.',
                'image' => 'nigeria.jpg',
                'flag' => '🇳🇬',
                'flagCode' => 'ng',
                'tags' => [
                    ['icon' => 'fas fa-box', 'label' => 'Produits variés'],
                    ['icon' => 'fas fa-tags', 'label' => 'Prix compétitifs'],
                ],
            ],
            [
                'slug' => 'chine',
                'pays' => 'Chine',
                'title' => 'Produits disponibles en Chine',
                'text' => 'Technologies, équipements et innovations à prix usine.',
                'image' => 'chine.jpg',
                'flag' => '🇨🇳',
                'flagCode' => 'cn',
                'tags' => [
                    ['icon' => 'fas fa-microchip', 'label' => 'Technologies'],
                    ['icon' => 'fas fa-industry', 'label' => 'Prix usine'],
                ],
            ],
            [
                'slug' => 'turquie',
                'pays' => 'Turquie',
                'title' => 'Produits disponibles en Turquie',
                'text' => 'Produits fiables, design et qualité européenne.',
                'image' => 'turquie.jpg',
                'flag' => '🇹🇷',
                'flagCode' => 'tr',
                'tags' => [
                    ['icon' => 'fas fa-check-circle', 'label' => 'Produits fiables'],
                    ['icon' => 'fas fa-award', 'label' => 'Qualité européenne'],
                ],
            ],
        ];
    }

    private function getImmobilierSolutions(): array
    {
        return [
            [
                'id' => 'maisons-vente',
                'title' => 'Maisons à vendre',
                'text' => 'Sélection de maisons et villas prêtes à habiter au Togo et en Côte d\'Ivoire.',
                'image' => 'maison-vente.jpg',
                'icon' => 'fas fa-home',
                'cta' => 'Voir les biens',
                'action' => 'link',
                'href' => url('/services/immobilier-btp/biens/vente'),
            ],
            [
                'id' => 'maisons-location',
                'title' => 'Maisons à louer',
                'text' => 'Locations résidentielles et professionnelles, avec accompagnement locatif.',
                'image' => 'maison-location.jpg',
                'icon' => 'fas fa-key',
                'cta' => 'Voir les locations',
                'action' => 'link',
                'href' => url('/services/immobilier-btp/biens/location'),
            ],
            [
                'id' => 'terrains',
                'title' => 'Achat de terrain',
                'text' => 'Terrains viabilisés ou à aménager, vérifiés et sécurisés.',
                'image' => 'terrain.jpg',
                'icon' => 'fas fa-map-marker-alt',
                'cta' => 'Voir les terrains',
                'action' => 'link',
                'href' => url('/services/immobilier-btp/biens/terrains'),
            ],
            [
                'id' => 'construire',
                'title' => 'Construire votre maison',
                'text' => 'Construction clé en main, suivi de chantier et contrôle qualité.',
                'image' => 'construire.jpg',
                'icon' => 'fas fa-hard-hat',
                'cta' => 'Demander un devis',
                'action' => 'devis',
            ],
            [
                'id' => 'plans',
                'title' => 'Plans & Conception',
                'text' => 'Plans architecturaux, études techniques et conception sur mesure.',
                'image' => 'plans.jpg',
                'icon' => 'fas fa-pencil-ruler',
                'cta' => 'Demander un devis',
                'action' => 'devis',
            ],
            [
                'id' => 'investir',
                'title' => 'Investir au Togo & en Côte d\'Ivoire',
                'text' => 'Opportunités d\'investissement pour la diaspora et les partenaires.',
                'image' => 'investir.jpg',
                'icon' => 'fas fa-chart-line',
                'cta' => 'Découvrir les opportunités',
                'action' => 'link',
                'href' => url('/services/immobilier-btp/biens/investir'),
            ],
        ];
    }

    private function getImmobilierCatalogues(): array
    {
        return [
            'vente' => [
                'title' => 'Maisons à vendre',
                'intro' => 'Sélection de maisons et villas disponibles à l’achat au Togo et en Côte d’Ivoire.',
                'label' => 'maisons',
                'priceSuffix' => 'FCFA',
                'stats' => [
                    ['value' => '+350', 'label' => 'Maisons disponibles'],
                    ['value' => '2 Pays', 'label' => 'Togo & Côte d’Ivoire'],
                    ['value' => 'Sécurité', 'label' => 'Transactions sécurisées'],
                    ['value' => 'Accompagnement', 'label' => 'Suivi de A à Z'],
                ],
            ],
            'location' => [
                'title' => 'Maisons à louer',
                'intro' => 'Locations résidentielles et professionnelles au Togo et en Côte d’Ivoire.',
                'label' => 'locations',
                'priceSuffix' => 'FCFA / mois',
                'stats' => [
                    ['value' => '+180', 'label' => 'Biens à louer'],
                    ['value' => '2 Pays', 'label' => 'Togo & Côte d’Ivoire'],
                    ['value' => 'Sécurité', 'label' => 'Contrats sécurisés'],
                    ['value' => 'Accompagnement', 'label' => 'Gestion locative'],
                ],
            ],
            'terrains' => [
                'title' => 'Achat de terrain',
                'intro' => 'Terrains viabilisés ou à aménager, vérifiés et sécurisés pour votre projet.',
                'label' => 'terrains',
                'priceSuffix' => 'FCFA',
                'stats' => [
                    ['value' => '+120', 'label' => 'Terrains disponibles'],
                    ['value' => '2 Pays', 'label' => 'Togo & Côte d’Ivoire'],
                    ['value' => 'Sécurité', 'label' => 'Titres vérifiés'],
                    ['value' => 'Accompagnement', 'label' => 'Démarches assistées'],
                ],
            ],
            'investir' => [
                'title' => 'Investir au Togo & en Côte d’Ivoire',
                'intro' => 'Opportunités d’investissement immobilières pour la diaspora et les partenaires.',
                'label' => 'opportunités',
                'priceSuffix' => 'FCFA',
                'stats' => [
                    ['value' => '+90', 'label' => 'Opportunités'],
                    ['value' => '2 Pays', 'label' => 'Togo & Côte d’Ivoire'],
                    ['value' => 'Rendement', 'label' => 'Potentiel attractif'],
                    ['value' => 'Diaspora', 'label' => 'Suivi à distance'],
                ],
            ],
        ];
    }

    private function getImmobilierBiens(): array
    {
        $imgs = [
            'maison-vente.jpg',
            'maison-location.jpg',
            'hero-villa.jpg',
            'investir.jpg',
            'construire.jpg',
            'terrain.jpg',
        ];

        $vente = [
            ['slug' => 'villa-piscine-agoe', 'title' => 'Villa moderne avec piscine', 'ville' => 'Lomé', 'quartier' => 'Agoè', 'pays' => 'Togo', 'chambres' => 4, 'sdb' => 3, 'surface' => 220, 'prix' => 65000000, 'badge' => 'nouveau'],
            ['slug' => 'duplex-standing-tokoin', 'title' => 'Maison duplex de standing', 'ville' => 'Lomé', 'quartier' => 'Tokoin', 'pays' => 'Togo', 'chambres' => 5, 'sdb' => 4, 'surface' => 280, 'prix' => 85000000, 'badge' => 'une', 'prixAvant' => 95000000],
            ['slug' => 'villa-cocody', 'title' => 'Villa 5 pièces Cocody', 'ville' => 'Abidjan', 'quartier' => 'Cocody', 'pays' => 'Côte d\'Ivoire', 'chambres' => 5, 'sdb' => 3, 'surface' => 310, 'prix' => 120000000, 'badge' => ''],
            ['slug' => 'maison-basse-kaladjen', 'title' => 'Maison basse neuve', 'ville' => 'Lomé', 'quartier' => 'Adidogomé', 'pays' => 'Togo', 'chambres' => 3, 'sdb' => 2, 'surface' => 150, 'prix' => 38000000, 'badge' => 'promo', 'prixAvant' => 42000000],
            ['slug' => 'villa-riviera', 'title' => 'Villa Riviera golf', 'ville' => 'Abidjan', 'quartier' => 'Riviera', 'pays' => 'Côte d\'Ivoire', 'chambres' => 6, 'sdb' => 4, 'surface' => 420, 'prix' => 185000000, 'badge' => 'une'],
            ['slug' => 'maison-bdk', 'title' => 'Maison familiale Bè', 'ville' => 'Lomé', 'quartier' => 'Bè', 'pays' => 'Togo', 'chambres' => 4, 'sdb' => 2, 'surface' => 190, 'prix' => 45000000, 'badge' => ''],
            ['slug' => 'appart-yopougon', 'title' => 'Appartement standing', 'ville' => 'Abidjan', 'quartier' => 'Yopougon', 'pays' => 'Côte d\'Ivoire', 'chambres' => 3, 'sdb' => 2, 'surface' => 120, 'prix' => 55000000, 'badge' => 'nouveau'],
            ['slug' => 'villa-kpogan', 'title' => 'Villa bord de mer', 'ville' => 'Lomé', 'quartier' => 'Kpogan', 'pays' => 'Togo', 'chambres' => 4, 'sdb' => 3, 'surface' => 250, 'prix' => 98000000, 'badge' => ''],
            ['slug' => 'maison-plateau', 'title' => 'Maison Plateau rénovée', 'ville' => 'Abidjan', 'quartier' => 'Plateau', 'pays' => 'Côte d\'Ivoire', 'chambres' => 4, 'sdb' => 3, 'surface' => 200, 'prix' => 110000000, 'badge' => 'promo', 'prixAvant' => 125000000],
            ['slug' => 'villa-nyekonakpoe', 'title' => 'Villa Nukafu moderne', 'ville' => 'Lomé', 'quartier' => 'Nukafu', 'pays' => 'Togo', 'chambres' => 5, 'sdb' => 3, 'surface' => 300, 'prix' => 78000000, 'badge' => 'nouveau'],
            ['slug' => 'duplex-marcory', 'title' => 'Duplex Marcory résidentiel', 'ville' => 'Abidjan', 'quartier' => 'Marcory', 'pays' => 'Côte d\'Ivoire', 'chambres' => 4, 'sdb' => 3, 'surface' => 240, 'prix' => 95000000, 'badge' => ''],
            ['slug' => 'maison-agoe-2', 'title' => 'Maison 3 chambres Agoè', 'ville' => 'Lomé', 'quartier' => 'Agoè', 'pays' => 'Togo', 'chambres' => 3, 'sdb' => 2, 'surface' => 140, 'prix' => 32000000, 'badge' => ''],
        ];

        $location = [
            ['slug' => 'loc-villa-agoe', 'title' => 'Villa meublée Agoè', 'ville' => 'Lomé', 'quartier' => 'Agoè', 'pays' => 'Togo', 'chambres' => 4, 'sdb' => 3, 'surface' => 200, 'prix' => 450000, 'badge' => 'nouveau'],
            ['slug' => 'loc-appart-cocody', 'title' => 'Appartement Cocody Angré', 'ville' => 'Abidjan', 'quartier' => 'Cocody', 'pays' => 'Côte d\'Ivoire', 'chambres' => 3, 'sdb' => 2, 'surface' => 110, 'prix' => 350000, 'badge' => 'une'],
            ['slug' => 'loc-maison-tokoin', 'title' => 'Maison Tokoin Habitat', 'ville' => 'Lomé', 'quartier' => 'Tokoin', 'pays' => 'Togo', 'chambres' => 3, 'sdb' => 2, 'surface' => 130, 'prix' => 220000, 'badge' => ''],
            ['slug' => 'loc-studio-plateau', 'title' => 'Studio Plateau affaires', 'ville' => 'Abidjan', 'quartier' => 'Plateau', 'pays' => 'Côte d\'Ivoire', 'chambres' => 1, 'sdb' => 1, 'surface' => 45, 'prix' => 180000, 'badge' => 'promo', 'prixAvant' => 220000],
            ['slug' => 'loc-duplex-riviera', 'title' => 'Duplex Riviera 4', 'ville' => 'Abidjan', 'quartier' => 'Riviera', 'pays' => 'Côte d\'Ivoire', 'chambres' => 4, 'sdb' => 3, 'surface' => 210, 'prix' => 650000, 'badge' => ''],
            ['slug' => 'loc-maison-be', 'title' => 'Maison Bè calme', 'ville' => 'Lomé', 'quartier' => 'Bè', 'pays' => 'Togo', 'chambres' => 2, 'sdb' => 1, 'surface' => 90, 'prix' => 150000, 'badge' => ''],
            ['slug' => 'loc-villa-nukafu', 'title' => 'Villa Nukafu jardin', 'ville' => 'Lomé', 'quartier' => 'Nukafu', 'pays' => 'Togo', 'chambres' => 5, 'sdb' => 3, 'surface' => 260, 'prix' => 700000, 'badge' => 'une'],
            ['slug' => 'loc-appart-yop', 'title' => 'Appartement Yopougon', 'ville' => 'Abidjan', 'quartier' => 'Yopougon', 'pays' => 'Côte d\'Ivoire', 'chambres' => 2, 'sdb' => 1, 'surface' => 75, 'prix' => 160000, 'badge' => 'nouveau'],
            ['slug' => 'loc-maison-adido', 'title' => 'Maison Adidogomé', 'ville' => 'Lomé', 'quartier' => 'Adidogomé', 'pays' => 'Togo', 'chambres' => 3, 'sdb' => 2, 'surface' => 125, 'prix' => 200000, 'badge' => ''],
            ['slug' => 'loc-villa-marcory', 'title' => 'Villa Marcory piscine', 'ville' => 'Abidjan', 'quartier' => 'Marcory', 'pays' => 'Côte d\'Ivoire', 'chambres' => 4, 'sdb' => 3, 'surface' => 230, 'prix' => 800000, 'badge' => 'promo', 'prixAvant' => 900000],
            ['slug' => 'loc-appart-kaladjen', 'title' => 'Appart F&B Lomé', 'ville' => 'Lomé', 'quartier' => 'Hédzranawoé', 'pays' => 'Togo', 'chambres' => 2, 'sdb' => 2, 'surface' => 85, 'prix' => 175000, 'badge' => ''],
            ['slug' => 'loc-maison-bingerville', 'title' => 'Maison Bingerville', 'ville' => 'Abidjan', 'quartier' => 'Bingerville', 'pays' => 'Côte d\'Ivoire', 'chambres' => 3, 'sdb' => 2, 'surface' => 140, 'prix' => 280000, 'badge' => ''],
        ];

        $terrains = [
            ['slug' => 'ter-agoe-500', 'title' => 'Terrain viabilisé 500 m²', 'ville' => 'Lomé', 'quartier' => 'Agoè', 'pays' => 'Togo', 'chambres' => 0, 'sdb' => 0, 'surface' => 500, 'prix' => 18000000, 'badge' => 'nouveau'],
            ['slug' => 'ter-cocody-800', 'title' => 'Parcelle Cocody 800 m²', 'ville' => 'Abidjan', 'quartier' => 'Cocody', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 0, 'surface' => 800, 'prix' => 65000000, 'badge' => 'une'],
            ['slug' => 'ter-tsevie', 'title' => 'Terrain Tsévié 1000 m²', 'ville' => 'Tsévié', 'quartier' => 'Centre', 'pays' => 'Togo', 'chambres' => 0, 'sdb' => 0, 'surface' => 1000, 'prix' => 12000000, 'badge' => 'promo', 'prixAvant' => 15000000],
            ['slug' => 'ter-bingerville', 'title' => 'Terrain Bingerville', 'ville' => 'Abidjan', 'quartier' => 'Bingerville', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 0, 'surface' => 600, 'prix' => 28000000, 'badge' => ''],
            ['slug' => 'ter-kpogan', 'title' => 'Terrain bord de mer', 'ville' => 'Lomé', 'quartier' => 'Kpogan', 'pays' => 'Togo', 'chambres' => 0, 'sdb' => 0, 'surface' => 750, 'prix' => 45000000, 'badge' => 'une'],
            ['slug' => 'ter-songon', 'title' => 'Parcelle Songon 1200 m²', 'ville' => 'Abidjan', 'quartier' => 'Songon', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 0, 'surface' => 1200, 'prix' => 32000000, 'badge' => ''],
            ['slug' => 'ter-adidogome', 'title' => 'Terrain Adidogomé', 'ville' => 'Lomé', 'quartier' => 'Adidogomé', 'pays' => 'Togo', 'chambres' => 0, 'sdb' => 0, 'surface' => 400, 'prix' => 15000000, 'badge' => 'nouveau'],
            ['slug' => 'ter-yopougon', 'title' => 'Terrain Yopougon', 'ville' => 'Abidjan', 'quartier' => 'Yopougon', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 0, 'surface' => 450, 'prix' => 22000000, 'badge' => ''],
            ['slug' => 'ter-vogan', 'title' => 'Terrain Vogan 2000 m²', 'ville' => 'Vogan', 'quartier' => 'Périphérie', 'pays' => 'Togo', 'chambres' => 0, 'sdb' => 0, 'surface' => 2000, 'prix' => 9000000, 'badge' => 'promo', 'prixAvant' => 11000000],
            ['slug' => 'ter-grand-bassam', 'title' => 'Terrain Grand-Bassam', 'ville' => 'Grand-Bassam', 'quartier' => 'Zone touristique', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 0, 'surface' => 900, 'prix' => 48000000, 'badge' => ''],
            ['slug' => 'ter-davie', 'title' => 'Terrain Davie lotissement', 'ville' => 'Davie', 'quartier' => 'Lotissement', 'pays' => 'Togo', 'chambres' => 0, 'sdb' => 0, 'surface' => 550, 'prix' => 10000000, 'badge' => ''],
            ['slug' => 'ter-anyama', 'title' => 'Parcelle Anyama', 'ville' => 'Abidjan', 'quartier' => 'Anyama', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 0, 'surface' => 700, 'prix' => 26000000, 'badge' => 'nouveau'],
        ];

        $investir = [
            ['slug' => 'inv-residence-locative', 'title' => 'Résidence locative 8 appartements', 'ville' => 'Lomé', 'quartier' => 'Nukafu', 'pays' => 'Togo', 'chambres' => 16, 'sdb' => 8, 'surface' => 900, 'prix' => 250000000, 'badge' => 'une', 'rendement' => '9 %'],
            ['slug' => 'inv-immeuble-cocody', 'title' => 'Immeuble de rapport Cocody', 'ville' => 'Abidjan', 'quartier' => 'Cocody', 'pays' => 'Côte d\'Ivoire', 'chambres' => 12, 'sdb' => 12, 'surface' => 1100, 'prix' => 420000000, 'badge' => 'nouveau', 'rendement' => '8 %'],
            ['slug' => 'inv-lotissement-tsevie', 'title' => 'Lotissement Tsévié 20 parcelles', 'ville' => 'Tsévié', 'quartier' => 'Extension', 'pays' => 'Togo', 'chambres' => 0, 'sdb' => 0, 'surface' => 10000, 'prix' => 180000000, 'badge' => 'promo', 'prixAvant' => 200000000, 'rendement' => '12 %'],
            ['slug' => 'inv-villa-touristique', 'title' => 'Villa touristique meublée', 'ville' => 'Grand-Bassam', 'quartier' => 'Bord de mer', 'pays' => 'Côte d\'Ivoire', 'chambres' => 5, 'sdb' => 4, 'surface' => 320, 'prix' => 160000000, 'badge' => '', 'rendement' => '10 %'],
            ['slug' => 'inv-bureaux-plateau', 'title' => 'Bureaux Plateau', 'ville' => 'Abidjan', 'quartier' => 'Plateau', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 4, 'surface' => 450, 'prix' => 380000000, 'badge' => 'une', 'rendement' => '7 %'],
            ['slug' => 'inv-mini-cite', 'title' => 'Mini-cité étudiante', 'ville' => 'Lomé', 'quartier' => 'Agoè', 'pays' => 'Togo', 'chambres' => 24, 'sdb' => 12, 'surface' => 800, 'prix' => 210000000, 'badge' => 'nouveau', 'rendement' => '11 %'],
            ['slug' => 'inv-terrain-promotion', 'title' => 'Terrain promotion immobilière', 'ville' => 'Abidjan', 'quartier' => 'Songon', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 0, 'surface' => 5000, 'prix' => 95000000, 'badge' => '', 'rendement' => '14 %'],
            ['slug' => 'inv-duplex-location', 'title' => 'Pack 3 duplex locatifs', 'ville' => 'Lomé', 'quartier' => 'Adidogomé', 'pays' => 'Togo', 'chambres' => 12, 'sdb' => 9, 'surface' => 540, 'prix' => 145000000, 'badge' => '', 'rendement' => '9 %'],
            ['slug' => 'inv-commerce-marcory', 'title' => 'Local commercial Marcory', 'ville' => 'Abidjan', 'quartier' => 'Marcory', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 2, 'surface' => 180, 'prix' => 130000000, 'badge' => 'promo', 'prixAvant' => 145000000, 'rendement' => '8 %'],
            ['slug' => 'inv-residence-services', 'title' => 'Résidence services Lomé', 'ville' => 'Lomé', 'quartier' => 'Tokoin', 'pays' => 'Togo', 'chambres' => 20, 'sdb' => 20, 'surface' => 1000, 'prix' => 320000000, 'badge' => 'une', 'rendement' => '8.5 %'],
            ['slug' => 'inv-foncier-strategique', 'title' => 'Foncier stratégique périphérie', 'ville' => 'Abidjan', 'quartier' => 'Anyama', 'pays' => 'Côte d\'Ivoire', 'chambres' => 0, 'sdb' => 0, 'surface' => 3500, 'prix' => 75000000, 'badge' => 'nouveau', 'rendement' => '13 %'],
            ['slug' => 'inv-coliving', 'title' => 'Projet coliving diaspora', 'ville' => 'Lomé', 'quartier' => 'Nukafu', 'pays' => 'Togo', 'chambres' => 18, 'sdb' => 10, 'surface' => 700, 'prix' => 195000000, 'badge' => '', 'rendement' => '10 %'],
        ];

        $out = [];
        foreach (['vente' => $vente, 'location' => $location, 'terrains' => $terrains, 'investir' => $investir] as $type => $list) {
            foreach ($list as $i => $b) {
                $b['type'] = $type;
                $b['image'] = $imgs[$i % count($imgs)];
                if ($type === 'terrains') {
                    $b['image'] = 'terrain.jpg';
                }
                $out[] = $b;
            }
        }

        return $out;
    }

    private function services(): array
    {
        return [
            'ong-environnement' => [
                'title' => 'ONG & Environnement',
                'intro' => "Agissons ensemble pour la planète. Reboisement, sensibilisation écologique et projets verts pour un avenir durable au Togo et ailleurs.",
                'cta' => 'Découvrir nos actions',
                'ctaUrl' => '/projetsenvironnement',
                'image' => 'ong-environnement.jpg',
                'color' => '#2E7D32',
                'colorSoft' => '#E8F5E9',
                'domainsTitle' => "Nos domaines d'action",
                'domains' => [
                    ['icon' => 'fas fa-tree', 'title' => 'Reboisement', 'text' => 'Plantation communautaire et restauration des écosystèmes.'],
                    ['icon' => 'fas fa-bullhorn', 'title' => 'Sensibilisation', 'text' => 'Éducation environnementale auprès des communautés.'],
                    ['icon' => 'fas fa-leaf', 'title' => 'Projets verts', 'text' => 'Initiatives durables à impact mesurable.'],
                ],
                'bannerTitle' => 'Notre impact',
                'bannerItems' => [
                    ['icon' => 'fas fa-seedling', 'value' => '12 500', 'label' => 'Arbres plantés'],
                    ['icon' => 'fas fa-users', 'value' => '28', 'label' => 'Communautés accompagnées'],
                    ['icon' => 'fas fa-folder-open', 'value' => '45', 'label' => 'Projets réalisés'],
                    ['icon' => 'fas fa-handshake', 'value' => '8', 'label' => 'Partenaires engagés'],
                ],
            ],
            'formation-informatique' => [
                'title' => 'Formation Informatique',
                'intro' => "Développez vos compétences numériques avec des formations pratiques, accessibles et orientées emploi : bureautique, web, design et outils pro.",
                'cta' => 'Voir les formations',
                'ctaUrl' => '/formations',
                'image' => 'formation-informatique.jpg',
                'color' => '#1565C0',
                'colorSoft' => '#E3F2FD',
                'domainsTitle' => 'Nos formations',
                'domains' => [
                    ['icon' => 'fas fa-file-alt', 'title' => 'Bureautique', 'text' => 'Word, Excel, PowerPoint et outils de productivité.'],
                    ['icon' => 'fas fa-code', 'title' => 'Développement Web', 'text' => 'HTML, CSS, JavaScript et bases du web moderne.'],
                    ['icon' => 'fas fa-paint-brush', 'title' => 'Design Graphique', 'text' => 'Création visuelle et outils de design.'],
                    ['icon' => 'fas fa-laptop', 'title' => 'Outils & Logiciels', 'text' => 'Maîtrise des logiciels professionnels.'],
                ],
                'bannerTitle' => 'Pourquoi nous choisir ?',
                'bannerItems' => [
                    ['icon' => 'fas fa-hands-helping', 'value' => '', 'label' => 'Formations pratiques'],
                    ['icon' => 'fas fa-chalkboard-teacher', 'value' => '', 'label' => 'Formateurs qualifiés'],
                    ['icon' => 'fas fa-certificate', 'value' => '', 'label' => 'Certificats délivrés'],
                    ['icon' => 'fas fa-user-check', 'value' => '', 'label' => 'Accompagnement personnalisé'],
                ],
            ],
            'trouver-un-emploi' => [
                'title' => 'Trouver un emploi',
                'intro' => "Votre carrière commence ici. Offres d'emploi, coaching CV/entretiens et mise en relation avec des employeurs pour une insertion durable.",
                'cta' => 'Voir les offres',
                'ctaUrl' => '/offreemplois',
                'image' => 'trouver-un-emploi.jpg',
                'color' => '#EF6C00',
                'colorSoft' => '#FFF3E0',
                'domainsTitle' => 'Nos services',
                'domains' => [
                    ['icon' => 'fas fa-briefcase', 'title' => "Offres d'emploi", 'text' => 'Opportunités locales et internationales.'],
                    ['icon' => 'fas fa-user-tie', 'title' => 'Accompagnement', 'text' => 'CV, lettres et préparation aux entretiens.'],
                    ['icon' => 'fas fa-people-arrows', 'title' => 'Mise en relation', 'text' => 'Connexion talents & employeurs.'],
                ],
                'bannerTitle' => 'Notre engagement',
                'bannerItems' => [
                    ['icon' => 'fas fa-route', 'value' => '', 'label' => 'Insertion durable'],
                    ['icon' => 'fas fa-balance-scale', 'value' => '', 'label' => 'Équité et transparence'],
                    ['icon' => 'fas fa-heart', 'value' => '', 'label' => 'Soutien personnalisé'],
                    ['icon' => 'fas fa-globe-africa', 'value' => '', 'label' => 'Opportunités pour tous'],
                ],
            ],
            'communication-digitale' => [
                'title' => 'Communication Digitale',
                'intro' => "Boostez votre présence en ligne. Stratégie digitale, réseaux sociaux, contenu et publicité pour faire grandir votre marque.",
                'cta' => 'Découvrir nos services',
                'ctaUrl' => '/digitalservice',
                'image' => 'communication-digitale.jpg',
                'color' => '#7B1FA2',
                'colorSoft' => '#F3E5F5',
                'domainsTitle' => 'Nos services',
                'domains' => [
                    ['icon' => 'fas fa-lightbulb', 'title' => 'Stratégie Digitale', 'text' => 'Plan d’action adapté à vos objectifs.'],
                    ['icon' => 'fas fa-share-alt', 'title' => 'Réseaux Sociaux', 'text' => 'Animation et communauté engagée.'],
                    ['icon' => 'fas fa-pen-fancy', 'title' => 'Création de Contenu', 'text' => 'Textes, visuels et storytelling.'],
                    ['icon' => 'fas fa-ad', 'title' => 'Publicité en Ligne', 'text' => 'Campagnes ciblées et performantes.'],
                ],
                'bannerTitle' => 'Pourquoi nous choisir ?',
                'bannerItems' => [
                    ['icon' => 'fas fa-palette', 'value' => '', 'label' => 'Équipe créative'],
                    ['icon' => 'fas fa-chart-line', 'value' => '', 'label' => 'Résultats mesurables'],
                    ['icon' => 'fas fa-cogs', 'value' => '', 'label' => 'Solutions sur mesure'],
                    ['icon' => 'fas fa-headset', 'value' => '', 'label' => 'Accompagnement'],
                ],
            ],
            'business-center' => [
                'title' => 'Business Center',
                'intro' => "Produits de qualité disponibles à l'international et au Togo. Approvisionnement, importation et livraison rapide.",
                'cta' => 'Voir les produits',
                'image' => 'business-center.jpg',
                'color' => '#00897B',
                'colorSoft' => '#E0F2F1',
                'domainsTitle' => 'Nos offres',
                'domains' => [
                    ['icon' => 'fas fa-globe', 'title' => 'International', 'text' => 'Produits depuis plusieurs pays partenaires.'],
                    ['icon' => 'fas fa-box', 'title' => 'Approvisionnement', 'text' => 'Importation et sourcing de qualité.'],
                    ['icon' => 'fas fa-truck', 'title' => 'Livraison', 'text' => 'Livraison rapide partout au Togo.'],
                ],
                'bannerTitle' => 'Avantages',
                'bannerItems' => [
                    ['icon' => 'fas fa-shield-alt', 'value' => '', 'label' => 'Paiement sécurisé'],
                    ['icon' => 'fas fa-shipping-fast', 'value' => '', 'label' => 'Livraison rapide'],
                    ['icon' => 'fas fa-box', 'value' => '', 'label' => 'Suivi de commande'],
                    ['icon' => 'fas fa-headset', 'value' => '', 'label' => 'Support dédié'],
                ],
            ],
            'immobilier-btp' => [
                'title' => 'Immobilier & BTP',
                'intro' => "Construisons l'avenir ensemble. Conseil immobilier, suivi de chantiers et opportunités pour des projets durables et maîtrisés.",
                'cta' => 'Découvrir nos projets',
                'image' => 'immobilier-btp.jpg',
                'color' => '#C62828',
                'colorSoft' => '#FFEBEE',
                'domainsTitle' => 'Nos services',
                'domains' => [
                    ['icon' => 'fas fa-home', 'title' => 'Conseil Immobilier', 'text' => 'Accompagnement à l’achat, vente et investissement.'],
                    ['icon' => 'fas fa-hard-hat', 'title' => 'Suivi de Chantiers', 'text' => 'Contrôle qualité et respect des délais.'],
                    ['icon' => 'fas fa-key', 'title' => 'Opportunités', 'text' => 'Projets et biens sélectionnés.'],
                ],
                'bannerTitle' => 'Nos engagements',
                'bannerItems' => [
                    ['icon' => 'fas fa-award', 'value' => '', 'label' => 'Qualité'],
                    ['icon' => 'fas fa-search', 'value' => '', 'label' => 'Transparence'],
                    ['icon' => 'fas fa-clock', 'value' => '', 'label' => 'Respect des délais'],
                    ['icon' => 'fas fa-smile', 'value' => '', 'label' => 'Satisfaction client'],
                ],
            ],
            'sous-traitance' => [
                'title' => 'Sous-traitance internationale',
                'intro' => "Connectons les talents au monde. Missions à distance pour clients européens, avec des talents togolais qualifiés et un suivi de qualité.",
                'cta' => "Je m'inscris",
                'image' => 'sous-traitance.jpg',
                'color' => '#1A237E',
                'colorSoft' => '#E8EAF6',
                'domainsTitle' => 'Nos services',
                'domains' => [
                    ['icon' => 'fas fa-laptop-house', 'title' => 'Missions à distance', 'text' => '100 % en ligne, flexibles et sécurisées.'],
                    ['icon' => 'fas fa-user-graduate', 'title' => 'Talents qualifiés', 'text' => 'Profils formés et accompagnés.'],
                    ['icon' => 'fas fa-tasks', 'title' => 'Gestion de projets', 'text' => 'Coordination, délais et qualité.'],
                ],
                'bannerTitle' => 'Nos avantages',
                'bannerItems' => [
                    ['icon' => 'fas fa-piggy-bank', 'value' => '', 'label' => 'Réduction des coûts'],
                    ['icon' => 'fas fa-sync-alt', 'value' => '', 'label' => 'Flexibilité'],
                    ['icon' => 'fas fa-check-double', 'value' => '', 'label' => 'Qualité garantie'],
                    ['icon' => 'fas fa-handshake', 'value' => '', 'label' => 'Partenariats durables'],
                ],
            ],
            'education-formation' => [
                'title' => "Conception d'application web, mobile et desktop",
                'intro' => "Concevez et développez des applications sur mesure : sites web, apps mobiles et logiciels desktop, de l'idée à la mise en production.",
                'cta' => 'Découvrir nos services',
                'ctaUrl' => '/conception-application',
                'image' => 'conception-application.jpg',
                'color' => '#00695C',
                'colorSoft' => '#E0F2F1',
                'domainsTitle' => 'Nos expertises',
                'domains' => [
                    ['icon' => 'fas fa-globe', 'title' => 'Application Web', 'text' => 'Sites et plateformes web modernes et performants.'],
                    ['icon' => 'fas fa-mobile-alt', 'title' => 'Application Mobile', 'text' => 'Apps iOS & Android adaptées à vos utilisateurs.'],
                    ['icon' => 'fas fa-desktop', 'title' => 'Application Desktop', 'text' => 'Logiciels métier pour Windows, macOS et Linux.'],
                    ['icon' => 'fas fa-pencil-ruler', 'title' => 'UI / UX Design', 'text' => 'Interfaces claires, élégantes et convertissantes.'],
                ],
                'bannerTitle' => 'Pourquoi nous choisir ?',
                'bannerItems' => [
                    ['icon' => 'fas fa-code', 'value' => '', 'label' => 'Code de qualité'],
                    ['icon' => 'fas fa-mobile-alt', 'value' => '', 'label' => 'Multi-plateformes'],
                    ['icon' => 'fas fa-cogs', 'value' => '', 'label' => 'Solutions sur mesure'],
                    ['icon' => 'fas fa-headset', 'value' => '', 'label' => 'Accompagnement'],
                ],
            ],
            'conception-application' => [
                'title' => "Conception d'application web, mobile et desktop",
                'intro' => "Concevez et développez des applications sur mesure : sites web, apps mobiles et logiciels desktop, de l'idée à la mise en production.",
                'cta' => 'Découvrir nos services',
                'ctaUrl' => '/conception-application',
                'image' => 'conception-application.jpg',
                'color' => '#00695C',
                'colorSoft' => '#E0F2F1',
                'domainsTitle' => 'Nos expertises',
                'domains' => [
                    ['icon' => 'fas fa-globe', 'title' => 'Application Web', 'text' => 'Sites et plateformes web modernes et performants.'],
                    ['icon' => 'fas fa-mobile-alt', 'title' => 'Application Mobile', 'text' => 'Apps iOS & Android adaptées à vos utilisateurs.'],
                    ['icon' => 'fas fa-desktop', 'title' => 'Application Desktop', 'text' => 'Logiciels métier pour Windows, macOS et Linux.'],
                    ['icon' => 'fas fa-pencil-ruler', 'title' => 'UI / UX Design', 'text' => 'Interfaces claires, élégantes et convertissantes.'],
                ],
                'bannerTitle' => 'Pourquoi nous choisir ?',
                'bannerItems' => [
                    ['icon' => 'fas fa-code', 'value' => '', 'label' => 'Code de qualité'],
                    ['icon' => 'fas fa-mobile-alt', 'value' => '', 'label' => 'Multi-plateformes'],
                    ['icon' => 'fas fa-cogs', 'value' => '', 'label' => 'Solutions sur mesure'],
                    ['icon' => 'fas fa-headset', 'value' => '', 'label' => 'Accompagnement'],
                ],
            ],
            'partenariats-projets' => [
                'title' => 'Partenariats & Projets',
                'intro' => "Ensemble, créons de la valeur. Co-construction de projets avec entreprises, institutions et partenaires pour des résultats concrets.",
                'cta' => 'Devenir partenaire',
                'ctaUrl' => '/nos-partenariats',
                'image' => 'partenariats-projets.jpg',
                'color' => '#6D4C41',
                'colorSoft' => '#EFEBE9',
                'domainsTitle' => 'Nos collaborations',
                'domains' => [
                    ['icon' => 'fas fa-puzzle-piece', 'title' => 'Co-construction', 'text' => 'Projets conçus avec les parties prenantes.'],
                    ['icon' => 'fas fa-handshake', 'title' => 'Partenariats durables', 'text' => 'Relations long terme basées sur la confiance.'],
                    ['icon' => 'fas fa-chart-pie', 'title' => 'Impact mesurable', 'text' => 'Suivi et évaluation des résultats.'],
                ],
                'bannerTitle' => 'Pourquoi nous rejoindre ?',
                'bannerItems' => [
                    ['icon' => 'fas fa-heart', 'value' => '', 'label' => 'Valeurs communes'],
                    ['icon' => 'fas fa-map-marked-alt', 'value' => '', 'label' => 'Expertise terrain'],
                    ['icon' => 'fas fa-network-wired', 'value' => '', 'label' => 'Réseau solide'],
                    ['icon' => 'fas fa-thumbs-up', 'value' => '', 'label' => 'Impact positif'],
                ],
            ],
        ];
    }
}
