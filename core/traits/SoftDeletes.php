<?php
namespace Core\Traits;

trait SoftDeletes
{
  protected $deletedAtColumn = "deleted_at";

  public function delete(): void
  {
    $this->{$this->deletedAtColumn} = date("Y-m-d H:i:s");
    $this->save();
  }

  public function forceDelete(): void
  {
    parent::delete();
  }

  public function restore(): void
  {
    $this->{$this->deletedAtColumn} = null;
    $this->save();
  }

  public static function all($withDeleted = false)
  {
    if ($withDeleted) {
      return parent::all();
    }
    return parent::where([
      "sql" => "deleted_at IS NULL",
    ]);
  }
}