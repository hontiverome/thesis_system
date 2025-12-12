<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMember extends Model
{
    use HasFactory;

    protected $table = 'GroupMembers';

    protected $primaryKey = ['GroupID', 'StudentUserID'];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'GroupID',
        'StudentUserID',
        'GroupRole',
    ];

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the value of the model's primary key.
     *
     * @param  string  $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (is_array($keyName)) {
            return $this->original[$keyName[0]] ?? null;
        }

        return $this->original[$keyName] ?? null;
    }

    /**
     * Get the primary key value for a where clause.
     *
     * @param  string|null  $keyName
     * @return mixed
     */
    public function getKey($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (is_array($keyName)) {
            $key = [];
            foreach ($keyName as $name) {
                $key[$name] = $this->getAttribute($name);
            }
            return $key;
        }

        return $this->getAttribute($keyName);
    }

    /**
     * Get the group that the member belongs to.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'GroupID', 'GroupID');
    }

    /**
     * Get the user (student) that is the member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'StudentUserID', 'UserID');
    }
}
