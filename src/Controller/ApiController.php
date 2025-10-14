<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/poblaciones', name: 'api_poblaciones')]
    public function getPoblaciones(Request $request): JsonResponse
    {
        $province = $request->query->get('province');

        $poblaciones = [
            'A Coruña' => [
                ['name' => 'A Coruña'], ['name' => 'Santiago de Compostela'], ['name' => 'Ferrol'], ['name' => 'Narón'], ['name' => 'Oleiros'],
            ],
            'Lugo' => [
                ['name' => 'Lugo'], ['name' => 'Monforte de Lemos'], ['name' => 'Viveiro'], ['name' => 'Vilalba'], ['name' => 'Sarria'],
            ],
            'Ourense' => [
                ['name' => 'Ourense'], ['name' => 'Verín'], ['name' => 'O Barco de Valdeorras'], ['name' => 'Carballiño'], ['name' => 'Xinzo de Limia'],
            ],
            'Pontevedra' => [
                ['name' => 'Vigo'], ['name' => 'Pontevedra'], ['name' => 'Vilagarcía de Arousa'], ['name' => 'Redondela'], ['name' => 'Cangas'],
            ],
        ];

        $data = $poblaciones[$province] ?? [];

        // Sort the data alphabetically by name
        usort($data, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $this->json($data);
    }
}