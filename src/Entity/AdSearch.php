<?php
namespace App\Entity;

class AdSearch{

    /**
     * @var int|null
     */
    private $type;
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var true|false
     */
    private $resolved;

    /**
     * @return false|true
     */

    public function getResolved(): bool
    {
        return $this->resolved;
    }

    /**
     * @param false|true $resolved
     */
    public function setResolved(bool $resolved): void
    {
        $this->resolved = $resolved;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int|null $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }



}


?>