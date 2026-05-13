<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12, unique: true)]
    private string $inn;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private bool $active;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $okved = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $kpp = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $ogrn = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $now = new \DateTimeImmutable();

        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function setInn(string $inn): self
    {
        $this->inn = $inn;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getOkved(): ?string
    {
        return $this->okved;
    }

    public function setOkved(?string $okved): self
    {
        $this->okved = $okved;

        return $this;
    }

    public function getKpp(): ?string
    {
        return $this->kpp;
    }

    public function setKpp(?string $kpp): self
    {
        $this->kpp = $kpp;

        return $this;
    }

    public function getOgrn(): ?string
    {
        return $this->ogrn;
    }

    public function setOgrn(?string $ogrn): self
    {
        $this->ogrn = $ogrn;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
