<?php

namespace App\Factory;

use App\Entity\Doctor;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Doctor>
 */
final class DoctorFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Doctor::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->name(),
            'address' => self::faker()->address(),
            'phone' => self::faker()->phoneNumber(),
            'city' => self::faker()->city(),
            'description' => self::faker()->text(255),
            'specialty' => SpecialtyFactory::random(),
            'longitude' => self::faker()->longitude(),
            'latitude' =>self::faker()->latitude(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Doctor $doctor): void {})
        ;
    }
}
