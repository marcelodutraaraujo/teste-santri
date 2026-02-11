<?php
declare(strict_types=1);

namespace App\Exception;

class InvalidStateException extends DomainException
{
    protected $message = 'A sigla deste estado não é válida';
}
