<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $firstName;

    /**
     * @ORM\Column(type="float", options={"default": 0})
     */
    private $balance;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $apiToken;

    /**
     * @ORM\Column(type="integer", options={"default": 0, "unsigned":true})
     */
    private $activate;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $telegram;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $viber;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $skype;

    /**
     * @ORM\OneToMany(targetEntity=Stream::class, mappedBy="user", orphanRemoval=true)
     */
    private $streams;

    /**
     * @ORM\OneToOne(targetEntity=Postback::class, mappedBy="user")
     */
    private ?Postback $postback;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="supportUsers")
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="manager")
     */
    private $supportUsers;

    /**
     * @ORM\Column(type="integer", options={"default": 0, "unsigned":true})
     */
    private $payOutAccess;

    /**
     * @ORM\OneToMany(targetEntity=PaymentSystem::class, mappedBy="user")
     */
    private $paymentSystems;

    /**
     * @ORM\OneToMany(targetEntity=Payout::class, mappedBy="user")
     */
    private $payouts;

    /**
     * @ORM\OneToMany(targetEntity=BalanceOperations::class, mappedBy="user")
     */
    private $balanceOperations;

    /**
     * @ORM\Column(type="integer", options={"default": 0, "unsigned":true})
     */
    private $countRequestsPerTime;

    public function __construct()
    {
        $this->streams = new ArrayCollection();
        $this->supportUsers = new ArrayCollection();
        $this->paymentSystems = new ArrayCollection();
        $this->payouts = new ArrayCollection();
        $this->balanceOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
         * @deprecated since Symfony 5.3, use getUserIdentifier instead
         */
        public function getUsername(): string
        {
            return (string) $this->email;
        }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function decreaseBalance(int $sum): self
    {
        $this->balance -= $sum;

        return $this;
    }

    public function increaseBalance(int $sum): self
    {
        $this->balance += $sum;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getActivate(): ?int
    {
        return $this->activate;
    }

    public function setActivate(int $activate): self
    {
        $this->activate = $activate;

        return $this;
    }

    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    public function setTelegram(?string $telegram): self
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function getViber(): ?string
    {
        return $this->viber;
    }

    public function setViber(?string $viber): self
    {
        $this->viber = $viber;

        return $this;
    }

    public function getSkype(): ?string
    {
        return $this->skype;
    }

    public function setSkype(?string $skype): self
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * @return Collection|Stream[]
     */
    public function getStreams(): Collection
    {
        return $this->streams;
    }

    public function addStream(Stream $stream): self
    {
        if (!$this->streams->contains($stream)) {
            $this->streams[] = $stream;
            $stream->setUser($this);
        }

        return $this;
    }

    public function removeStream(Stream $stream): self
    {
        if ($this->streams->removeElement($stream)) {
            // set the owning side to null (unless already changed)
            if ($stream->getUser() === $this) {
                $stream->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Postback
     */
    public function getPostback(): ?Postback
    {
        return $this->postback;
    }

    public function addPostback(Postback $postback): self
    {
        if (!$this->postback->contains($postback)) {
            $this->postback = $postback;
            $postback->setUser($this);
        }

        return $this;
    }

    public function removePostback(Postback $postback): self
    {
        if ($this->postback->removeElement($postback)) {
            // set the owning side to null (unless already changed)
            if ($postback->getUser() === $this) {
                $postback->setUser(null);
            }
        }

        return $this;
    }

    public function getManager(): ?self
    {
        return $this->manager;
    }

    public function setManager(?self $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSupportUsers(): Collection
    {
        return $this->supportUsers;
    }

    public function addSupportUser(self $supportUser): self
    {
        if (!$this->supportUsers->contains($supportUser)) {
            $this->supportUsers[] = $supportUser;
            $supportUser->setManager($this);
        }

        return $this;
    }

    public function removeSupportUser(self $supportUser): self
    {
        if ($this->supportUsers->removeElement($supportUser)) {
            // set the owning side to null (unless already changed)
            if ($supportUser->getManager() === $this) {
                $supportUser->setManager(null);
            }
        }

        return $this;
    }

    public function getPayOutAccess(): ?int
    {
        return $this->payOutAccess;
    }

    public function setPayOutAccess(int $payOutAccess): self
    {
        $this->payOutAccess = $payOutAccess;

        return $this;
    }

    /**
     * @return Collection|PaymentSystem[]
     */
    public function getPaymentSystems(): Collection
    {
        return $this->paymentSystems;
    }

    public function addPaymentSystem(PaymentSystem $paymentSystem): self
    {
        if (!$this->paymentSystems->contains($paymentSystem)) {
            $this->paymentSystems[] = $paymentSystem;
            $paymentSystem->setUser($this);
        }

        return $this;
    }

    public function removePaymentSystem(PaymentSystem $paymentSystem): self
    {
        if ($this->paymentSystems->removeElement($paymentSystem)) {
            // set the owning side to null (unless already changed)
            if ($paymentSystem->getUser() === $this) {
                $paymentSystem->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Payout[]
     */
    public function getPayouts(): Collection
    {
        return $this->payouts;
    }

    public function addPayout(Payout $payout): self
    {
        if (!$this->payouts->contains($payout)) {
            $this->payouts[] = $payout;
            $payout->setUser($this);
        }

        return $this;
    }

    public function removePayout(Payout $payout): self
    {
        if ($this->payouts->removeElement($payout)) {
            // set the owning side to null (unless already changed)
            if ($payout->getUser() === $this) {
                $payout->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BalanceOperations[]
     */
    public function getBalanceOperations(): Collection
    {
        return $this->balanceOperations;
    }

    public function addBalanceOperation(BalanceOperations $balanceOperation): self
    {
        if (!$this->balanceOperations->contains($balanceOperation)) {
            $this->balanceOperations[] = $balanceOperation;
            $balanceOperation->setUser($this);
        }

        return $this;
    }

    public function removeBalanceOperation(BalanceOperations $balanceOperation): self
    {
        if ($this->balanceOperations->removeElement($balanceOperation)) {
            // set the owning side to null (unless already changed)
            if ($balanceOperation->getUser() === $this) {
                $balanceOperation->setUser(null);
            }
        }

        return $this;
    }

    public function getCountRequestsPerTime(): ?int
    {
        return $this->countRequestsPerTime;
    }

    public function setCountRequestsPerTime(int $countRequestsPerTime): self
    {
        $this->countRequestsPerTime = $countRequestsPerTime;

        return $this;
    }
}
