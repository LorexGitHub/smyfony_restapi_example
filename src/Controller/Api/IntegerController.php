<?php

namespace App\Controller\Api;

use App\Entity\Integer;
use App\Repository\IntegerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class IntegerController extends AbstractController
{
    #[Route('/api/integers', methods: ['GET'])]
    public function index(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $integers = $em->getRepository(Integer::class)->findAll();

        $json_content = $serializer->serialize($integers, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['id']
        ]);

        return JsonResponse::fromJsonString($json_content);
    }

    #[Route('/api/integers/{id}', methods: ['GET'])]
    public function show(Integer $integer): JsonResponse
    {
        return $this->json($integer);
    }

    #[Route('/api/integers', methods: ['POST'])]
    public function create(Request $request,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em,
                           ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();
        $integer = $serializer->deserialize($content, Integer::class, 'json');

        $errors = $validator->validate($integer);
        if (count($errors) > 0) {
            $error_messages = [];

            foreach ($errors as $error) {
                $error_messages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $error_messages], 422);
        }

        // Calculate sum and count
        $values = [];
        $reflection = new \ReflectionProperty($integer, 'value');
        // We need to calculate sum from the array - but since we're deserializing,
        // let's just persist and calculate

        $em->persist($integer);
        $em->flush();

        // Calculate sum after persist
        $integer->setSum($integer->getValue() ?? 0);
        $integer->setCount(1);
        $em->flush();

        return $this->json($integer, 201);
    }

    #[Route('/api/integers/{id}', methods: ['PUT', "PATCH"])]
    public function update(Request $request,
                           Integer $integer,
                           SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(),
                                 Integer::class,
                                 'json',
                                 ["object_to_populate" => $integer]);
        $em->flush();

        return $this->json($integer);
    }

    #[Route('/api/integers/{id}', methods: ['DELETE'])]
    public function delete(Integer $integer, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($integer);
        $em->flush();

        return $this->json(null, 204);
    }
}