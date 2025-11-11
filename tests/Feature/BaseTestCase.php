<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    use RefreshDatabase;
}
