<?php

namespace App\Entity;

use App\Repository\ResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'relation_response')]
    private ?User $user = null;

    /**
     * @var Collection<int, Votes>
     */
    #[ORM\OneToMany(targetEntity: Votes::class, mappedBy: 'response')]
    private Collection $relation_votes;

    #[ORM\ManyToOne(inversedBy: 'relation_response')]
    private ?Thread $thread = null;

    #[ORM\Column(length: 255)]
    private ?string $body_response = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_update = null;

    public function __construct()
    {
        $this->relation_votes = new ArrayCollection();
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
            $relationVote->setResponse($this);
        }

        return $this;
    }

    public function removeRelationVote(Votes $relationVote): static
    {
        if ($this->relation_votes->removeElement($relationVote)) {
            // set the owning side to null (unless already changed)
            if ($relationVote->getResponse() === $this) {
                $relationVote->setResponse(null);
            }
        }

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): static
    {
        $this->thread = $thread;

        return $this;
    }

    public function getBodyResponse(): ?string
    {
        return $this->body_response;
    }

    public function setBodyResponse(string $body_response): static
    {
        $this->body_response = $body_response;

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

    public function getTotalVotes(): int
    {
        $count = 0;
        foreach ($this->getRelationVotes() as $vote) {
            if ($vote->isVote()) {
                $count++;
            }

            if (!$vote->isVote()) {
                $count--;
            }
        }
        return $count;
    }
}
