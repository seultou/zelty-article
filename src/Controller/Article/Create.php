<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Event\Error\ModelCreate;
use App\Event\Exception\Validation\ViolatedArticle;
use App\Model\Article\Article;
use App\Service\Article\CreateFromRequest;
use Error;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function var_dump;

class Create extends AbstractFOSRestController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function __invoke(Request $request, CreateFromRequest $createFromRequest): Response
    {
        try {
            $validation = $this->validator->validate(Article::fromHttpRequest($request, $this->getUser()));
        } catch (Error $error) {
            die(var_dump($error->getMessage()));
            throw new ModelCreate('One member of your request body is not valid');
        }
        if ($validation->count() > 0) {
            throw ViolatedArticle::create($validation);
        }


        return $this->handleView($this->view($createFromRequest->__invoke($request), Response::HTTP_OK));
    }
}
