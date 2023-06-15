<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Gender;
use App\Entity\Pokemon;
use App\Entity\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PokemonController extends AbstractController
{
    /**
     * @throws ReflectionException
     */
    #[Route('/pokemons', methods: ['POST'])]
    public function createPokemon(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager) : JsonResponse {
        $payload = json_decode($request->getContent(), true);

        $genderRepo = $entityManager->getRepository(Gender::class);
        $pokemonRepo = $entityManager->getRepository(Pokemon::class);
        $categoryRepo = $entityManager->getRepository(Category::class);
        $typeRepo = $entityManager->getRepository(Type::class);
        $category = $categoryRepo->find($payload['category']);
        $evolution = $payload['evolution'] ? $pokemonRepo->find($payload['evolution']) : null;
        $regression = $payload['regression'] ? $pokemonRepo->find($payload['regression']) : null;
        $pokemon = (new Pokemon())
            ->setDescription($payload['description'])
            ->setNumber($payload['number'])
            ->setName($payload['name'])
            ->setHeight($payload['height'])
            ->setWeight($payload['weight'])
            ->setCategory($category)
            ->setRegression($regression)
            ->setEvolution($evolution)
            ->setImageUrl($payload['imageUrl']);


        $this->extractArray($payload['gender'], $genderRepo, 'App\Entity\Gender', $pokemon, 'Gender');
        $this->extractArray($payload['weaknesses'], $typeRepo, 'App\Entity\Type', $pokemon, 'Weakness');
        $this->extractArray($payload['types'], $typeRepo, 'App\Entity\Type', $pokemon, 'Type');
        $entityManager->persist($pokemon);
        $entityManager->flush();
        return new JsonResponse($serializer->serialize($pokemon, 'json'), Response::HTTP_CREATED, [], true);
    }


    /**
     * @throws ReflectionException
     */
    private function extractArray($array, $repository, $class, $parent, $field) {
        if(null !== $array) {
            foreach ($array as $id) {
                $method = 'add'.$field;
                $classObject = $this->buildObject($repository, $id, $class);
                $parent->$method($classObject);
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private function buildObject($repository, $id, $class): ?object
    {
        $object = $repository->find($id);
        $reflection = new ReflectionClass($class);
        return $reflection->newInstance()->setName($object->getName());

    }
}