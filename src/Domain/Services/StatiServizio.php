<?php

namespace App\Domain\Services;

enum StatiServizio: string
{
    case Attivo = 'attivo';
    case Disattivo = 'disattivo';

}
