<?php

 namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Ulid\Ulid;

 class IdGenerator extends AbstractIdGenerator {
    public function generateId(EntityManagerInterface $em, object|null $entity): mixed {
        return Ulid::generate();
    }
 }