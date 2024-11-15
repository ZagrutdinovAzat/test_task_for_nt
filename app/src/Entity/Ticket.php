<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
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
     * @ORM\Column(type="string", length=120)
     */
    private $barcode;

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

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }
}
