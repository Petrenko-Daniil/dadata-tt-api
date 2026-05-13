<?php

namespace App\Request\Company;

use Symfony\Component\Validator\Constraints as Assert;

final class CompanyByInnRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Regex(pattern: '/^\d+$/')]
    #[Assert\Length(min: 10, max: 12)]
    public ?string $inn = null;

    public function __construct(string $inn)
    {
        $this->inn = $inn;
    }
}
