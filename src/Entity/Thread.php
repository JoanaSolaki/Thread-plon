<?php

namespace App\Entity;

use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
class Thread
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'relation_thread')]
    private ?User $user = null;

    /**
     * @var Collection<int, Response>
     */
    #[ORM\OneToMany(targetEntity: Response::class, mappedBy: 'thread')]
    private Collection $relation_response;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'threads')]
    private Collection $relation_category;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_update = null;

    #[ORM\Column(length: 255)]
    private ?string $body = null;

    public function __construct()
    {
        $this->relation_response = new ArrayCollection();
        $this->relation_category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
            $relationResponse->setThread($this);
        }

        return $this;
    }

    public function removeRelationResponse(Response $relationResponse): static
    {
        if ($this->relation_response->removeElement($relationResponse)) {
            // set the owning side to null (unless already changed)
            if ($relationResponse->getThread() === $this) {
                $relationResponse->setThread(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getRelationCategory(): Collection
    {
        return $this->relation_category;
    }

    public function addRelationCategory(Category $relationCategory): static
    {
        if (!$this->relation_category->contains($relationCategory)) {
            $this->relation_category->add($relationCategory);
        }

        return $this;
    }

    public function removeRelationCategory(Category $relationCategory): static
    {
        $this->relation_category->removeElement($relationCategory);

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }
}
