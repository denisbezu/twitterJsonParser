<?php
/**
 * Created by PhpStorm.
 * User: denys
 * Date: 25.01.19
 * Time: 20:31
 */

namespace App\Service;


class AddResult
{
    const ADDED = 'added';

    const SKIPPED = 'skipped';

    const FAILED = 'failed';

    protected $result;

    protected $resultId;

    public function __construct($result, $resultId = null)
    {
        $this->result = $result;
        $this->resultId = $resultId;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResultId()
    {
        return $this->resultId;
    }

    /**
     * @param mixed $resultId
     */
    public function setResultId($resultId): void
    {
        $this->resultId = $resultId;
    }
}