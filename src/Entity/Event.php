<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $eventName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAndHour;

    /**
     * @ORM\Column(type="date")
     */
    private $registrationLimit;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfPlaces;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;



    /**
     * @ORM\ManyToOne(targetEntity=Cities::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;


    /**
     * @ORM\ManyToOne(targetEntity=EventStatus::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organizer;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbRegistration;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="eventsUtilisateur")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): self
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getDateAndHour(): ?DateTimeInterface
    {
        return $this->dateAndHour;
    }

    public function setDateAndHour(DateTimeInterface $dateAndHour): self
    {
        $this->dateAndHour = $dateAndHour;

        return $this;
    }

    public function getRegistrationLimit(): ?DateTimeInterface
    {
        return $this->registrationLimit;
    }

    public function setRegistrationLimit(DateTimeInterface $registrationLimit): self
    {
        $this->registrationLimit = $registrationLimit;

        return $this;
    }

    public function getNumberOfPlaces(): ?int
    {
        return $this->numberOfPlaces;
    }

    public function setNumberOfPlaces(int $numberOfPlaces): self
    {
        $this->numberOfPlaces = $numberOfPlaces;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getCity(): ?Cities
    {
        return $this->city;
    }

    public function setCity(?Cities $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStatus(): ?EventStatus
    {
        return $this->status;
    }

    public function setStatus(?EventStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getNbRegistration(): ?int
    {
        return $this->nbRegistration;
    }

    public function setNbRegistration(?int $nbRegistration): self
    {
        $this->nbRegistration = $nbRegistration;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
        }

        return $this;
    }

}
