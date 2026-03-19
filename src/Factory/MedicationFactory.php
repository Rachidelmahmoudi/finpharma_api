<?php

namespace App\Factory;

use App\Entity\Medication;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Medication>
 */
final class MedicationFactory extends PersistentProxyObjectFactory
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
        return Medication::class;
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
            'name' =>  self::faker()->name(),
            'presentation' =>  self::faker()->text(100),
            'dosage' =>  self::faker()->text(100),
            'composition' =>  self::faker()->text(),
            'price' =>  12.3,
            'hospitalPrice' => 34.5,
            'status' => 1,
            'nature' => 1,
            'isRefundable' => self::faker()->boolean(),
            'isRecalled' => self::faker()->boolean(),
            'isRecalled' => self::faker()->boolean(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Medication $medication): void {})
        ;
    }
}
