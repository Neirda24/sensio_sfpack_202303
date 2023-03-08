<?php

namespace App\Entity;

use App\Model\Rated;
use App\Repository\MovieRepository;
use App\Validation\Constraint\Poster;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[Length(min: 3)]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Length(min: 3)]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Poster]
    #[ORM\Column(length: 255)]
    private ?string $poster = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $releasedAt = null;

    #[Length(min: 20)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $plot = null;

    #[Count(min: 1)]
    #[ORM\ManyToMany(targetEntity: Genre::class)]
    private Collection $genres;

    #[ORM\Column(length: 15, enumType: Rated::class)]
    private ?Rated $rated = Rated::GeneralAudiences;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function sluggable(): string
    {
        return "{$this->getTitle()}-{$this->getYear()}";
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeImmutable
    {
        return $this->releasedAt;
    }

    public function getYear(): string
    {
        return $this->getReleasedAt()->format('Y');
    }

    public function setReleasedAt(\DateTimeImmutable $releasedAt): self
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string $plot): self
    {
        $this->plot = $plot;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function getRated(): ?Rated
    {
        return $this->rated;
    }

    public function setRated(Rated $rated): self
    {
        $this->rated = $rated;

        return $this;
    }
}
