<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryController extends AbstractController
{

    #[Route('/categories', methods: ['POST'])]
    public function createCategory(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $payload = json_decode($request->getContent(), 'json');

        $category = (new Category())
            ->setName($payload['name']);

        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($category, 'json'), Response::HTTP_CREATED, [], true);
    }

    #[Route('/categories', methods: ['GET'])]
    public function getCategories(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Category::class);
        $results = $repository->findAll();
        return new JsonResponse($serializer->serialize($results, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route('/category/{id}', methods: ['GET'])]
    public function getOneCategories(int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Category::class);

        $results = $repository->find($id);
        if (null === $results) {
            return new JsonResponse(['message' => 'category not found'], Response::HTTP_NOT_FOUND, [], true);
        }

        return new JsonResponse($serializer->serialize($results, 'json'), Response::HTTP_OK, [], true);

    }

    #[Route('/category/{id}', methods: ['PUT'])]
    public function editOneCategories(Request $request, int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Category::class);
        $payload = json_decode($request->getContent(), true);
        $category = $repository->find($id);

        if (null !== $category) {
            if (!isset($payload['name'])) {
                return new JsonResponse(['message' => 'missing name'], 400, [], true);
            }
            $category->setName($payload['name']);
            $entityManager->persist($category);
            $entityManager->flush();

            return new JsonResponse($serializer->serialize(['message' => 'category successfully updated'], 'json'), Response::HTTP_CREATED, [], true);
        }
        return new JsonResponse(['message' => 'this category does not exist'], Response::HTTP_NOT_FOUND, [], true);
    }

    #[Route('/category/{id}', methods: ['DELETE'])]
    public function deleteOneCategories(int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Category::class);
        $category = $repository->find($id);

        if (null !== $category) {
            $entityManager->remove($category);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize(['message' => 'category successfully deleted'], 'json'), Response::HTTP_NO_CONTENT, [], true);
        }
        return new JsonResponse(['message' => 'this category does not exist'], Response::HTTP_NOT_FOUND, [], true);
    }
}