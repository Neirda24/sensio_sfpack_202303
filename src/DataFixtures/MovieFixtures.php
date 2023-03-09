<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Model\Rated;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var list<array{title: string, slug: string, poster: string, releasedAt: string, plot: string, genres: list<string>, rated: Rated}> */
    private const MOVIES = [
        [
            'title'      => 'Astérix & Obélix: Mission Cléopâtre',
            'poster'     => 'mission-cleopatre.png',
            'releasedAt' => '30 Jan 2002',
            'plot'       => "Cléopâtre, la reine d’Égypte, décide, pour défier l'Empereur romain Jules César, de construire en trois mois un palais somptueux en plein désert. Si elle y parvient, celui-ci devra concéder publiquement que le peuple égyptien est le plus grand de tous les peuples. Pour ce faire, Cléopâtre fait appel à Numérobis, un architecte d'avant-garde plein d'énergie. S'il réussit, elle le couvrira d'or. S'il échoue, elle le jettera aux crocodiles.
Celui-ci, conscient du défi à relever, cherche de l'aide auprès de son vieil ami Panoramix. Le druide fait le voyage en Égypte avec Astérix et Obélix. De son côté, Amonbofis, l'architecte officiel de Cléopâtre, jaloux que la reine ait choisi Numérobis pour construire le palais, va tout mettre en œuvre pour faire échouer son concurrent.",
            'genres'     => ['Comedy'],
            'rated'      => Rated::GeneralAudiences,
        ],
        [
            'title'      => 'Le sens de la fête',
            'poster'     => 'le-sens-de-la-fete.png',
            'releasedAt' => '01 Aug 2017',
            'plot'       => "Max est traiteur depuis trente ans. Des fêtes il en a organisé des centaines, il est même un peu au bout du parcours. Aujourd'hui c'est un sublime mariage dans un château du 17ème siècle, un de plus, celui de Pierre et Héléna. Comme d'habitude, Max a tout coordonné : il a recruté sa brigade de serveurs, de cuisiniers, de plongeurs, il a conseillé un photographe, réservé l'orchestre, arrangé la décoration florale, bref tous les ingrédients sont réunis pour que cette fête soit réussie... Mais la loi des séries va venir bouleverser un planning sur le fil où chaque moment de bonheur et d'émotion risque de se transformer en désastre ou en chaos. Des préparatifs jusqu'à l'aube, nous allons vivre les coulisses de cette soirée à travers le regard de ceux qui travaillent et qui devront compter sur leur unique qualité commune : Le sens de la fête.",
            'genres'     => ['Comedy'],
            'rated'      => Rated::AdultsOnly,
        ],
        [
            'title'      => 'Eva',
            'poster'     => 'eva.png',
            'releasedAt' => '13 Mar 2015',
            'plot'       => "Set in 2041, Alex Garel is a well-known robot programmer who after 10 years returns to his home town to work in his old university when his friend Julia brings him a project to create a new line of robot child. There Alex meets his brother David, Lana (Alex's former lover and David's current wife), and Eva, Alex's 10-years-old niece. Looking for inspiration, Alex asks Eva to be the muse of the new robot, watching her attitude and behavior during the time they spend together, making emotional tests to configure its personality. The relationship with his niece gives Alex doubts about finishing the project and awakens old feelings for Lana. At the same time he starts suspecting that perhaps the lovely and imaginative Eva is hiding an important secret about Lana and herself.",
            'genres'     => ['Adventure', 'Drama', 'Fantasy'],
            'rated'      => Rated::Restricted,
        ],
        [
            'title'      => 'Casino Royale',
            'poster'     => 'casino-royale.png',
            'releasedAt' => '22 Nov 2006',
            'plot'       => "Pour sa première mission, James Bond affronte le tout-puissant banquier privé du terrorisme international, Le Chiffre. Pour achever de le ruiner et démanteler le plus grand réseau criminel qui soit, Bond doit le battre lors d'une partie de poker à haut risque au Casino Royale. La très belle Vesper, attachée au Trésor, l'accompagne afin de veiller à ce que l'agent 007 prenne soin de l'argent du gouvernement britannique qui lui sert de mise, mais rien ne va se passer comme prévu.
Alors que Bond et Vesper s'efforcent d'échapper aux tentatives d'assassinat du Chiffre et de ses hommes, d'autres sentiments surgissent entre eux, ce qui ne fera que les rendre plus vulnérables...",
            'genres'     => ['Spy', 'Action', 'Thriller'],
            'rated'      => Rated::ParentsStronglyCautioned,
        ],
    ];

    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
    }

    public function getDependencies(): array
    {
        return [
            GenreFixtures::class,
        ];
    }

    private function getGenre(string $genreName): Genre
    {
        return $this->getReference("Genre.{$genreName}");
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::MOVIES as $movieData) {
            $movie = (new Movie())
                ->setTitle($movieData['title'])
                ->setPoster($movieData['poster'])
                ->setPlot($movieData['plot'])
                ->setReleasedAt(new DateTimeImmutable($movieData['releasedAt']))
                ->setRated($movieData['rated'])
            ;

            $movie->setSlug($this->slugger->slug($movie->sluggable())->toString());

            foreach ($movieData['genres'] as $genreName) {
                $movie->addGenre($this->getGenre($genreName));
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }
}
