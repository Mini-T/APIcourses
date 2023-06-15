<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Gender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GenderController extends AbstractController
{

    #[Route('/genders', methods: ['POST'])]
    public function createGender(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $payload = json_decode($request->getContent(), 'json');

        $gender = (new Gender())
            ->setName($payload['name']);

        $entityManager->persist($gender);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($gender, 'json'), Response::HTTP_CREATED, [], true);
    }

    #[Route('/genders', methods: ['GET'])]
    public function getGender(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Gender::class);
        $results = $repository->findAll();
        return new JsonResponse($serializer->serialize($results, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route('/gender/{id}', methods: ['GET'])]
    public function getOneGender(int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Gender::class);

        $results = $repository->find($id);
        if (null === $results) {
            return new JsonResponse(['message' => 'gender not found'], Response::HTTP_NOT_FOUND, [], true);
        }

        return new JsonResponse($serializer->serialize($results, 'json'), Response::HTTP_OK, [], true);

    }

    #[Route('/gender/{id}', methods: ['PUT'])]
    public function editOneGender(Request $request, int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Gender::class);
        $payload = json_decode($request->getContent(), true);
        $gender = $repository->find($id);

        if (null !== $gender) {
            if (!isset($payload['name'])) {
                return new JsonResponse(['message' => 'missing name'], 400, [], true);
            }
            $gender->setName($payload['name']);
            $entityManager->persist($gender);
            $entityManager->flush();

            return new JsonResponse($serializer->serialize(['message' => 'gender successfully updated'], 'json'), Response::HTTP_CREATED, [], true);
        }
        return new JsonResponse(['message' => 'this gender does not exist'], Response::HTTP_NOT_FOUND, [], true);
    }

    #[Route('/gender/{id}', methods: ['DELETE'])]
    public function deleteOneGender(int $id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Gender::class);
        $gender = $repository->find($id);

        if (null !== $gender) {
            $entityManager->remove($gender);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize(['message' => 'gender successfully deleted'], 'json'), Response::HTTP_NO_CONTENT, [], false);
        }
        return new JsonResponse(['message' => 'this gender does not exist'], Response::HTTP_NOT_FOUND, [], false);
    }
}