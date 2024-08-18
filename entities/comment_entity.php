<?php
include_once("entities/mother_entity.php");
class Comment extends Entity
{
    private int $id;
    private int $user_id;
    private int $experience_id;
    private string $comment;
    private string $created_at;

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
    public function setExperienceId(int $experience_id)
    {
        $this->experience_id = $experience_id;
    }
    public function getExperienceId(): int
    {
        return $this->experience_id;
    }
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }
    public function getComment(): string
    {
        return $this->comment;
    }
    public function setCreatedAt(string $created_at)
    {
        $this->created_at = $created_at;
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
