<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_does_something()
    {
        $this->get('/')->assertOk();
    }
}
