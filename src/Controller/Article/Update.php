<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Event\Error\ModelUpdate;
use App\Event\Exception\Validation\ViolatedArticle;
use App\Model\Article\Article;
use App\Service\Article\UpdateFromRequest;
use Error;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Update extends AbstractFOSRestController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function __invoke(Request $request, Article $article, UpdateFromRequest $updateFromRequest)
    {
        try {
            $update = $article->updateFromRequest($request);
            $validation = $this->validator->validate($update);
        } catch (Error $error) {
            throw new ModelUpdate('One member of your request body is not valid');
        }

        if ($validation->count() > 0) {
            throw ViolatedArticle::create($validation);
        }

        return $this->handleView($this->view($updateFromRequest->__invoke($update)));
    }
}
