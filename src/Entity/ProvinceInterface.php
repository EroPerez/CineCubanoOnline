<?php

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;

/**
 *
 * @author Michel
 */
interface ProvinceInterface 
{

    public function getId(): ?UuidInterface;

    public function setName(string $name): void;

    public function getName(): string;
}
