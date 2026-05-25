<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/customers');
    }

    public function test_customers_page_renders_successfully(): void
    {
        $response = $this->get('/customers');

        $response->assertStatus(200);
    }

    public function test_services_page_renders_successfully(): void
    {
        $response = $this->get('/services');

        $response->assertStatus(200);
    }

    public function test_subscriptions_page_renders_successfully(): void
    {
        $response = $this->get('/subscriptions');

        $response->assertStatus(200);
    }
}
