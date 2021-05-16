<?php

namespace Live\Collection;

use Exception;

/**
 * File collection
 *
 * @package Live\Collection
 */
class FileCollection implements CollectionInterface
{
    /**
     * Filename
     *
     * @var string
     */
    protected $filename;

    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor
     */
    public function __construct(string $filename)
    {
        if (!file_exists($filename)) {
            throw new Exception('File does not exists');
        }

        $this->filename = $filename;

        $this->data = $this->read($filename) ?? [];
    }

    /**
     * Reads values of collection the file
     *
     * @param string $filename
     * @return array
     */
    protected function read(string $filename)
    {
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * Writes values of collection in file
     *
     * @param array $data
     * @return void
     */
    protected function write(array $data): void
    {
        file_put_contents($this->filename, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        if (!$this->has($index)) {
            return $defaultValue;
        }

        if ($this->data[$index]['expiresIn'] < time()) {
            return null;
        }

        return $this->data[$index]['value'];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, $expiresIn = 1)
    {
        $this->data[$index] = ['value' => $value, 'expiresIn' => time() + $expiresIn];

        $this->write($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        return array_key_exists($index, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
    }
}
