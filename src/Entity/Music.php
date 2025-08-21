<?php

namespace App\Entity;

use App\Repository\MusicRepository;
use DateTime;
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
    private ?DateTime $releaseDate = null;

    /**
     * @var Collection<int, Artist>
     */
    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'musics')]
    private Collection $artists;
    /**
     * @var Collection<int, MusicGenre>
     */
//    #[ORM\ManyToMany(targetEntity: MusicGenre::class, mappedBy: 'artist')]
//    private Collection $musicGenres;
    #[ORM\ManyToMany(targetEntity: MusicGenre::class, inversedBy: 'music')]
    #[ORM\JoinTable(name: "music_genre_music")]
    private Collection $musicGenres;

    public function __construct()
    {
        $this->musicGenres = new ArrayCollection();
        $this->artists = new ArrayCollection();
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

    public function getReleaseDate(): ?DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTime $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): static
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
            $artist->addMusic($this); // synchronisation
        }

        return $this;
    }

    public function removeArtist(Artist $artist): static
    {
        if ($this->artists->removeElement($artist)) {
            $artist->removeMusic($this); // synchronisation
        }

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
