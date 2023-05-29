<?php

namespace App\Business\Link\Port;

interface SlugService {
    public function generate(): string;
}