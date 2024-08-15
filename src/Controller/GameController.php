<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    #[Route('/', name: 'game', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }

    #[Route('/', name: 'place_piece', methods: [Request::METHOD_POST])]
    public function placePiece(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }

    #[Route('/restart', name: 'restart', methods: [Request::METHOD_POST])]
    public function restart(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }

    #[Route('/', name: 'delete_game', methods: [Request::METHOD_DELETE])]
    public function deleteGame(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }
}
