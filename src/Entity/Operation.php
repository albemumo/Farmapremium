<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\Column(type="integer")
     */
    private $remaining_points;

    /**
     * @ORM\ManyToOne(targetEntity=Pharmacy::class, inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pharmacy;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct(int $points, int $remaining_points, Pharmacy $pharmacy, Customer $customer, DateTime $createdAt = null, DateTime $updatedAt = null)
    {
        $this->points           = $points;
        $this->remaining_points = $remaining_points;
        $this->pharmacy         = $pharmacy;
        $this->customer         = $customer;
        $this->createdAt        = $createdAt ?: new DateTime();
        $this->updatedAt        = $updatedAt ?: new DateTime();
    }

    public function toArray()
    {
        return [
            'id'               => $this->id,
            'points'           => $this->points,
            'remaining_points' => $this->remaining_points,
            'pharmacy'         => $this->pharmacy->getId(),
            'customer'         => $this->customer->getId(),
            'createdAt'        => $this->createdAt,
            'updatedAt'        => $this->updatedAt
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getRemainingPoints(): ?int
    {
        return $this->remaining_points;
    }

    public function setRemainingPoints(int $remaining_points): self
    {
        $this->remaining_points = $remaining_points;

        return $this;
    }

    public function getPharmacy(): ?Pharmacy
    {
        return $this->pharmacy;
    }

    public function setPharmacy(?Pharmacy $pharmacy): self
    {
        $this->pharmacy = $pharmacy;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
