<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Job;

use App\Application\Actions\ActionPayload;
use App\Application\Actions\ValidationException;
use App\Domain\Job\JobRepository;
use Tests\TestCase;

class ListJobsActionTest extends TestCase
{
    public function testShouldManagerUserSeeAllJobs()
    {
        $app = $this->getAppInstance();

        $request  = $this->createRequest('GET', '/jobs')
            ->withQueryParams(['user' => 'manager@jobsmapp.com']);
        $response = $app->handle($request);

        /** @var JobRepository $payload */
        $jobRepository = $app->getContainer()->get(JobRepository::class);
        $jobs          = $jobRepository->findAll();

        $payload           = (string) $response->getBody();
        $expectedPayload   = new ActionPayload(200, $jobs);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testShouldRegularUserSeeOnlyHisJobs()
    {
        $app = $this->getAppInstance();

        $request  = $this->createRequest('GET', '/jobs')
            ->withQueryParams(['user' => 'regular.1@jobsmapp.com']);
        $response = $app->handle($request);

        /** @var JobRepository $payload */
        $jobRepository = $app->getContainer()->get(JobRepository::class);
        $jobs          = $jobRepository->findAll(1);

        $payload           = (string) $response->getBody();
        $expectedPayload   = new ActionPayload(200, $jobs);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testShouldValidateUserRequirement()
    {
        $app = $this->getAppInstance();

        $this->expectException(ValidationException::class);

        $request = $this->createRequest('GET', '/jobs')
            ->withQueryParams([]);
        $app->handle($request);
    }
}
