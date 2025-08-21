<?php

namespace App\Entity;

use App\Repository\MusicGenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicGenreRepository::class)]
class MusicGenre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    /**
     * @var Collection<int, Artist>
     */
    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'musicGenres')]
    private Collection $artists;

    /**
     * @var Collection<int, Music>
     */
    #[ORM\ManyToMany(targetEntity: Music::class, inversedBy: 'genres')]
    private Collection $music;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->music = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtist(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): static
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
            $artist->addMusicGenre($this); // synchronisation
        }

        return $this;
    }

    public function removeArtist(Artist $artist): static
    {
        if ($this->artists->removeElement($artist)) {
            $artist->removeMusicGenre($this); // synchronisation
        }

        return $this;
    }


    /**
     * @return Collection<int, Music>
     */
    public function getMusic(): Collection
    {
        return $this->music;
    }

    public function addMusic(Music $music): static
    {
        if (!$this->music->contains($music)) {
            $this->music->add($music);
        }

        return $this;
    }

    public function removeMusic(Music $music): static
    {
        $this->music->removeElement($music);

        return $this;
    }

    public function __toString()
    {
        return $this->genre;
    }
}
