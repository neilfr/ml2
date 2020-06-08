<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Illuminate\Contracts\View\View;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('ensureResponseHasInertia', function () {
            if (!isset($this->original) || !$this->original instanceof View || !isset($this->original->getData()['page'])) {
                return Assert::fail('The response is not an Inertia view.');
            }
        });
    }
}
