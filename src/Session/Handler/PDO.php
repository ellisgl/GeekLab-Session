<?php

namespace GeekLab\Session\Handler;

use GeekLab\ArrayTranslation;

/**
 * PDO DB storage session handler
 *
 * Class PDO
 * @package GeekLab\Session\Handler
 */
class PDO extends HandlerAbstract implements HandlerInterface
{
    private $db;
    private $dataPHP;
    private $dataStorage;

    /**
     * PDO constructor.
     *
     * @param \PDO             $db
     * @param ArrayTranslation $dataStorage
     * @param ArrayTranslation $dataPHP
     */
    public function __construct(\PDO $db, ArrayTranslation\TranslationInterface $dataStorage, ArrayTranslation\TranslationInterface $dataPHP)
    {
        $this->db          = $db;
        $this->dataStorage = $dataStorage;
        $this->dataPHP     = $dataPHP;
    }

    /**
     * Open session handler.
     *
     * @return bool
     */
    public function open(): bool
    {
        return ($this->db) ? TRUE : FALSE;
    }

    /**
     * Close session handler.
     *
     * @return bool
     */
    public function close(): bool
    {
        // Just return true...
        return TRUE;
    }

    /**
     * Read a session.
     *
     * @param string $id
     * @return string
     */
    public function read(string $id): string
    {
        // Set query
        $stmt = $this->db->prepare('SELECT data FROM sessions WHERE id = :id');

        // Bind the Id
        $stmt->bindParam(':id', $id);

        if ($stmt->execute())
        {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (empty($row))
            {
                return '';
            }

            // Convert the data column from DB to an array
            $data = $this->dataStorage->decode($row['data']);

            // Return valid session serialized string if we have data, if not, return empty string.
            return (empty($data)) ? '' : $this->dataPHP->encode($data);
        }
        else
        {
            // Return an empty string
            return '';
        }
    }

    /**
     * Write a session.
     *
     * @param  string $id
     * @param  string $sessionData
     * @return bool
     */
    public function write(string $id, string $sessionData): bool
    {
        // Convert data types.
        $data = $this->dataStorage->encode($this->dataPHP->decode($sessionData));

        // Create time stamp
        $access = time();

        // Set query
        $stmt = $this->db->prepare('REPLACE INTO sessions VALUES(:id, :access, :data)');

        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':access', $access);
        $stmt->bindParam(':data', $data);

        // Run statement and return true if all was good and false if everything went wrong!
        return ($stmt->execute()) ? TRUE : FALSE;
    }

    /**
     * Destroy a session.
     *
     * @param  string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        // Set query.
        $stmt = $this->db->prepare('DELETE FROM sessions WHERE id = :id');

        // Bind data.
        $stmt->bindParam(':id', $id);

        // Run statement and return true if all was good and false if everything went wrong!
        return ($stmt->execute()) ? TRUE : FALSE;
    }

    /**
     * Garbage collection.
     *
     * @param  int $old - Remove if last access time is less than this.
     * @return bool
     */
    public function _gc(int $old): bool
    {
        // Set query
        $stmt = $this->db->prepare('DELETE FROM sessions WHERE access < :old');

        // Bind data
        $stmt->bindParam(':old', $old);

        // Run statement and return true if all was good and false if everything went wrong!
        return ($stmt->execute()) ? TRUE : FALSE;
    }
}