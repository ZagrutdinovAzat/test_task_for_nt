<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Order;
use App\Entity\OrderTicket;
use App\Entity\Ticket;

class CreateOrder extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function list(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $event_id = $data['event_id'] ?? null;
        $event_date = $data['event_date'] ?? null;
        $user_id = $data['user_id'] ?? null;
        $ticket_types = $data['ticket_types'] ?? [];

        if (!$this->validateInput($event_id, $event_date, $user_id, $ticket_types)) {
            return new JsonResponse(['error' => 'Invalid input data'], 400);
        }

        $httpClient = HttpClient::create();
        $barcodes = [];

        // Create order
        $order = new Order();
        $order->setEventId($event_id);
        $order->setEventDate(new \DateTime($event_date));
        $order->setUserId($user_id);
        $order->setCreated(new \DateTime());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        foreach ($ticket_types as $ticket_type) {
            $ticketTypeId = $ticket_type['ticket_type_id'];
            $quantity = $ticket_type['quantity'];

            // Create order_tickets
            $orderTicket = new OrderTicket();
            $orderTicket->setOrder($order);
            $orderTicket->setTicketTypeId($ticketTypeId);
            $orderTicket->setQuantity($quantity);

            $this->entityManager->persist($orderTicket);
            $this->entityManager->flush();

            // Generate barcodes and create tickets
            for ($i = 0; $i < $quantity; $i++) {
                $barcode = $this->generateUniqueBarcode();
                $ticket = new Ticket();
                $ticket->setOrder($order);
                $ticket->setTicketTypeId($ticketTypeId);
                $ticket->setBarcode($barcode);

                $this->entityManager->persist($ticket);
                $this->entityManager->flush();

                $barcodes[] = $barcode;
            }
        }

        return new JsonResponse(['generated_barcodes' => $barcodes, 'message' => 'Order successfully added']);
    }

    private function validateInput($event_id, $event_date, $user_id, $ticket_types): bool
    {
        return !empty($event_id) && !empty($event_date) && !empty($user_id) && !empty($ticket_types);
    }

    private function generateUniqueBarcode(): string
    {
        return bin2hex(random_bytes(5));
    }

    private function bookOrder($httpClient, $event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $barcode): array
    {
        $response = $httpClient->request('POST', 'https://api.site.com/book', [
            'json' => [
                'event_id' => $event_id,
                'event_date' => $event_date,
                'ticket_adult_price' => $ticket_adult_price,
                'ticket_adult_quantity' => $ticket_adult_quantity,
                'ticket_kid_price' => $ticket_kid_price,
                'ticket_kid_quantity' => $ticket_kid_quantity,
                'barcode' => $barcode
            ]
        ]);

        $data = $response->toArray();

        if (isset($data['message']) && $data['message'] == 'order successfully booked') {
            return ['status' => 'success'];
        } elseif (isset($data['error']) && $data['error'] == 'barcode already exists') {
            return ['status' => 'error', 'message' => 'barcode already exists'];
        } else {
            return ['status' => 'error', 'message' => 'Unknown error'];
        }
    }

    private function approveOrder($httpClient, $barcode): array
    {
        $response = $httpClient->request('POST', 'https://api.site.com/approve', [
            'json' => [
                'barcode' => $barcode
            ]
        ]);

        $data = $response->toArray();

        if (isset($data['message']) && $data['message'] == 'order successfully aproved') {
            return ['status' => 'success'];
        } elseif (isset($data['error'])) {
            return ['status' => 'error', 'message' => $data['error']];
        } else {
            return ['status' => 'error', 'message' => 'Unknown error'];
        }
    }
}
