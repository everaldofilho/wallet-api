<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\EntityTimestamp;
use App\Traits\EntityUuid;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 * @UniqueEntity("document")
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, JsonSerializable
{
    use EntityUuid;
    use EntityTimestamp;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=UserType::class)
     * @ORM\JoinColumn(name="user_type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=10, max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Assert\NotBlank
     * @Assert\Email    
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=11, max=14)
     * @Assert\Type(type={"digit"})
     * @ORM\Column(type="string", length=14, unique=true)
     */
    private $document;

    /**
     * @Assert\Length(min=6, max=150)
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=150)
     */
    private $password;


    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ]; 
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(?UserType $type): self
    {
        $this->type = $type;

        return $this;
    }

     /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->document;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}
