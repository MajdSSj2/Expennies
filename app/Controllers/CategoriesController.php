<?php

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\responseFormatter;
use App\Services\CategoryService;
use App\Validators\CreateCategoryRequestValidator;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CategoriesController
{
    public function __construct(
        private readonly Twig                             $twig,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly CategoryService                  $categoryService,
        private readonly responseFormatter                $responseFormatter
    )
    {
    }

    public function index(Request $request, Response $response): Response
    {

        return $this->twig->render($response, 'categories/index.twig', [
            'categories' => $this->categoryService->getAll()
        ]);
    }

    public function store(Request $request, Response $response): Response
    {
        $data = ($this->requestValidatorFactory)->make(CreateCategoryRequestValidator::class)
            ->validate($request->getParsedBody());

        $this->categoryService->create($data['name'], $request->getAttribute('user'));

        return $response->withHeader('Location', "/categories")->withStatus(302);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $this->categoryService->destroy((int)$args['id']);
        return $response->withHeader('Location', "/categories")->withStatus(302);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        error_log("SHOW ROUTE HIT: " . $args['id']);

        $category = $this->categoryService->getById((int)$args['id']);

        if (!$category) {
            return $response->withStatus(404);
        }

        $data = ['id' => $category->getId(), 'name' => $category->getName()];


        return $this->responseFormatter->asJson($response, $data);
    }
}