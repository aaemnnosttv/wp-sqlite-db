<?php

namespace Tests;

class SmokeTest extends TestCase
{
    /** @test */
    function it_works()
    {
        // Do something that triggers a database query.

        $this->assertCount(0, get_posts());

        $this->factory()->post->create();

        $this->assertCount(1, get_posts());
    }
}
