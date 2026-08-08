<?php

namespace App\Models\Contracts;


interface Shareable
{

    const string SQUARE = 'square';
    const string RECTANGLE = 'rectangle';

    public function isShareable(): bool;

    public function getShareableUrl(): string;
}
