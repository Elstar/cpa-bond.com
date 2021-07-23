<?php


namespace App\Form\Model;


use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\Email(message="You put incorrect Email")
     * @Assert\NotBlank(message="You did not enter your E-mail")
     * @UniqueUser()
     */
    public $email;

    /**
     * @Assert\NotBlank(message="You did not enter First name")
     */
    public $firstName;

    /**
     * @Assert\NotBlank(message="You did not enter a password")
     * @Assert\Length(min="4", minMessage="Minimum password length is 4 symbols")
     */
    public $plainPassword;

    public $telegram;

    public $viber;

    public $skype;
}