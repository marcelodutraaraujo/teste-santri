<?php
declare(strict_types=1);

namespace App\Exception;

class InvalidWeightException extends DomainException
{
    protected $message = 'O peso deve ser maior que zero.';
}
