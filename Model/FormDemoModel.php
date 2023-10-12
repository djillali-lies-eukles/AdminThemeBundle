<?php
/**
 * FormDemoModel.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace Avanzu\AdminThemeBundle\Model;

use DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FormDemoModel
 *
 * @package Avanzu\AdminThemeBundle\Model
 */
class FormDemoModel
{
    /**
     * @var string
     */
    protected string $gender;

    /**
     * @var string
     */
    protected string $someOption;

    /**
     * @var string
     */
    protected string $someChoices;

    /**
     * @var string
     */
    protected string $username;

    /**
     * @var string
     */
    protected string $email;

    /**
     * @var bool
     */
    protected bool $termsAccepted;

    /**
     * @var string
     */
    protected string $message;

    /**
     * @var float
     */
    protected float $price;

    /**
     * @var DateTime
     */
    protected DateTime $date;

    /**
     * @var DateTime
     */
    protected DateTime $time;

    /**
     * @var UploadedFile
     */
    protected UploadedFile $file;

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param mixed $someChoices
     */
    public function setSomeChoices($someChoices): void
    {
        $this->someChoices = $someChoices;
    }

    /**
     * @return mixed
     */
    public function getSomeChoices(): string
    {
        return $this->someChoices;
    }

    /**
     * @param mixed $someOption
     */
    public function setSomeOption($someOption): void
    {
        $this->someOption = $someOption;
    }

    /**
     * @return mixed
     */
    public function getSomeOption(): string
    {
        return $this->someOption;
    }

    /**
     * @param mixed $termsAccepted
     */
    public function setTermsAccepted($termsAccepted): void
    {
        $this->termsAccepted = $termsAccepted;
    }

    /**
     * @return mixed
     */
    public function getTermsAccepted(): bool
    {
        return $this->termsAccepted;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param UploadedFile $file
     *
     * @return $this
     */
    public function setFile($file): self
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}
