<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/24/16
 * Time: 1:10 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category
{

    private $blog;

    public function addBlog(Blog $blog)
    {
        $blog->addCategory($this);
        $this->blog[] = $blog;
    }


    /**
     * @return mixed
     */
    public function getBlog()
    {
        return $this->blog;
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}