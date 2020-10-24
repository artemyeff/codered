<?php

namespace App\Context\Order\Entity;

use App\Context\Order\Repository\OrderProductRepository;
use App\Context\Product\Entity\Product;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderProductRepository::class)
 * @ORM\Table("`orders_products`")
 */
class OrderProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class)
     * @ORM\JoinColumn(nullable=false, name="order_id", referencedColumnName="id")
     */
    private $orderCreated;

    /**
     * @ORM\Column(type="float")
     */
    private $count;

    /**
     * @ORM\Column(type="float")
     */
    private $sum;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOrderCreated(): ?Order
    {
        return $this->orderCreated;
    }

    public function setOrderCreated(?Order $orderCreated): self
    {
        $this->orderCreated = $orderCreated;

        return $this;
    }

    public function getCount(): ?float
    {
        return $this->count;
    }

    public function setCount(float $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getSum(): ?float
    {
        return $this->sum;
    }

    public function setSum(float $sum): self
    {
        $this->sum = $sum;

        return $this;
    }
}
