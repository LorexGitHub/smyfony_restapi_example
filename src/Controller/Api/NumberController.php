<?php

namespace App\Controller\Api;

use App\Entity\Number;
use App\Repository\NumberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class NumberController extends AbstractController
{
    #[Route('/api/numbers', methods: ['GET'])]
    public function index(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $numbers = $em->getRepository(Number::class)->findAll();

        $json_content = $serializer->serialize($numbers, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['id']
        ]);

        return JsonResponse::fromJsonString($json_content);
    }

    #[Route('/api/numbers/{id}', methods: ['GET'])]
    public function show(Number $number): JsonResponse
    {
        return $this->json($number);
    }

    #[Route('/api/numbers', methods: ['POST'])]
    public function create(Request $request,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em,
                           ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();
        $number = $serializer->deserialize($content, Number::class, 'json');

        $errors = $validator->validate($number);
        if (count($errors) > 0) {
            $error_messages = [];

            foreach ($errors as $error) {
                $error_messages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $error_messages], 422);
        }

        $number->setFoundIndex(null);

        $em->persist($number);
        $em->flush();

        return $this->json($number, 201);
    }

    #[Route('/api/numbers/{id}', methods: ['PUT', "PATCH"])]
    public function update(Request $request,
                           Number $number,
                           SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(),
                                 Number::class,
                                 'json',
                                 ["object_to_populate" => $number]);
        $em->flush();

        return $this->json($number);
    }

    #[Route('/api/numbers/{id}', methods: ['DELETE'])]
    public function delete(Number $number, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($number);
        $em->flush();

        return $this->json(null, 204);
    }
}