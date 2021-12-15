<?php


namespace Dhi\BlogBundle\Core\Interfaces\Model;


interface OTPInterface
{
    public function getCode(): ?string;

    public function getEmail(): ?string;

    public function getPhoneNumber(): ?string;
}
