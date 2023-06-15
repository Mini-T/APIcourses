<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Gender;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TypeController extends AbstractController
{

    #[Route('/types', methods: ['POST'])]
    public function createType(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $payload = json_decode($request->getContent(), 'json');

        $type = (new Type())
            ->setName($payload['name']);

        $entityManager->persist($type);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($type, 'json'), Response::HTTP_CREATED, [], true);
    }

    #[Route('/types', methods: ['GET'])]
    public function getType(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Type::class);
        $results = $repository->findAll();
        return new JsonResponse($serializer->serialize($results, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route('/type/{id}', methods: ['GET'])]
    public function getOneType(int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Type::class);

        $results = $repository->find($id);
        if (null === $results) {
            return new JsonResponse(['message' => 'type not found'], Response::HTTP_NOT_FOUND, [], true);
        }

        return new JsonResponse($serializer->serialize($results, 'json'), Response::HTTP_OK, [], true);

    }

    #[Route('/type/{id}', methods: ['PUT'])]
    public function editOneType(Request $request, int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Type::class);
        $payload = json_decode($request->getContent(), true);
        $type = $repository->find($id);

        if (null !== $type) {
            if (!isset($payload['name'])) {
                return new JsonResponse(['message' => 'missing name'], 400, [], true);
            }
            $type->setName($payload['name']);
            $entityManager->persist($type);
            $entityManager->flush();

            return new JsonResponse($serializer->serialize(['message' => 'type successfully updated'], 'json'), Response::HTTP_CREATED, [], true);
        }
        return new JsonResponse(['message' => 'this type does not exist'], Response::HTTP_NOT_FOUND, [], true);
    }

    #[Route('/type/{id}', methods: ['DELETE'])]
    public function deleteOneType(int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Type::class);
        $type = $repository->find($id);

        if (null !== $type) {
            $entityManager->remove($type);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize(['message' => 'type successfully deleted'], 'json'), Response::HTTP_NO_CONTENT, [], false);
        }
        return new JsonResponse(['message' => 'this type does not exist'], Response::HTTP_NOT_FOUND, [], false);
    }
}