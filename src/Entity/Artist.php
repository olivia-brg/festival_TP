<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a name')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Your name must be at least {{ limit }} characters long', maxMessage: 'Your name cannot be longer than {{ limit }} characters')]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan('today', message: 'You cannot mix in the past')]
    private ?\DateTime $mixDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTime $mixTime = null;


    /**
     * @var Collection<int, Music>
     */
    #[ORM\ManyToMany(targetEntity: Music::class, mappedBy: 'artists')]
    private Collection $musics;

    /**
     * @var Collection<int, MusicGenre>
     */
    #[ORM\ManyToMany(targetEntity: MusicGenre::class, mappedBy: 'artists')]
    private Collection $musicGenres;

    public function __construct()
    {
        $this->mixDate = new \DateTime();
        $this->musics = new ArrayCollection();
        $this->musicGenres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMixDate(): ?\DateTime
    {
        return $this->mixDate;
    }

    public function setMixDate(\DateTime $mixDate): static
    {
        $this->mixDate = $mixDate;

        return $this;
    }

    public function getMixTime(): ?\DateTime
    {
        return $this->mixTime;
    }

    public function setMixTime(\DateTime $mixTime): static
    {
        $this->mixTime = $mixTime;
        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getMusics(): Collection
    {
        return $this->musics;
    }

    public function addMusic(Music $music): static
    {
        if (!$this->musics->contains($music)) {
            $this->musics->add($music);
            $music->addArtist($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): static
    {
        if ($this->musics->removeElement($music)) {
            // set the owning side to null (unless already changed)
            if ($music->getArtists() === $this) {
                $music->addArtist(null);
            }
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
            $genre->addArtist($this);
        }

        return $this;
    }


    public function removeMusicGenre(MusicGenre $genre): static
    {
        if ($this->musicGenres->removeElement($genre)) {
            $genre->removeArtist($this);
        }

        return $this;
    }

}
