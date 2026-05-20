<?php

namespace App\Controller\Api;

use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/volunteer')]
class VolunteerController extends AbstractController
{
    #[Route('/search', name: 'api_volunteer_search', methods: ['GET'])]
    public function search(Request $request, VolunteerRepository $volunteerRepository): Response
    {
        $term = $request->query->get('q', '');
        
        $volunteers = $volunteerRepository->createQueryBuilder('v')
            ->where('v.status = :active')
            ->andWhere('v.name LIKE :term OR v.lastName LIKE :term OR v.indicativo LIKE :term')
            ->setParameter('active', 'Activo')
            ->setParameter('term', '%' . $term . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($volunteers as $v) {
            $data[] = [
                'id' => $v->getId(),
                'name' => $v->getName(),
                'lastName' => $v->getLastName(),
                'indicativo' => $v->getIndicativo(),
                'displayName' => trim(sprintf('%s %s', $v->getIndicativo() ?: '', $v->getName()))
            ];
        }

        return $this->json($data);
    }
}
