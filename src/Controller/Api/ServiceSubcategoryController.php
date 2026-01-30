<?php

namespace App\Controller\Api;

use App\Entity\ServiceSubcategory;
use App\Entity\ServiceCategory;
use App\Form\ServiceSubcategoryType;
use App\Repository\ServiceSubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/subcategories')]
class ServiceSubcategoryController extends AbstractController
{
    #[Route('/new', name: 'api_subcategories_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subcategory = new ServiceSubcategory();
        $form = $this->createForm(ServiceSubcategoryType::class, $subcategory);

        $data = json_decode($request->getContent(), true);
        $form->submit($data['service_subcategory'] ?? $data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$subcategory->getCode()) {
                $category = $subcategory->getCategory();
                $last = $entityManager->getRepository(ServiceSubcategory::class)->findOneBy(['category' => $category], ['code' => 'DESC']);
                if ($last) {
                    $parts = explode('.', $last->getCode());
                    $lastNum = (int)end($parts);
                    $newNum = $lastNum + 1;
                    $subcategory->setCode($category->getCode() . '.' . $newNum);
                } else {
                    $subcategory->setCode($category->getCode() . '.1');
                }
            }

            $entityManager->persist($subcategory);
            $entityManager->flush();

            return $this->json([
                'id' => $subcategory->getId(),
                'name' => ($subcategory->getCode() ? $subcategory->getCode() . ' ' : '') . $subcategory->getName(),
            ], Response::HTTP_CREATED);
        }

        return $this->json([
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }

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
