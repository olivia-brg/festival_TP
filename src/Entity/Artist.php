<?php

namespace App\Entity;

use App\Repository\ArtistRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $style = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $mixDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $mixTime = null;

    public function getMixTime(): ?\DateTime
    {
        return $this->mixTime;
    }

    public function setMixTime(\DateTime $mixTime): static
    {
        $this->mixTime = $mixTime;
        return $this;
    }

    public function __construct()
    {
        $this->mixDate = new \DateTime();
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

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): static
    {
        $this->style = $style;

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

    public function getDateUpdated(): ?\DateTime
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?\DateTime $dateUpdated): static
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }
}
