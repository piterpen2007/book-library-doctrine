<?php

namespace EfTech\BookLibraryTest\LearningDoctrine\AssocTestEntities\OneToOne\UnidirectionalAddressOwn;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="address")
 *
 */
class Address
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     *
     * @ORM\OneToOne(targetEntity=User::class)
     */
    private User $user;
}