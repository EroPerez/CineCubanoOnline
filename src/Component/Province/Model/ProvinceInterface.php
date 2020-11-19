<?php

declare(strict_types=1);

namespace App\Component\Province\Model;

use Ramsey\Uuid\UuidInterface;

/**
 *
 * @author Michel
 */
interface ProvinceInterface 
{

    public function getId(): ?string;

    public function setName(string $name): void;

    public function getName(): string;
}
