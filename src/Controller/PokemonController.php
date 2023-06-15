<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Gender;
use App\Entity\Pokemon;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PokemonController extends AbstractController
{
    #[Route('/pokemons', methods: ['POST'])]
    public function createPokemon(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager) : JsonResponse {
        $payload = json_decode($request->getContent(), true);
        $genderRepo = $entityManager->getRepository(Gender::class);
        $pokemonRepo = $entityManager->getRepository(Pokemon::class);
        $categoryRepo = $entityManager->getRepository(Category::class);
        $typeRepo = $entityManager->getRepository(Type::class);

        $category = $categoryRepo->find($payload['category']);
        $genders = $genderRepo->find($payload['gender']);
        $weaknesses = $typeRepo->find($payload['weaknesses']);
        $types = $typeRepo->find($payload['types']);
        $regression = $pokemonRepo->find($payload['regression']);
        $evolution = $pokemonRepo->find($payload['evolution']);
        $pokemon = (new Pokemon())
            ->setDescription($payload['description'])
            ->setNumber($payload['number'])
            ->setName($payload['name'])
            ->setHeight($payload['height'])
            ->setWeight($payload['weight'])
            ->setCategory($category)
            ->setRegression($regression)
            ->setEvolution($evolution)
            ->setImageUrl($genders)
            ->setGenders($weaknesses)
            ->setImageUrl($types)
            ->setImageUrl($payload['imageUrl']);


        return new JsonResponse($serializer->serialize($pokemon, 'json'), Response::HTTP_CREATED, [], true);
    }
}