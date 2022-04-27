<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock", indexes={@ORM\Index(name="id_produit", columns={"id_produit"})})
 * @ORM\Entity
 */
class Stock
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_stock", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStock;

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
    public function getIdStock(): int
    {
        return $this->idStock;
    }

    /**
     * @param int $idStock
     */
    public function setIdStock(int $idStock): void
    {
        $this->idStock = $idStock;
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
