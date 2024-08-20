<?php
include("entities/mother_entity.php");

class Experience extends Entity
{
    private int $id;
    private int $user_id;
    private string $title;
    private string $content;
    private string $created_at;
    private string $updated_at;
    private bool $is_public;
    private bool $is_deleted;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setCreatedAt(string $created_at)
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setUpdatedAt(string $updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function setIsPublic(bool $is_public)
    {
        $this->is_public = $is_public;
    }

    public function getIsPublic(): bool
    {
        return $this->is_public;
    }

    public function setIsDeleted(bool $is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    public function getIsDeleted(): bool
    {
        return $this->is_deleted;
    }
}
