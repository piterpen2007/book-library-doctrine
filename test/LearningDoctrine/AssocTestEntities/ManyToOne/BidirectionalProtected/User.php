<?php

namespace EfTech\BookLibraryTest\LearningDoctrine\AssocTestEntities\ManyToOne\BidirectionalProtected;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var Address|Collection
     *
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user")
     */
    private Collection $address;


    public function __construct()
    {
        $this->address = new ArrayCollection();
    }

    /**
     * @return Collection|Address
     */
    public function getAddress()
    {
        return $this->address;
    }


}