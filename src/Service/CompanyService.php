<?php

namespace App\Service;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dadata\DadataClient;

final readonly class CompanyService
{
    private const TTL_DAYS = 7;

    public function __construct(
        private CompanyRepository $repository,
        private EntityManagerInterface $em,
        private DadataClient $dadata,
    ) {}

    public function getCompanyByInn(string $inn): ?Company
    {
        $company = $this->repository->findOneByInn($inn);

        if ($company && $this->isFresh($company)) {
            return $company;
        }

        $data = $this->fetchFromDaData($inn);

        if ($data === null) {
            return null;
        }

        return $this->createOrUpdateFromSourceData($company, $data);
    }

    private function isFresh(Company $company): bool
    {
        $updatedAt = $company->getUpdatedAt();

        if (!$updatedAt) {
            return false;
        }

        $threshold = new \DateTimeImmutable('-' . self::TTL_DAYS . ' days');

        return $updatedAt >= $threshold;
    }

    private function fetchFromDaData(string $inn): ?array
    {
        $response = $this->dadata->findById('party', $inn);

        $item = $response[0]['data'] ?? null;

        return $item ?: null;
    }

    private function createOrUpdateFromSourceData(?Company $company, array $data): Company
    {
        $company ??= new Company();

        $company->setInn($data['inn']);

        $company->setName(
            $data['name']['short_with_opf']
            ?? $data['name']['full_with_opf']
            ?? 'Unknown'
        );

        $company->setActive(($data['state']['status'] ?? null) === 'ACTIVE');

        $company->setOkved($data['okved'] ?? null);
        $company->setKpp($data['kpp'] ?? null);
        $company->setOgrn($data['ogrn'] ?? null);

        $this->em->persist($company);
        $this->em->flush();

        return $company;
    }
}
