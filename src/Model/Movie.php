<?php

declare(strict_types=1);

namespace App\Model;

use function array_column;

final class Movie
{
    public const LIST = [
        [
            'title'      => 'Astérix & Obélix: Mission Cléopâtre',
            'slug'       => 'mission-cleopatre',
            'poster'     => 'mission-cleopatre.png',
            'releasedAt' => '30 Jan 2002',
            'plot'       => "Cléopâtre, la reine d’Égypte, décide, pour défier l'Empereur romain Jules César, de construire en trois mois un palais somptueux en plein désert. Si elle y parvient, celui-ci devra concéder publiquement que le peuple égyptien est le plus grand de tous les peuples. Pour ce faire, Cléopâtre fait appel à Numérobis, un architecte d'avant-garde plein d'énergie. S'il réussit, elle le couvrira d'or. S'il échoue, elle le jettera aux crocodiles.
Celui-ci, conscient du défi à relever, cherche de l'aide auprès de son vieil ami Panoramix. Le druide fait le voyage en Égypte avec Astérix et Obélix. De son côté, Amonbofis, l'architecte officiel de Cléopâtre, jaloux que la reine ait choisi Numérobis pour construire le palais, va tout mettre en œuvre pour faire échouer son concurrent.",
            'genres'     => ['Comedy'],
        ],
    ];

    public static function getBySlug(string $slug): array
    {
        return array_column(self::LIST, null, 'slug')[$slug];
    }
}
