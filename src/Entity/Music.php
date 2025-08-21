<?php

namespace App\Entity;

use App\Repository\MusicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
class Music
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a name')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Your name must be at least {{ limit }} characters long', maxMessage: 'Your name cannot be longer than {{ limit }} characters')]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $releaseDate = null;

    #[ORM\ManyToOne(inversedBy: 'musics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?artist $artist = null;

    /**
     * @var Collection<int, MusicGenre>
     */
    #[ORM\ManyToMany(targetEntity: MusicGenre::class, mappedBy: 'artist')]
    private Collection $musicGenres;

    public function __construct()
    {
        $this->musicGenres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTime $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getArtist(): ?artist
    {
        return $this->artist;
    }

    public function setArtist(?artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return Collection<int, MusicGenre>
     */
    public function getMusicGenres(): Collection
    {
        return $this->musicGenres;
    }

    public function addMusicGenre(MusicGenre $genre): static
    {
        if (!$this->musicGenres->contains($genre)) {
            $this->musicGenres->add($genre);
            $genre->addMusic($this);
        }

        return $this;
    }

    public function removeMusicGenre(MusicGenre $genre): static
    {
        if ($this->musicGenres->removeElement($genre)) {
            $genre->removeMusic($this);
        }

        return $this;
    }
}
