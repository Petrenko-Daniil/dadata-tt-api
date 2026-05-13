<?php

namespace App\Response;

use App\Entity\Company;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CompanyHttpResponseFactory
{
    public function response(?Company $company): JsonResponse
    {
        if (!$company) {
            return $this->notFound("Company not found");
        }
        return $this->success($company);
    }

    private function success(Company $company): JsonResponse
    {
        return new JsonResponse(
            [
                'inn' => $company->getInn(),
                'name' => $company->getName(),
                'active' => $company->isActive(),
                'okved' => $company->getOkved(),
                'kpp' => $company->getKpp(),
                'ogrn' => $company->getOgrn(),
            ],
            200,
            $this->cacheHeaders(3600)
        );
    }

    private function notFound(string $message): JsonResponse
    {
        return new JsonResponse(
            ['message' => $message],
            404,
            [
                'Cache-Control' => 'no-store',
            ]
        );
    }

    private function cacheHeaders(int $ttl): array
    {
        return [
            'Cache-Control' => sprintf('public, max-age=%d', $ttl),
        ];
    }
}
