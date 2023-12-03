<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $NumReclamation = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'The reclamation should not be blank')]
    private ?string $CorpReclamation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateReclamation = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    #[ORM\JoinColumn(nullable: false, name: 'utilisateur_id', referencedColumnName: 'pseudo_nom')]
    private ?Utilisateur $utilisateur = null;

    public function getNumReclamation(): ?string
    {
        return $this->NumReclamation;
    }

    public function setNumReclamation(string $NumReclamation): static
    {
        $this->NumReclamation = $NumReclamation;

        return $this;
    }

    public function getCorpReclamation(): ?string
    {
        return $this->CorpReclamation;
    }

    public function setCorpReclamation(string $CorpReclamation): static
    {
        $this->CorpReclamation = $CorpReclamation;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->DateReclamation;
    }

    public function setDateReclamation(\DateTimeInterface $DateReclamation): static
    {
        $this->DateReclamation = $DateReclamation;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function isBelongsTo(Utilisateur $utilisateur): bool
    {
        return $this->getUtilisateur()->getUserIdentifier() == $utilisateur->getUserIdentifier();
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
