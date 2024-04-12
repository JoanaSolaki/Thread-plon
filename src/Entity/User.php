<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Thread>
     */
    #[ORM\OneToMany(targetEntity: Thread::class, mappedBy: 'user')]
    private Collection $relation_thread;

    /**
     * @var Collection<int, Votes>
     */
    #[ORM\OneToMany(targetEntity: Votes::class, mappedBy: 'user')]
    private Collection $relation_votes;

    /**
     * @var Collection<int, Response>
     */
    #[ORM\OneToMany(targetEntity: Response::class, mappedBy: 'user')]
    private Collection $relation_response;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_update = null;

    public function __construct()
    {
        $this->relation_thread = new ArrayCollection();
        $this->relation_votes = new ArrayCollection();
        $this->relation_response = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Thread>
     */
    public function getRelationThread(): Collection
    {
        return $this->relation_thread;
    }

    public function addRelationThread(Thread $relationThread): static
    {
        if (!$this->relation_thread->contains($relationThread)) {
            $this->relation_thread->add($relationThread);
            $relationThread->setUser($this);
        }

        return $this;
    }

    public function removeRelationThread(Thread $relationThread): static
    {
        if ($this->relation_thread->removeElement($relationThread)) {
            // set the owning side to null (unless already changed)
            if ($relationThread->getUser() === $this) {
                $relationThread->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Votes>
     */
    public function getRelationVotes(): Collection
    {
        return $this->relation_votes;
    }

    public function addRelationVote(Votes $relationVote): static
    {
        if (!$this->relation_votes->contains($relationVote)) {
            $this->relation_votes->add($relationVote);
            $relationVote->setUser($this);
        }

        return $this;
    }

    public function removeRelationVote(Votes $relationVote): static
    {
        if ($this->relation_votes->removeElement($relationVote)) {
            // set the owning side to null (unless already changed)
            if ($relationVote->getUser() === $this) {
                $relationVote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Response>
     */
    public function getRelationResponse(): Collection
    {
        return $this->relation_response;
    }

    public function addRelationResponse(Response $relationResponse): static
    {
        if (!$this->relation_response->contains($relationResponse)) {
            $this->relation_response->add($relationResponse);
            $relationResponse->setUser($this);
        }

        return $this;
    }

    public function removeRelationResponse(Response $relationResponse): static
    {
        if ($this->relation_response->removeElement($relationResponse)) {
            // set the owning side to null (unless already changed)
            if ($relationResponse->getUser() === $this) {
                $relationResponse->setUser(null);
            }
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(?\DateTimeInterface $date_update): static
    {
        $this->date_update = $date_update;

        return $this;
    }
}
