<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promotion
 *
 * @ORM\Table(name="promotion", indexes={@ORM\Index(name="id_produit", columns={"id_produit"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PrommotionRepository")
 */
class Promotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_promotion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPromotion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle_promotion", type="string", length=255, nullable=true)
     */
    private $libellePromotion;

    /**
     * @var int|null

     *
     * @ORM\Column(name="pourcentage", type="integer", nullable=true)

     */
    private $pourcentage;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_debut_promotion", type="date", nullable=true)
     */
    private $dateDebutPromotion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_fin_pourcentage", type="date", nullable=true)
     */
    private $dateFinPourcentage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id_produit")
     * })
     */
    private $idProduit;

    /**
     * @return int
     */
    public function getIdPromotion(): int
    {
        return $this->idPromotion;
    }

    /**
     * @param int $idPromotion
     */
    public function setIdPromotion(int $idPromotion): void
    {
        $this->idPromotion = $idPromotion;
    }

    /**
     * @return string|null
     */
    public function getLibellePromotion(): ?string
    {
        return $this->libellePromotion;
    }

    /**
     * @param string|null $libellePromotion
     */
    public function setLibellePromotion(?string $libellePromotion): void
    {
        $this->libellePromotion = $libellePromotion;
    }

    /**
     * @return int|null
     */
    public function getPourcentage(): ?int
    {
        return $this->pourcentage;
    }

    /**
     * @param int|null $pourcentage
     */
    public function setPourcentage(?int $pourcentage): void
    {
        $this->pourcentage = $pourcentage;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateDebutPromotion(): ?\DateTime
    {
        return $this->dateDebutPromotion;
    }

    /**
     * @param \DateTime|null $dateDebutPromotion
     */
    public function setDateDebutPromotion(?\DateTime $dateDebutPromotion): void
    {
        $this->dateDebutPromotion = $dateDebutPromotion;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateFinPourcentage(): ?\DateTime
    {
        return $this->dateFinPourcentage;
    }

    /**
     * @param \DateTime|null $dateFinPourcentage
     */
    public function setDateFinPourcentage(?\DateTime $dateFinPourcentage): void
    {
        $this->dateFinPourcentage = $dateFinPourcentage;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }


    public function setIdProduit(?Produit $idProduit): self
    {
        $this->idProduit = $idProduit;
        return $this;
    }


}
