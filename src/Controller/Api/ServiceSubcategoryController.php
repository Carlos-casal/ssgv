<?php

namespace App\Controller\Api;

use App\Entity\ServiceSubcategory;
use App\Repository\ServiceSubcategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/subcategories')]
class ServiceSubcategoryController extends AbstractController
{
    #[Route('', name: 'api_subcategories_list', methods: ['GET'])]
    public function list(Request $request, ServiceSubcategoryRepository $repository): Response
    {
        $categoryId = $request->query->get('category');
        $typeId = $request->query->get('type_id');

        if ($categoryId) {
            $subcategories = $repository->findBy(['category' => $categoryId]);
        } elseif ($typeId) {
            $subcategories = $repository->createQueryBuilder('s')
                ->join('s.category', 'c')
                ->where('c.type = :typeId')
                ->setParameter('typeId', $typeId)
                ->getQuery()
                ->getResult();
        } else {
            $subcategories = $repository->findAll();
        }

        $data = [];
        foreach ($subcategories as $subcategory) {
            $cat = $subcategory->getCategory();
            $data[] = [
                'id' => $subcategory->getId(),
                'name' => ($subcategory->getCode() ? $subcategory->getCode() . ' ' : '') . $subcategory->getName(),
                'categoryName' => ($cat->getCode() ? $cat->getCode() . ' ' : '') . $cat->getName(),
            ];
        }

        return $this->json($data);
    }
}
