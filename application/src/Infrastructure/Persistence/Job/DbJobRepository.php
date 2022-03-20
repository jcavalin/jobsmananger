<?php


namespace App\Infrastructure\Persistence\Job;

use App\Domain\Job\Job;
use App\Domain\Job\JobRepository;
use App\Infrastructure\Db\DatabaseInterface;

class DbJobRepository implements JobRepository
{
    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(int $userId = null): array
    {
        $sql    = "SELECT id, title, description, user_id FROM app.job";
        $params = [];

        if (!is_null($userId)) {
            $sql              .= " WHERE user_id = :userId";
            $params['userId'] = $userId;
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function save(Job $job): Job
    {

    }
}