<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\Table(name="movie")
 */
class Movie implements PublicEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue("NONE")
     * @ORM\Column(name="imdb_id", type="string", length=50)
     */
    private $imdbId;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    /**
     * @ORM\Column(type="integer")
     */
    private $year;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poster;
    /**
     * @ORM\OneToMany(targetEntity="UserMovie", mappedBy="movie", orphanRemoval=true)
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getImdbId(): ?string
    {
        return $this->imdbId;
    }

    public function setImdbId(string $imdbId): self
    {
        $this->imdbId = $imdbId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collection|UserMovie[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserMovie $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setMovie($this);
        }

        return $this;
    }

    public function removeUser(UserMovie $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getMovie() === $this) {
                $user->setMovie(null);
            }
        }

        return $this;
    }
}
