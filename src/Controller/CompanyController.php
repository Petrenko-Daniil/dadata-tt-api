<?php

namespace App\Controller;

use App\Request\Company\CompanyByInnRequest;
use App\Service\CompanyService;
use App\Response\CompanyHttpResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class CompanyController extends AbstractController
{
    public function __construct(
        private readonly CompanyService $companyService,
        private readonly CompanyHttpResponseFactory $companyHttpResponseFactory,
    ) {}

    #[Route('/api/company', methods: ['GET'])]
    public function getByInn(
        #[MapQueryString] CompanyByInnRequest $request,
    ): JsonResponse {
        $company = $this->companyService->getCompanyByInn($request->inn);

        return $this->companyHttpResponseFactory->response($company);
    }
}
