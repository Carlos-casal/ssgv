<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    // A simple, hardcoded list for demonstration.
    // In a real application, this would likely come from a database or a more robust service.
    private const GALICIA_POBLACIONES = [
        'A Coruña' => ['A Coruña', 'Ferrol', 'Santiago de Compostela', 'Oleiros', 'Narón'],
        'Lugo' => ['Lugo', 'Monforte de Lemos', 'Viveiro', 'Vilalba', 'Sarria'],
        'Ourense' => ['Ourense', 'Verín', 'O Barco de Valdeorras', 'Xinzo de Limia', 'Celanova'],
        'Pontevedra' => ['Pontevedra', 'Vigo', 'Vilagarcía de Arousa', 'Redondela', 'Cangas'],
    ];

    #[Route('/api/poblaciones', name: 'api_get_poblaciones', methods: ['GET'])]
    public function getPoblaciones(Request $request): JsonResponse
    {
        $province = $request->query->get('province');

        if (!$province || !isset(self::GALICIA_POBLACIONES[$province])) {
            return new JsonResponse(['cities' => []], 400);
        }

        $cities = self::GALICIA_POBLACIONES[$province];

        return new JsonResponse(['cities' => $cities]);
    }
}