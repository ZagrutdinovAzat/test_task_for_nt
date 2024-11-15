<?php

namespace App\Entity;

use App\Repository\OrderTicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderTicketRepository::class)
 */
class OrderTicket
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
    private $order_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ticket_type_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getTicketTypeId(): ?int
    {
        return $this->ticket_type_id;
    }

    public function setTicketTypeId(int $ticket_type_id): self
    {
        $this->ticket_type_id = $ticket_type_id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
