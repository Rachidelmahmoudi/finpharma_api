<?php
namespace App\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EstablishmentType extends Type
{
    public const PHARMACY = 'pharmacy';
    public const DOCTOR = 'doctor';
    public const LABORATORY = 'laboratory';
    public const OTHER = 'other';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return "ENUM('pharmacy', 'doctor', 'laboratory', 'other')";
    }
    
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!in_array($value, [self::DOCTOR, self::LABORATORY, self::PHARMACY, self::OTHER], true)) {
            throw new \InvalidArgumentException("Invalid type");
        }
        return $value;
    }

    public function getName(): string
    {
        return strtolower(self::class);
    }
}