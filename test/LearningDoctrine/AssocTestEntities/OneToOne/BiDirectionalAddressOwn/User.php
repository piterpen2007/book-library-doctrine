<?php

namespace EfTech\BookLibraryTest\LearningDoctrine\AssocTestEntities\OneToOne\BiDirectionalAddressOwn;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @var int
     *
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     *
     *
     * @ORM\Column(type="string",name="user_name", length=50,nullable=false)
     */
    private string $userName;


    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity=Address::class, mappedBy="address")
     */
    private Address $address;
}